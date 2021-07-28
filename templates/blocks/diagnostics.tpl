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
<div class="pgblock pgblock__max__xl">
    <h2>
        {'blocks.diagnostic.title'|pgtrans}
    </h2>

    <h3 class="pg__default">
        {'blocks.diagnostic.subtitle'|pgtrans}
    </h3>

    <table>
        <thead>
        <tr>
            <td>{'blocks.diagnostic.columns.name'|pgtrans}</td>
            <td>{'blocks.diagnostic.columns.state'|pgtrans}</td>
        </tr>
        </thead>

        <tbody>
            {foreach from=$results item=result}
                <tr>
                    <td>{$result['name']}</td>
                    <td style="text-align: center;">{$result['test']|pgbool}</td>
                </tr>
            {/foreach}
        </tbody>
    </table>

    {if $hasInvalidDiagnostic}
        <div class="pgbutton__container pg__mtop-sm">
            <a class="pgbutton" href="{'backoffice.diagnostic.run'|toback}" title="{'blocks.diagnostic.links.run.title'|pgtrans}">{'blocks.diagnostic.links.run.text'|pgtrans}</a>
        </div>
    {/if}

</div>
