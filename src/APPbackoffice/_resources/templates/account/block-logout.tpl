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
 * @version   1.2.5
 *
 *}
<div class="pgblock pgblock__md pg__danger">
    <h2 class="pg__danger">
        {'pages.account.logout.title'|pgtrans}
    </h2>
    
    <p>
        {'pages.account.logout.description'|pgtrans}
    </p>

    <div class="pgbutton__container pg__mtop-md">
        <a
            href="{'backoffice.account.disconnect'|toback}"
            class="pgbutton pg__danger"
        >
            {'pages.account.logout.action'|pgtrans}
        </a>
    </div>
</div>