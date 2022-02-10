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

namespace PGI\Module\BOCharity\Services\Listeners;

use PGI\Module\PGCharity\Services\Handlers\CharityAccountHandler;
use PGI\Module\PGClient\Exceptions\Response as ResponseException;
use PGI\Module\PGFramework\Services\Notifier;
use PGI\Module\PGServer\Components\Events\Action as ActionEventComponent;

/**
 * Class DisplayTestModeExpirationNotificationListener
 * @package BOCharity\Services\Listeners
 */
class DisplayTestModeExpirationNotificationListener
{
    /** @var Notifier */
    private $notifier;

    /** @var CharityAccountHandler */
    private $charityAccountHandler;

    private $bin;

    public function __construct(
        Notifier $notifier,
        CharityAccountHandler $charityAccountHandler
    ) {
        $this->notifier = $notifier;
        $this->charityAccountHandler = $charityAccountHandler;
    }

    /**
     * @param ActionEventComponent $event
     * @throws ResponseException
     */
    public function listen(ActionEventComponent $event)
    {
        // Thrashing unused arguments
        $this->bin = $event;
        
        if ($this->charityAccountHandler->isTestModeExpired() && !$this->charityAccountHandler->isMandateSigned()) {
            $this->notifier->add(
                Notifier::STATE_NOTICE,
                'misc.charity_account.notifications.test_mode.expired'
            );
        }
    }
}
