<?php
/**
 * 2014 - 2020 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.1.1
 */

class PGServerServicesServer extends PGFrameworkFoundationsAbstractObject
{
    /** @var PGFrameworkComponentsBag */
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

    /** @var PGServerServicesDispatcher */
    private $dispatcher;

    /** @var PGServerServicesRouter */
    private $router;

    /** @var PGServerServicesDerouter */
    private $derouter;

    /** @var PGFrameworkServicesLogger */
    private $logger;

    /** @var PGServerServicesFactoriesStageFactory */
    private $stageFactory;

    /** @var PGServerFoundationsAbstractStage[] */
    private $stages = array();

    /**
     * PGServerServicesServer constructor.
     * @param PGServerServicesRouter $router
     * @param PGServerServicesDerouter $derouter
     * @param PGServerServicesDispatcher $dispatcher
     * @param PGFrameworkServicesLogger $logger
     * @param PGServerServicesFactoriesStageFactory $stageFactory
     * @param array $config
     * @throws Exception
     */
    public function __construct(
        PGServerServicesRouter $router,
        PGServerServicesDerouter $derouter,
        PGServerServicesDispatcher $dispatcher,
        PGFrameworkServicesLogger $logger,
        PGServerServicesFactoriesStageFactory $stageFactory,
        array $config
    ) {
        $this->router = $router;
        $this->derouter = $derouter;
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
        $this->stageFactory = $stageFactory;

        $this->config = new PGFrameworkComponentsBag($this->defaultConfig);
        $this->config->merge($config);

        if (!is_array($this->config['areas'])) {
            throw new Exception("A Server must have an array of area names.");
        }
    }

    /**
     * @param string $type
     * @return PGServerInterfacesCleanerInterface
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

        if ($service instanceof PGServerInterfacesCleanerInterface) {
            return $service;
        } else {
            throw new Exception("Target service : '$serviceName' is not a valid Cleaner.");
        }
    }

    /**
     * @param string $type
     * @param Exception $exception
     * @param null $request
     * @return PGServerFoundationsAbstractResponse
     * @throws Exception
     */
    protected function clean($type, Exception $exception, $request = null)
    {
        /** @var PGServerInterfacesCleanerInterface $cleaner */
        $cleaner = $this->getCleaner($type);

        $type = strtoupper($type);

        $this->logger->error("Server error type '$type' has occurred : " . $exception->getMessage(), $exception);

        if ($request === null) {
            $request = new PGServerComponentsRequestsEmptyRequest();
        }

        return $cleaner->processError($request, $exception);
    }

    /**
     * @return PGServerServicesRequestBuilder
     * @throws Exception
     */
    public function getRequestBuilder()
    {
        $defaultRequestBuilderServiceName = $this->config['request_builder'];

        /** @var PGServerServicesRequestBuilder $requestBuilder */
        $requestBuilder = $this->getService($defaultRequestBuilderServiceName);

        if (!$requestBuilder instanceof PGServerServicesRequestBuilder) {
            throw new Exception("Target service '$defaultRequestBuilderServiceName' is not a valid RequestBuilder.");
        }

        return $requestBuilder;
    }

    /**
     * @param mixed|null $context
     * @return PGServerFoundationsAbstractResponse|null
     * @throws Exception
     */
    public function run($context = null)
    {
        $request = null;

        $this->logger->debug("Running PayGreen server.");

        do {
            $continue = false;

            /** @var PGServerFoundationsAbstractResponse $response */
            $response = $this->buildResponse($context, $request);

            if (!$response instanceof PGServerComponentsResponsesForwardResponse) {
                try {
                    return $this->renderResponse($response);
                } catch (Exception $exception) {
                    $response = $this->clean('rendering_error', $exception, $response->getRequest());
                    $continue = true;
                }
            }

            if ($response instanceof PGServerComponentsResponsesForwardResponse) {
                $this->logger->debug("Forwarding root process to '{$response->getRequest()->getTarget()}'.");
                $request = $response->getRequest();
                $continue = true;
            }
        } while ($continue);
    }

    /**
     * @param mixed|null $context
     * @param PGServerFoundationsAbstractRequest|null $request
     * @return PGServerFoundationsAbstractResponse|null
     * @throws Exception
     */
    protected function buildResponse($context = null, PGServerFoundationsAbstractRequest $request = null)
    {
        $this->logger->debug("Build response process.");

        try {
            if ($request === null) {
                /** @var PGServerFoundationsAbstractRequest $request */
                $request = $this->getRequestBuilder()->buildRequest($context);
            }

            do {
                $continue = false;

                /** @var PGServerFoundationsAbstractResponse $response */
                $response = $this->processRequest($request);

                if ($response instanceof PGServerComponentsResponsesForwardResponse) {
                    /** @var PGServerComponentsRequestsForwardRequest $request */
                    $request = $response->getRequest();

                    $this->logger->debug("Forwarding response process to '{$request->getTarget()}'.");

                    $continue = true;
                }
            } while ($continue);
        } catch (PGServerExceptionsHTTPBadRequestException $exception) {
            $response = $this->clean('bad_request', $exception);
        } catch (Exception $exception) {
            $response = $this->clean('server_error', $exception, $request);
        }

        return $response;
    }

    /**
     * @param PGServerFoundationsAbstractRequest $request
     * @return PGServerFoundationsAbstractResponse
     * @throws Exception
     */
    protected function processRequest(PGServerFoundationsAbstractRequest $request)
    {
        $class = get_class($request);
        $this->logger->debug("Build response from request with type '$class'.");

        /** @var PGServerFoundationsAbstractResponse $response */
        $response = null;

        try {
            if ($request instanceof PGServerComponentsRequestsEmptyRequest) {
                return new PGServerComponentsResponsesEmptyResponse($request);
            } elseif ($request instanceof PGServerComponentsRequestsForwardRequest) {
                $target = $request->getTarget();
            } elseif ($request instanceof PGServerComponentsRequestsHTTPRequest) {
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
        } catch (PGServerExceptionsHTTPNotFoundException $exception) {
            $response = $this->clean('not_found', $exception, $request);
        } catch (PGServerExceptionsHTTPUnauthorizedException $exception) {
            $response = $this->clean('unauthorized_access', $exception, $request);
        }

        return $response;
    }

    /**
     * @param PGServerFoundationsAbstractResponse $response
     * @param PGServerComponentsStage[] $stages
     * @return PGServerFoundationsAbstractResponse
     * @throws Exception
     */
    protected function renderResponse(PGServerFoundationsAbstractResponse $response, array $stages = array())
    {
        if (empty($stages)) {
            $stages = $this->getStages();
        }

        $this->logger->debug("Running rendering process for : " . get_class($response));

        /** @var PGServerComponentsStage $stage */
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
     * @return PGServerComponentsStage[]
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
     * @return PGServerComponentsStage[]
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
