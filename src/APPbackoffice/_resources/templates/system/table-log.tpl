{*
 * 2014 - 2020 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.0.0
 *}
<table>
    <thead>
        <tr>
            <td>{'system.logs.columns.filename'|pgtrans}</td>
            <td>{'system.logs.columns.size'|pgtrans}</td>
            <td>{'system.logs.columns.last_update'|pgtrans}</td>
            <td>{'system.logs.columns.actions'|pgtrans}</td>
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
                        href="{'backoffice.system.log.download'|toback:["filename" => $log['name']]}"
                        class="pgbutton-light pg__mright-xs"
                    >
                        {'system.logs.actions.download.button'|pgtrans}
                    </a>

                    <a
                        href="{'backoffice.system.log.delete'|toback:["filename" => $log['name']]}"
                        class="pgbutton-light pg__mright-xs"
                    >
                        {'system.logs.actions.delete.button'|pgtrans}
                    </a>
                </div>
                {/if}
            </td>
        </tr>
        {/foreach}
    </tbody>
</table>