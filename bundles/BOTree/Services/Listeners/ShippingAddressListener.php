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
 * @version   2.4.0
 *
 */

namespace PGI\Module\BOTree\Services\Listeners;

use PGI\Module\PGFramework\Services\Notifier;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGTree\Services\Handlers\TreeAuthenticationHandler;

/**
 * Class ShippingAddressListener
 * @package BOTree\Services\Listeners
 */
class ShippingAddressListener
{
    /** @var Notifier */
    private $notifier;

    /** @var TreeAuthenticationHandler */
    private $treeAuthenticationHandler;

    /** @var Settings */
    private $settings;

    public function __construct(
        Notifier $notifier,
        TreeAuthenticationHandler $treeAuthenticationHandler,
        Settings $settings
    ) {
        $this->notifier = $notifier;
        $this->treeAuthenticationHandler = $treeAuthenticationHandler;
        $this->settings = $settings;
    }

    public function listen()
    {
        $isConnected = $this->treeAuthenticationHandler->isConnected();

        $tree_shipping_address_line_1 = $this->settings->get('shipping_address_line_1');

        if ($isConnected && (empty($tree_shipping_address_line_1))) {
            $this->notifier->add(
                Notifier::STATE_NOTICE,
                'misc.tree_configuration.notifications.needShippingAddress'
            );
        }
    }
}
