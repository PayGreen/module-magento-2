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
 * @version   2.0.1
 *
 */

/**
 * Class BOPaymentServicesListenersDisplayBackoffice
 * @package BOPayment\Services\Listeners
 */
class BOPaymentServicesListenersDisplayBackoffice
{
    /** @var PGFrameworkServicesNotifier */
    private $notifier;

    /** @var PGPaymentServicesPaygreenFacade */
    private $paygreenFacade;

    private $bin;

    public function __construct(
        PGFrameworkServicesNotifier $notifier,
        PGPaymentServicesPaygreenFacade $paygreenFacade
    ) {
        $this->notifier = $notifier;
        $this->paygreenFacade = $paygreenFacade;
    }

    public function listen(PGServerComponentsActionEvent $event)
    {
        // Thrashing unused arguments
        $this->bin = $event;

        if (!$this->paygreenFacade->isConfigured()) {
            $this->notifier->add(PGFrameworkServicesNotifier::STATE_NOTICE, 'misc.account.notification.needLogin');
        } elseif (!$this->paygreenFacade->isConnected()) {
            $this->notifier->add(PGFrameworkServicesNotifier::STATE_FAILURE, 'misc.account.notification.incorrectKey');
        }
    }
}