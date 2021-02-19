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
 * @version   1.2.3
 *
 *}
<div class="pglayout">
    {view name="menu" selected="translations"}
    {view name="notifications"}

    <div class="pgcontainer">
        <div class="pgblock pgblock__lg">
            <h2>
                {'pages.translations.frontoffice.title'|pgtrans}
            </h2>

            <p>
                {'pages.translations.frontoffice.description'|pgtrans}
            </p>

            {$formView}
        </div>

        {view name="blocks" page="translations"}
    </div>
</div>
