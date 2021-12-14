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
 * @version   2.5.0
 *
 *}
{if !isset($action)}
    {assign var="action" value="null"}
{/if}

<tr id="pgbutton{$button['id']}" draggable="false" class="pgdraggabletable">
    {if $action !== "UPDATE" && $action !== "FILTERING"}
        <td id="{$button['id']}" class="pgdraggablebutton">
            <p class="pg__icon-container">
                <i class="rgni-menu pgdraggable"></i>
            </p>
        </td>
    {/if}
    <td>
    <img
        src="{$button['imageUrl']|escape:'html':'UTF-8'}"
        alt="{'pages.buttons.list.image'|pgtrans}"
        class="pg__height-sm pg__width-md"
    />
    </td>
    <td>
        <h3 class="pg__default pg__mbottom-xs">
            {$button['label']|escape:'htmlall':'UTF-8'}
        </h3>
        {if not empty($button['errors'])}
            {include
            file="fields/partials/errors.tpl"
            errors=['pages.buttons.list.error']
            }
        {/if}
    </td>
    <td>
    <p class="pg__icon-container">
        <i class="rgni-schedule"></i>
        {$button['paymentMode']|modename}
    </p>
    </td>
    <td>
    <p class="pg__icon-container">
        <i class="rgni-wallet"></i>
        {$button['paymentType']|typename}
    </p>
    </td>
    <td {if $action === "UPDATE" || $action === "FILTERING"}class="pgtable__noborder"{/if}>
    <div class="pgcontainer pgcontainer__end pg__mbottom-xs">
        {if $action !== "UPDATE"}
            <a
                    href="{'backoffice.buttons.display_update'|toback:['id' => $button['id']]}"
                    class="pgbutton pg__default pg__mtop-xs pg__mlateral-xxs "
            		title="{'actions.button.update.button'|pgtrans}"

            >
                <i class="pgbutton__icon rgni-pen"></i>
            </a>
        {/if}

        {if $action !== "FILTERING"}
            <a
                href="{'backoffice.buttons.display_filters'|toback:['id' => $button['id']]}"
                class="pgbutton pg__default pg__mtop-xs pg__mlateral-xxs"
                title="{'actions.button.filters.button'|pgtrans}"

            >
                <i class="pgbutton__icon rgni-wheels"></i>
            </a>
        {/if}

        <a
            href="{'backoffice.buttons.delete'|toback:['id' => $button['id']]}"
            onclick="return confirm('{'actions.button.delete.confirmation'|pgtrans|escape:javascript}')"
            class="pgbutton pg__danger pg__mtop-xs pg__mlateral-xxs"
            title="{'actions.button.delete.button'|pgtrans}"
        >
            <i class="pgbutton__icon rgni-cross-bold"></i>
        </a>
    </div>
    </td>
</tr>