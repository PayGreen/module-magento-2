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

window.addEventListener('load', function()
{
    let categoryFilter = document.getElementById('category-filter');

    categoryFilter.addEventListener('keyup', function(event)
    {
        let text = event.target.value;
        filterCategories(text);
    });

    let text = categoryFilter.value;
    if (text !== '') {
        filterCategories(text);
    }

    let categories = document.querySelectorAll('#categories input[type="checkbox"]')

    Array.prototype.forEach.call(categories, function (category)
    {
        category.addEventListener('change', function (event)
        {
            let checkbox = event.target;
            let category = getClosest(checkbox, 'tr');

            let paymentType = checkbox.value;
            let checked = checkbox.checked;

            checkChildren(category, getDepth(category), paymentType, checked)
        });
    });
});

function getClosest (elem, selector) {
    for ( ; elem && elem !== document; elem = elem.parentNode ) {
        if ( elem.matches( selector ) ) return elem;
    }
    return null;
};

function filterCategories(text)
{
    let categories = document.querySelectorAll('#categories tr');
    let regex = new RegExp(text,'i');

    Array.prototype.forEach.call(categories, function (category)
    {
        if ((text === '') || (category.attributes['data-name'].value.match(regex) !== null)) {
            category.style.display = 'table-row';

            if (text !== '') {
                displayParents(category, getDepth(category));
            }
        } else {
            category.style.display = 'none';
        }
    });
}

function displayParents(element, depth)
{
    if (depth > 0) {
        element = element.previousElementSibling;
        if (getDepth(element) < depth) {
            element.style.display = 'table-row';
            depth = getDepth(element);
        }

        displayParents(element, depth);
    }
}

function getDepth(element)
{
    return (0 + element.attributes['data-depth'].value);
}

function checkChildren(element, depth, paymentType, check)
{
    element = element.nextElementSibling;

    if ((element !== null) && (getDepth(element) > depth)) {
        let checkbox = element.querySelector('input[value="' + paymentType + '"]');
        checkbox.checked = check;

        checkChildren(element, depth, paymentType, check);
    }
}
