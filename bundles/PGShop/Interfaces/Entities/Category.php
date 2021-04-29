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
 * Interface PGShopInterfacesEntitiesCategory
 * @package PGShop\Interfaces\Entities
 */
interface PGShopInterfacesEntitiesCategory extends PGDatabaseInterfacesEntityWrapped
{
    /**
     * @return mixed
     */
    public function id();

    /**
     * @return int
     */
    public function getName();

    /**
     * @return int
     */
    public function getSlug();

    /**
     * @param PGShopInterfacesEntitiesCategory|null $parent
     */
    public function setParent(PGShopInterfacesEntitiesCategory $parent = null);

    /**
     * @return PGShopInterfacesEntitiesCategory|null
     */
    public function getParent();

    /**
     * @return bool
     */
    public function hasParent();

    /**
     * @return int
     */
    public function getParentId();

    /**
     * @param PGShopInterfacesEntitiesCategory $category
     */
    public function addChild(PGShopInterfacesEntitiesCategory $category);

    /**
     * @return PGShopInterfacesEntitiesCategory[]
     */
    public function getChildren();

    /**
     * @return bool
     */
    public function hasChildren();

    /**
     * @return int
     */
    public function getDepth();

    /**
     * @return array
     */
    public function getPaymentModes();

    public function addPaymentMode($mode);

    public function hasPaymentMode($mode);
}
