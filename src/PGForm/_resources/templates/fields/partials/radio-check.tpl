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
<div class="pgform__field__radio-check{if isset($classes)} {$classes}{/if}">
    {if !isset($attr.id) && isset($id)}
        {if isset($value)}
            {assign var="childId" value="{$id}-{$value}"}
        {elseif isset($attr.value)}
            {assign var="childId" value="{$id}-{$attr.value}"}
        {/if}
    {/if}

    <input
        {foreach $attr as $key => $val}{$key}="{$val}"{/foreach}
        {if isset($isChecked) && $isChecked}checked="checked"{/if}
        {if isset($name)}name="{$name}"{/if}
        {if isset($value)}value="{$value}"{/if}
        {if isset($childId)}id="{$childId}"{/if}
    />

    <label for="{if isset($attr.id)}{$attr.id}{elseif isset($childId)}{$childId}{/if}">
        {if isset($translate) && $translate}
            {$label|pgtrans}
        {else}
            {$label}
        {/if}
    </label>
</div>