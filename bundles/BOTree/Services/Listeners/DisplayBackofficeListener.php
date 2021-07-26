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
 * @version   2.2.0
 *
 */

namespace PGI\Module\BOTree\Services\Listeners;

use PGI\Module\PGFramework\Services\Notifier;
use PGI\Module\PGServer\Components\Events\Action as ActionEventComponent;
use PGI\Module\PGTree\Services\Handlers\TreeAuthenticationHandler;

/**
 * Class DisplayBackofficeListener
 * @package BOTree\Services\Listeners
 */
class DisplayBackofficeListener
{
    /** @var Notifier */
    private $notifier;

    /** @var TreeAuthenticationHandler */
    private $treeAuthenticationHandler;

    private $bin;

    public function __construct(
        Notifier $notifier,
        TreeAuthenticationHandler $treeAuthenticationHandler
    ) {
        $this->notifier = $notifier;
        $this->treeAuthenticationHandler = $treeAuthenticationHandler;
    }

    public function listen(ActionEventComponent $event)
    {
        // Thrashing unused arguments
        $this->bin = $event;

        if (!$this->treeAuthenticationHandler->isConnected()) {
            $this->notifier->add(
                Notifier::STATE_NOTICE,
                'misc.tree_account.notifications.needLogin'
            );
        }
    }
}
