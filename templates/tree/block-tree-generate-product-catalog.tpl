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
 * @version   2.5.2
 *
 *}
<div class="pgdiv_flex_column">
    <p>{"blocks.tree_generate_product_catalog.description"|pgtranslines}</p>
    <div class="pgtreegenerate__button pgbutton__container {if !($empty_cache)}pgbutton__hidden{/if}">
        <a class="pgbutton">
            {'blocks.tree_generate_product_catalog.buttons.file_generate.label'|pgtrans}
        </a>
    </div>

    <div class="pgtreegenerate__div pgbutton__container {if !isset($notices)}pgbutton__hidden{/if}">
            <ul class="pgtreegenerate__list">
                {foreach $notices as $notice}
                    <li>
                        {$notice|escape:'html':'UTF-8'}
                    </li>
                {/foreach}
            </ul>
    </div>

    <div class="pgtreeloader pgloader pgbutton__container pgbutton__hidden">
    </div>

    <div class="pgtreegenerate__warning pgbutton__container">
        {if isset($error)}<p class="pgbutton__warning">{$error|escape:'html':'UTF-8'}</p>{/if}
    </div>

    <div class="pgtreeregenerate__button pgbutton__container {if ($empty_cache)}pgbutton__hidden{/if}">
        <a class="pgbutton">
            {'blocks.tree_generate_product_catalog.buttons.file_regenerate.label'|pgtrans}
        </a>
    </div>
</div>