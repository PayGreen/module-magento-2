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
<div class="pgcharity-popin">
        <div class="pgcharity-popin-close-cross pgcharity-popin-close-option">X</div>

        {if $isCharityTestModeActivated}
                <div class="pgdiv_flex_row pg_justify_content_center pgdiv__mbottom-sm">
                        <span class="pgbadge">
                                {'misc.charity.test_mode.title'|pgtrans}
                        </span>
                </div>
        {/if}

        <header class="pgcharity-popin-header">
                <img class="pgcharity-logo" src="{'FOCharity:logo-charity-kit.png'|picture}" alt="logo-charity">
                <div class="pgcharity-popin-header-custom-title-container">
                        <h3 class="pgcharity-popin-header-custom-title">{'~charity_popin_title'|pgtrans}</h3>
                        <h4 class="pgcharity-popin-header-subtitle">{'~charity_popin_subtitle'|pgtrans}</h4>
                </div>
        </header>

        <div class="pgcharity-popin-association-list">
                {foreach from=$partnerships item=partnership}
                        {include file="charity-association.tpl"}
                {/foreach}
        </div>

        <form id="pgcharity-popin-gift-form" action="{'front.charity.save_gift'|tofront}" method="post">
                <label for="pgcharity-gift-amount">
                        {'~charity_popin_message'|pgtrans}
                        <input name="pgcharity-gift-amount" type="number" min="0" step="0.01" value="{$giftAmount|escape:'htmlall':'UTF-8'}">
                        <span>€</span>
                </label>
                <input name="pgcharity-gift-association" type="hidden" value="{$associationSelectedId|escape:'htmlall':'UTF-8'}">
        </form>

        <footer class="pgcharity-popin-footer">
                <div class="pgcharity-popin-buttons">
                        <button class="pgcharity-popin-button-close pgcharity-popin-close-option">
                                {'misc.charity.popin.close'|pgtrans}
                        </button>
                        {if $hasGift}
                                <a href="{'front.charity.cancel_gift'|tofront}" class="pgcharity-popin-button-cancel">
                                        {'misc.charity.gift.cancel'|pgtrans}
                                </a>
                        {/if}
                        <button class="pgcharity-popin-button-validate">{'misc.charity.popin.validate'|pgtrans}</button>
                </div>
        </footer>
</div>