<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.5.2
 *
 */

namespace PGI\Module\PGPayment\Services\ChainLinks;

use PGI\Module\PGPayment\Components\PaymentProject as PaymentProjectComponent;
use PGI\Module\PGPayment\Data;
use PGI\Module\PGPayment\Foundations\AbstractPaymentCreationChainLink;
use PGI\Module\PGPayment\Interfaces\Entities\ButtonEntityInterface;
use DateTime;
use Exception;

/**
 * Class AddRecurringDataChainLink
 * @package PGPayment\Services\ChainLinks
 */
class AddRecurringDataChainLink extends AbstractPaymentCreationChainLink
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function process(PaymentProjectComponent $paymentProject)
    {
        /** @var ButtonEntityInterface $button */
        $button = $paymentProject->getButton();

        if ($button->getPaymentMode() === Data::MODE_RECURRING) {
            $paymentReport = $button->getPaymentReport();

            if ($paymentReport === '0') {
                $startAt = null;
                $day = date('d');
            } else {
                try {
                    $dt = new DateTime($paymentReport);
                } catch (Exception $exception) {
                    $text = "Unable to parse payment report with value : '$paymentReport'.";
                    throw new Exception($text, 0, $exception);
                }

                $startAt = $dt->getTimestamp();
                $day = $dt->format('d');
            }

            if (!is_array($paymentProject['orderDetails'])) {
                $paymentProject['orderDetails'] = array();
            }

            $paymentProject['orderDetails'] += array(
                'cycle' => Data::RECURRING_MONTHLY,
                'count' => $button->getPaymentNumber(),
                'firstAmount' => $paymentProject->getPrePaymentProvisionner()->getTotalAmount(),
                'day' => $day,
                'startAt' => $startAt
            );
        }
    }
}
