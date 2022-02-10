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
 * @version   2.5.2
 *
 */

namespace PGI\Module\BOModule\Services\Actions;

use Exception;
use PGI\Module\PGForm\Interfaces\Views\FormViewInterface;
use PGI\Module\PGIntl\Services\Builders\TranslationFormBuilder;
use PGI\Module\PGIntl\Services\Handlers\TranslationHandler;
use PGI\Module\PGServer\Foundations\AbstractAction;
use PGI\Module\PGView\Components\Box as BoxComponent;
use ReflectionException;

/**
 * Class StandardizedFormTranslationsBlockAction
 * @package BOModule\Services\Actions
 */
class StandardizedFormTranslationsBlockAction extends AbstractAction
{
    const DEFAULT_TEMPLATE = 'translations/block-form-translations-management';

    /** @var TranslationFormBuilder */
    private $translationFormBuilder;

    /** @var TranslationHandler */
    private $translationHandler;

    public function __construct(
        TranslationFormBuilder $translationFormBuilder,
        TranslationHandler $translationHandler
    ) {
        $this->translationFormBuilder = $translationFormBuilder;
        $this->translationHandler = $translationHandler;

        parent::__construct();
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function process()
    {
        $template = self::DEFAULT_TEMPLATE;

        if ($this->hasConfig('template')) {
            $template = $this->getConfig('template');
        }

        $translation_tag = $this->getConfig('translation_tag');
        $form_action = $this->getConfig('form_action');

        $values = $this->buildTranslationValues();
        $form = $this->translationFormBuilder->build($translation_tag, $values);

        /** @var FormViewInterface $formView */
        $formView = $form->buildView();

        $formView->setAction($this->getLinkHandler()->buildBackOfficeUrl($form_action));

        return $this->buildTemplateResponse($template, array(
            'formView' => new BoxComponent($formView)
        ));
    }

    private function buildTranslationValues()
    {
        $translations = $this->translationHandler->getTranslations(true);

        $values = array();

        foreach ($translations as $name => $data) {
            if (array_key_exists('texts', $data) && !empty($data['texts'])) {
                $values[$name] = $data['texts'];
            }
        }

        return $values;
    }
}
