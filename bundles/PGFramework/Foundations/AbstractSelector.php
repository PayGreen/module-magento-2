<?php
/**
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
 * @version   2.6.1
 *
 */

namespace PGI\Module\PGFramework\Foundations;

use PGI\Module\PGFramework\Interfaces\SelectorInterface;
use PGI\Module\PGIntl\Services\Translator;
use PGI\Module\PGLog\Interfaces\LoggerInterface;
use PGI\Module\PGSystem\Foundations\AbstractObject;

/**
 * Class AbstractSelector
 * @package PGFramework\Foundations
 */
abstract class AbstractSelector extends AbstractObject implements SelectorInterface
{
    private $choices = array();

    /** @var LoggerInterface */
    protected $logger;

    /** @var Translator */
    protected $translator;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Translator $translator
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @param string $code
     * @return string
     */
    public function getName($code)
    {
        $choices = $this->getChoices();

        if (array_key_exists($code, $choices)) {
            $name = $choices[$code];
        } else {
            $name = $this->translate($code);
        }

        return $name;
    }

    public function getKeys()
    {
        return array_keys($this->getChoices());
    }

    /**
     * @return array
     */
    public function getChoices()
    {
        if (empty($this->choices)) {
            $this->choices = $this->buildChoices();
        }

        return $this->choices;
    }

    /**
     * @param array $choices
     */
    protected function setChoices(array $choices)
    {
        $this->choices = $choices;
    }

    /**
     * @param $code
     * @return string
     */
    protected function translate($code)
    {
        $root = $this->getTranslationRoot();

        $path = "$root.$code";

        if ($this->translator->has($path)) {
            $name = $this->translator->get($path);
        } else {
            $this->logger->warning("Label not found in '$path'.");
            $name = $code;
        }

        return $name;
    }

    /**
     * @return array
     */
    abstract protected function buildChoices();

    /**
     * @return string|null
     */
    protected function getTranslationRoot()
    {
        return null;
    }
}
