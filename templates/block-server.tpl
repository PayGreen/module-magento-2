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
{if $servers != null}
    <div class="pgblock pgblock__max__xl">
        <h2>
            {'blocks.server.list'|pgtrans}
        </h2>
        <table>
            <thead>
            <tr>
                <td>{'blocks.server.product'|pgtrans}</td>
                <td>{'blocks.server.name'|pgtrans}</td>
            </tr>
            </thead>
            {foreach from=$servers key=key item=item}
                <tr>
                    <td>{$key}</td>
                    <td>{$item|pgtrans}</td>
                </tr>
            {/foreach}
        </table>
    </div>
{/if}