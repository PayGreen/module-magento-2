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
 * @version   2.5.2
 *
 *}
<div class="pgdiv_flex_row pg_justify_content_between pg_align_items-flex_start">
    <h2 class="pg__mtop-0">
        {'blocks.charity.title'|pgtrans}
    </h2>

    {if $charityKitInfos != null}
        {if $charityKitInfos['is_test_mode_activated'] && !$charityKitInfos['is_test_mode_expired'] && $charityActivated}
            {include file="charity/test_mode/badge-test-mode.tpl"}
        {/if}
    {/if}
</div>

<div class="pgdiv_flex_row">
    {if $connected}
        {if $charityKitInfos != null}
            {include file="charity/block-charity-kit-infos.tpl" infos=[
                'blocks.charity_kit_infos.form.client_id' => $credentials['client_id'],
                'blocks.charity_kit_infos.form.username' => $credentials['username']
            ] giftsOverview=$charityKitInfos['gifts_overview']}
        {/if}
    {else}
        <div class="pgdiv_flex_column">
            <div class="pgblock pgblock__min__md">
                <p>{'blocks.charity_account_login.title'|pgtrans}</p>
                <div class="pgbutton__container pg__mtop-md pg__mbottom-md">
                    <a href="{'backoffice.charity_account.display'|toback}" class="pgbutton">
                        {'blocks.charity_account_login.action'|pgtrans}
                    </a>
                </div>
            </div>
        </div>
    {/if}

    <div class="pgdiv_flex_column">
        {if $charityKitInfos != null}
            <div class="pgblock pgblock__max__md">
                {if $charityKitInfos['is_mandate_signed'] == false && $charityKitInfos['is_test_mode_expired']}
                    {'misc.charity_account.notifications.mandate.unsigned'|pgtrans}
                {else}
                    {include
                    file="toggle.tpl"
                    title="blocks.charity.charity_activation.title"
                    description="blocks.charity.charity_activation.help"
                    action="backoffice.charity.activation"
                    active=$charityActivated}
                {/if}
            </div>
        {/if}
        <div class="pgblock pgblock__max__md">
            <p>{'blocks.charity.shortcuts'|pgtrans} :</p>
            <ul class="p-0 no-list-style">
                <li>
                    <a href="{'backoffice.charity_account.display'|toback}" class="pglink pg__default">
                        {'pages.charity_account.name'|pgtrans}
                    </a>
                </li>
                {if $connected}
                    {if $charityKitInfos['is_mandate_signed']}
                        <li>
                            <a href="{'backoffice.charity_config.display'|toback}" class="pglink pg__default">
                                {'pages.charity_config.name'|pgtrans}
                            </a>
                        </li>
                    {/if}
                {/if}
                {if $connected}
                    <li>
                        <a target="_blank" href="{$charityKitInfos['link_backoffice']}" class="pglink pg__default">
                            {'pages.charitykit.link'|pgtrans}
                        </a>
                    </li>
                {/if}
            </ul>
        </div>
    </div>
</div>
