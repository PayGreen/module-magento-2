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

namespace PGI\Module\BOModule\Services\Views;

use PGI\Module\BOModule\Services\Handlers\MenuHandler;
use PGI\Module\PGShop\Services\Handlers\ShopHandler;
use PGI\Module\PGShop\Services\Managers\ShopManager;
use PGI\Module\PGSystem\Components\Parameters;
use PGI\Module\PGView\Services\View;
use Exception;

/**
 * Class MenuView
 * @package BOModule\Services\Views
 */
class MenuView extends View
{
    /** @var MenuHandler */
    private $menuHandler;

    /** @var ShopManager */
    private $shopManager;

    /** @var ShopHandler */
    private $shopHandler;

    /** @var Parameters */
    private $parameters;

    public function __construct(
        MenuHandler $menuHandler,
        ShopManager $shopManager,
        ShopHandler $shopHandler,
        Parameters $parameters
    ) {
        $this->menuHandler = $menuHandler;
        $this->shopManager = $shopManager;
        $this->shopHandler = $shopHandler;
        $this->parameters = $parameters;

        $this->setTemplate('block-menu');
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getData()
    {
        $data = parent::getData();

        if (!array_key_exists('selected', $data)) {
            throw new Exception("MenuView require 'selected' attribut.");
        }

        $data['entries'] = $this->menuHandler->getEntries();
        $data['logo'] = $this->parameters['logo'];

        if ($this->menuHandler->isShopSelectorActivated() && $this->shopHandler->isMultiShopActivated()) {
            $data['shops'] = $this->shopManager->getAll();
            $data['currentShop'] = $this->shopHandler->getCurrentShop();
        } else {
            $data['shops'] = array();
        }

        return $data;
    }
}
