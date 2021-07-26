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

namespace PGI\Module\PGForm\Foundations;

use PGI\Module\PGForm\Interfaces\ValidatorInterface;
use PGI\Module\PGIntl\Components\Translation as TranslationComponent;
use Exception;

/**
 * Class AbstractValidator
 * @package PGForm\Foundations
 */
abstract class AbstractValidator implements ValidatorInterface
{
    const ERROR_TRANSLATION_KEY = null;

    private $config = array();

    private $processed = false;

    private $errors = array();

    private $children = array();

    public function validate($value)
    {
        $this->errors = array();

        $test = (bool) $this->test($value);

        $this->processed = true;

        if (!$test) {
            $this->errors[] = $this->getErrorText();
        } else {
            /** @var ValidatorInterface $child */
            foreach ($this->children as $child) {
                if (!$child->validate($value)->isValid()) {
                    $this->errors = array_merge($this->errors, $child->getErrors());
                }
            }
        }

        return $this;
    }

    abstract protected function test($value);

    /**
     * @return bool
     */
    public function isProcessed()
    {
        return $this->processed;
    }

    /**
     * @return bool|null
     * @throws Exception
     */
    public function isValid()
    {
        if ($this->processed === false) {
            throw new Exception("Validator is not processed.");
        }

        return empty($this->errors);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getErrors()
    {
        if ($this->processed === false) {
            throw new Exception("Validator is not processed.");
        }

        return $this->errors;
    }

    /**
     * @inheritDoc
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        return $this;
    }

    protected function getConfig($key)
    {
        return $this->hasConfig($key) ? $this->config[$key] : null;
    }

    protected function getDefaultConfig($key)
    {
        return $this->hasConfig($key) ? $this->config[$key] : $this->getConfig('default');
    }

    protected function hasConfig($key)
    {
        return array_key_exists($key, $this->config);
    }

    protected function getErrorText()
    {
        if ($this->hasConfig('error')) {
            $text = $this->getConfig('error');
        } elseif (static::ERROR_TRANSLATION_KEY !== null) {
            $text = static::ERROR_TRANSLATION_KEY;
        } else {
            throw new Exception("Validator has no error text defined.");
        }

        return new TranslationComponent($text, $this->getErrorData());
    }

    protected function getErrorData()
    {
        return array();
    }

    public function addChild(ValidatorInterface $child)
    {
        $this->children[] = $child;
    }
}
