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
 * Class PGMagentoEntitiesShop
 *
 * @package PGMagento\Entities
 * @method Magento\Store\Model\Group getLocalEntity()
 */
class PGMagentoEntitiesShop extends PGDatabaseFoundationsEntityWrapped implements PGShopInterfacesEntitiesShop
{
    public function id()
    {
        return (int) $this->getLocalEntity()->getId();
    }

    public function getName()
    {
        return $this->getLocalEntity()->getName();
    }

    public function getMail()
    {
        return $this->getLocalEntity()->getWebsite()->getConfig('trans_email/ident_sales/email');
    }
}