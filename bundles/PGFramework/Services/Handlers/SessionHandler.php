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
 * @version   2.0.0
 *
 */

/**
 * Class PGFrameworkServicesHandlersSessionHandler
 * @package PGFramework\Services\Handlers
 */
class PGFrameworkServicesHandlersSessionHandler implements PGFrameworkInterfacesHandlersSessionHandlerInterface
{
    /** @var PGFrameworkInterfacesSuperglobal */
    protected $sessionAdapter;

    private $bin;

    public function __construct(PGFrameworkInterfacesSuperglobal $sessionAdapter)
    {
        $this->sessionAdapter = $sessionAdapter;
    }

    /**
     * @inheritDoc
     */
    public function get($var)
    {
        if ($this->has($var)) {
            return $this->sessionAdapter[$var];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function set($var, $value)
    {
        $this->sessionAdapter[$var] = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function rem($var)
    {
        if ($this->has($var)) {
            unset($this->sessionAdapter[$var]);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function raz()
    {
        foreach ($this->sessionAdapter as $key => $value) {
            unset($this->sessionAdapter[$key]);

            // Thrashing unused var
            $this->bin = $value;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function has($var)
    {
        return isset($this->sessionAdapter[$var]);
    }
}
