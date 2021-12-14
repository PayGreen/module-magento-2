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

namespace PGI\Module\FOPayment\Services\Controllers;

use PGI\Module\PGPayment\Components\Tasks\PaymentValidation as PaymentValidationTaskComponent;
use PGI\Module\PGPayment\Services\Processors\PaymentValidationProcessor;
use PGI\Module\PGServer\Foundations\AbstractController;
use PGI\Module\PGServer\Foundations\AbstractResponse;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use PGI\Module\PGSystem\Components\Bag as BagComponent;
use Exception;

/**
 * Class CustomerReturnController
 * @package FOPayment\Services\Controllers
 */
class CustomerReturnController extends AbstractController
{
    /** @var BagComponent */
    private $config;

    /** @var PaymentValidationProcessor */
    private $processor;

    public function __construct(array $config, PaymentValidationProcessor $processor)
    {
        $this->config = new BagComponent($config);
        $this->processor = $processor;
    }

    public function processAction()
    {
        $pid = $this->getRequest()->get('pid');

        $this->getLogger()->info("Customer validation for PID : '$pid'.");

        try {
            $task = new PaymentValidationTaskComponent($pid);

            $this->processor->execute($task);

            $taskState = strtolower(substr($task->getStatusName($task->getStatus()), 6));

            return $this->buildCustomResponse('task', $taskState, array(
                'task' => $task,
                'processor' => $this->processor,
                'order' => $task->getOrder()
            ));
        } catch (Exception $exception) {
            $this->getLogger()->error("Customer return processing error : " . $exception->getMessage(), $exception);

            return $this->forward('displayNotification@front.notification', array(
                'title' => 'frontoffice.payment.errors.customer_return.title',
                'message' => 'frontoffice.payment.errors.customer_return.message',
                'exceptions' => array($exception)
            ));
        }
    }

    public function dispatchByOrderStateAction()
    {
        /** @var PaymentValidationTaskComponent $task */
        $task = $this->getRequest()->get('task');

        try {
            if ($task === null) {
                throw new Exception("Action 'dispatchByOrderState' require valid PaymentValidationTask.");
            }

            /** @var PaymentValidationProcessor $processor */
            $processor = $this->getRequest()->get('processor');

            if ($processor === null) {
                throw new Exception("Action 'dispatchByOrderState' require valid PaymentValidationProcessor.");
            }

            /** @var OrderEntityInterface $order */
            $order = $task->getOrder();

            if ($order === null) {
                throw new Exception("Action 'dispatchByOrderState' require valid Order.");
            }

            $orderState = strtolower($task->getFinalOrderState());

            return $this->buildCustomResponse('order', $orderState, array(
                'task' => $task,
                'processor' => $processor,
                'order' => $order
            ));
        } catch (Exception $exception) {
            $this->getLogger()->error("Customer return processing error : " . $exception->getMessage(), $exception);

            return $this->forward('displayNotification@front.notification', array(
                'title' => 'frontoffice.payment.errors.customer_return.title',
                'message' => 'frontoffice.payment.errors.customer_return.message',
                'exceptions' => array($exception),
                'url' => array(
                    'link' => $this->getLinkHandler()->buildLocalUrl('home'),
                    'text' => 'frontoffice.payment.errors.customer_return.link',
                    'reload' => false,
                )
            ));
        }
    }

    /**
     * @param $type
     * @param $state
     * @param $data
     * @return AbstractResponse
     * @throws Exception
     */
    protected function buildCustomResponse($type, $state, $data)
    {
        $config = $this->buildCustomResponseConfig($type, $state);

        $this->getLogger()->debug("Build custom response '$type.$state' of type '{$config['type']}'.");

        switch ($config['type']) {
            case 'forward':
                if (!$config['target']) {
                    throw new Exception("Forwarding target must contains 'target' key or else be a string.");
                }

                if ($config['data']) {
                    $data = array_merge($data, $config['data']);
                }

                $response = $this->forward($config['target'], $data);

                break;

            case 'redirect':
                $url = $this->getLinkHandler()->buildLocalUrl($config['link'], $data);

                $response = $this->redirect($url);

                break;

            case 'message':
                $reload = $config['link.reload'];

                if ($reload === null) {
                    $reload = true;
                } else {
                    $reload = (bool) $reload;
                }

                $response = $this->forward('displayNotification@front.notification', array(
                    'title' => $config['title'],
                    'message' => $config['message'],
                    'url' => array(
                        'link' => $this->getLinkHandler()->buildLocalUrl($config['link.name'], $data),
                        'text' => $config['link.text'],
                        'reload' => $reload,
                    )
                ));

                break;

            case 'error':
                $response = $this->forward('displayNotification@front.notification', array(
                    'title' => 'frontoffice.payment.errors.validation.title',
                    'message' => 'frontoffice.payment.errors.validation.message',
                    'errors' => array($config['error']),
                    'url' => array(
                        'link' => $this->getLinkHandler()->buildLocalUrl('home'),
                        'text' => 'frontoffice.payment.errors.validation.link',
                        'reload' => false,
                    )
                ));

                break;

            default:
                throw new Exception("Unknown custom response type : '{$config['type']}'.");
        }

        return $response;
    }

    /**
     * @param string $type
     * @param string $state
     * @return BagComponent
     * @throws Exception
     */
    protected function buildCustomResponseConfig($type, $state)
    {
        $config = new BagComponent();

        $rawConfig = $this->config["forwarding.$type.$state"];

        if (is_string($rawConfig)) {
            $config->merge(array(
                'type' => 'forward',
                'target' => $rawConfig
            ));
        } elseif (is_array($rawConfig)) {
            if (array_key_exists('extends', $rawConfig)) {
                $config = $this->buildCustomResponseConfig($type, $rawConfig['extends']);
                unset($rawConfig['extends']);
            }

            $config->merge($rawConfig);
        } elseif ($rawConfig === null) {
            throw new Exception("No action has been defined for this $type state '$state'.");
        }

        return $config;
    }
}
