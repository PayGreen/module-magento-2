<?php
/**
 * 2014 - 2020 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.0.0
 */

class APPbackofficeServicesViewsNotificationsView extends PGViewServicesView
{
    /** @var PGFrameworkServicesNotifier */
    private $notifier;

    private static $NOTIFICATION_CODES = array(
        PGFrameworkServicesNotifier::STATE_FAILURE => 'failure',
        PGFrameworkServicesNotifier::STATE_NOTICE => 'notice',
        PGFrameworkServicesNotifier::STATE_SUCCESS => 'success'
    );

    public function __construct(PGFrameworkServicesNotifier $notifier)
    {
        $this->notifier = $notifier;

        $this->setTemplate('block-notifications');
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getData()
    {
        $notifications = $this->notifier->collect();

        array_walk($notifications, array($this, 'formatNotification'));

        return array(
            'notifications' => $notifications
        );
    }

    public function formatNotification(array &$notification)
    {
        if (!array_key_exists('type', $notification)) {
            throw new Exception("Notification must contains 'type' key.");
        }

        $type = $notification['type'];

        $notification['type'] = self::$NOTIFICATION_CODES[$type];
    }
}
