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

namespace PGI\Module\PGCharity\Services\Handlers;

use Exception;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGShop\Interfaces\Entities\ShopableItemEntityInterface;
use PGI\Module\PGShop\Services\Managers\CartManager;
use PGI\Module\PGShop\Services\Managers\ProductManager;

/**
 * Class CharityCartHandler
 * @package PGCharity\Services\Handlers
 */
class CharityCartHandler
{
    /** @var CartManager */
    private $cartManager;

    /** @var CharityGiftHandler */
    private $charityGiftHandler;

    /** @var ProductManager */
    private $productManager;

    /** @var Logger */
    private $logger;

    /** @var int|null */
    private $giftProductPrimary = null;

    public function __construct(
        CartManager $cartManager,
        CharityGiftHandler $charityGiftHandler,
        ProductManager $productManager,
        Logger $logger
    ) {
        $this->cartManager = $cartManager;
        $this->charityGiftHandler = $charityGiftHandler;
        $this->productManager = $productManager;
        $this->logger = $logger;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function hasGift()
    {
        $cart = $this->cartManager->getCurrent();

        /** @var ShopableItemEntityInterface $item */
        foreach ($cart->getItems() as $item) {
            if ($item->getProduct()->id() === $this->getGiftProductPrimary()) {
                return true;
            }
        }

        $this->logger->debug('No gift product found in the cart.');

        return false;
    }

    /**
     * @return ShopableItemEntityInterface|null
     * @throws Exception
     */
    public function getGift()
    {
        $cart = $this->cartManager->getCurrent();

        /** @var ShopableItemEntityInterface $item */
        foreach ($cart->getItems() as $item) {
            if ($item->getProduct()->id() === $this->getGiftProductPrimary()) {
                return $item;
            }
        }
        
        return null;
    }

    /**
     * @param float $amount
     * @return bool
     * @throws Exception
     */
    public function addGift($amount)
    {
        try {
            if ($this->hasGift() === true) {
                $this->removeGift();
            }

            if (!$this->charityGiftHandler->isValid()) {
                $this->charityGiftHandler->install();
            }

            $giftProduct = $this->productManager->getByPrimary($this->getGiftProductPrimary());

            $this->cartManager->addItem($giftProduct, $amount);
        } catch (Exception $exception) {
            $this->logger->error(
                'An error occured while adding gift to the cart : ' . $exception->getMessage(),
                $exception
            );

            return false;
        }

        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function removeGift()
    {
        try {
            if (!$this->hasGift()) {
                $this->logger->debug('Cart does not contain any gift. Nothing to remove.');
                return true;
            }

            if (!$this->charityGiftHandler->isValid()) {
                $this->charityGiftHandler->install();
            }

            $giftProduct = $this->productManager->getByPrimary($this->getGiftProductPrimary());

            $this->cartManager->removeItem($giftProduct);
        } catch (Exception $exception) {
            $this->logger->error(
                'An error occured while removing gift from the cart : ' . $exception->getMessage(),
                $exception
            );

            return false;
        }

        return true;
    }

    /**
     * @return int
     */
    public function getCartTotalAmount()
    {
        return $this->cartManager->getCurrent()->getTotalCost();
    }

    /**
     * @return int
     * @throws Exception
     */
    private function getGiftProductPrimary()
    {
        if ($this->giftProductPrimary === null) {
            $this->giftProductPrimary = $this->charityGiftHandler->getPrimary();
        }

        return $this->giftProductPrimary;
    }
}
