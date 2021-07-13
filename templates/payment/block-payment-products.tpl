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
 * @version   2.1.1
 *
 *}
<div class="pgdiv_flex_column">
    <div class="pgblock">
        <h2>
            {'blocks.payment.payment_products.title'|pgtrans}
        </h2>

        {include
        file="toggle.tpl"
        title="blocks.payment.payment_products.label"
        description="blocks.payment.payment_products.help"
        confirm="true"
        action="backoffice.payment_products.activation"
        active=$paymentActivated}

        <p class="pg__default">
            {'blocks.payment.payment_products.description'|pgtrans}
        </p>

    </div>
</div>