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
<div class="pgdiv_flex_row">
    <div class="pgblock__max__sm">
        {$form}
    </div>
    <div class="pg__mleft-lg">
        {include
            file="tree-bot.tpl"
            attr=[
            'color' => $color,
            'position' => $position,
            'corner' => $corner,
            'isDetailsActivated' => $isDetailsActivated,
            'detailsUrl' => $detailsUrl,
            'carbonEmittedTotal' => $carbonEmittedTotal,
            'carbonEmittedFromDigital' => $carbonEmittedFromDigital,
            'carbonEmittedFromTransportation' => $carbonEmittedFromTransportation,
            'carbonEmittedFromProduct' => $carbonEmittedFromProduct,
            'isTreeTestModeActivated' => $isTreeTestModeActivated]
        }

        <div class='pgclimatebot__button pgclimatebot__button--{$position|escape:'html':'UTF-8'} pgclimatebot__button--{$position|escape:'html':'UTF-8'}--{$corner|escape:'html':'UTF-8'}'>
            <div class="pgclimatebot__button__closebutton"></div>
        </div>
    </div>
</div>