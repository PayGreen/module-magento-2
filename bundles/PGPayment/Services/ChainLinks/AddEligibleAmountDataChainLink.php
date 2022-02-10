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

use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGPayment\Components\PaymentProject as PaymentProjectComponent;
use PGI\Module\PGPayment\Foundations\AbstractPaymentCreationChainLink;
use PGI\Module\PGShop\Interfaces\Entities\ShopableItemEntityInterface;
use PGI\Module\PGShop\Interfaces\Provisioners\PrePaymentProvisionerInterface;
use PGI\Module\PGShop\Services\Managers\ProductManager;
use Exception;

/**
 * Class AddEligibleAmountDataChainLink
 * @package PGPayment\Services\ChainLinks
 */
class AddEligibleAmountDataChainLink extends AbstractPaymentCreationChainLink
{
    /** @var ProductManager */
    private $productManager;

    /** @var Settings */
    private $settings;

    public function __construct(ProductManager $productManager, Settings $settings)
    {
        $this->productManager = $productManager;
        $this->settings = $settings;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function process(PaymentProjectComponent $paymentProject)
    {
        $eligible_amount = 0;

        $type = $paymentProject->getButton()->getPaymentType();

        /** @var ShopableItemEntityInterface $item */
        foreach ($paymentProject->getPrePaymentProvisionner()->getItems() as $item) {
            if ($this->productManager->isEligibleProduct($item->getProduct(), $type)) {
                $eligible_amount += $item->getCost();
            }
        }

        if ($eligible_amount > 0) {
            if ($paymentProject->getPrePaymentProvisionner()->getShippingAmount() > 0) {
                $eligible_amount += $this->getShippingEligibleAmount(
                    $paymentProject->getPrePaymentProvisionner(),
                    $type
                );
            }
        }

        $paymentProject['eligibleAmount'] = array(
            $type => $eligible_amount
        );

        $this->getLogger()->notice("Computing eligible amount for type '$type' and amount '$eligible_amount'.");
    }

    /**
     * @param PrePaymentProvisionerInterface $prePaymentProvisioner
     * @param string $type
     * @return int
     * @throws Exception
     */
    protected function getShippingEligibleAmount(
        PrePaymentProvisionerInterface $prePaymentProvisioner,
        $type
    ) {
        $shippingDeactivatedPaymentTypes = $this->settings->get('shipping_deactivated_payment_modes');

        if (!is_array($shippingDeactivatedPaymentTypes)) {
            $shippingDeactivatedPaymentTypes = array();
        }

        $isEligibleShipping = !in_array($type, $shippingDeactivatedPaymentTypes);

        return $isEligibleShipping ? $prePaymentProvisioner->getShippingAmount() : 0;
    }
}
