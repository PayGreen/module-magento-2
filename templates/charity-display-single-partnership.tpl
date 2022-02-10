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
<div id="pgcharity-container">
    <div id="pgcharity-association-details" class="pgcharity-block-association">
        <div class="pgcharity-association-img">
            <img src="{$partnership->association->picture1|escape:'htmlall':'UTF-8'}" alt="association-image">
        </div>
        <span>{'misc.charity.single.details'|pgtrans}</span>
    </div>
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
            <div class="pgcharity-custom-title pgdiv__mbottom-sm">{'misc.charity.single.help'|pgtrans} {$partnership->association->name|escape:'htmlall':'UTF-8'}</div>

            <form class="pgcharity-form" action="{'front.charity.save_gift'|tofront}" method="post">
                <label for="pgcharity-gift-amount">
                    {'~charity_popin_message'|pgtrans}
                    <input name="pgcharity-gift-amount" type="number" min="0" step="0.01" value="{$currentAmount|escape:'htmlall':'UTF-8'}">
                    <span>€</span>
                </label>
                <input name="pgcharity-gift-association" type="hidden" value={$partnership->association->idAssociation|escape:'htmlall':'UTF-8'}>

                <div class="pgcharity-popin-buttons pgdiv__mtop-sm">
                    {if $hasGift}
                        <a href="{'front.charity.cancel_gift'|tofront}" class="pgcharity-button pgcharity-button-cancel">
                            {'misc.charity.gift.cancel'|pgtrans}
                        </a>
                    {/if}
                    <button class="pgcharity-button pgcharity-button-validate">{'misc.charity.popin.validate'|pgtrans}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="paygreen-charity-popin-container pgHidden">
    <div class="pgcharity-popin">
        <div id="pgcharity-popin-close-option" class="pgcharity-popin-close-cross pgcharity-popin-close-option">X</div>
            <div class="pgcharity-association-img pgcharity-association-img_popin">
                <img src="{$partnership->association->picture1|escape:'htmlall':'UTF-8'}" alt="association logo popin">
            </div>

            <div class="pgcharity-association-infos">
                <h5 class="pgcharity-association-name">{$partnership->association->name|escape:'htmlall':'UTF-8'}</h5>
                <p class="pgcharity-association-description">{$partnership->association->description|escape:'htmlall':'UTF-8'}</p>
            </div>
    </div>
</div>