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
 * @version   2.4.0
 *
 *}
<div>
    <span style="font-size: 1.1em; font-weight: bold;">{'front.confirmation.carbon_offsetting.label'|pgtrans} : {$carbon_offset} CO²</span>
</div>
{if $isTreeTestModeActivated}
    {include file="tree/badge-test-mode.tpl"}
{/if}
<div>
    <span style="font-size: 0.7em; line-height: 0.25em;">{'~message_carbon_offsetting'|pgtrans}</span>
</div>
<hr />
