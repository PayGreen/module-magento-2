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
 * @version   2.0.1
 *
 */

/**
 * Class PGMagentoEntitiesCategory
 *
 * @package PGMagento\Entities
 * @method Magento\Catalog\Model\Category getLocalEntity()
 */
class PGMagentoEntitiesCategory extends PGShopFoundationsEntitiesCategory
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
