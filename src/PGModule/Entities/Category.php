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
 * @version   1.1.0
 */

/**
 * Class PGModuleEntitiesCategory
 *
 * @package PGModule\Entities
 * @method Magento\Catalog\Model\Category getLocalEntity()
 */
class PGModuleEntitiesCategory extends PGDomainFoundationsEntitiesAbstractCategory
{
    /** @var string */
    private $slug;

    /**
     * PGModuleEntitiesWrappedCategory constructor.
     * @param Magento\Catalog\Model\Category $localEntity
     */
    protected function hydrateFromLocalEntity($localEntity)
    {
        $this->slug = $this->slugify($localEntity->getName());
    }

    public function getParentId()
    {
        return (int) $this->getLocalEntity()->getParentId();
    }

    /**
     * @return int
     */
    public function id()
    {
        return (int) $this->getLocalEntity()->getId();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return (string) $this->getLocalEntity()->getName();
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return (string) $this->slug;
    }
}
