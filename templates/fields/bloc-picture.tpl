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
 * @version   2.5.2
 *
 *}
<fieldset
    {if isset($id)}id="{$id|escape:'html':'UTF-8'}"{/if}
    class="pgform__field{if isset($fieldsetClasses)} {$fieldsetClasses|clip:' '|escape:'html':'UTF-8'}{/if}{if isset($class)} {$class|escape:'html':'UTF-8'}{/if}"
    {if isset($url)}data-preview-original="{$url|escape:'html':'UTF-8'}"{/if}
>
    {if isset($label)}
        {include file="fields/partials/label.tpl" label=$label attr=$attr}
    {/if}

    {if isset($warning)}
        {include file="fields/partials/warning.tpl" warning=$warning}
    {/if}

    {$children.reset nofilter}{* HTML content, no escape necessary *}
    {$children.image nofilter}{* HTML content, no escape necessary *}

    <div class="pgpreview__container" id="buttonPreview" title="{'pages.buttons.shared.design.preview'|pgtrans}">
        <img
                src=""
                alt="{'pages.buttons.shared.design.previewImageAlt'|pgtrans}"
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