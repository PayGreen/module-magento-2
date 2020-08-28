{*
 * 2014 - 2020 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.0.1
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