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
 * @version   1.2.5
 *
 *}
<div class="pglayout">
    {view name="menu" selected="account"}
    {view name="notifications"}

    <div class="pgcontainer">
    {if $connected}
        {include file="account/block-status.tpl" form=$activationFormView}
        {include file="account/block-ids.tpl" form=$settingsFormView}
        {include file="account/block-logout.tpl"}

        {if $infoAccount != null}
            {include file="account/block-infos.tpl" infos=[
                'pages.account.infos.form.url' => $infoAccount->url,
                'pages.account.infos.form.siret' => $infoAccount->siret,
                'pages.account.infos.form.iban' => $infoAccount->IBAN
            ]}
        {/if}
    {else}
        {include file="account/block-login.tpl" form=$settingsFormView}
        {include file="account/block-subscription.tpl"}
    {/if}
    </div>

    {view name="blocks" page="account"}
</div>
