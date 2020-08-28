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
<div class="pgiframe">
    <h1>
        {$title|escape:'html':'UTF-8'}
    </h1>

    <iframe
        id="pgIframe{$id|escape:'html':'UTF-8'}"
        src="{$url|escape:'html':'UTF-8'}"
        style="min-height:{$minHeightIframe|escape:'html':'UTF-8'}px; min-width:{$minWidthIframe|escape:'html':'UTF-8'}px;">
    </iframe>

    <a href="{$return_url|escape:'html':'UTF-8'}">
        {'frontoffice.payment.insite.return.link'|pgtrans}
    </a>

    <p class="pgiframe__bottom">
        {'frontoffice.payment.insite.bottom'|pgtrans}
    </p>
</div>