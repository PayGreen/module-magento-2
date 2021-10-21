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

namespace PGI\Module\BOCharity\Services\Listeners;

use Exception;
use PGI\Module\PGCharity\Services\Handlers\CharityAuthenticationHandler;
use PGI\Module\PGClient\Exceptions\Response as ResponseException;
use PGI\Module\PGFramework\Services\Notifier;
use PGI\Module\PGServer\Components\Events\Action as ActionEventComponent;

/**
 * Class DisplayBackofficeNotificationListener
 * @package BOCharity\Services\Listeners
 */
class DisplayBackofficeNotificationListener
{
    /** @var Notifier */
    private $notifier;

    /** @var CharityAuthenticationHandler */
    private $charityAuthenticationHandler;

    private $bin;

    public function __construct(
        Notifier $notifier,
        CharityAuthenticationHandler $charityAuthenticationHandler
    ) {
        $this->notifier = $notifier;
        $this->charityAuthenticationHandler = $charityAuthenticationHandler;
    }

    /**
     * @param ActionEventComponent $event
     * @throws Exception
     */
    public function listen(ActionEventComponent $event)
    {
        // Thrashing unused arguments
        $this->bin = $event;

        if (!$this->charityAuthenticationHandler->isConnected()) {
            $this->notifier->add(
                Notifier::STATE_NOTICE,
                'misc.charity_account.notifications.needLogin'
            );
        }
    }
}
