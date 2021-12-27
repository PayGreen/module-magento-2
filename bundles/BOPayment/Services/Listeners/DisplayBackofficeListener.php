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

namespace PGI\Module\BOPayment\Services\Listeners;

use PGI\Module\PGFramework\Services\Notifier;
use PGI\Module\PGPayment\Services\Facades\PaygreenFacade;
use PGI\Module\PGServer\Components\Events\Action as ActionEventComponent;

/**
 * Class DisplayBackofficeListener
 * @package BOPayment\Services\Listeners
 */
class DisplayBackofficeListener
{
    /** @var Notifier */
    private $notifier;

    /** @var PaygreenFacade */
    private $paygreenFacade;

    private $bin;

    public function __construct(
        Notifier $notifier,
        PaygreenFacade $paygreenFacade
    ) {
        $this->notifier = $notifier;
        $this->paygreenFacade = $paygreenFacade;
    }

    public function listen(ActionEventComponent $event)
    {
        // Thrashing unused arguments
        $this->bin = $event;

        if (!$this->paygreenFacade->isConfigured()) {
            $this->notifier->add(Notifier::STATE_NOTICE, 'misc.account.notification.needLogin');
        } elseif (!$this->paygreenFacade->isConnected()) {
            $this->notifier->add(Notifier::STATE_FAILURE, 'misc.account.notification.incorrectKey');
        }
    }
}
