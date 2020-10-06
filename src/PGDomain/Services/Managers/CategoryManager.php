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
 * Class PGDomainServicesManagersWrappedCategoryManager
 *
 * @package PGDomain\Services\Managers
 * @method PGDomainInterfacesRepositoriesCategoryRepositoryInterface getRepository()
 */
class PGDomainServicesManagersCategoryManager extends PGFrameworkFoundationsAbstractManager
{
    private $initialized = false;

    private $categories = array();

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

    public function getRawCategories()
    {
        $categories = array();

        /** @var PGDomainInterfacesEntitiesCategoryInterface $category */
        foreach ($this->getAll() as $category) {
            $categories[$category->id()] = $category->getPaymentModes();
        }

        return $categories;
    }

    public function getRootCategories()
    {
        $categories = $this->getAll();

        /** @var PGDomainInterfacesEntitiesCategoryInterface[] $rootCategories */
        $rootCategories = array();

        /** @var PGDomainInterfacesEntitiesCategoryInterface $category */
        foreach ($categories as $category) {
            if (!$category->hasParent()) {
                $rootCategories[] = $category;
            }
        }

        return $rootCategories;
    }

    protected function loadCategories()
    {
        $this->categories = array();

        foreach ($this->getRepository()->findAll() as $category) {
            $this->categories[$category->id()] = $category;
        }

        $this->initialized = true;

        $this->hierarchizeCategories();
        $this->insertPaymentTypes();
    }

    protected function hierarchizeCategories()
    {
        /** @var PGDomainInterfacesEntitiesCategoryInterface $category */
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

        /** @var PGDomainInterfacesEntitiesCategoryHasPaymentTypeInterface $categoryPayment */
        foreach ($categoryPayments as $categoryPayment) {
            /** @var PGDomainInterfacesEntitiesCategoryInterface|null $category */
            $category = $categoryPayment->getCategory();

            if ($category !== null) {
                $category->addPaymentMode($categoryPayment->getPaymentType());
            }
        }
    }
}
