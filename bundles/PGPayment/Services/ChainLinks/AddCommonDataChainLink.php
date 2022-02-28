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
 * @version   2.6.0
 *
 */

namespace PGI\Module\PGPayment\Services\ChainLinks;

use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGPayment\Components\PaymentProject as PaymentProjectComponent;
use PGI\Module\PGPayment\Foundations\AbstractPaymentCreationChainLink;
use PGI\Module\PGSystem\Services\Container;
use Exception;

/**
 * Class AddCommonDataChainLink
 * @package PGPayment\Services\ChainLinks
 */
class AddCommonDataChainLink extends AbstractPaymentCreationChainLink
{
    /** @var array  */
    private $required_metadata;

    public function __construct(array $required_metadata)
    {
        $this->required_metadata = $required_metadata;
    }

    /**
     * @param PGPaymentComponentsPaymentProject $paymentProject
     * @throws Exception
     */
    protected function process(PaymentProjectComponent $paymentProject)
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
        /** @var Settings $settings */
        $settings = Container::getInstance()->get('settings');

        return  $orderId . '-' . $settings->get('shop_identifier') . '-' . mt_rand(10000, 99999);
    }

    /**
     * @param array $metadata
     * @return bool
     * @throws Exception
     */
    private function checkRequiredMetadata($metadata)
    {
        foreach ($metadata as $key => $value) {
            if (in_array($key, $this->required_metadata) && (!empty($value))) {
                return true;
            }
        }

        throw new Exception('Missing required metadata.');
    }
}
