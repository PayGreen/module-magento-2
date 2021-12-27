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
 * @version   2.5.1
 *
 *}
<div
    id="pgcharity-association-{$partnership->association->idAssociation|escape:'htmlall':'UTF-8'}"
    class="pgcharity-association {if $partnership->association->idAssociation == $associationSelectedId}selected{/if}"
    data-association-id="{$partnership->association->idAssociation|escape:'html':'UTF-8'}"
>
    <div class="pgcharity-association-img">
        <img src="{$partnership->association->picture1|escape:'htmlall':'UTF-8'}">
    </div>

    <div class="pgcharity-association-infos">
        <h5 class="pgcharity-association-name">{$partnership->association->name|escape:'htmlall':'UTF-8'}</h5>
        <p class="pgcharity-association-description">{$partnership->association->description|escape:'htmlall':'UTF-8'}</p>
    </div>
</div>