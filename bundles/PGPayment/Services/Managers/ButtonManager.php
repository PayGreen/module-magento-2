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
 * Class PGPaymentServicesManagersButtonManager
 *
 * @package PGPayment\Services\Managers
 * @method PGPaymentInterfacesRepositoriesButtonRepositoryInterface getRepository()
 */
class PGPaymentServicesManagersButtonManager extends PGDatabaseFoundationsManager
{
    const XTIME_MAX_COMMITMENTS = 4;
    const CATEGORY_FILTERING_MODE_NONE = 'NONE';
    const CATEGORY_FILTERING_MODE_STRICT = 'STRICT';
    const CATEGORY_FILTERING_MODE_FLEXIBLE = 'FLEXIBLE';

    /**
     * @param $id
     * @return PGPaymentInterfacesEntitiesButtonInterface|null
     */
    public function getByPrimary($id)
    {
        return $this->getRepository()->findByPrimary($id);
    }

    /**
     * @return PGPaymentInterfacesEntitiesButtonInterface[]
     */
    public function getAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @return PGPaymentInterfacesEntitiesButtonInterface
     */
    public function getNew()
    {
        return $this->getRepository()->create();
    }

    /**
     * @return int
     */
    public function count()
    {
        return (int) $this->getRepository()->count();
    }

    /**
     * @param PGPaymentInterfacesEntitiesButtonInterface $button
     * @return bool
     */
    public function save(PGPaymentInterfacesEntitiesButtonInterface $button)
    {
        if ($button->id() > 0) {
            return $this->getRepository()->update($button);
        } else {
            return $this->getRepository()->insert($button);
        }
    }

    /**
     * @param PGPaymentInterfacesEntitiesButtonInterface $button
     * @return bool
     */
    public function delete(PGPaymentInterfacesEntitiesButtonInterface $button)
    {
        /** @var PGIntlServicesManagersTranslationManager $translationManager */
        $translationManager = $this->getService('manager.translation');

        $result = $this->getRepository()->delete($button);

        if ($result) {
            try {
                $code = 'button-' . $button->id();
                $translationManager->deleteByCode($code);
            } catch (Exception $exception) {
                /** @var PGModuleServicesLogger $logger */
                $logger = $this->getService('logger');

                $logger->error(
                    "An error occurred during deletion of associated translations : " . $exception->getMessage(),
                    $exception
                );
            }
        }

        return $result;
    }

    /**
     * @param PGShopInterfacesProvisionersCheckout $checkoutProvisioner
     * @return PGPaymentInterfacesEntitiesButtonInterface[]
     * @throws PGClientExceptionsResponse
     * @throws Exception
     */
    public function getValidButtons(PGShopInterfacesProvisionersCheckout $checkoutProvisioner)
    {
        /** @var PGPaymentInterfacesEntitiesButtonInterface[] $buttons */
        $buttons = $this->getAll();

        /** @var PGPaymentInterfacesEntitiesButtonInterface[] $validButtons */
        $validButtons = array();

        /** @var PGPaymentInterfacesEntitiesButtonInterface $button */
        foreach ($buttons as $button) {
            $isValidAmount = $this->isValidAmount($button, $checkoutProvisioner->getTotalUserAmount());
            $isValidCurrency = $this->isValidCurrency($button, $checkoutProvisioner->getCurrency()->getCode());
            $hasEligibleProduct = $this->hasEligibleProduct($button, $checkoutProvisioner->getItems());
            $hasEligibleCategories = $this->hasEligibleCategories($button, $checkoutProvisioner->getItems());
            $errors = $this->check($button);

            if ($isValidAmount && $isValidCurrency && $hasEligibleProduct && $hasEligibleCategories && empty($errors)) {
                $validButtons[] = $button;
            }
        }

        usort($validButtons, function (
            PGPaymentInterfacesEntitiesButtonInterface $a,
            PGPaymentInterfacesEntitiesButtonInterface $b
        ) {
            if ($a->getPosition() === $b->getPosition()) {
                return 0;
            }
            return ($a->getPosition() < $b->getPosition()) ? -1 : 1;
        });

        return $validButtons;
    }

