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
 * @version   2.1.1
 *
 */

/**
 * Class PGIntlServicesPluginsSmartyTranslator
 * @package PGIntl\Services\Plugins
 */
class PGIntlServicesPluginsSmartyTranslator
{
    /** @var PGIntlServicesTranslator */
    private $translator;

    public function __construct(PGIntlServicesTranslator $translator)
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
