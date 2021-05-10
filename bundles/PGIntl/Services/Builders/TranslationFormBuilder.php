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
 * @version   2.0.2
 *
 */

/**
 * Class PGIntlServicesBuildersTranslationFormBuilder
 * @package PGIntl\Services\Builders
 */
class PGIntlServicesBuildersTranslationFormBuilder
{
    const TRANSLATION_FORM_NAME = 'translations';

    private static $BASIC_FIELD_CONFIGURATION = array(
        'model' => 'collection.translations'
    );

    /** @var PGFormServicesFormBuilder */
    private $formBuilder;

    /** @var PGFormServicesFieldBuilder */
    private $fieldBuilder;

    /** @var array */
    private $translations;

    public function __construct(
        PGFormServicesFormBuilder $formBuilder,
        PGFormServicesFieldBuilder $fieldBuilder,
        array $translations
    ) {
        $this->formBuilder = $formBuilder;
        $this->fieldBuilder = $fieldBuilder;
        $this->translations = $translations;
    }

    /**
     * @param array $values
     * @return PGFormInterfacesFormInterface
     * @throws ReflectionException
     * @throws Exception
     */
    public function build(array $values = array())
    {
        $form = $this->formBuilder->buildForm(static::TRANSLATION_FORM_NAME);

        foreach ($this->translations as $name => $config) {
            if (!array_key_exists('enabled', $config) || ($config['enabled'] === true)) {
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
