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
 * Class PGFrameworkServicesManagersButtonManager
 *
 * @package PGDomain\Services\Managers
 * @method PGDomainInterfacesRepositoriesButtonRepositoryInterface getRepository()
 */
class PGDomainServicesManagersButtonManager extends PGFrameworkFoundationsAbstractManager
{
    public function getByPrimary($id)
    {
        return $this->getRepository()->findByPrimary($id);
    }

    public function getAll()
    {
        return $this->getRepository()->findAll();
    }

    public function getNew()
    {
        return $this->getRepository()->create();
    }

    public function count()
    {
        return (int) $this->getRepository()->count();
    }

    public function save(PGDomainInterfacesEntitiesButtonInterface $button)
    {
        if ($button->id() > 0) {
            return $this->getRepository()->update($button);
        } else {
            return $this->getRepository()->insert($button);
        }
    }

    public function delete(PGDomainInterfacesEntitiesButtonInterface $button)
    {
        return $this->getRepository()->delete($button);
    }

    public function getValidButtons(PGDomainInterfacesCheckoutProvisionerInterface $checkoutProvisioner)
    {
        /** @var PGDomainInterfacesEntitiesButtonInterface[] $buttons */
        $buttons = $this->getAll();

        /** @var PGDomainInterfacesEntitiesButtonInterface[] $validButtons */
        $validButtons = array();

        /** @var PGDomainInterfacesEntitiesButtonInterface $button */
        foreach ($buttons as $button) {
            $isValidAmount = $this->isValidAmount($button, $checkoutProvisioner->getTotalUserAmount());
            $hasEligibleProduct = $this->hasEligibleProduct($button, $checkoutProvisioner->getItems());
            $errors = $this->check($button);

            if ($isValidAmount && $hasEligibleProduct && empty($errors)) {
                $validButtons[] = $button;
            }
        }

        usort($validButtons, function (
            PGDomainInterfacesEntitiesButtonInterface $a,
            PGDomainInterfacesEntitiesButtonInterface $b
        ) {
            if ($a->getPosition() === $b->getPosition()) {
                return 0;
            }
            return ($a->getPosition() < $b->getPosition()) ? -1 : 1;
        });

        return $validButtons;
    }

    /**
     * @param PGDomainInterfacesEntitiesButtonInterface $button
     * @param float $userAmount
     * @return bool
     */
    public function isValidAmount(PGDomainInterfacesEntitiesButtonInterface $button, $userAmount)
    {
        /** @var bool $result */
        $result = true;

        if (($button->getMaxAmount() > 0) && (($button->getMaxAmount() * 100) < $userAmount)) {
            $result = false;
        } elseif (($button->getMinAmount() > 0) && (($button->getMinAmount() * 100) > $userAmount)) {
            $result = false;
        }

        return $result;
    }

    /**
     * @param PGDomainInterfacesEntitiesButtonInterface $button
     * @param PGDomainInterfacesEntitiesCartItemInterface[] $items
     * @return bool
     */
    public function hasEligibleProduct(PGDomainInterfacesEntitiesButtonInterface $button, array $items)
    {
        /** @var PGDomainServicesManagersProductManager $productManager */
        $productManager = $this->getService('manager.product');

        /** @var PGDomainInterfacesEntitiesCartItemInterface $item */
        foreach ($items as $item) {
            if ($productManager->isEligibleProduct($item->getProduct(), $button->getPaymentType())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param PGDomainInterfacesEntitiesButtonInterface $button
     * @return array
     */
    public function check(PGDomainInterfacesEntitiesButtonInterface $button)
    {
        /** @var PGDomainServicesPaygreenFacade $paygreenFacade */
        $paygreenFacade = $this->getService('paygreen.facade');

        /** @var PGDomainServicesManagersPaymentTypeManager $paymentTypeManager */
        $paymentTypeManager = $this->getService('manager.payment_type');

        $errors = array();

        if (strlen($button->getLabel()) > 100) {
            $errors[] = "button.errors.title_max_length";
        } elseif (strlen($button->getLabel()) === 0) {
            $errors[] = "button.errors.title_min_length";
        }

        if ($button->getImageHeight() < 0) {
            $errors[] = "button.errors.image_height_positive";
        }

        if ($button->getMaxAmount() < 0) {
            $errors[] = "button.errors.max_amount_positive";
        }

        if ($button->getMinAmount() < 0) {
            $errors[] = "button.errors.min_amount_positive";
        }

        if ($button->getPosition() < 0) {
            $errors[] = "button.errors.position_positive";
        }

        if ($button->getPaymentNumber() > 1) {
            if ($button->getPaymentMode() === PGDomainData::MODE_CASH) {
                $errors[] = "button.errors.payment_number_with_cash";
            } elseif ($button->getPaymentMode() == PGDomainData::MODE_TOKENIZE) {
                $errors[] = "button.errors.payment_number_with_tokenize";
            }
        } else {
            if ($button->getPaymentMode() === PGDomainData::MODE_XTIME) {
                $errors[] = "button.errors.not_payment_number_with_xtime";
            } elseif ($button->getPaymentMode() == PGDomainData::MODE_RECURRING) {
                $errors[] = "button.errors.not_payment_number_with_recurring";
            }
        }

        if ($button->getPaymentMode() === PGDomainData::MODE_XTIME) {
            if ($button->getPaymentNumber() > 3) {
                $errors[] = "button.errors.xtime_fewer_than_3_commitment";
            }
        }

        if ($button->getPaymentReport() > 0) {
            if ($button->getPaymentMode() === PGDomainData::MODE_CASH) {
                $errors[] = "button.errors.payment_report_with_cash";
            } elseif ($button->getPaymentMode() === PGDomainData::MODE_TOKENIZE) {
                $errors[] = "button.errors.payment_report_with_tokenize";
            } elseif ($button->getPaymentMode() === PGDomainData::MODE_XTIME) {
                $errors[] = "button.errors.payment_report_with_xtime";
            }
        }

        if ($button->getFirstPaymentPart() !== 0) {
            if ($button->getPaymentMode() === PGDomainData::MODE_XTIME) {
                if (($button->getFirstPaymentPart() <= 0) || ($button->getFirstPaymentPart() >= 100)) {
                    $errors[] = "button.errors.first_payment_part_range";
                }
            } else {
                $errors[] = "button.errors.first_payment_part_without_xtime";
            }
        }

        if ($button->isOrderRepeated() && ($button->getPaymentMode() !== PGDomainData::MODE_RECURRING)) {
            $errors[] = "button.errors.order_repeated_without_recurring";
        }

        if (!in_array($button->getPaymentMode(), $paygreenFacade->getAvailablePaymentModes())) {
            $errors[] = "button.errors.unavailable_payment_mode";
        }

        if (!in_array($button->getPaymentType(), $paymentTypeManager->getCodes())) {
            $errors[] = "button.errors.unavailable_payment_type";
        }

        return $errors;
    }
}
