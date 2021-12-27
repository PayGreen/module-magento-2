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
 * @version   2.5.1
 *
 *}
<tr id="pgbutton{$partnership['idPartnership']|escape:'html':'UTF-8'}" draggable="false" class="pgdraggabletable">
    <td id="{$partnership['idPartnership']|escape:'html':'UTF-8'}" class="pgdraggableelement">
        <p class="pg__icon-container pg_justify_content_center">
            <i class="rgni-menu pgdraggable" style="margin-right: 0"></i>
        </p>
    </td>

    <td class="pgdiv_flex_row pg_justify_content_center">
        <img
            src="{$partnership['associationLogo']|escape:'html':'UTF-8'}"
            alt="{'pages.charity_partnerships.list.logo'|pgtrans}"
            class="pg__height-lg pg__width-md"
        />
    </td>

    <td>
        <a
            href="{$partnership['associationUrl']|escape:'htmlall':'UTF-8'}"
            target="_blank"
            class="pg__default pg__mbottom-xs">
            {$partnership['associationName']|escape:'htmlall':'UTF-8'}
        </a>
    </td>

    <td>
        <p class="pg__default pg__mbottom-xs">
            {$partnership['associationFieldOfAction']|escape:'htmlall':'UTF-8'}
        </p>
    </td>

    <td>
        <h4 class="pg__default pg__mbottom-xs">
            {$partnership['associationStatus']|escape:'htmlall':'UTF-8'}
        </h4>
    </td>
</tr>