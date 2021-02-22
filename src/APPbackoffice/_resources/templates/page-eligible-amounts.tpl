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
 * @version   1.2.4
 *
 *}
<div class="pglayout" data-pglayout="eligible-amounts">
    {view name="menu" selected="eligible_amounts"}
    {view name="notifications"}

    <div class="pgblock">
        <h2>
            {'eligible_amounts.actions.save_category_payments.title'|pgtrans}
        </h2>

        {'eligible_amounts.actions.save_category_payments.explain'|pgtranslines}

        {$eligibleAmountsViewForm}

        <div class="pgblock__footer pg__default">
            <h3 class="pg__default">
                {'eligible_amounts.actions.save_category_payments.footerTitle'|pgtrans}
            </h3>

            {'eligible_amounts.actions.save_category_payments.footer'|pgtranslines}
        </div>
    </div>

    <div class="pgblock pgblock__xxl">
        <h2>
            {'eligible_amounts.actions.save_shipping_payments.title'|pgtrans}
        </h2>

        {'eligible_amounts.actions.save_shipping_payments.explain'|pgtranslines}

        {$shippingCostViewForm}
    </div>

    {view name="blocks" page="eligible-amounts"}
</div>
