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
 * @version   2.0.0
 *
 */

/**
 * Class PGPaymentServicesManagersPaymentTypeManager
 *
 * @package PGPayment\Services\Managers
 * @method PGPaymentServicesRepositoriesPaymentTypeRepository getRepository
 */
class PGPaymentServicesManagersPaymentTypeManager extends PGDatabaseFoundationsManager
{
    const SOLIDARITY_ROUNDING = 'ROUNDING';
    const SOLIDARITY_CCARBON = 'CCARBON';
    const SOLIDARITY_DEFAULT = 'DEFAULT';
    const SOLIDARITY_NO = 'NO';

    /** @var PGPaymentEntitiesPaymentType[]  */
    private $paymentTypes = array();

    /** @var string[]  */
    private $codes = array();

    /**
     * @return PGPaymentEntitiesPaymentType[]
     * @throws PGClientExceptionsResponse
     */
    public function getAll()
    {
        if (empty($this->paymentTypes)) {
            $this->paymentTypes = $this->getRepository()->findAll();
        }

        return $this->paymentTypes;
    }

    /**
     * @return PGPaymentEntitiesPaymentType|null
     * @throws PGClientExceptionsResponse
     */
    public function getByCode($code)
    {
        $selectedPaymentType = null;

        /** @var PGPaymentEntitiesPaymentType $paymentType */
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
     * @throws PGClientExceptionsResponse
     */
    public function hasPaymentTypes()
    {
        $paymentTypes = $this->getAll();

        return !empty($paymentTypes);
    }

    /**
     * @return string[]
     * @throws PGClientExceptionsResponse
     */
    public function getCodes()
    {
        if (empty($this->codes)) {
            /** @var $codes */
            $codes = array();

            /** @var PGPaymentEntitiesPaymentType $paymentType */
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
     * @throws PGClientExceptionsResponse
     */
    public function getIframeSizeOrderByType()
    {
        /** @var $iframeSizes */
        $iframeSizes = array();

        /** @var PGPaymentEntitiesPaymentType $paymentType */
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
     * @throws PGClientExceptionsResponse
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
