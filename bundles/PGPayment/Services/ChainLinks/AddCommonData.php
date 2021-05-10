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
 * @version   2.0.2
 *
 */

/**
 * Class PGPaymentServicesChainLinksAddCommonData
 * @package PGPayment\Services\ChainLinks
 */
class PGPaymentServicesChainLinksAddCommonData extends PGPaymentFoundationsPaymentCreationChainLink
{
    private static $REQUIRED_METADATA = array ('order_id', 'cart_id');

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function process(PGPaymentComponentsPaymentProject $paymentProject)
    {
        $paymentProject['orderId'] = $this->generateOrderId(
            $paymentProject->getPrePaymentProvisionner()->getReference()
        );
        $paymentProject['currency'] = $paymentProject->getPrePaymentProvisionner()->getCurrency();
        $paymentProject['mode'] = $paymentProject->getButton()->getPaymentMode();
        $paymentProject['paymentType'] = $paymentProject->getButton()->getPaymentType();
        $paymentProject['amount'] = $paymentProject->getPrePaymentProvisionner()->getTotalAmount();
        $paymentProject['metadata'] = $paymentProject->getPrePaymentProvisionner()->getMetadata();

        $this->checkRequiredMetadata($paymentProject['metadata']);
    }

    /**
     * @param $orderId
     * @return string
     * @throws Exception
     */
    private function generateOrderId($orderId)
    {
        /** @var PGModuleServicesSettings $settings */
        $settings = PGSystemServicesContainer::getInstance()->get('settings');

        return  $orderId . '-' . $settings->get('shop_identifier') . '-' . mt_rand(10000, 99999);
    }

    /**
     * @param array $metadata
     * @return bool
     * @throws Exception
     */
    private function checkRequiredMetadata($metadata) {
        foreach ($metadata as $key => $value) {
            if (in_array($key, self::$REQUIRED_METADATA) && (!empty($value))) {
                return true;
            }
        }

        throw new Exception('Missing required metadata.');
    }
}
