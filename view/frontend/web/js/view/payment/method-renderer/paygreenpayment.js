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
 * @version   0.3.5
 */
define(
    [
        'Magento_Checkout/js/view/payment/default',
        'ko',
        'Paygreen_Payment/js/action/redirect-after-checkout'
    ],
    function (Component, ko, redirectAfterCheckout) {
        'use strict';

        let title = window.checkoutConfig.paygreen.title;
        let confirmation = window.checkoutConfig.paygreen.confirmation;
        let buttons = window.checkoutConfig.paygreen.buttons;

        let selectedButton = ko.observable(null);

        return Component.extend({
            defaults: {
                redirectAfterPlaceOrder: false,
                template: 'Paygreen_Payment/payment/paygreenpayment'
            },

            afterPlaceOrder: function () {
                redirectAfterCheckout(selectedButton());
                return false;
            },

            getTitle: function () {
                return title;
            },

            getConfirmation: function () {
                return confirmation;
            },

            getButtons: function () {
                return buttons;
            },

            isPictureDisplayed: function (displayMode) {
                let pictureModes = ['DEFAULT', 'IMAGE'];

                return pictureModes.includes(displayMode);
            },

            isTextDisplayed: function (displayMode) {
                let pictureModes = ['DEFAULT', 'TEXT'];

                return pictureModes.includes(displayMode);
            },

            selectButton: function(event, obj) {
                selectedButton(event.id);

                obj.currentTarget
                    .closest('#paygreen-payment-list-container')
                    .querySelector('.radio')
                    .click();

                return true;
            },

            isButtonSelected: function() {
                return (selectedButton() !== null);
            }
        });
    }
);