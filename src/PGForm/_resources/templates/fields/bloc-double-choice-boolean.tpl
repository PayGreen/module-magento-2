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
<table
    {if isset($id)}id="{$id}"{/if}
    class="pgtable pgtable__align-center pgtable__static-columns-width pg__mtop-sm pg__mbottom-sm {if isset($class)} {$class}{/if}"
>
    <thead>
        <tr>
            <td>
            {if $filter}
                <span class="pgform__field js__table-row-search">
                    {include
                        file="fields/partials/input.tpl"
                        attr=['type' => 'search']
                        placeholder="{$filterPlaceholder}"
                    }
                </span>
            {else}
                &nbsp;
            {/if}
            </td>

            {foreach from=$horizontal_choices item=horizontal_name}
            <td>
                {$horizontal_name}
            </td>
            {/foreach}
        </tr>
    </thead>

    <tbody class="js__table-column-check">
        {foreach from=$vertical_choices key=vertical_code item=vertical_name}
        <tr data-name="{$vertical_name}">
            <td>
                {$vertical_name}
            </td>

            {foreach from=$horizontal_choices key=horizontal_code item=horizontal_name}
                {assign var="data" value=$guideline[$vertical_code][$horizontal_code]}

                <td>
                    {include
                        file="fields/partials/radio-check.tpl"
                        attr=$attr
                        name=$data['name']
                        id="{$id}-{$data['name']}-{$data['value']}"
                        value=$data['value']
                        isChecked=$data['checked']
                        label="config.buttons.yes"
                        translate=true
                        classes="pgform__field__radio-check--without-label"
                    }
                </td>
            {/foreach}
        </tr>
        {/foreach}
    </tbody>
</table>

{if isset($errors)}
    {include file="fields/partials/errors.tpl" errors=$errors}
{/if}