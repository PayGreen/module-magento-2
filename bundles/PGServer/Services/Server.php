<?php
/**
 * 2014 - 2021 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License X11
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/mit-license.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2021 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.5.0
 *
 */

namespace PGI\Module\PGServer\Services;

use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGServer\Components\Requests\Blank as BlankRequestComponent;
use PGI\Module\PGServer\Components\Requests\Forward as ForwardRequestComponent;
use PGI\Module\PGServer\Components\Requests\HTTP as HTTPRequestComponent;
use PGI\Module\PGServer\Components\Responses\Blank as BlankResponseComponent;
use PGI\Module\PGServer\Components\Responses\Forward as ForwardResponseComponent;
use PGI\Module\PGServer\Components\Stage as StageComponent;
use PGI\Module\PGServer\Exceptions\HTTPBadRequest as HTTPBadRequestException;
use PGI\Module\PGServer\Exceptions\HTTPNotFound as HTTPNotFoundException;
use PGI\Module\PGServer\Exceptions\HTTPUnauthorized as HTTPUnauthorizedException;
use PGI\Module\PGServer\Foundations\AbstractRequest;
use PGI\Module\PGServer\Foundations\AbstractResponse;
use PGI\Module\PGServer\Foundations\AbstractStage;
use PGI\Module\PGServer\Interfaces\CleanerInterface;
use PGI\Module\PGServer\Services\Builders\RequestBuilder;
use PGI\Module\PGServer\Services\Derouter;
use PGI\Module\PGServer\Services\Dispatcher;
use PGI\Module\PGServer\Services\Factories\StageFactory;
use PGI\Module\PGServer\Services\Router;
use PGI\Module\PGSystem\Components\Bag as BagComponent;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use Exception;

/**
 * Class Server
 * @package PGServer\Services
 */
class Server extends AbstractObject
{
    /** @var BagComponent */
    private $config;

    private $defaultConfig = array(
        'areas' => null,
        'request_builder' => 'builder.request.default',
        'deflectors' => array(),
        'cleaners' => array(
            'not_found' => 'cleaner.basic_throw',
            'unauthorized_access' => 'cleaner.basic_throw',
            'server_error' => 'cleaner.basic_throw',
            'bad_request' => 'cleaner.basic_throw',
            'rendering_error' => 'cleaner.basic_throw'
        )
    );

    /** @var Dispatcher */
    private $dispatcher;

    /** @var Router */
    private $router;

    /** @var Derouter */
    private $derouter;

    /** @var Logger */
    private $logger;

    /** @var StageFactory */
    private $stageFactory;

    /** @var AbstractStage[] */
    private $stages = array();

    /**
     * Server constructor.
     * @param Router $router
     * @param Derouter $derouter
     * @param Dispatcher $dispatcher
     * @param Logger $logger
     * @param StageFactory $stageFactory
     * @param array $config
     * @throws Exception
     */
    public function __construct(
        Router $router,
        Derouter $derouter,
        Dispatcher $dispatcher,
        Logger $logger,
        StageFactory $stageFactory,
        array $config
    ) {
        $this->router = $router;
        $this->derouter = $derouter;
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
        $this->stageFactory = $stageFactory;

        $this->config = new BagComponent($this->defaultConfig);
        $this->config->merge($config);

        if (!is_array($this->config['areas'])) {
            throw new Exception("A Server must have an array of area names.");
        }
    }

    /**
     * @param string $type
     * @return CleanerInterface
     * @throws Exception
     */
    protected function getCleaner($type)
    {
        $type = strtolower($type);

        if (!isset($this->config["cleaners.$type"])) {
            $type = strtoupper($type);
            throw new Exception("Unknown cleaner type : '$type'.");
        }

        $serviceName = $this->config["cleaners.$type"];

        $service = $this->getService($serviceName);

        if ($service instanceof CleanerInterface) {
            return $service;
        } else {
            throw new Exception("Target service : '$serviceName' is not a valid Cleaner.");
        }
    }

    /**
     * @param string $type
     * @param Exception $exception
     * @param null $request
     * @return AbstractResponse
     * @throws Exception
     */
    protected function clean($type, Exception $exception, $request = null)
    {
        /** @var CleanerInterface $cleaner */
        $cleaner = $this->getCleaner($type);

        $type = strtoupper($type);

        $this->logger->error("Server error type '$type' has occurred : " . $exception->getMessage(), $exception);

        if ($request === null) {
            $request = new BlankRequestComponent();
        }

        return $cleaner->processError($request, $exception);
    }

    /**
     * @return RequestBuilder
     * @throws Exception
     */
    public function getRequestBuilder()
    {
        $defaultRequestBuilderServiceName = $this->config['request_builder'];

        /** @var RequestBuilder $requestBuilder */
        $requestBuilder = $this->getService($defaultRequestBuilderServiceName);

        if (!$requestBuilder instanceof RequestBuilder) {
            throw new Exception("Target service '$defaultRequestBuilderServiceName' is not a valid RequestBuilder.");
        }

        return $requestBuilder;
    }

