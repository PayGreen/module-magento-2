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
 * @version   2.5.0
 *
 */

namespace PGI\Module\PGIntl\Services\Builders;

use PGI\Module\PGForm\Interfaces\FormInterface;
use PGI\Module\PGForm\Services\Builders\FieldBuilder;
use PGI\Module\PGForm\Services\Builders\FormBuilder;
use Exception;
use ReflectionException;

/**
 * Class TranslationFormBuilder
 * @package PGIntl\Services\Builders
 */
class TranslationFormBuilder
{
    const TRANSLATION_FORM_NAME = 'translations';

    private static $BASIC_FIELD_CONFIGURATION = array(
        'model' => 'collection.translations'
    );

    /** @var FormBuilder */
    private $formBuilder;

    /** @var FieldBuilder */
    private $fieldBuilder;

    /** @var array */
    private $translations;

    public function __construct(
        FormBuilder $formBuilder,
        FieldBuilder $fieldBuilder,
        array $translations
    ) {
        $this->formBuilder = $formBuilder;
        $this->fieldBuilder = $fieldBuilder;
        $this->translations = $translations;
    }

    /**
     * @param string $tag
     * @param array $values
     * @return FormInterface
     * @throws ReflectionException
     * @throws Exception
     */
    public function build($tag, array $values = array())
    {
        $form = $this->formBuilder->buildForm(static::TRANSLATION_FORM_NAME);

        foreach ($this->translations as $name => $config) {
            $tags = array_key_exists('tags', $config) ? $config['tags'] : array();
            $enabled = (!array_key_exists('enabled', $config) || ($config['enabled'] === true));

            if ($enabled && in_array($tag, $tags)) {
                $fieldConfig = array_merge(self::$BASIC_FIELD_CONFIGURATION, array(
                    'view' => array(
                        'data' => array(
                            'label' => $config['label'],
                            'help' => $config['help']
                        )
                    )
                ));

                $field = $this->fieldBuilder->build($name, $fieldConfig);

                $form->addField($name, $field);
            }
        }

        $form->setValues($values);

        return $form;
    }
}
