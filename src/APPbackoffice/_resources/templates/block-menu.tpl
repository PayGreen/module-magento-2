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
 * @version   1.1.1
 *}
<div class="pgnavbar">
    <a
        href="https://paygreen.fr/login"
        target="_blank"
        title="{'backoffice.menu.logo'|pgtrans}"
        class="pgnavbar__logo"
    >
        <img src="{'PGDomain:paygreen-logo.svg'|picture}" alt="PayGreen" />
    </a>

    <ul class="pgnavbar__menu">
        {foreach from=$entries item=entry}
            {if isset($entry['children'])}
                {foreach from=$entry['children'] item=child}
                    {if $child['code'] === $selected}
                        {assign var="selectedChild" value=true}
                        {break}
                    {else}
                        {assign var="selectedChild" value=false}
                    {/if}
                {/foreach}
            {else}
                {assign var="selectedChild" value=false}
            {/if}

            {if isset($selectedChild) && $selectedChild || $entry['code'] === $selected}
                {assign var="selectedCss" value=" pgnavbar__menu__element--selected"}
            {else}
                {assign var="selectedCss" value=""}
            {/if}

            <li class="pgnavbar__menu__element{$selectedCss}{if isset($entry['children'])} pgnavbar__menu__element--submenu{/if}">
                {if isset($entry['href'])}
                    <a href="{$entry['href']}" title="{$entry['title']|pgtrans}">
                        {$entry['name']|pgtrans}
                    </a>
                {else}
                    <span title="{$entry['title']|pgtrans}">
                        {$entry['name']|pgtrans}
                    </span>
                {/if}

                {if isset($entry['children'])}
                    <ul>
                        {foreach from=$entry['children'] item=child}
                            <li class="{if $child['code'] === $selected}pg-selected{/if}">
                                <a
                                    href="{$child['href']}"
                                    title="{$child['title']|pgtrans}"
                                >
                                    {$child['name']|pgtrans}
                                </a>
                            </li>
                        {/foreach}
                    </ul>
                {/if}
            </li>
        {/foreach}
    </ul>

    {if !empty($shops) && count($shops) > 1}
        <div class="pgtooltip pgnavbar__shop-select">
            <button
                type="button"
                class="pgbtnIcon pgbtnIcon--secondary"
                data-js="tooltip"
                data-target="#shopSelectTooltip"
            >
                <i class="rgni-shop"></i>
            </button>

            <div
                id="shopSelectTooltip"
                class="pgtooltip__content pgtooltip__content--secondary pgtooltip__content--right"
            >
                <div class="pgform__field">
                    <label for="shopSelect" class="pgform__field__label">
                        {'backoffice.menu.shop_selector.label'|pgtrans}
                    </label>

                    <select id="shopSelect">
                        <option selected="selected">
                            {$currentShop->getName()}
                        </option>

                        {foreach from=$shops item=shop}
                            {if $shop->id() !== $currentShop->id()}
                                <option value="{'backoffice.shop.select'|toback:['id' => $shop->id(), 'selected' => $selected]}">
                                    {$shop->getName()}
                                </option>
                            {/if}
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
    {/if}
</div>