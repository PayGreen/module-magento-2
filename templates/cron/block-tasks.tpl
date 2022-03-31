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
 * @version   2.6.1
 *
 *}
<h2>{'blocks.cron.tasks.title'|pgtrans}</h2>
<p>{'blocks.cron.tasks.description'|pgtrans}</p>
<table>
    <thead>
        <tr>
            <td>{'blocks.cron.tasks.head.name'|pgtrans}</td>
            <td>{'blocks.cron.tasks.head.status'|pgtrans}</td>
        </tr>
    </thead>
    <tbody>
        {foreach from=$tasks key="name" item="time"}
            {assign var="transkey" value="task.{$name}"}
            <tr>
                <td>{$transkey|pgtrans}</td>
                <td>{$time|pgtrans}</td>
            </tr>
        {/foreach}
    </tbody>
</table>
