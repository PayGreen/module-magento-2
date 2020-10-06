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
 * @version   1.2.0
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