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
 * @version   2.1.1
 *
 *}
<div class="pglayout">
    {view name="menu" selected="buttons"}
    {view name="notifications"}

    {include file="button/breadcrumb.tpl" currentPage="pages.buttons.update.title"}

    <div class="pgcontainer pgcontainer__align-center pg__mtop-sm">
        <h1 class="pg__mleft-page pg__mtop-xs pg__mbottom-xs">
            {$button['label']|escape:'htmlall':'UTF-8'}
        </h1>

        <div>
            <a
                href="{'backoffice.buttons.display_filters'|toback:['id' => $button['id']]}"
                class="pgbutton pg__default pg__mleft-page pg__mtop-xs pg__mbottom-xss"
            >
                {'actions.button.filters.button'|pgtrans}
            </a>

            <a
                href="{'backoffice.buttons.delete'|toback:['id' => $button['id']]}"
                onclick="return confirm('{'actions.button.delete.confirmation'|pgtrans|escape:javascript}')"
                class="pgbutton pg__danger pg__mleft-page pg__mtop-xs pg__mbottom-xs"
            >
                {'actions.button.delete.button'|pgtrans}
            </a>
        </div>

        {if isset($errors)}
            {include
            file="fields/partials/errors.tpl"
            errors=$errors
            class="pgform__errors--right"
            }
        {/if}
    </div>

    {$form}
</div>
