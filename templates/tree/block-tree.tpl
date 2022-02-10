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
        {'blocks.tree.title'|pgtrans}
    </h2>
    {if $treeKitInfos != null}
        {if $treeKitInfos['is_test_mode_activated'] && !$treeKitInfos['is_test_mode_expired'] && $treeActivated}
            {include file="tree/badge-test-mode.tpl"}
        {/if}
    {/if}
</div>


<div class="pgdiv_flex_row">
    {if $connected}
        {if $treeKitInfos != null}
            {include file="tree/block-tree-kit-infos.tpl" infos=[
                'blocks.tree_kit_infos.form.client_id' => $credentials['client_id'],
                'blocks.tree_kit_infos.form.username' => $credentials['username']
            ] carbonDataOverview=$treeKitInfos['carbon_data_overview']}
        {/if}
    {else}
        <div class="pgdiv_flex_column">
            <div class="pgblock pgblock__min__md">
                <p>{'blocks.tree_account_login.title'|pgtrans}</p>
                <div class="pgbutton__container pg__mtop-md pg__mbottom-md">
                    <a href="{'backoffice.tree_account.display'|toback}" class="pgbutton">
                        {'blocks.tree_account_login.action'|pgtrans}
                    </a>
                </div>
            </div>
        </div>
    {/if}

    <div class="pgdiv_flex_column">
        {if $treeKitInfos != null}
            <div class="pgblock pgblock__max__md">
                {if
                    $treeKitInfos['is_mandate_signed'] == false &&
                    $treeKitInfos['is_test_mode_expired']
                }
                    {'misc.tree_account.notifications.mandate.unsigned'|pgtrans}
                {else}
                    {include
                    file="toggle.tpl"
                    title="blocks.tree.tree_activation.title"
                    description="blocks.tree.tree_activation.help"
                    action="backoffice.tree.activation"
                    active=$treeActivated}
                {/if}
            </div>
        {/if}
        <div class="pgblock pgblock__max__md">
            <p>{'blocks.tree.shortcuts'|pgtrans} :</p>
            <ul class="p-0 no-list-style">
                <li>
                    <a href="{'backoffice.tree_account.display'|toback}" class="pglink pg__default">
                        {'pages.tree_account.name'|pgtrans}
                    </a>
                </li>
                <li>
                    <a href="{'backoffice.tree_config.display'|toback}" class="pglink pg__default">
                        {'pages.tree_config.name'|pgtrans}
                    </a>
                </li>
                {if $connected}
                    <li>
                        <a target="_blank" href="{$treeKitInfos['link_backoffice']|escape:'html':'UTF-8'}" class="pglink pg__default">
                            {'pages.climatekit.link'|pgtrans}
                        </a>
                    </li>
                {/if}
            </ul>
        </div>
    </div>
</div>
