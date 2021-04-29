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
 * @version   2.0.1
 *
 *}
<h2>
    {'blocks.tree.title'|pgtrans}
</h2>

<div class="pgdiv_flex_row">
    {if $treeActivated}
        {if $connected}
            {if $clientId != null}
                {include file="tree/block-infos.tpl" infos=[
                    'blocks.tree_account_infos.form.client_id' => $clientId
                ]}
            {/if}
        {else}
            <div class="pgdiv_flex_column">
                <div class="pgblock pgblock__md-force">
                    <p>{'blocks.tree_account_login.title'|pgtrans}</p>
                    <div class="pgbutton__container pg__mtop-md pg__mbottom-md">
                        <a href="{'backoffice.tree_account.display'|toback}" class="pgbutton">
                            {'blocks.tree_account_login.action'|pgtrans}
                        </a>
                    </div>
                </div>
            </div>
        {/if}

     {else}
        {include file="tree/tree-disclaimer.tpl"}
    {/if}

    <div class="pgdiv_flex_column">
        <div class="pgblock pgblock__md">
            {$treeActivationFormView}
        </div>
        {if $treeActivated}
            <div class="pgblock pgblock__md">
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
                </ul>
            </div>
        {/if}
    </div>
</div>