    /**
     * @param PGPaymentInterfacesEntitiesButtonInterface $button
     * @param float $userAmount
     * @return bool
     */
    public function isValidAmount(PGPaymentInterfacesEntitiesButtonInterface $button, $userAmount)
    {
        /** @var bool $result */
        $result = true;

        $min = (float) $button->getMinAmount();
        $max = (float) $button->getMaxAmount();

        if (($max > 0) && ($max < $userAmount)) {
            $result = false;
        } elseif (($min > 0) && ($min > $userAmount)) {
            $result = false;
        }

        return $result;
    }

    /**
     * @param PGPaymentInterfacesEntitiesButtonInterface $button
     * @param array $items
     * @return bool
     * @throws Exception
     */
    public function hasEligibleProduct(PGPaymentInterfacesEntitiesButtonInterface $button, array $items)
    {
        /** @var PGShopServicesManagersProduct $productManager */
        $productManager = $this->getService('manager.product');

        /** @var PGShopInterfacesEntitiesShopableItem $item */
        foreach ($items as $item) {
            $product = $item->getProduct();

            if ($product === null) {
                throw new Exception("Cart product not found.");
            } elseif ($productManager->isEligibleProduct($product, $button->getPaymentType())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param PGPaymentInterfacesEntitiesButtonInterface $button
     * @param array $items
     * @return bool
     * @throws Exception
     */
    public function hasEligibleCategories(PGPaymentInterfacesEntitiesButtonInterface $button, array $items)
    {
        $filteringMode = $button->getFilteredCategoryMode();

        if ($filteringMode !== self::CATEGORY_FILTERING_MODE_NONE) {
            /** @var PGShopServicesManagersCategory $categoryManager */
            $categoryManager = $this->getService('manager.category');

            $eligibleProducts = array();

            /** @var PGShopInterfacesEntitiesShopableItem $item */
            foreach ($items as $item) {
                $product = $item->getProduct();

                if ($product === null) {
                    throw new Exception("Cart product not found.");
                } else {
                    if ($categoryManager->isEligibleCategory($button, $product->getCategories())) {
                        $eligibleProducts[] = $product;
                    }
                }

                switch ($filteringMode) {
                    case self::CATEGORY_FILTERING_MODE_STRICT:
                        if (count($eligibleProducts) === count($items)) {
                            return true;
                        }
                        break;
                    case self::CATEGORY_FILTERING_MODE_FLEXIBLE:
                        if (!empty($eligibleProducts)) {
                            return true;
                        }
                        break;
                    default:
                        return true;
                }
            }

            return false;
        } else {
            return true;
        }
    }

    /**
     * @param PGPaymentInterfacesEntitiesButtonInterface $button
     * @param bool $skipCompositeTests
     * @return array
     * @throws PGClientExceptionsResponse
     */
    public function check(PGPaymentInterfacesEntitiesButtonInterface $button, $skipCompositeTests = false)
    {
        /** @var PGPaymentServicesPaygreenFacade $paygreenFacade */
        $paygreenFacade = $this->getService('paygreen.facade');

        /** @var PGPaymentServicesManagersPaymentTypeManager $paymentTypeManager */
        $paymentTypeManager = $this->getService('manager.payment_type');

        $errors = array();

        if (!$skipCompositeTests) {
            if (strlen($button->getLabel()) > 100) {
                $errors[] = "errors.button.title_max_length";
            } elseif (strlen($button->getLabel()) === 0) {
                $errors[] = "errors.button.title_min_length";
            }

            array_merge($errors, $this->checkFilters($button));
        }

        if ($button->getImageHeight() < 0) {
            $errors[] = "errors.button.image_height_positive";
        }

        if ($button->getPosition() < 0) {
            $errors[] = "errors.button.position_positive";
        }

        if ($button->getPaymentNumber() > 1) {
            if ($button->getPaymentMode() === PGPaymentData::MODE_CASH) {
                $errors[] = "errors.button.payment_number_with_cash";
            } elseif ($button->getPaymentMode() == PGPaymentData::MODE_TOKENIZE) {
                $errors[] = "errors.button.payment_number_with_tokenize";
            }
        } else {
            if ($button->getPaymentMode() === PGPaymentData::MODE_XTIME) {
                $errors[] = "errors.button.not_payment_number_with_xtime";
            } elseif ($button->getPaymentMode() == PGPaymentData::MODE_RECURRING) {
                $errors[] = "errors.button.not_payment_number_with_recurring";
            }
        }

        if ($button->getPaymentReport() > 0) {
            if ($button->getPaymentMode() === PGPaymentData::MODE_CASH) {
                $errors[] = "errors.button.payment_report_with_cash";
            } elseif ($button->getPaymentMode() === PGPaymentData::MODE_TOKENIZE) {
                $errors[] = "errors.button.payment_report_with_tokenize";
            } elseif ($button->getPaymentMode() === PGPaymentData::MODE_XTIME) {
                $errors[] = "errors.button.payment_report_with_xtime";
            }
        }

        if ($button->getFirstPaymentPart() !== 0) {
            if ($button->getPaymentMode() === PGPaymentData::MODE_XTIME) {
                if (($button->getFirstPaymentPart() <= 0) || ($button->getFirstPaymentPart() >= 100)) {
                    $errors[] = "errors.button.first_payment_part_range";
                }
            } else {
                $errors[] = "errors.button.first_payment_part_without_xtime";
            }
        }

        if ($button->isOrderRepeated() && ($button->getPaymentMode() !== PGPaymentData::MODE_RECURRING)) {
            $errors[] = "errors.button.order_repeated_without_recurring";
        }

        if (!in_array($button->getPaymentMode(), $paygreenFacade->getAvailablePaymentModes())) {
            $errors[] = "errors.button.unavailable_payment_mode";
        }

        if (!in_array($button->getPaymentType(), $paymentTypeManager->getCodes())) {
            $errors[] = "errors.button.unavailable_payment_type";
        }

        return $errors;
    }

    /**
     * @param PGPaymentInterfacesEntitiesButtonInterface $button
     * @return array
     */
    public function checkFilters(PGPaymentInterfacesEntitiesButtonInterface $button)
    {
        $errors = array();

        $buttonMinAmount = $button->getMinAmount();
        $buttonMaxAmount = $button->getMaxAmount();

        if (($buttonMinAmount > 0) && ($buttonMaxAmount > 0) && ($buttonMinAmount > $buttonMaxAmount)) {
            $errors[] = "errors.button.min_amount_greater_than_max_amount";
        }

        if ($button->getMaxAmount() < 0) {
            $errors[] = "errors.button.max_amount_positive";
        }

        if ($button->getMinAmount() < 0) {
            $errors[] = "errors.button.min_amount_positive";
        }

        return $errors;
    }

    /**
     * @param PGPaymentInterfacesEntitiesButtonInterface $button
     * @param string $currency
     * @return bool
     */
    public function isValidCurrency($button, $currency)
    {
        /** @var PGPaymentServicesPaygreenFacade $paygreenFacade */
        $paygreenFacade = $this->getService('paygreen.facade');

        /** @var PGModuleServicesLogger $logger */
        $logger = $this->getService('logger');
        
        $buttonPaymentType = $button->getPaymentType();

        foreach ($paygreenFacade->getAvailablePaymentTypes() as $paymentType) {
            if ($buttonPaymentType === $paymentType->paymentType) {
                if ($paymentType->currency === $currency) {
                    return true;
                }
            }
        }

        $logger->debug("'$currency' not supported for '$buttonPaymentType' payment type.");

        return false;
    }
}
