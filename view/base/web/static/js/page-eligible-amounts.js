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
!function(e){var t={};function n(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(r,o,function(t){return e[t]}.bind(null,o));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="../",n(n.s="1Klx")}({"1Klx":function(e,t,n){"use strict";n.r(t);var r=n("q2qq"),o=function(){r.a.install()};window.addEventListener("DOMContentLoaded",(function(e){o()}))},q2qq:function(e,t,n){"use strict";e={cssHidden:"js-hidden",install:function(){var e=this,t=document.querySelectorAll(".js__table-row-search input");Array.prototype.forEach.call(t,(function(t){t.addEventListener("input",(function(t){var n=t.target.value;e.pgFilterCategories(t.target,n)}));var n=t.value;""!==n&&e.pgFilterCategories(t,n)}));var n=document.querySelectorAll('.js__table-column-check input[type="checkbox"]');Array.prototype.forEach.call(n,(function(t){t.addEventListener("change",(function(t){var n=t.target,r=n.closest("tr"),o=n.value,a=n.checked;e.checkChildren(r,e.pgGetCategoryDepth(r),o,a)}))}))},pgFilterCategories:function(t,n){var r=this,o=t.closest("table").querySelectorAll("tbody tr"),a=new RegExp(n,"i");Array.prototype.forEach.call(o,(function(t){""===n||null!==t.dataset.name.match(a)?(t.classList.remove(e.cssHidden),""!==n&&r.pgDisplayParents(t,r.pgGetCategoryDepth(t))):t.classList.add(e.cssHidden)}))},pgGetCategoryDepth:function(e){var t=e.dataset.name;return t.length?t.search(/\S|$/)/8:0},pgDisplayParents:function(t,n){n>0&&(t=t.previousElementSibling,this.pgGetCategoryDepth(t)<n&&(t.classList.remove(e.cssHidden),n=this.pgGetCategoryDepth(t)),this.pgDisplayParents(t,n))},checkChildren:function(e,t,n,r){var o=e.nextElementSibling;null!==o&&this.pgGetCategoryDepth(o)>t&&(o.querySelector('input[value="'+n+'"]').checked=r,this.checkChildren(o,t,n,r))}};t.a=e}});