    /**
     * @param mixed|null $context
     * @return AbstractResponse|null
     * @throws Exception
     */
    public function run($context = null)
    {
        $request = null;

        $this->logger->debug("Running PayGreen server.");

        do {
            $continue = false;

            /** @var AbstractResponse $response */
            $response = $this->buildResponse($context, $request);

            if (!$response instanceof ForwardResponseComponent) {
                try {
                    return $this->renderResponse($response);
                } catch (Exception $exception) {
                    $response = $this->clean('rendering_error', $exception, $response->getRequest());
                    $continue = true;
                }
            }

            if ($response instanceof ForwardResponseComponent) {
                $this->logger->debug("Forwarding root process to '{$response->getRequest()->getTarget()}'.");
                $request = $response->getRequest();
                $continue = true;
            }
        } while ($continue);
    }

    /**
     * @param mixed|null $context
     * @param AbstractRequest|null $request
     * @return AbstractResponse|null
     * @throws Exception
     */
    protected function buildResponse($context = null, AbstractRequest $request = null)
    {
        $this->logger->debug("Build response process.");

        try {
            if ($request === null) {
                /** @var AbstractRequest $request */
                $request = $this->getRequestBuilder()->buildRequest($context);
            }

            do {
                $continue = false;

                /** @var AbstractResponse $response */
                $response = $this->processRequest($request);

                if ($response instanceof ForwardResponseComponent) {
                    /** @var ForwardRequestComponent $request */
                    $request = $response->getRequest();

                    $this->logger->debug("Forwarding response process to '{$request->getTarget()}'.");

                    $continue = true;
                }
            } while ($continue);
        } catch (HTTPBadRequestException $exception) {
            $response = $this->clean('bad_request', $exception);
        } catch (Exception $exception) {
            $response = $this->clean('server_error', $exception, $request);
        }

        return $response;
    }

    /**
     * @param AbstractRequest $request
     * @return AbstractResponse
     * @throws Exception
     */
    protected function processRequest(AbstractRequest $request)
    {
        $class = get_class($request);
        $this->logger->debug("Build response from request with type '$class'.");

        /** @var AbstractResponse $response */
        $response = null;

        try {
            if ($request instanceof BlankRequestComponent) {
                return new BlankResponseComponent($request);
            } elseif ($request instanceof ForwardRequestComponent) {
                $target = $request->getTarget();
            } elseif ($request instanceof HTTPRequestComponent) {
                $target = $this->router->getTarget($request, $this->config['areas']);
            } else {
                $class = get_class($request);
                throw new Exception("Unknown Request type : '$class'.");
            }

            $this->logger->debug("Target found : '$target'.");

            $deflector = $this->derouter->getMatchingDeflector($request, $this->config['deflectors']);

            if ($deflector !== null) {
                $response = $deflector->process($request);
            } else {
                $response = $this->dispatcher->dispatch($request, $target);
            }
        } catch (HTTPNotFoundException $exception) {
            $response = $this->clean('not_found', $exception, $request);
        } catch (HTTPUnauthorizedException $exception) {
            $response = $this->clean('unauthorized_access', $exception, $request);
        }

        return $response;
    }

    /**
     * @param AbstractResponse $response
     * @param StageComponent[] $stages
     * @return AbstractResponse
     * @throws Exception
     */
    protected function renderResponse(AbstractResponse $response, array $stages = array())
    {
        if (empty($stages)) {
            $stages = $this->getStages();
        }

        $this->logger->debug("Running rendering process for : " . get_class($response));

        /** @var StageComponent $stage */
        foreach ($stages as $stage) {
            if ($stage->isTriggered($response)) {
                $this->logger->debug("Execute stage action : '{$stage->do}'.");

                switch ($stage->do) {
                    case 'RETURN':
                        return $stage->execute($response);
                    case 'RESTART':
                        $response = $this->renderResponse(
                            $stage->execute($response),
                            $stages
                        );

                        break;
                    case 'FORK':
                        $response = $this->renderResponse(
                            $response,
                            $this->buildStages($stage->with)
                        );

                        break;
                    case 'CONTINUE':
                        $response = $stage->execute($response);
                        break;
                    case 'STOP':
                        $stage->execute($response);
                        die();
                    default:
                        throw new Exception("Unknown stage action : '{$stage->do}'.");
                }
            }
        }

        return $response;
    }

    /**
     * @return StageComponent[]
     * @throws Exception
     */
    protected function getStages()
    {
        if (empty($this->stages)) {
            $this->stages = $this->buildStages($this->config['rendering']);

            $this->logger->debug("Rendering stages successfully built.");
        }

        reset($this->stages);

        return $this->stages;
    }

    /**
     * @param array $renderers
     * @return StageComponent[]
     * @throws Exception
     */
    protected function buildStages(array $renderers)
    {
        $stages = array();

        foreach ($renderers as $renderer) {
            $stages[] = $this->stageFactory->buildStage($renderer);
        }

        $this->logger->debug("Rendering stages successfully built.");

        return $stages;
    }
}
