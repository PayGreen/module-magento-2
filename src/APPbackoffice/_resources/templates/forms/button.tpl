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
<form{foreach $attr as $key => $val} {$key}="{$val}"{/foreach}>
    {if isset($fields.id)}{$fields.id}{/if}

    <div class="pgcontainer" data-pglayout="button-form">
        <div class="pgblock pg__flex-1 pgblock__md">
            <h2>
                {'button.pages.general.design.title'|pgtrans}
            </h2>

            {foreach $columns.appearance as $name}
                {if isset($fields[$name])}{$fields[$name]}{/if}
            {/foreach}
        </div>

        <div class="pgblock pg__flex-1 pgblock__md">
            <h2>
                {'button.pages.general.payment.title'|pgtrans}
            </h2>

            {foreach $columns.payment as $name}
                {if isset($fields[$name])}{$fields[$name]}{/if}
            {/foreach}
        </div>

        <div class="pgblock pg__flex-1 pgblock__md">
            <h2>
                {'button.pages.general.other.title'|pgtrans}
            </h2>

            {foreach $columns.other as $name}
                {if isset($fields[$name])}{$fields[$name]}{/if}
            {/foreach}
        </div>
    </div>

    {if isset($errors)}
        {include
            file="../../../../PGForm/_resources/templates/fields/partials/errors.tpl"
            errors=$errors
            class="pgform__errors--right"
        }
    {/if}

    <div class="pgbutton__container pg__mtop-sm pg__mbottom-md">
        <button type="submit" class="pgbutton">
            {$validate|pgtrans}
        </button>
    </div>
</form>