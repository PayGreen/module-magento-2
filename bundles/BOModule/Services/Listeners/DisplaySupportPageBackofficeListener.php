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
 * Class BOModuleServicesListenersDisplaySupportPageBackofficeListener
 * @package BOModule\Services\Listeners
 */
class BOModuleServicesListenersDisplaySupportPageBackofficeListener
{
    /** @var PGFrameworkServicesNotifier */
    private $notifier;

    /** @var PGShopInterfacesShopHandler */
    private $shopHandler;

    private $bin;

    public function __construct(
        PGFrameworkServicesNotifier $notifier,
        PGShopInterfacesShopHandler $shopHandler
    ) {
        $this->notifier = $notifier;
        $this->shopHandler = $shopHandler;
    }

    public function listen(PGServerComponentsActionEvent $event)
    {
        // Thrashing unused arguments
        $this->bin = $event;

        if ($this->shopHandler->isMultiShopActivated()) {
            $this->notifier->add(PGFrameworkServicesNotifier::STATE_NOTICE, 'pages.support.notices.global_settings');
        }
    }
}
