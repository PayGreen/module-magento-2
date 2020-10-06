<?php
/**
 * 2014 - 2020 Watt Is It
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
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.2.0
 */

/**
 * Class Paygreen_Services_Managers_PaymentType
 *
 * @package PGDomain\Services\Managers
 * @method PGDomainServicesRepositoriesPaymentTypeRepository getRepository
 */
class PGDomainServicesManagersPaymentTypeManager extends PGFrameworkFoundationsAbstractManager
{
    const SOLIDARITY_ROUNDING = 'ROUNDING';
    const SOLIDARITY_CCARBON = 'CCARBON';
    const SOLIDARITY_DEFAULT = 'DEFAULT';
    const SOLIDARITY_NO = 'NO';

    /** @var PGDomainEntitiesPaymentType[]  */
    private $paymentTypes = array();

    /** @var string[]  */
    private $codes = array();

    /**
     * @return PGDomainEntitiesPaymentType[]
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function getAll()
    {
        if (empty($this->paymentTypes)) {
            $this->paymentTypes = $this->getRepository()->findAll();
        }

        return $this->paymentTypes;
    }

    /**
     * @return PGDomainEntitiesPaymentType|null
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function getByCode($code)
    {
        $selectedPaymentType = null;

        /** @var PGDomainEntitiesPaymentType $paymentType */
        foreach ($this->getAll() as $paymentType) {
            if ($paymentType->getCode() === $code) {
                $selectedPaymentType = $paymentType;
                break;
            }
        }

        return $selectedPaymentType;
    }

    /**
     * @return bool
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function hasPaymentTypes()
    {
        $paymentTypes = $this->getAll();

        return !empty($paymentTypes);
    }

    /**
     * @return string[]
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function getCodes()
    {
        if (empty($this->codes)) {
            /** @var $codes */
            $codes = array();

            /** @var PGDomainEntitiesPaymentType $paymentType */
            foreach ($this->getAll() as $paymentType) {
                $codes[] = $paymentType->getCode();
            }

            $this->codes = array_unique($codes);
        }

        return $this->codes;
    }

    /**
     * get Iframe Sizes ordered by payment method
     *
     * @return array
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function getIframeSizeOrderByType()
    {
        /** @var $iframeSizes */
        $iframeSizes = array();

        /** @var PGDomainEntitiesPaymentType $paymentType */
        foreach ($this->getAll() as $paymentType) {
            $iframeSizes[$paymentType->getCode()] = $paymentType->getIframeConfiguration();
        }

        return $iframeSizes;
    }

    /**
     * Get Size for Iframe depending on payment options
     *
     * @param string $solidarityType
     * @param string $paymentType
     * @param string $paymentMode
     * @return array
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function getSizeIFrameFromPayment($solidarityType, $paymentType, $paymentMode)
    {
        $iframeSizeByType = $this->getIframeSizeOrderByType();

        $minHeight = '400';
        $minWidth = '400';

        if (!empty($iframeSizeByType[$paymentType])) {
            $solidarityType = empty($solidarityType) ? self::SOLIDARITY_DEFAULT : $solidarityType;
            $isNoSolidarity = ($solidarityType === self::SOLIDARITY_NO);
            $solidarityType = $isNoSolidarity ? self::SOLIDARITY_DEFAULT : $solidarityType;

            if (!empty($iframeSizeByType[$paymentType]->{$paymentMode}->{$solidarityType})) {
                $iframeSize = $iframeSizeByType[$paymentType]->{$paymentMode}->{$solidarityType};

                $minHeight = $iframeSize->minHeight;
                $minWidth = $iframeSize->minWidth;
            }
        }

        return array(
            'minHeight' => $minHeight,
            'minWidth' => $minWidth
        );
    }
}
