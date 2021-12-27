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
 * @version   2.5.1
 *
 */

namespace PGI\Module\PGPayment\Entities;

use PGI\Module\PGDatabase\Foundations\AbstractEntityPersisted;
use PGI\Module\PGPayment\Interfaces\Entities\ProcessingEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\ShopEntityInterface;
use PGI\Module\PGShop\Services\Managers\OrderManager;
use PGI\Module\PGShop\Services\Managers\ShopManager;
use PGI\Module\PGShop\Tools\Price as PriceTool;
use DateTime;

/**
 * Class Processing
 * @package PGPayment\Entities
 */
class Processing extends AbstractEntityPersisted implements ProcessingEntityInterface
{
    /** @var OrderEntityInterface|null */
    private $order = null;

    /** @var ShopEntityInterface */
    private $shop = null;

    /** @inheritDoc */
    public function getShopPrimary()
    {
        return (string) $this->get('id_shop');
    }

    /** @inheritDoc */
    public function getShop()
    {
        if (($this->shop === null) && ($this->getShopPrimary() > 0)) {
            $this->loadShop();
        }

        return $this->shop;
    }

    protected function loadShop()
    {
        /** @var ShopManager $shopManager */
        $shopManager = $this->getService('manager.shop');

        $id_shop = $this->getShopPrimary();

        $this->shop = $shopManager->getByPrimary($id_shop);
    }

    /** @inheritDoc */
    public function getReference()
    {
        return (string) $this->get('reference');
    }

    /** @inheritDoc */
    public function isSuccess()
    {
        return (bool) $this->get('success');
    }

    /** @inheritDoc */
    public function getStatus()
    {
        return (string) $this->get('status');
    }

    /** @inheritDoc */
    public function getPid()
    {
        return (string) $this->get('pid');
    }

    /** @inheritDoc */
    public function getPidStatus()
    {
        return (string) $this->get('pid_status');
    }

    /** @inheritDoc */
    public function getCreatedAt()
    {
        $timestamp = (int) $this->get('created_at');

        return new DateTime("@$timestamp");
    }

    /** @inheritDoc */
    public function getEchoes()
    {
        $echoes = array();

        foreach ($this->get('echoes') as $echo) {
            $echoes = new DateTime("@$echo");
        }

        return $echoes;
    }

    public function addEcho(DateTime $echo)
    {
        $echoes = $this->get('echoes');

        $echoes[] = $echo->getTimestamp();

        $this->set('echoes', $echoes);
    }

    /** @inheritDoc */
    public function getAmount()
    {
        return (int) $this->get('amount');
    }

    /** @inheritDoc */
    public function getUserAmount()
    {
        return PriceTool::toFloat($this->getAmount());
    }

    /** @inheritDoc */
    public function getOrderPrimary()
    {
        return (string) $this->get('id_order');
    }

    /** @inheritDoc */
    public function getOrder()
    {
        if (($this->order === null) && ($this->getOrderPrimary() > 0)) {
            $this->loadOrder();
        }

        return $this->order;
    }

    protected function loadOrder()
    {
        /** @var OrderManager $orderManager */
        $orderManager = $this->getService('manager.order');

        $id_order = $this->getOrderPrimary();

        $this->order = $orderManager->getByPrimary($id_order);
    }

    /** @inheritDoc */
    public function getOrderStateFrom()
    {
        return (string) $this->get('state_from');
    }

    /** @inheritDoc */
    public function getOrderStateTo()
    {
        return (string) $this->get('state_to');
    }
}
