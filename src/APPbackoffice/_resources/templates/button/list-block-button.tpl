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
 * @version   1.2.2
 *
 *}
<div class="pgblock">
    <img
        src="{$button['imageUrl']|escape:'html':'UTF-8'}"
        alt="{'button.pages.list.image'|pgtrans}"
        class="pg__height-sm pg__width-md"
    />

    <h3 class="pg__default pg__mtop-xs">
        {$button['label']|escape:'htmlall':'UTF-8'}
    </h3>
    
    <p class="pg__icon-container">
        <i class="rgni-schedule"></i>
        {$button['paymentMode']|modename}
    </p>

    <p class="pg__icon-container">
        <i class="rgni-wallet"></i>
        {$button['paymentType']|typename}
    </p>

    {if not empty($button['errors'])}
        {include
            file="../../../../PGForm/_resources/templates/fields/partials/errors.tpl"
            errors=['button.pages.list.error']
        }
    {/if}
    
    <div class="pgcontainer pg__mtop-xs">
        <a
            href="{'backoffice.buttons.display_update'|toback:['id' => $button['id']]}"
            class="pgbutton pg__default pg__mtop-xs pg__mlateral-xs"
        >
            {'button.actions.update.button'|pgtrans}
        </a>

        <a
            href="{'backoffice.buttons.delete'|toback:['id' => $button['id']]}"
            onclick="return confirm('{'button.actions.delete.confirmation'|pgtrans|escape:javascript}')"
            class="pgbutton pg__danger pg__mtop-xs pg__mlateral-xs"
        >
            {'button.actions.delete.button'|pgtrans}
        </a>
    </div>
</div>