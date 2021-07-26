<?php
/**
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
 * @version   2.2.0
 *
 */

namespace PGI\Module\BOModule\Services\Actions;

use PGI\Module\BOModule\Services\Actions\StandardizedDisplayPageAction;
use PGI\Module\PGServer\Components\Resources\ScriptFile as ScriptFileResourceComponent;
use PGI\Module\PGServer\Components\Responses\Template as TemplateResponseComponent;
use Exception;

/**
 * Class DisplayProductsPageAction
 * @package BOModule\Services\Actions
 */
class DisplayProductsPageAction extends StandardizedDisplayPageAction
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function process()
    {
        /** @var TemplateResponseComponent $response */
        $response = parent::process();

        return $response->addResource(
            new ScriptFileResourceComponent("/js/toggle-confirmation.js")
        );
    }
}
