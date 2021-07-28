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
 * @version   2.3.0
 *
 */

namespace PGI\Module\FOPayment\Services\Linkers;

use PGI\Module\PGPayment\Components\Tasks\PaymentValidation as PaymentValidationTaskComponent;
use PGI\Module\PGPayment\Services\Handlers\PaymentCreationHandler;
use PGI\Module\PGServer\Interfaces\LinkerInterface;
use Exception;

/**
 * Class RetryPaymentValidationLinker
 * @package FOPayment\Services\Linkers
 */
class RetryPaymentValidationLinker implements LinkerInterface
{
    /** @var PaymentCreationHandler */
    private $paymentCreationHandler;

    public function __construct(PaymentCreationHandler $paymentCreationHandler)
    {
        $this->paymentCreationHandler = $paymentCreationHandler;
    }

    /**
     * @param array $data
     * @return string
     * @throws Exception
     */
    public function buildUrl(array $data = array())
    {
        if (!array_key_exists('task', $data)) {
            throw new Exception("Building retry payment validation URL require task entity.");
        } elseif (!$data['task'] instanceof PaymentValidationTaskComponent) {
            throw new Exception(
                "Building retry payment validation URL require PaymentValidationTaskComponent entity."
            );
        }

        $pid = $data['task']->getPid();

        if (!$pid) {
            throw new Exception("Building retry payment validation URL require PID.");
        }

        $entrypoint = $this->paymentCreationHandler->buildCustomerEntrypointURL();

        return "$entrypoint&pid=$pid";
    }
}
