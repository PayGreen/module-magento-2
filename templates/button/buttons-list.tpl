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
 * @version   2.6.0
 *
 *}
<h2>
    {'pages.buttons.list.title'|pgtrans}
</h2>
{if !empty($buttons)}
    <div class="pgcontainer">
        <table>
            <thead>
            <tr>
                <td></td>
                <td>{'pages.buttons.list.logo'|pgtrans}</td>
                <td>{'pages.buttons.list.name'|pgtrans}</td>
                <td>{'pages.buttons.list.payment_mode'|pgtrans}</td>
                <td>{'pages.buttons.list.payment_type'|pgtrans}</td>
                <td>{'pages.buttons.list.actions'|pgtrans}</td>
            </tr>
            </thead>
            <tbody>
            {foreach from=$buttons item=button}
                {view name="button.line" button=$button}
            {/foreach}
            </tbody>
        </table>
    </div>
{/if}
<div class="pgbutton__container pg__mtop-sm">
    <a
            href="{'backoffice.buttons.display_insert'|toback}"
            class="pgbutton"
    >
        {'actions.button.insert.button'|pgtrans}
    </a>
</div>