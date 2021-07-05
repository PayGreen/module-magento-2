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
 * @version   2.1.0
 *
 */

/**
 * Class PGShopServicesManagersProduct
 *
 * @package PGShop\Services\Managers
 * @method PGShopInterfacesRepositoriesProduct getRepository()
 */
class PGShopServicesManagersProduct extends PGDatabaseFoundationsManager
{
    /**
     * @param int $id
     * @return PGShopInterfacesEntitiesProduct|null
     */
    public function getByPrimary($id)
    {
        return $this->getRepository()->findByPrimary($id);
    }

    /**
     * @param PGShopInterfacesEntitiesProduct $product
     * @param string $type
     * @return bool
     */
    public function isEligibleProduct(PGShopInterfacesEntitiesProduct $product, $type)
    {
        /** @var PGPaymentServicesManagersCategoryHasPaymentTypeManager $categoryPaymentManager */
        $categoryPaymentManager = $this->getService('manager.category_has_payment_type');

        /** @var PGShopInterfacesEntitiesCategory[] $categories */
        $categories = $product->getCategories();

        $is_eligible = false;

        if ($categoryPaymentManager->isUnlimitedPaymentType($type)) {
            $is_eligible = true;
        } else {
            /** @var PGShopInterfacesEntitiesCategory $category */
            foreach ($categories as $category) {
                if ($categoryPaymentManager->isEligibleCategory($category, $type)) {
                    $is_eligible = true;
                    break;
                }
            }
        }

        return $is_eligible;
    }
}
