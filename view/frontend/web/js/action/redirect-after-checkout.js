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
 * @version   0.3.4
 */
define(
    [
        'jquery',
        'mage/url'
    ],
    function ($, urlBuilder) {
        'use strict';

        let buttons = window.checkoutConfig.paygreen.buttons;

        return function (id_button) {
            let button;

            buttons.forEach(function(element) {
                if (element.id === id_button) {
                    button = element;
                }
            });

            let url;

            if (button.insite) {
                url = urlBuilder.build("pgfront/checkout/insite") + '?button=' + id_button;
            } else {
                url = urlBuilder.build("pgfront/checkout/redirect") + '?button=' + id_button;
            }

            console.log('url', url);

            $.mage.redirect(url);
        };
    }
);