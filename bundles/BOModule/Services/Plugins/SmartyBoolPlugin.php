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
 * @version   2.3.0
 *
 */

namespace PGI\Module\BOModule\Services\Plugins;

use PGI\Module\PGIntl\Services\Translator;
use Exception;

/**
 * Class SmartyBoolPlugin
 * @package BOModule\Services\Plugins
 */
class SmartyBoolPlugin
{
    /** @var Translator */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param null|bool $bool
     * @return mixed
     * @throws Exception
     */
    public function writeBoolean($bool)
    {
        switch (true) {
            case ($bool === null):
                $text = $this->translator->get('misc.module.bool.null');
                $html = '<span class="pgemphasis">' . $text . '</span>';
                break;
            case ($bool === true):
                $text = $this->translator->get('misc.module.bool.true');
                $html = '<span class="pgemphasis pgemphasis--success">' . $text . '</span>';
                break;
            case ($bool === false):
                $text = $this->translator->get('misc.module.bool.false');
                $html = '<span class="pgemphasis pgemphasis--danger">' . $text . '</span>';
                break;
            default:
                throw new Exception("Unknown bool type : '$bool'.");
        }

        return $html;
    }
}
