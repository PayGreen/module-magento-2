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
{assign var="deactivationLabel" value='misc.toggle.values.false.label'}
{assign var="activationLabel" value='misc.toggle.values.true.label'}

<div>
    <p class="pgtoggle__title">{$title|pgtrans}</p>

    <div class="pgtoggle__container">
        <div class="pgtoggle">
            {if $active == true}
                <a
                    class="pgtoggle__link pgtoggle__link__off"
                    href="{$action|toback}"
                    {if isset($confirm) && $confirm == true}
                        data-confirm=true
                        data-message="{'misc.toggle.values.false.confirmation'|pgtrans}"
                    {/if}
                >
                    {$deactivationLabel|pgtrans}
                </a>
                <span class="pgtoggle__link pgtoggle__link__on__active">
                    {$activationLabel|pgtrans}
                </span>
            {else}
                <span class="pgtoggle__link pgtoggle__link__off__active">
                    {$deactivationLabel|pgtrans}
                </span>
                <a
                    class="pgtoggle__link pgtoggle__link__on"
                    href="{$action|toback}"
                    {if isset($confirm) && $confirm == true}
                        data-confirm=true
                        data-message="{'misc.toggle.values.true.confirmation'|pgtrans}"
                    {/if}
                >
                    {$activationLabel|pgtrans}
                </a>
            {/if}
        </div>

        <p class="pgtoggle__description">{$description|pgtrans}</p>
    </div>
</div>