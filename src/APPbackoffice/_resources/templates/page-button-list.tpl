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
<div class="pglayout">
    {view name="menu" selected="buttons"}
    {view name="notifications"}

    <div class="pgcontainer">
        <div class="pgblock pgblock__xxl">
            <h2>
                {'button.pages.list.title'|pgtrans}
            </h2>

            <div class="pgcontainer">
                {foreach from=$buttons item=button}
                    {include file="button/list-block-button.tpl" button=$button}
                {/foreach}
            </div>

            <div class="pgbutton__container pg__mtop-sm">
                <a
                    href="{'backoffice.buttons.display_insert'|toback}"
                    class="pgbutton"
                >
                    {'button.actions.insert.button'|pgtrans}
                </a>
            </div>
        </div>

        {view name="blocks" page="button-list"}
    </div>
</div>
