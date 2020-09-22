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
 * @version   1.1.1
 *}
<div class="pgform__field__switch">
    <input
        type="radio"
        name="{$attr.name}"
        id="{$attr.id}_off"
        value="0"
        class="pgform__field__switch__off"
        {if not $attr.value}checked="checked"{/if}
    />

    <label for="{$attr.id}_off">
        {'config.buttons.no'|pgtrans}
    </label>

    <input
        type="radio"
        name="{$attr.name}"
        id="{$attr.id}_on"
        value="1"
        class="pgform__field__switch__on"
        {if $attr.value}checked="checked"{/if}
    />

    <label for="{$attr.id}_on">
        {'config.buttons.yes'|pgtrans}
    </label>
</div>