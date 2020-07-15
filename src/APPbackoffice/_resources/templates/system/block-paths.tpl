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
<div class="pgblock pgblock__xl">
    <h2>
        {'system.filesystem.title'|pgtrans}
    </h2>

    <h3 class="pg__default">
        {'system.filesystem.subtitle'|pgtrans}
    </h3>

    <table>
        <thead>
        <tr>
            <td>{'system.filesystem.columns.name'|pgtrans}</td>
            <td class="pg-break-words">{'system.filesystem.columns.path'|pgtrans}</td>
            <td>{'system.filesystem.columns.exists'|pgtrans}</td>
            <td>{'system.filesystem.columns.writable'|pgtrans}</td>
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
