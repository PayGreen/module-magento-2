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
<div class="pgblock pgblock__max__xl">
    <h2>
        {'blocks.system.filesystem.title'|pgtrans}
    </h2>

    <h3 class="pg__default">
        {'blocks.system.filesystem.subtitle'|pgtrans}
    </h3>

    <table>
        <thead>
        <tr>
            <td>{'blocks.system.filesystem.columns.name'|pgtrans}</td>
            <td class="pg-break-words">{'blocks.system.filesystem.columns.path'|pgtrans}</td>
            <td>{'blocks.system.filesystem.columns.exists'|pgtrans}</td>
            <td>{'blocks.system.filesystem.columns.writable'|pgtrans}</td>
        </tr>
        </thead>

        <tbody>
        {foreach from=$entries item=entry}
            <tr>
                <td>{$entry['name']}</td>
                <td>{$entry['path']}</td>
                <td style="text-align: center;">{$entry['exists']|pgbool}</td>
                <td style="text-align: center;">{$entry['writable']|pgbool}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>

</div>
