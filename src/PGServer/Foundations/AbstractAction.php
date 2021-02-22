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
 * @version   1.2.4
 *
 */

/**
 * Class PGServerFoundationsAbstractAction
 * @package PGServer\Foundations
 */
abstract class PGServerFoundationsAbstractAction extends PGServerFoundationsAbstractController implements PGServerInterfacesActionInterface
{
    private $config = array(
        'success_message' => 'server.action.notices.default.success'
    );

    private $success = false;

    protected $default = array();

    public function __construct(
        PGFrameworkServicesNotifier $notifier,
        PGFrameworkServicesLogger $logger,
        PGServerServicesLinker $linker
    ) {
        parent::__construct($notifier, $logger, $linker);

        if (is_array($this->default) && !empty($this->default)) {
            $this->config = array_merge($this->config, $this->default);
        }
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
                PGFrameworkServicesNotifier::STATE_SUCCESS,
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
     * @param string $key
     * @return mixed
     * @throws Exception
     */
    protected function getConfig($key)
    {
        if (!$this->hasConfig($key)) {
            throw new Exception("Required parameter '$key' not found.");
        }

        return $this->config[$key];
    }

    protected function hasConfig($key)
    {
        return array_key_exists($key, $this->config);
    }

    /**
     * @return PGServerFoundationsAbstractResponse
     */
    abstract public function process();
}
