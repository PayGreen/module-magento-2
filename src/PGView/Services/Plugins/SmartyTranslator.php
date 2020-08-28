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

class PGViewServicesPluginsSmartyTranslator
{
    /** @var PGFrameworkServicesHandlersTranslatorHandler */
    private $translator;

    public function __construct(PGFrameworkServicesHandlersTranslatorHandler $translator)
    {
        $this->translator = $translator;
    }

    public function translateExpression($key)
    {
        return $this->translator->get($key);
    }

    public function translateParagraph($key)
    {
        $lines = $this->translator->get($key);

        if (!is_array($lines)) {
            $lines = array($lines);
        }

        $result = '';

        foreach ($lines as $line) {
            $result .= "<p>$line</p>" . PHP_EOL;
        }

        return $result;
    }
}
