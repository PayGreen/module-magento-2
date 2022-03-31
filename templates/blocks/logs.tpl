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
<div class="pgblock pgblock__max__xl">
    <h2>
        {'blocks.logs.title'|pgtrans}
    </h2>

    <h3 class="pg__default">
        {'blocks.logs.subtitle'|pgtrans}
    </h3>

    <table>
        <thead>
            <tr>
                <td>{'blocks.logs.columns.filename'|pgtrans}</td>
                <td>{'blocks.logs.columns.size'|pgtrans}</td>
                <td>{'blocks.logs.columns.last_update'|pgtrans}</td>
                <td>{'blocks.logs.columns.actions'|pgtrans}</td>
            </tr>
        </thead>

        <tbody>
            {foreach from=$logs item=log}
            <tr>
                <td>{$log['name']|escape:'html':'UTF-8'}</td>
                <td>{$log['size']|escape:'html':'UTF-8'}</td>
                <td>{$log['updatedAt']|escape:'html':'UTF-8'}</td>
                <td>
                    {if $log['action']}
                    <div class="pgcontainer">
                        <a
                            href="{'backoffice.logs.download'|toback:["filename" => $log['name']]}"
                            class="pgbutton-light pg__mright-xs"
                        >
                            {'actions.logs.download.button'|pgtrans}
                        </a>

                        <a
                            href="{'backoffice.logs.delete'|toback:["filename" => $log['name']]}"
                            data-confirm="{'actions.logs.delete.confirmation'|pgtrans|escape:javascript}"
                            class="pgbutton-light pg__danger pg__mright-xs"
                        >
                            {'actions.logs.delete.button'|pgtrans}
                        </a>
                    </div>
                    {/if}
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>
</div>