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
 * @version   2.1.0
 *
 *}
<div class="pgclimatebot__content pgclimatebot__content--{$position} pgclimatebot__content--{$position}--{$corner}">
    <div class="pgclimatebot__content__title">
        {'~message_carbon_footprint'|pgtrans}
    </div>

    <div class="pgclimatebot__carbondata">
        <div class="pgclimatebot__carbondata__total" style="color:{$color}">{$carbonEmittedTotal} kg{'climatebot.total'|pgtrans}</div>
        <div class="pgclimatebot__carbondata__description">{'climatebot.description'|pgtrans}</div>

        <div class="pgclimatebot__carbondata__details">
            <div
                class="
                pgclimatebot__carbondata__details__item
                pgclimatebot__carbondata__details__item--{$corner}
                pgclimatebot__carbondata__details__item--web
                {if $carbonEmittedFromDigital == 0}pgclimatebot__carbondata__details__item--disabled{/if}"
                style="background-color:{$color}"
            >
                <div class="pgclimatebot__carbondata__details__item__title">{'climatebot.data.web'|pgtrans}</div>
                <div class="pgclimatebot__carbondata__details__item__carbon">
                    {if $carbonEmittedFromDigital == 0}
                        --
                    {else}
                        {$carbonEmittedFromDigital} g
                    {/if}
                </div>
            </div>

            <div
                class="
                pgclimatebot__carbondata__details__item
                pgclimatebot__carbondata__details__item--{$corner}
                pgclimatebot__carbondata__details__item--cart
                {if $carbonEmittedFromProduct == 0}pgclimatebot__carbondata__details__item--disabled{/if}"
                style="background-color:{$color}"
            >
                <div class="pgclimatebot__carbondata__details__item__title">{'climatebot.data.cart'|pgtrans}</div>
                <div class="pgclimatebot__carbondata__details__item__carbon">
                    {if $carbonEmittedFromProduct == 0}
                        --
                    {else}
                        {$carbonEmittedFromProduct} kg
                    {/if}
                </div>
            </div>

            <div
                class="
                pgclimatebot__carbondata__details__item
                pgclimatebot__carbondata__details__item--{$corner}
                pgclimatebot__carbondata__details__item--shipping
                {if $carbonEmittedFromTransportation == 0} pgclimatebot__carbondata__details__item--disabled{/if}"
                style="background-color:{$color}"
            >
                <div class="pgclimatebot__carbondata__details__item__title">{'climatebot.data.shipping'|pgtrans}</div>
                <div class="pgclimatebot__carbondata__details__item__carbon">
                    {if $carbonEmittedFromTransportation == 0}
                        --
                    {else}
                        {$carbonEmittedFromTransportation} g
                    {/if}
                </div>
            </div>
        </div>
    </div>

    {if $isDetailsActivated}
        <div class="pgclimatebot__learnmore">
            <a href="{$detailsUrl}" target="_blank">{'~message_find_out_more'|pgtrans}</a>
        </div>
    {/if}
</div>
