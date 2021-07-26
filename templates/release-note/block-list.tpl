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
 * @version   2.2.0
 *
 *}
<div class="pgcontainer">
    <div class="pg_div_flex_column">
    {foreach from=$releases item=release}
        <div class="pg__release pg_div_flex_column">
            <h4>Release : {$release['version']}</h4>
            <h5>{if isset($release['date'])}Date : {$release['date']} {/if}</h5>
            {if isset($release['description'])}
                <p class="pg__default">{$release['description']}</p>
            {/if}
            {if isset($release['dependencies'])}
                {foreach from=$release['dependencies'] key=dependencyName item=dependency}
                    {assign var="dependencyName" value=$dependencyName|capitalize}
                    {assign var="currentDependencyVersion" value=$dependency['version']}
                    {if isset($dependency['from'])}
                        {assign var="previousDependencyVersion" value=$dependency['from']}
                        <h6 class="pgnote__dependency_version">
                            {$dependencyName} : {$previousDependencyVersion} -> {$currentDependencyVersion}
                        </h6>
                    {else}
                        <h6 class="pgnote__dependency_version">{$dependencyName} : {$currentDependencyVersion}</h6>
                    {/if}
                {/foreach}
            {/if}
            <ul class="pgnotes">
                {if isset($release['notes'])}
                    {foreach from=$release['notes'] key=index item=note}
                    {if $index < $nbNotes}
                        <li class="pgnote">
                            <div class="pgnote__picto pgnote__picto__{$note['type']}"></div>
                            <span>{$note['text']}</span>
                        </li>
                    {else}
                        <li class="pgnote pgnote__hidden">
                            <div class="pgnote__picto pgnote__picto__{$note['type']}"></div>
                            <span>{$note['text']}</span>
                        </li>
                    {/if}

                    {if $index == $nbNotes}
                        <button class="pgbutton__notes__show__all">Voir toutes les notes</button>
                    {/if}
                    {/foreach}
                {/if}
            </ul>
        </div>
    {/foreach}
    </div>
</div>