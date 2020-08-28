{*
 * 2014 - 2020 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.0.1
 *}
<div class="pglayout">
    {view name="menu" selected="error"}
    {view name="notifications"}

    <div class="pg__mlateral-page">
        <h1 class="pg__danger">
            {'support.error.title'|pgtrans}
        </h1>

        {'support.error.text'|pgtranslines}
    </div>

    <div class="pgcontainer">
        {foreach $exceptions as $exception}
        <div class="pgblock">
            <h2>
                {$exception['type']}
                ({'support.error.exception.code'|pgtrans} {$exception['code']})
            </h2>

            <ul>
                <li>
                    {'support.error.exception.type'|pgtrans}
                    <strong>{$exception['type']}</strong>
                </li>

                <li>
                    {'support.error.exception.text'|pgtrans}
                    <strong>{$exception['text']}</strong>
                </li>

                <li>
                    {'support.error.exception.file'|pgtrans}
                    <strong>{$exception['file']}</strong>
                    {'support.error.exception.line'|pgtrans}
                    <strong>{$exception['line']}</strong>
                </li>
            </ul>

            <h3 class="pg__mtop-md">
                {'support.error.exception.traces'|pgtrans}
            </h3>

            <ol>
                {foreach $exception['traces'] as $trace}
                <li>
                    <strong>{$trace['call']}()</strong>

                    <br />

                    {'support.error.trace.file'|pgtrans}
                    <em>{$trace['file']}</em>
                    {'support.error.trace.line'|pgtrans}
                    <strong>{$trace['line']}</strong>
                </li>
                {/foreach}
            </ol>
        </div>
        {/foreach}
    </div>

    {view name="blocks" page="error"}
</div>