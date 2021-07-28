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
<table>
    <thead>
    <tr>
        <td class="pg-break-words">{'blocks.carbon_data_overview.columns.period'|pgtrans}</td>
        <td class="pg-break-words">{'blocks.carbon_data_overview.columns.footprint'|pgtrans}</td>
        <td class="pg-break-words">{'blocks.carbon_data_overview.columns.carbon_offset'|pgtrans}</td>
    </tr>
    </thead>

    <tbody>
    {foreach from=$entries item=entry}
        <tr>
            <td>{"blocks.carbon_data_overview.period.`$entry['period']`"|pgtrans}</td>
            <td style="text-align: center;">{$entry['footprint']} kg CO²</td>
            <td style="text-align: center;">{$entry['carbon_offset']} €</td>
        </tr>
    {/foreach}
    </tbody>
</table>