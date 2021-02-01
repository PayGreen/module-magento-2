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
 * @version   1.2.1
 *
 *}
<fieldset
    {if isset($id)}id="{$id}"{/if}
    class="pgform__field{if isset($fieldsetClasses)} {' '|join:$fieldsetClasses}{/if}{if isset($class)} {$class}{/if}"
    {if isset($url)}data-preview-original="{$url}"{/if}
>
    {if isset($label)}
        {include file="fields/partials/label.tpl" label=$label attr=$attr}
    {/if}

    {$children.reset}
    {$children.image}

    <div class="pgpreview__container" id="buttonPreview" title="{'button.pages.general.design.preview'|pgtrans}">
        <img
                src=""
                alt="{'button.pages.general.design.previewImageAlt'|pgtrans}"
                class="pg__height-lg pg__width-md pg__mbottom-xxs"
        />
    </div>

    {if isset($help)}
        {include file="fields/partials/help.tpl" help=$help}
    {/if}

    {if isset($errors)}
        {include file="fields/partials/errors.tpl" errors=$errors}
    {/if}
</fieldset>