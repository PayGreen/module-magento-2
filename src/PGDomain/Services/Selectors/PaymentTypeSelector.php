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
 * @version   1.2.4
 *
 */

/**
 * Class PGDomainServicesSelectorsPaymentTypeSelector
 * @package PGFramework\Services\Selectors
 */
class PGDomainServicesSelectorsPaymentTypeSelector extends PGFrameworkFoundationsAbstractSelector
{
    /** @var PGDomainServicesManagersPaymentTypeManager */
    private $paymentTypeManager;

    /**
     * @param PGDomainServicesManagersPaymentTypeManager $paymentTypeManager
     */
    public function setPaymentTypeManager($paymentTypeManager)
    {
        $this->paymentTypeManager = $paymentTypeManager;
    }

    /**
     * @return array
     * @throws PGClientExceptionsPaymentRequestException
     */
    protected function buildChoices()
    {
        $choices = array();

        $codes = $this->paymentTypeManager->getCodes();

        foreach ($codes as $code) {
            $choices[$code] = $this->translate($code);
        }

        return $choices;
    }

    protected function getTranslationRoot()
    {
        return 'data.payment_types';
    }
}
