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
 * @version   1.1.1
 *}
<div class="pglayout">
    {view name="menu" selected="system"}
    {view name="notifications"}

    <div class="pgcontainer">
        <div class="pgblock pgblock__xl">
            <h2>
                {'system.title'|pgtrans}
            </h2>

            <h3 class="pg__default">
                {'system.platform.title'|pgtrans}
            </h3>

            {include file="table.tpl" tbodyTranslationBase="system.platform.fields" tbody=[
                'platform' => "$platforme $version_platforme",
                'module' => $version_module,
                'framework' => $version_framework,
                'php' => $version_php,
                'curl' => $version_curl,
                'ssl' => $version_ssl
            ]}

        </div>

        {view name="system.paths"}

        {view name="blocks" page="system"}
    </div>
</div>
