{*
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
 * @version   2.1.1
 *
 *}
<section class="block_paygreen_infos">
    {assign var="target" value="FOPayment:footer/lock-{$color|escape:'html':'UTF-8'}.png"}
    <div>
        <img src="{$target|picture}" />
        <span class="{$color}">{'frontoffice.footer.securized.short'|pgtrans} :</span>
    </div>

    <a href="{$backlink|escape:'html':'UTF-8'}" target="_blank" title="paygreen" >
        {assign var="target" value="FOPayment:footer/paygreen-{$color|escape:'html':'UTF-8'}.png"}
        <img src="{$target|picture}" alt="{'frontoffice.footer.securized.long'|pgtrans}" />
    </a>
</section>