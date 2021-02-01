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
 * @version   1.2.1
 *
 *}
<fieldset
    {if isset($id)}id="{$id}"{/if}
    class="pgform__field{if isset($fieldsetClasses)} {' '|join:$fieldsetClasses}{/if}{if isset($class)} {$class}{/if}"
>
    {if isset($label)}
        {include file="fields/partials/label.tpl" label=$label attr=$attr}
    {/if}

    <div class="pgform__field__radio-check__container">
    {foreach $choices as $code => $name}
        {if $multiple}
            {assign var="checked" value=in_array($code, $value)}
        {else}
            {assign var="checked" value=($code === $value)}
        {/if}

        {if isset($id)}
            {assign var="fieldId" value=$id}
        {else}
            {assign var="fieldId" value=null}
        {/if}

        {include
            file="fields/partials/radio-check.tpl"
            attr=$attr
            id=$fieldId
            value=$code
            isChecked=$checked
            label=$name
        }
    {/foreach}
    </div>
    
    {if isset($help)}
        {include file="fields/partials/help.tpl" help=$help}
    {/if}

    {if isset($errors)}
        {include file="fields/partials/errors.tpl" errors=$errors}
    {/if}
</fieldset>