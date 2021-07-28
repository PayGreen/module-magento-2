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
 * @version   2.3.0
 *
 *}
<h2>
    {'blocks.payment.title'|pgtrans}
</h2>

<div class="pgdiv_flex_row">
    {if $connected}
        {if $paymentKitInfos != null}
            {include file="payment/block-payment-kit-infos.tpl" infos=[
                'blocks.payment_kit_infos.form.public_key' => $paymentKitInfos['public_key']
            ]   paymentsOverview=$paymentKitInfos['payments_overview']
                growth=$growth}
        {/if}
    {else}
        <div class="pgdiv_flex_column">
            <div class="pgblock pgblock__min__md">
                <p>{'blocks.account_login.title'|pgtrans}</p>

                <div class="pgbutton__container pg__mtop-md pg__mbottom-md">
                    <a href="{'backoffice.account.oauth.request'|toback}" class="pgbutton">
                        {'blocks.account_login.action'|pgtrans}
                    </a>
                </div>

                <p>{'blocks.account_subscription.title'|pgtrans}</p>

                <div class="pgbutton__container pg__mtop-md">
                    <a href="http://paygreen.fr/subscribe" target="_blank" class="pgbutton">
                        {'blocks.account_subscription.action'|pgtrans}
                    </a>
                </div>
            </div>
        </div>
    {/if}

    <div class="pgdiv_flex_column">
        <div class="pgblock pgblock__max__md">
            {include
            file="toggle.tpl"
            title="blocks.payment.payment_activation.title"
            description="blocks.payment.payment_activation.help"
            action="backoffice.payment.activation"
            active=$paymentActivated}
        </div>
        <div class="pgblock pgblock__max__md">
            <p>{'blocks.payment.shortcuts'|pgtrans} :</p>
            <ul class="p-0 no-list-style">
                <li>
                    <a href="{'backoffice.account.display'|toback}" class="pglink pg__default">
                        {'pages.account.name'|pgtrans}
                    </a>
                </li>
                {if $connected}
                    <li>
                        <a href="{'backoffice.config.display'|toback}" class="pglink pg__default">
                            {'pages.config.name'|pgtrans}
                        </a>
                    </li>
                    <li>
                        <a href="{'backoffice.buttons.display'|toback}" class="pglink pg__default">
                            {'pages.buttons.name'|pgtrans}
                        </a>
                    </li>
                {/if}
                <li>
                    <a target="_blank" href="https://paygreen.fr/login" class="pglink pg__default">
                        {'pages.link.name'|pgtrans}
                    </a>
                </li>
            </ul>
        </div>
    </div>

</div>
