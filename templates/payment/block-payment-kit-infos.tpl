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
<div class="pgblock  pgblock__max__md">
    <h2>
        {'blocks.payment_kit_infos.title'|pgtrans}
    </h2>

    {include file="table-infos.tpl"}

    {if $growth > 0}
        <p class="growth">{'blocks.payment_kit_infos.increase'|pgtrans} <span class="pgincrease">+{$growth}%</span>.</p>
    {elseif $growth < 0}
        <p class="growth">{'blocks.payment_kit_infos.decrease'|pgtrans} <span class="pgdecrease">{$growth}%</span>.</p>
    {/if}

    {include file="payment/block-payments-overview.tpl" entries=$paymentsOverview}
    <p>*{'blocks.payment_statistics.disclaimer'|pgtrans}</p>
</div>