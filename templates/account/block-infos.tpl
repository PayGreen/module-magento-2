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
{if $infos != null}
    <h2>
        {'blocks.account_infos.title'|pgtrans}
    </h2>

    <p>
        {'blocks.account_infos.description'|pgtrans}

        <a href="http://paygreen.fr/shop/wizard-activation" target="_blank">
            {'blocks.account_infos.descriptionLink'|pgtrans}
        </a>
    </p>

    {foreach from=$infos key=key item=item}
        {if !empty($item)}
            {assign var="inputValue" value="{$item|escape:'html':'UTF-8'}"}
        {else}
            {assign var="inputValue" value=null}
        {/if}

        {include
            file="fields/input-bloc.tpl"
            attr=[
                'type' => 'text',
                'disabled' => 'disabled',
                'value' => $inputValue,
                'placeholder' => "{'misc.forms.default.input.empty'|pgtrans}"
            ]
            label=$key
            class="pgform__field--disabled"
        }
    {/foreach}
{/if}