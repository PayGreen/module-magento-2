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
 * @version   2.5.1
 *
 */
!function(e){var t={};function n(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(r,o,function(t){return e[t]}.bind(null,o));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="../",n(n.s="OwOK")}({OwOK:function(e,t){function n(e,t){var n="undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(!n){if(Array.isArray(e)||(n=function(e,t){if(!e)return;if("string"==typeof e)return r(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return r(e,t)}(e))||t&&e&&"number"==typeof e.length){n&&(e=n);var o=0,a=function(){};return{s:a,n:function(){return o>=e.length?{done:!0}:{done:!1,value:e[o++]}},e:function(e){throw e},f:a}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var i,u=!0,l=!1;return{s:function(){n=n.call(e)},n:function(){var e=n.next();return u=e.done,e},e:function(e){l=!0,i=e},f:function(){try{u||null==n.return||n.return()}finally{if(l)throw i}}}}function r(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}var o,a=!1;e={install:function(){var e=this;document.querySelectorAll(".pgdraggablebutton").forEach((function(t){t.addEventListener("mouseenter",function(){a||this.enableDragAndDrop()}.bind(e)),t.addEventListener("mouseleave",function(){a||this.disableDragAndDrop()}.bind(e))})),document.querySelectorAll(".pgdraggabletable").forEach((function(t){t.addEventListener("dragstart",(function(e){var t=e.currentTarget;t===e.target&&(a=!0,o=t.id,t.style.border="solid 1px black",t.style.backgroundColor="white")})),t.addEventListener("dragover",function(e){a&&e.preventDefault()}.bind(e)),t.addEventListener("dragend",function(e){if(a){a=!1,e.preventDefault();var t=document.getElementById(o);t.style.border="none",t.style.backgroundColor=null,this.disableDragAndDrop(),this.sendNewOrder()}}.bind(e)),t.addEventListener("dragenter",function(e){if(a){e.preventDefault();var n=o;n!==t.id&&this.reorder(n,t.id)}}.bind(e))}))},enableDragAndDrop:function(){var e,t=n(document.querySelectorAll(".pgdraggabletable"));try{for(t.s();!(e=t.n()).done;){e.value.setAttribute("draggable",!0)}}catch(e){t.e(e)}finally{t.f()}},disableDragAndDrop:function(){var e,t=n(document.querySelectorAll(".pgdraggabletable"));try{for(t.s();!(e=t.n()).done;){e.value.setAttribute("draggable",!1)}}catch(e){t.e(e)}finally{t.f()}},reorder:function(e,t){var n=document.getElementById(e),r=document.getElementById(t),o=r.cloneNode(!0);n.parentNode.insertBefore(o,n),r.parentNode.insertBefore(n,r),r.parentNode.replaceChild(r,o)},sendNewOrder:function(){var e=document.querySelectorAll(".pgdraggablebutton"),t=1,n=new URLSearchParams;e.forEach((function(e){n.append(t.toString(),e.id.toString()),t++}));var r=window.paygreen_update_buttons_position_url;this.makeXmlHttpRequest("POST",r,n)},makeXmlHttpRequest:function(e,t,n){var r=!(arguments.length>3&&void 0!==arguments[3])||arguments[3],o=new XMLHttpRequest;o.open(e,t,r),o.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8"),o.setRequestHeader("X-Requested-With","XMLHttpRequest"),o.send(n)}};window.addEventListener("DOMContentLoaded",(function(t){e.install()}))}});