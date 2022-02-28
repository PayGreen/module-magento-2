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
 * @version   2.6.0
 *
 *}
<h2>{'blocks.cron.control.title'|pgtrans}</h2>
<p>{'blocks.cron.control.description'|pgtrans}</p>
<div class="pgbutton__container pg__mtop-sm">
    <a
        href="{'backoffice.cron.run'|toback}"
        class="pgbutton"
        data-confirm=true
        data-message="{'blocks.cron.control.button.confirm'|pgtrans}"
    >{'blocks.cron.control.button.text'|pgtrans}</a>
</div>
{if $cron_activation == true}
    <h2>{'blocks.cron.control.link.title'|pgtrans}</h2>
    <p>{'blocks.cron.control.link.description'|pgtrans}</p>
    <div style="border: 2px solid #F00; background-color: #FFF; color: #000; padding: 5px 8px;">{'front.cron.run'|tofront}</div>
    {if $cron_activation_mode != 'URL'}
        <p class="pgbutton__warning">{'blocks.cron.control.link.warning'|pgtrans} <strong>{'data.cron_activation_mode.url'|pgtrans}</strong>.</p>
    {/if}
{/if}