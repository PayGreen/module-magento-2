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
 * @version   2.6.1
 *
 */

namespace PGI\Module\PGModule\Components\Storages;

use Exception;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGSystem\Foundations\AbstractStorage;

/**
 * Class Setting
 * @package PGModule\Components\Storages
 */
class Setting extends AbstractStorage
{
    /** @var Settings */
    private $settings;

    /** @var string */
    private $key;

    /**
     * Setting constructor.
     * @param Settings $settings
     * @param string $key
     * @throws Exception
     */
    public function __construct(Settings $settings, $key)
    {
        $this->settings = $settings;
        $this->key = $key;

        $this->loadData();
    }

    /**
     * @inheridoc
     * @throws Exception
     */
    protected function loadData()
    {
        $data = $this->settings->get($this->key);

        if (!is_array($data)) {
            throw new Exception("'{$this->key}' setting must be an array.");
        }

        $this->setData($data);
    }

    /**
     * @throws Exception
     */
    protected function saveData()
    {
        $this->settings->set($this->key, $this->getData());
    }
}
