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

namespace PGI\Module\PGCharity\Services\Handlers;

use Exception;
use PGI\Module\PGCharity\Interfaces\Officers\CharityGiftOfficerInterface;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGShop\Interfaces\Entities\ProductEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\ShopEntityInterface;
use PGI\Module\PGShop\Services\Handlers\ShopHandler;
use PGI\Module\PGShop\Services\Managers\ProductManager;

/**
 * Class CharityGiftHandler
 * @package PGCharity\Services\Handlers;
 */
class CharityGiftHandler
{
    /** @var ProductManager */
    private $productManager;

    /** @var CharityGiftOfficerInterface */
    private $charityGiftOfficer;

    /** @var ShopHandler */
    private $shopHandler;

    public function __construct(
        ProductManager $productManager,
        CharityGiftOfficerInterface $charityGiftOfficer,
        ShopHandler $shopHandler
    ) {
        $this->productManager = $productManager;
        $this->charityGiftOfficer = $charityGiftOfficer;
        $this->shopHandler = $shopHandler;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getPrimary()
    {
        if (!$this->exist()) {
            throw new Exception('Gift primary not found.');
        }

        return $this->charityGiftOfficer->getPrimary($this->shopHandler->getCurrentShop());
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function exist()
    {
        $primary = $this->charityGiftOfficer->getPrimary($this->shopHandler->getCurrentShop());

        return (!empty($primary));
    }

    /**
     * @return ProductEntityInterface
     * @throws Exception
     */
    public function get()
    {
        $id = $this->getPrimary();

        return $this->productManager->getByPrimary($id);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isValid()
    {
        $result = false;

        $gift = $this->get();

        if ($gift !== null) {
            $result = $this->charityGiftOfficer->isValid(
                $gift,
                $this->shopHandler->getCurrentShop()
            );
        }

        return $result;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function install()
    {
        if ($this->exist() && ($this->get() !== null)) {
            if ($this->isValid()) {
                throw new Exception('Valid gift product already installed.');
            } else {
                $gift = $this->get();
                $this->charityGiftOfficer->validate($gift, $this->shopHandler->getCurrentShop());
            }
        } else {
            $this->charityGiftOfficer->create($this->shopHandler->getCurrentShop());
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function uninstall()
    {
        $gift = $this->get();
        return $this->charityGiftOfficer->disable($gift, $this->shopHandler->getCurrentShop());
    }
}
