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

namespace PGI\Module\PGShop\Services\Managers;

use PGI\Module\PGDatabase\Foundations\AbstractManager;
use PGI\Module\PGDatabase\Interfaces\RepositoryInterface;
use PGI\Module\PGPayment\Interfaces\Entities\ButtonEntityInterface;
use PGI\Module\PGPayment\Interfaces\Entities\CategoryHasPaymentTypeEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\CategoryEntityInterface;
use PGI\Module\PGShop\Interfaces\Repositories\CategoryRepositoryInterface;
use PGI\Module\PGShop\Services\Handlers\ShopHandler;

/**
 * Class CategoryManager
 *
 * @package PGShop\Services\Managers
 * @method CategoryRepositoryInterface getRepository()
 */
class CategoryManager extends AbstractManager
{
    /** @var ShopHandler */
    private $shopHandler;

    private $initialized = false;

    private $categories = array();

    /**
     * @param ShopHandler $shopHandler
     */
    public function setShopHandler($shopHandler)
    {
        $this->shopHandler = $shopHandler;
    }

    public function getByPrimary($id)
    {
        $id = (int) $id;

        if (isset($this->categories[$id])) {
            $category = $this->categories[$id];
        } else {
            $category = $this->getRepository()->findByPrimary($id);

            if ($category !== null) {
                $this->categories[$id] = $category;
            }
        }

        return $category;
    }

    public function getAll()
    {
        if (!$this->initialized) {
            $this->loadCategories();
        }

        return $this->categories;
    }

    public function getCurrentCategories()
    {
        return $this->getRepository()->findAllByShop($this->shopHandler->getCurrentShopPrimary());
    }

    public function getRawCategories()
    {
        $categories = array();

        /** @var CategoryEntityInterface $category */
        foreach ($this->getAll() as $category) {
            $categories[$category->id()] = $category->getPaymentModes();
        }

        return $categories;
    }

    public function getRootCategories()
    {
        $categories = $this->getAll();

        /** @var CategoryEntityInterface[] $rootCategories */
        $rootCategories = array();

        /** @var CategoryEntityInterface $category */
        foreach ($categories as $category) {
            if (!$category->hasParent()) {
                $rootCategories[] = $category;
            }
        }

        return $rootCategories;
    }

    /**
     * @param ButtonEntityInterface $button
     * @param CategoryEntityInterface[] $productCategories
     * @return bool
     */
    public function isEligibleCategory(ButtonEntityInterface $button, array $productCategories)
    {
        $filteredCategoriesPrimaries = array_keys($button->getFilteredCategoryPrimaries());

        if (empty($filteredCategoriesPrimaries)) {
            return false;
        } else {
            foreach ($productCategories as $category) {
                if (in_array($category->id(), $filteredCategoriesPrimaries)) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function loadCategories()
    {
        $this->categories = array();

        foreach ($this->getCurrentCategories() as $category) {
            $this->categories[$category->id()] = $category;
        }

        $this->initialized = true;

        $this->hierarchizeCategories();
        $this->insertPaymentTypes();
    }

    protected function hierarchizeCategories()
    {
        /** @var CategoryEntityInterface $category */
        foreach ($this->categories as $category) {
            $id_parent = $category->getParentId();

            if ($id_parent > 0) {
                $parentCategory = $this->getByPrimary($id_parent);

                if ($parentCategory !== null) {
                    $parentCategory->addChild($category);
                    $category->setParent($parentCategory);
                }
            }
        }
    }

    protected function insertPaymentTypes()
    {
        $categoryPayments = $this->getService('repository.category_has_payment_type')->findAll();

        /** @var CategoryHasPaymentTypeEntityInterface $categoryPayment */
        foreach ($categoryPayments as $categoryPayment) {
            /** @var CategoryEntityInterface|null $category */
            $category = $categoryPayment->getCategory();

            if ($category !== null) {
                $category->addPaymentMode($categoryPayment->getPaymentType());
            }
        }
    }
}
