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
 * @version   2.0.1
 *
 */

/**
 * Class PGPaymentServicesHandlersTestingPaymentHandler
 * @package PGPayment\Services\Handlers
 */
class PGPaymentServicesHandlersTestingPaymentHandler extends PGSystemFoundationsObject
{
    const FILENAME_LAST_PAYMENT_RESULT = 'last-payment-result.json';
    const FILENAME_LAST_PAYMENT_SENT = 'last-payment-sent.json';
    const FILENAME_FAKE_PAYMENT_DATA = 'fake-payment-data.json';

    /** @var PGModuleServicesLogger */
    private $logger;

    /** @var PGModuleServicesLogger */
    private $apiLogger;

    /** @var PGSystemServicesPathfinder */
    private $pathfinder;

    public function __construct(
        PGModuleServicesLogger $logger,
        PGModuleServicesLogger $apiLogger,
        PGSystemServicesPathfinder $pathfinder
    ) {
        $this->logger = $logger;
        $this->apiLogger = $apiLogger;
        $this->pathfinder = $pathfinder;
    }

    /**
     * @param PGPaymentComponentsTasksPaymentValidation $task
     * @param PGPaymentComponentsTasksTransactionManagement $subTask
     * @throws Exception
     */
    public function manageFakeOrder(
        PGPaymentComponentsTasksPaymentValidation $task,
        PGPaymentComponentsTasksTransactionManagement $subTask
    ) {
        $data = array(
            'task' => $task->getStatusName($task->getStatus()),
            'subtask' => $subTask->getStatusName($subTask->getStatus()),
            'order' => $subTask->getOrder() ? $subTask->getOrder()->getState() : null,
            'id_order' => $subTask->getOrder() ? $subTask->getOrder()->id() : null
        );

        $this->logger->debug('Save last process result :', $data);

        $this->overwrite(self::FILENAME_LAST_PAYMENT_RESULT, $data);
    }

    public function isFakeRequest()
    {
        $path = sys_get_temp_dir() . '/fake-payment-data.json';

        return file_exists($path);
    }

    public function buildFakeResponse($pid)
    {
        $paymentData = $this->loadFile(self::FILENAME_LAST_PAYMENT_SENT);

        $fakeData = $this->loadFile(self::FILENAME_FAKE_PAYMENT_DATA);

        $dt = new DateTime();

        $data = array(
            'id' => $pid,
            'orderId' => $paymentData['orderId'],
            'amount' => $paymentData['amount'],
            'currency' => $paymentData['currency'],
            'type' => $paymentData['mode'],
            'paymentType' => $paymentData['paymentType'],
            'url' => 'http://paygreen.fr',
            'testMode' => 1,
            'idFingerprint' => 0,
            'rank' => (int) mt_rand(1, 10),
            'createdAt' => $dt->format(DateTime::RFC3339),
            'valueAt' => $dt->format(DateTime::RFC3339),
            'answeredAt' => $dt->format(DateTime::RFC3339),
            'result' => array(
                'status' => $fakeData['status'],
                'threeDSecureStatus' => '3DS_SUCCESSED',
                'paymentErrorStatus' => ''
            ),
            'metadata' => isset($paymentData['metadata']) ? $paymentData['metadata'] : array()
        );

        $this->apiLogger->debug('Fake response data :', $data);

        return $data;
    }

    /**
     * @param string $filename
     * @return array
     * @throws Exception
     */
    private function loadFile($filename)
    {
        if ($filename ===  self::FILENAME_FAKE_PAYMENT_DATA) {
            $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $filename;
        } else {
            $path = $this->pathfinder->toAbsolutePath('var', "/$filename");
        }

        if (!is_file($path)) {
            throw new Exception("Unable to load file : '$path'.");
        }

        return json_decode(file_get_contents($path), true);
    }

    /**
     * @param array $data
     * @throws Exception
     */
    public function savePaymentData(array $data)
    {
        $this->apiLogger->debug('Fake payment data :', $data);

        $this->overwrite(self::FILENAME_LAST_PAYMENT_SENT, $data);
    }

    /**
     * @param string $filename
     * @param array $data
     * @throws Exception
     */
    private function overwrite($filename, array $data)
    {
        $path = $this->pathfinder->toAbsolutePath('var', "/$filename");

        $content = json_encode($data);

        if (is_file($path)) {
            if (!is_writable($path)) {
                throw new Exception("File '$path' is not deletable.");
            } else {
                unlink($path);
            }
        }

        if (file_put_contents($path, $content) === false) {
            throw new Exception("Unable to write fake data in file '$path'.");
        }
    }
}
