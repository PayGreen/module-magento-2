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
<div class="pgform__field__range">
    <span class="pgform__field__range__from">
        {'misc.forms.default.input.range.from'|pgtrans}
    </span>

    {$children.min}

    <span class="pgform__field__range__to">
        {'misc.forms.default.input.range.to'|pgtrans}
    </span>

    {$children.max}
</div>