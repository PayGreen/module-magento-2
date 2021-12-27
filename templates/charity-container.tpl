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
<div id="pgcharity-container">
    <img class="pgcharity-logo" src="{'FOCharity:logo-charity-kit.png'|picture}" alt="logo-charity">
    <div id="pgcharity-block" >
        <div id="pgcharity-block-container">
            {if $isCharityTestModeActivated}
                <div class="pgdiv__mbottom-sm">
                    <span class="pgbadge">
                        {'misc.charity.test_mode.title'|pgtrans}
                    </span>
                </div>
            {/if}

            <h3 class="pgcharity-custom-title">{'~charity_block_title'|pgtrans}</h3>

            <div class="pgcharity-choice">
                <span class="pgcharity-gift pgcharity-gift--{if $hasGift}yes{else}no{/if}"></span>
                <p class="pgcharity-custom-message">
                    {'~charity_block_message'|pgtrans}
                    <span class="pgcharity-gift-amount">{$currentAmount|escape:'html':'UTF-8'}€</span>
                </p>
            </div>
        </div>
    </div>

    {if $hasGift}
        <a
            href="{'front.charity.cancel_gift'|tofront}"
            class="pgcharity-button-cancel"
            title="{'misc.charity.gift.cancel_title'|pgtrans}"
        >
            {'misc.charity.gift.cancel'|pgtrans}
        </a>
    {/if}
</div>