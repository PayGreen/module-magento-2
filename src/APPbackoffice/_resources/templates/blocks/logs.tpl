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
<div class="pgblock pgblock__xl">
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
                <td>{$log['name']}</td>
                <td>{$log['size']}</td>
                <td>{$log['updatedAt']}</td>
                <td>
                    {if $log['action']}
                    <div class="pgcontainer">
                        <a
                            href="{'backoffice.logs.download'|toback:["filename" => $log['name']]}"
                            class="pgbutton-light pg__mright-xs"
                        >
                            {'blocks.logs.actions.download.button'|pgtrans}
                        </a>

                        <a
                            href="{'backoffice.logs.delete'|toback:["filename" => $log['name']]}"
                            class="pgbutton-light pg__danger pg__mright-xs"
                        >
                            {'blocks.logs.actions.delete.button'|pgtrans}
                        </a>
                    </div>
                    {/if}
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>
</div>