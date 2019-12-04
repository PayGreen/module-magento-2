<?php
/**
 * 2014 - 2019 Watt Is It
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
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.2
 */

namespace Paygreen\Payment\Block;

use Paygreen\Payment\Foundations\AbstractTemplate;
use PGFrameworkServicesSettings;

class DisplayModuleConfig extends AbstractTemplate
{
    public function getParameters()
    {
        /** @var PGFrameworkServicesSettings $settings */
        $settings = $this->getService('settings');

        return [
            'public_key' => $settings->get('public_key'),
            'private_key' => $settings->get('private_key'),
            'active' => $settings->get('active'),
            'visibility' => $settings->get('visibility'),
            'title' => $settings->get('title'),
            'payment_confirmation_button' => $settings->get('payment_confirmation_button'),
            'payment_success_text' => $settings->get('payment_success_text'),
            'payment_error_text' => $settings->get('payment_error_text'),
            'behavior_payment_refused' => $settings->get('behavior_payment_refused'),
            'behavior_transmit_refund' => $settings->get('behavior_transmit_refund')
        ];
    }
}
