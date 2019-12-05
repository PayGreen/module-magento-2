<?php
/**
 * 2014 - 2019 Watt Is It
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
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.4
 */

namespace Paygreen\Payment\Block;

use Paygreen\Payment\Foundations\AbstractTemplate;
use PGDomainServicesManagersCategoryManager;
use PGDomainServicesManagersPaymentTypeManager;
use PGFrameworkServicesSettings;

class DisplayModuleEligibleAmount extends AbstractTemplate
{
    public function isConfigured()
    {
        return $this->getService('paygreen.facade')->isConfigured();
    }

    public function isValidShop()
    {
        return ($this->getService('paygreen.facade')->getStatusShop() !== null);
    }

    public function getRootCategories()
    {
        /** @var PGDomainServicesManagersCategoryManager $categoryManager */
        $categoryManager = $this->getService('manager.category');

        return $categoryManager->getRootCategories();
    }

    public function getPaymentTypeCodes()
    {
        /** @var PGDomainServicesManagersPaymentTypeManager $paymentTypeManager */
        $paymentTypeManager = $this->getService('manager.payment_type');

        return $paymentTypeManager->getCodes();
    }

    public function getShippingEligiblePaymentModes()
    {
        /** @var PGFrameworkServicesSettings $settings */
        $settings = $this->getService('settings');

        return $settings->get('shipping_deactivated_payment_modes');
    }
}
