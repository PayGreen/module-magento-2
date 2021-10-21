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
<div class="{if isset($class)} {$class}{/if}">
    {if isset($title)}
        <h2>
            {$title|pgtrans}
        </h2>
    {/if}

    {if isset($subtitle)}
        <h3 class="pg__default">
            {$subtitle|pgtrans}
        </h3>
    {/if}

    {if isset($description)}
        <p class="pg__default">
            {$description|pgtrans}
        </p>
    {/if}

    {$form}
</div>
