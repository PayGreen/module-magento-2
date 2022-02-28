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

namespace PGI\Module\PGShop\Services\Managers;

use PGI\Module\PGDatabase\Foundations\AbstractManager;
use PGI\Module\PGPayment\Services\Managers\CategoryHasPaymentTypeManager;
use PGI\Module\PGShop\Interfaces\Entities\CategoryEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\ProductEntityInterface;
use PGI\Module\PGShop\Interfaces\Repositories\ProductRepositoryInterface;

/**
 * Class ProductManager
 *
 * @package PGShop\Services\Managers
 * @method ProductRepositoryInterface getRepository()
 */
class ProductManager extends AbstractManager
{
    /**
     * @param int $id
     * @return ProductEntityInterface|null
     */
    public function getByPrimary($id)
    {
        return $this->getRepository()->findByPrimary($id);
    }

    /** @return ProductEntityInterface[] */
    public function getAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @param ProductEntityInterface $product
     * @param string $type
     * @return bool
     */
    public function isEligibleProduct(ProductEntityInterface $product, $type)
    {
        /** @var CategoryHasPaymentTypeManager $categoryPaymentManager */
        $categoryPaymentManager = $this->getService('manager.category_has_payment_type');

        /** @var CategoryEntityInterface[] $categories */
        $categories = $product->getCategories();

        $is_eligible = false;

        if ($categoryPaymentManager->isUnlimitedPaymentType($type)) {
            $is_eligible = true;
        } else {
            /** @var CategoryEntityInterface $category */
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
