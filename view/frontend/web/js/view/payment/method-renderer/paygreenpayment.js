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
 * @version   2.6.0
 *
 */
define(
    [
        'Magento_Checkout/js/view/payment/default',
        'ko',
        'jquery'
    ],
    function (Component, ko, $) {
        'use strict';

        let title = window.checkoutConfig.paygreen.title;
        let submitTitle = window.checkoutConfig.paygreen.submitTitle;
        let confirmation = window.checkoutConfig.paygreen.confirmation;
        let buttons = window.checkoutConfig.paygreen.buttons;

        let selectedButton = ko.observable(null);

        return Component.extend({
            defaults: {
                redirectAfterPlaceOrder: false,
                template: 'Paygreen_Payment/payment/paygreenpayment'
            },

            afterPlaceOrder: function () {
                this.redirectAfterCheckout(selectedButton());
                return false;
            },

            getTitle: function () {
                return title;
            },

            getConfirmation: function () {
                return confirmation;
            },

            getSubmitTitle: function() {
                return submitTitle;
            },

            getButtons: function () {
                return buttons;
            },

            getFirstButton: function () {
                return buttons[0];
            },

            hasManyButtons: function () {
                return (buttons.length > 1);
            },

            computeButtonPictureHeight: function(buttonHeight) {
                if (buttonHeight > 0) {
                    return buttonHeight + 'px';
                } else {
                    return 'auto';
                }
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
                if (buttons.length === 1) {
                    selectedButton(this.getFirstButton().id);
                } else {
                    selectedButton(event.id);

                    obj.currentTarget
                        .closest('#paygreen-payment-list-container')
                        .querySelector('.radio')
                        .click();
                }

                return true;
            },

            isButtonSelected: function() {
                return (selectedButton() !== null);
            },

            redirectAfterCheckout: function (id_button) {
                this.getButtons().forEach(function(button) {
                    if (button.id === id_button) {
                        $.mage.redirect(button.url);
                    }
                });
            }
        });
    }
);