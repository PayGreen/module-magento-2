<?php
/**
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
 */

class APPbackofficeServicesPluginsSmartyBool
{
    /** @var PGFrameworkServicesHandlersTranslatorHandler */
    private $translatorHandler;

    public function __construct(PGFrameworkServicesHandlersTranslatorHandler $translatorHandler)
    {
        $this->translatorHandler = $translatorHandler;
    }

    /**
     * @param null|bool $bool
     * @return mixed
     * @throws Exception
     */
    public function writeBoolean($bool)
    {
        switch(true) {
            case ($bool === null):
                $text = $this->translatorHandler->get('module.bool.null');
                $html = '<span class="pg_bool_null">' . $text . '</span>';
                break;
            case ($bool === true):
                $text = $this->translatorHandler->get('module.bool.true');
                $html = '<span class="pg_bool_true">' . $text . '</span>';
                break;
            case ($bool === false):
                $text = $this->translatorHandler->get('module.bool.false');
                $html = '<span class="pg_bool_false">' . $text . '</span>';
                break;
            default:
                throw new Exception("Unknown bool type : '$bool'.");
        }

        return $html;
    }
}
