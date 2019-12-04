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
 * @version   0.3.3
 */

/**
 * Class PGDomainFoundationsEntitiesAbstractProduct
 * @package PGDomain\Foundations\Entities
 */
abstract class PGDomainFoundationsEntitiesAbstractCategoryHasPaymentType extends PGFrameworkFoundationsAbstractEntityWrapped implements PGDomainInterfacesEntitiesCategoryHasPaymentTypeInterface
{
    /** @var null|PGDomainInterfacesEntitiesCategoryInterface */
    private $category = null;

    /**
     * @return PGDomainInterfacesEntitiesCategoryInterface
     * @throws Exception
     */
    public function getCategory()
    {
        if ($this->category === null) {
            $this->loadCategory();
        }

        return $this->category;
    }

    /**
     * @throws Exception
     */
    protected function loadCategory()
    {
        /** @var PGDomainServicesManagersCategoryManager $categoryManager */
        $categoryManager = $this->getService('manager.category');

        $id_category = $this->getCategoryId();

        $this->category = $categoryManager->getByPrimary($id_category);
    }
}
