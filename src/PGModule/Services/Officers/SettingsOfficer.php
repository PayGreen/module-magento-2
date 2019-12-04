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
 * @version   0.3.2
 */

/**
 * Class PGModuleServicesOfficersSettingsOfficer
 * @package PGModule\Services\Officers
 */
class PGModuleServicesOfficersSettingsOfficer extends PGFrameworkFoundationsAbstractObject implements PGFrameworkInterfacesOfficersSettingsOfficerInterface
{
    /**
     * @inheritDoc
     */
    public function getOption($name, $defaultValue)
    {
        /** @var Magento\Framework\App\Config\ScopeConfigInterface $scope */
        $scope = $this->getService('magento')->get('Magento\Framework\App\Config\ScopeConfigInterface');

        $value = $scope->getValue("payment/paygreen/$name");

        return $value ? $value : $defaultValue;
    }

    /**
     * @inheritDoc
     */
    public function setOption($name, $value)
    {
        /** @var Magento\Config\Model\ResourceModel\Config $config */
        $config = $this->getService('magento')->get('Magento\Config\Model\ResourceModel\Config');

        $config->saveConfig("payment/paygreen/$name", $value);

        /** @var Magento\Framework\App\Cache\TypeListInterface $cleaner */
        $cleaner = $this->getService('magento')->create('Magento\Framework\App\Cache\TypeListInterface');

        $cleaner->cleanType('config');
    }

    public function setDefaultSettings()
    {
        $this->setOption('active', '1');
        $this->setOption('visibility', '0');

        $this->setOption('title', 'Options de paiement Paygreen');
        $this->setOption('payment_confirmation_button', 'Payer votre commande');
        $this->setOption('payment_success_text', 'Votre paiement a été validé.');
        $this->setOption('payment_error_text', 'Votre paiement a été refusé.');

        $this->setOption('behavior_payment_refused', 'none');
        $this->setOption('behavior_transmit_refund', '1');
        $this->setOption('behavior_transmit_delivering', '1');
    }
}
