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
 * @version   2.1.1
 *
 */

/**
 * Class PGPaymentServicesChainLinksAddXTimeData
 * @package PGPayment\Services\ChainLinks
 */
class PGPaymentServicesChainLinksAddXTimeData extends PGPaymentFoundationsPaymentCreationChainLink
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function process(PGPaymentComponentsPaymentProject $paymentProject)
    {
        /** @var PGPaymentInterfacesEntitiesButtonInterface $button */
        $button = $paymentProject->getButton();

        if ($button->getPaymentMode() === PGPaymentData::MODE_XTIME) {
            $paymentNumber = $button->getPaymentNumber();
            $amount = $paymentProject->getPrePaymentProvisionner()->getTotalAmount();

            if ($button->getFirstPaymentPart() > 0) {
                $firstAmount = ceil($amount / 100 * $button->getFirstPaymentPart());
            } else {
                $firstAmount = ceil($amount / $paymentNumber);
            }

            if (!is_array($paymentProject['orderDetails'])) {
                $paymentProject['orderDetails'] = array();
            }

            $paymentProject['orderDetails'] += array(
                'cycle' => PGPaymentData::RECURRING_MONTHLY,
                'count' => $paymentNumber,
                'firstAmount' => $firstAmount
            );
        }
    }
}
