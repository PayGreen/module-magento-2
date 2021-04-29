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
 * @version   2.0.1
 *
 */

/**
 * Class BOModuleControllersTranslationsController
 * @package BOModule\Controllers
 */
class BOModuleControllersTranslationsController extends BOModuleFoundationsAbstractBackofficeController
{
    /** @var PGIntlServicesBuildersTranslationFormBuilder */
    private $translationFormBuilder;

    /** @var PGIntlServicesHandlersTranslationHandler */
    private $translationHandler;

    /** @var PGIntlServicesManagersTranslationManager */
    private $translationManager;

    public function __construct(
        PGIntlServicesBuildersTranslationFormBuilder $translationFormBuilder,
        PGIntlServicesHandlersTranslationHandler $translationHandler,
        PGIntlServicesManagersTranslationManager $translationManager
    ) {
        $this->translationFormBuilder = $translationFormBuilder;
        $this->translationHandler = $translationHandler;
        $this->translationManager = $translationManager;
    }

    /**
     * @return PGServerComponentsResponsesTemplateResponse
     * @throws Exception
     */
    public function displayTranslationsFormAction()
    {
        /** @var PGFormInterfacesFormInterface $form */
        $form = null;

        if ($this->getRequest()->has('form')) {
            $form = $this->getRequest()->get('form');
        } else {
            $values = $this->buildTranslationValues();
            $form = $this->translationFormBuilder->build($values);
        }

        /** @var PGFormInterfacesFormViewInterface $formView */
        $formView = $form->buildView();

        $formView->setAction($this->getLinker()->buildBackOfficeUrl('backoffice.translations.save'));

        return $this->buildTemplateResponse('translations/block-form-translations-management', array(
            'formView' => new PGViewComponentsBox($formView)
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

        /** @var PGFormInterfacesFormInterface $form */
        $form = $this->translationFormBuilder->build($values);

        if ($form->isValid()) {
            foreach ($this->translationHandler->getCodes() as $code) {
                $this->translationManager->saveByCode($code, $form->getValue($code), null, true);
            }

            $this->success('actions.translations.save.result.success');

            return $this->redirect($this->getLinker()->buildBackOfficeUrl('backoffice.translations.display'));
        } else {
            $this->failure('actions.translations.save.result.failure');

            return $this->forward('display@backoffice.translations', array('form' => $form));
        }
    }
}
