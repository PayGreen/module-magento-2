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
 * @version   2.5.0
 *
 */
!function(n){var t={};function e(i){if(t[i])return t[i].exports;var o=t[i]={i:i,l:!1,exports:{}};return n[i].call(o.exports,o,o.exports,e),o.l=!0,o.exports}e.m=n,e.c=t,e.d=function(n,t,i){e.o(n,t)||Object.defineProperty(n,t,{enumerable:!0,get:i})},e.r=function(n){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(n,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(n,"__esModule",{value:!0})},e.t=function(n,t){if(1&t&&(n=e(n)),8&t)return n;if(4&t&&"object"==typeof n&&n&&n.__esModule)return n;var i=Object.create(null);if(e.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:n}),2&t&&"string"!=typeof n)for(var o in n)e.d(i,o,function(t){return n[t]}.bind(null,o));return i},e.n=function(n){var t=n&&n.__esModule?function(){return n.default}:function(){return n};return e.d(t,"a",t),t},e.o=function(n,t){return Object.prototype.hasOwnProperty.call(n,t)},e.p="../",e(e.s="bSD8")}({bSD8:function(n,t){function e(n){return(e="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(n){return typeof n}:function(n){return n&&"function"==typeof Symbol&&n.constructor===Symbol&&n!==Symbol.prototype?"symbol":typeof n})(n)}n={install:function(){document.querySelector("#pgcharity-association-details")&&(this.handleClickOnCharityDetails(),this.handleClickOnClosingOptions(),this.handleClickOutsideOfPopin())},handleClickOutsideOfPopin:function(){document.querySelector(".paygreen-charity-popin-container").addEventListener("click",function(n){this.hideCharityPopin()}.bind(this))},handleClickOnClosingOptions:function(){document.querySelector("#pgcharity-popin-close-option").addEventListener("click",function(n){this.hideCharityPopin()}.bind(this))},handleClickOnCharityDetails:function(){document.querySelector("#pgcharity-association-details").addEventListener("click",function(){this.displayCharityPopin()}.bind(this))},displayCharityPopin:function(){document.querySelector(".paygreen-charity-popin-container").classList.remove("pgHidden")},hideCharityPopin:function(){document.querySelector(".paygreen-charity-popin-container").classList.add("pgHidden")}};"object"!==e(window.pgmodules)&&(window.pgmodules={}),window.pgmodules.charityCheckout=n,window.addEventListener("DOMContentLoaded",(function(t){n.install()}))}});