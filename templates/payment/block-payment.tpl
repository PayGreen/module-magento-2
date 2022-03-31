{*
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
 * @version   2.6.1
 *
 *}
<div class="pgdiv_flex_row pg_justify_content_between pg_align_items-flex_start pgpayment_home_block">
    <h2 class="pgblock__shadow__title pg__mtop-md">
        {'blocks.payment.title'|pgtrans}
    </h2>
</div>

<article>
    {if $connected}
        {include
        file="toggle.tpl"
        title="blocks.payment.payment_activation.title"
        description="blocks.payment.payment_activation.help"
        action="backoffice.payment.activation"
        active=$paymentActivated}
        <div class="pgblock pg__success_container">
            <div class="pgbutton__container">
                <a
                        href="{'backoffice.account.display'|toback}"
                        class="pgbutton pg__default"
                >
                    {'pages.account.name'|pgtrans}
                </a>
            </div>
            <p>
                {'pages.account.title'|pgtrans}
            </p>
        </div>
        <div class="pgblock pg__danger_container">
            {include file="account/block-logout.tpl"}
        </div>
    {else}
        <ul>
            <li>
                {'blocks.payment.text1'|pgtrans}
            </li>
            <li class="pg__mtop">
                {'blocks.payment.text2'|pgtrans}
            </li>
            <li class="pg__mtop">
                {'blocks.payment.text3'|pgtrans}
            </li>
        </ul>

        <div class="pgdiv_flex_row">
            <div class="pgbutton__container">
                <a
                        target="_blank"
                        href="https://paygreen.fr/subscribe"
                        class="pgbutton pg__default"
                >
                    {'blocks.account_subscription.activate'|pgtrans}
                </a>
            </div>
            <div class="pgbutton__container">
                <a href="{'backoffice.account.oauth.request'|toback}" class="pgbutton">
                    {'blocks.account_login.action'|pgtrans}
                </a>
            </div>
        </div>
        <p>
            {'blocks.payment.description'|pgtrans}
        </p>
    {/if}

</article>