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
 * @version   2.6.1
 *
 *}
<form{foreach $attr as $key => $val} {$key|escape:'html':'UTF-8'}="{$val|escape:'html':'UTF-8'}"{/foreach}>
    {foreach $fields as $field}
        {$field nofilter}{* HTML content, no escape necessary *}
    {/foreach}

    {if isset($errors)}
        {include
            file="./fields/partials/errors.tpl"
            errors=$errors
        }
    {/if}

    <div class="pgbutton__container pg__mtop-sm">
        <button type="submit" class="pgbutton">
            {$validate|pgtrans}
        </button>
    </div>
</form>