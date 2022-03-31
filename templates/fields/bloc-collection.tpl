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
<fieldset
    {if isset($id)}id="{$id|escape:'html':'UTF-8'}"{/if}
    class="pgform__field{if isset($fieldsetClasses)} {$fieldsetClasses|clip:' '|escape:'html':'UTF-8'}{/if}{if isset($class)} {$class|escape:'html':'UTF-8'}{/if}"
    data-js="collection"
>
    {if isset($label)}
        {include file="fields/partials/label.tpl" label=$label attr=$attr}
    {/if}

    {if isset($warning)}
        {include file="fields/partials/warning.tpl" warning=$warning}
    {/if}

    {if isset($help)}
        {include file="fields/partials/help.tpl" help=$help}
    {/if}

    <div
        class="pgform__field__collection pg-translated-field"
        data-collection="container"
    >
        {foreach $children as $index => $child}
            <div
                class="pgform__field__collection__child"
                data-collection-name="{$name|escape:'html':'UTF-8'}"
                data-collection-index="{$index|escape:'html':'UTF-8'}"
            >
                {$child nofilter}{* HTML content, no escape necessary *}

                {if $allowDeletion}
                    <button
                        type="button"
                        data-collection="remove"
                        title="{'misc.fields.collection.buttons.remove.text'|pgtrans}"
                        class="pgbtnIconLight pgbtnIconLight--danger"
                    >
                    </button>
                {/if}
            </div>
        {/foreach}
    </div>

    {if $allowCreation}
        <button
            type="button"
            class="pgform__field__collection__add"
            data-collection="add"
            title="{'misc.fields.collection.buttons.add.text'|pgtrans}"
        >
            {'misc.fields.collection.buttons.add.text'|pgtrans}
        </button>
    {/if}

    {if isset($errors)}
        {include file="fields/partials/errors.tpl" errors=$errors}
    {/if}
</fieldset>