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
<fieldset
    {if isset($id)}id="{$id}"{/if}
    class="pgform__field{if isset($fieldsetClasses)} {' '|join:$fieldsetClasses}{/if}{if isset($class)} {$class}{/if}"
    data-js="collection"
>
    {if isset($label)}
        {include file="fields/partials/label.tpl" label=$label attr=$attr}
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
                data-collection-name="{$name}"
                data-collection-index="{$index}"
            >
                {$child}

                {if $allowDeletion}
                    <button
                        type="button"
                        data-collection="remove"
                        title="{'fields.collection.buttons.remove.text'|pgtrans}"
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
            title="{'fields.collection.buttons.add.text'|pgtrans}"
        >
            {'fields.collection.buttons.add.text'|pgtrans}
        </button>
    {/if}

    {if isset($errors)}
        {include file="fields/partials/errors.tpl" errors=$errors}
    {/if}
</fieldset>