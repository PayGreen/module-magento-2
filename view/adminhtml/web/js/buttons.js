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

window.addEventListener('load', function() {
    let selectorElements = document.getElementsByClassName('js-payment-mode-selector');

    Array.prototype.forEach.call(selectorElements, function(element) {
        element.addEventListener('change', function(event) {
            let paymentMode = event.target.value;
            let id = event.target.dataset.button;

            manageButtonFields(paymentMode, id);
        });

        let paymentMode = element.value;
        let id = element.dataset.button;

        manageButtonFields(paymentMode, id);
    });

    selectorElements = document.getElementsByClassName('js-integration-selector');

    Array.prototype.forEach.call(selectorElements, function(element) {
        element.addEventListener('change', function(event) {
            displayInsiteWarning(event.target);
        });

        displayInsiteWarning(element);
    });

    selectorElements = document.getElementsByClassName('js-payment-type-selector');

    Array.prototype.forEach.call(selectorElements, function(element) {
        element.addEventListener('change', function(event) {
            let paymentType = event.target.value;
            let id = event.target.dataset.button;

            checkPaymentModeCompatibility(paymentType, id);
        });
    });

    selectorElements = document.getElementsByClassName('js-default-picture');

    Array.prototype.forEach.call(selectorElements, function(element) {
        element.addEventListener('change', function(event) {
            let checked = event.target.checked;

            if (checked) {
                let id = event.target.dataset.button;
                document.getElementById('image_' + id).value = '';
            }
        });
    });

    selectorElements = document.getElementsByClassName('js-image');

    Array.prototype.forEach.call(selectorElements, function(element) {
        element.addEventListener('change', function(event) {
            let id = event.target.dataset.button;
            document.getElementById('defaultPicture_' + id).checked = false;
        });
    });

    selectorElements = document.getElementsByClassName('js-expendable');

    Array.prototype.forEach.call(selectorElements, function(element) {
        element.addEventListener('click', function(event) {
            let parent = event.target.closest('.pg-button-form');
            if (!parent.classList.contains('expended')) {
                parent.classList.add('expended');
                event.stopPropagation();
            }
        });
    });

    selectorElements = document.getElementsByClassName('js-unexpendable');

    Array.prototype.forEach.call(selectorElements, function(element) {
        element.addEventListener('click', function(event) {
            let parent = event.target.closest('.pg-button-form');
            if (parent.classList.contains('expended')) {
                parent.classList.remove('expended');
                event.stopPropagation();
            }
        });
    });

    selectorElements = document.getElementsByClassName('js-delete-button');

    Array.prototype.forEach.call(selectorElements, function(element) {
        element.addEventListener('click', function(event) {
            let url = event.target.dataset.url;
            if (confirm("Voulez-vous vraiment effacer ce bouton ?")) {
                document.location.href = url;
            }
        });
    });
});

function checkPaymentModeCompatibility(paymentType, id) {
    if (paymentType === 'AMEX') {
        let paymentMode = document.getElementById('paymentMode_' + id).value;

        if (paymentMode !== 'CASH') {
            forcePaymentCash(id);
        }
    }
}

function manageButtonFields(paymentMode, id) {
    let togglableElements = document.getElementsByClassName('js-hidden-field-togglable-' + id);

    Array.prototype.forEach.call(togglableElements, function(element) {
        if (!element.classList.contains('hidden-field')) {
            element.classList.add('hidden-field');
        }
    });

    let paymentType = document.getElementById('paymentType_' + id).value;

    if (paymentType === 'AMEX') {
        paymentMode = 'CASH';
        checkPaymentModeCompatibility(paymentType, id);
    }

    switch(paymentMode) {
        case 'RECURRING':
            document.getElementById('paymentNumberField-' + id).classList.remove('hidden-field');
            document.getElementById('paymentReportField-' + id).classList.remove('hidden-field');
            // @todo Remove comments when the feature will be coded
            // document.getElementById('orderRepeatedField-' + id).classList.remove('hidden-field');
            break;
        case 'XTIME':
            document.getElementById('paymentNumberField-' + id).classList.remove('hidden-field');
            document.getElementById('firstPaymentPartField-' + id).classList.remove('hidden-field');
            break;
    }

}

function displayInsiteWarning(element) {
    let integration = element.value;
    let brother = element.nextElementSibling;

    if ((brother !== null) && brother.classList.contains('js-unactive-insite')) {
        switch (integration) {
            case 'EXTERNAL':
                if (!brother.classList.contains('hidden-field')) {
                    brother.classList.add('hidden-field');
                }
                break;
            case 'INSITE':
                if (brother.classList.contains('hidden-field')) {
                    brother.classList.remove('hidden-field');
                }
                break;
        }
    }

}

function forcePaymentCash(id) {
    document.getElementById('paymentMode_' + id).value = 'CASH';
    alert("American Mastercard ne supporte que le paiement comptant.");
}
