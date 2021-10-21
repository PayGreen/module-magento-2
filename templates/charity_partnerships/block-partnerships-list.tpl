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
 * @version   2.4.0
 *
 *}
<div class="pgcontainer">
    <table class="pgtable__align-center">
        <thead>
            <tr>
                <td></td>
                <td>{'pages.charity_partnerships.list.logo'|pgtrans}</td>
                <td>{'pages.charity_partnerships.list.name'|pgtrans}</td>
                <td>{'pages.charity_partnerships.list.field_of_action'|pgtrans}</td>
                <td>{'pages.charity_partnerships.list.status'|pgtrans}</td>
            </tr>
        </thead>

        <tbody>
            {foreach from=$partnerships item=partnership}
                {view name="partnership.line" partnership=$partnership}
            {/foreach}
        </tbody>
    </table>
</div>