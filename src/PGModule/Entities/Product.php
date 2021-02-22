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
 * @version   1.2.5
 *
 */

/**
 * Class PGModuleEntitiesCustomer
 *
 * @package PGModule\Entities
 * @method Magento\Catalog\Model\Product getLocalEntity()
 */
class PGModuleEntitiesProduct extends PGDomainFoundationsEntitiesAbstractProduct implements PGDomainInterfacesEntitiesProductInterface
{
    protected function hydrateFromLocalEntity($localEntity)
    {
        // Do nothing.
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->getLocalEntity()->getName();
    }

    public function getWeight()
    {
        return $this->getLocalEntity()->getWeight();
    }

    /**
     * @inheritdoc
     */
    protected function preloadCategories()
    {
        /** @var PGDomainServicesManagersCategoryManager $categoryManager */
        $categoryManager = $this->getService('manager.category');

        $categories = array();

        foreach ($this->getLocalEntity()->getCategoryIds() as $id_category) {
            $category = $categoryManager->getByPrimary($id_category);

            if ($category !== null) {
                $categories[] = $category;
            }
        }

        return $categories;
    }
}
