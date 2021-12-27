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

namespace PGI\Module\PGServer\Foundations;

use PGI\Module\PGFramework\Services\Notifier;
use PGI\Module\PGServer\Foundations\AbstractController;
use PGI\Module\PGServer\Foundations\AbstractResponse;
use PGI\Module\PGServer\Interfaces\ActionInterface;
use Exception;

/**
 * Class AbstractAction
 * @package PGServer\Foundations
 */
abstract class AbstractAction extends AbstractController implements ActionInterface
{
    private static $DEFAULT = array(
        'success_message' => 'actions.server.notices.result.success'
    );

    private $config = array();

    private $success = false;

    protected $default = array();

    public function __construct()
    {
        $this->config = $this->buildDefaultConfiguration();
    }

    protected function buildDefaultConfiguration()
    {
        return array_merge(self::$DEFAULT, $this->default);
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    protected function setSuccess($success = true)
    {
        if ($success && $this->hasConfig('success_message')) {
            $this->getNotifier()->add(
                Notifier::STATE_SUCCESS,
                $this->getConfig('success_message')
            );
        }

        $this->success = $success;
    }

    /**
     * @inheritDoc
     */
    public function addConfig(array $config)
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setConfig(array $config)
    {
        $this->config = array_merge($this->buildDefaultConfiguration(), $config);

        return $this;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getConfig($key)
    {
        if (!$this->hasConfig($key)) {
            throw new Exception("Required parameter '$key' not found.");
        }

        return $this->config[$key];
    }

    /**
     * @inheritDoc
     */
    public function hasConfig($key)
    {
        return array_key_exists($key, $this->config);
    }

    /**
     * @return AbstractResponse
     */
    abstract public function process();
}
