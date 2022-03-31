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
<table class="pgtable__infos pgtable__align-center pg__mbottom-lg">
    {foreach from=$infos key=key item=item}
        {if !empty($item)}
            {assign var="itemValue" value="{$item|escape:'html':'UTF-8'}"}
        {else}
            {assign var="itemValue" value="Value not found"}
        {/if}

        <tr>
            <td>{$key|pgtrans}</td>
            <td>{$itemValue|escape:'html':'UTF-8'}</td>
        </tr>
    {/foreach}
</table>