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

namespace PGI\Module\BOPayment\Services\Controllers;

use PGI\Module\BOModule\Foundations\Controllers\AbstractBackofficeController;
use PGI\Module\PGForm\Interfaces\FormInterface;
use PGI\Module\PGForm\Interfaces\Views\FormViewInterface;
use PGI\Module\PGIntl\Services\Builders\TranslationFormBuilder;
use PGI\Module\PGIntl\Services\Handlers\TranslationHandler;
use PGI\Module\PGIntl\Services\Managers\TranslationManager;
use PGI\Module\PGServer\Components\Responses\Template as TemplateResponseComponent;
use PGI\Module\PGView\Components\Box as BoxComponent;
use Exception;
use ReflectionException;

/**
 * Class Translations
 * @package BOPayment\Services\Controllers
 */
class Translations extends AbstractBackofficeController
{
    /** @var TranslationFormBuilder */
    private $translationFormBuilder;

    /** @var TranslationHandler */
    private $translationHandler;

    /** @var TranslationManager */
    private $translationManager;

    public function __construct(
        TranslationFormBuilder $translationFormBuilder,
        TranslationHandler $translationHandler,
        TranslationManager $translationManager
    ) {
        $this->translationFormBuilder = $translationFormBuilder;
        $this->translationHandler = $translationHandler;
        $this->translationManager = $translationManager;
    }

    /**
     * @return TemplateResponseComponent
     * @throws Exception
     */
    public function displayPaymentTranslationsFormAction()
    {
        /** @var FormInterface $form */
        $form = null;

        if ($this->getRequest()->has('form')) {
            $form = $this->getRequest()->get('form');
        } else {
            $values = $this->buildTranslationValues();
            $form = $this->translationFormBuilder->build('payment', $values);
        }

        /** @var FormViewInterface $formView */
        $formView = $form->buildView();

        $formView->setAction($this->getLinkHandler()->buildBackOfficeUrl('backoffice.payment_translations.save'));

        return $this->buildTemplateResponse('translations/block-form-translations-management', array(
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

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function saveAction()
    {
        $values = $this->getRequest()->getAll();

        /** @var FormInterface $form */
        $form = $this->translationFormBuilder->build('payment', $values);

        if ($form->isValid()) {
            foreach ($this->translationHandler->getCodes() as $code) {
                if($form->hasField($code)){
                    $this->translationManager->saveByCode($code, $form->getValue($code), null, true);
                }
            }

            $this->success('actions.translations.save.result.success');

            return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.payment_translations.display'));
        } else {
            $this->failure('actions.translations.save.result.failure');

            return $this->forward('display@backoffice.payment_translations', array('form' => $form));
        }
    }
}
