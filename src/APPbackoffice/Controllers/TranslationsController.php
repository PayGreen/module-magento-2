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
 * @version   1.1.1
 */

class APPbackofficeControllersTranslationsController extends APPbackofficeFoundationsAbstractBackofficeController
{
    /**
     * @return PGServerComponentsResponsesTemplateResponse
     * @throws Exception
     */
    public function displayAction()
    {
        /** @var PGIntlServicesBuildersTranslationFormBuilder $translationFormBuilder */
        $translationFormBuilder = $this->getService('builder.translation_form');

        /** @var PGFormInterfacesFormInterface $form */
        $form = null;

        if ($this->getRequest()->has('form')) {
            $form = $this->getRequest()->get('form');
        } else {
            $values = $this->buildTranslationValues();
            $form = $translationFormBuilder->build($values);
        }

        /** @var PGFormInterfacesFormViewInterface $formView */
        $formView = $form->buildView();

        $formView->setAction($this->getLinker()->buildBackOfficeUrl('backoffice.translations.save'));

        return $this->buildTemplateResponse('page-translations', array(
            'formView' => new PGViewComponentsBox($formView)
        ));
    }

    private function buildTranslationValues()
    {
        /** @var PGIntlServicesHandlersTranslationHandler $translationHandler */
        $translationHandler = $this->getService('handler.translation');

        $translations = $translationHandler->getTranslations(true);

        $values = array();

        foreach($translations as $name => $data) {
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
        /** @var PGIntlServicesBuildersTranslationFormBuilder $translationFormBuilder */
        $translationFormBuilder = $this->getService('builder.translation_form');

        /** @var PGIntlServicesHandlersTranslationHandler $translationHandler */
        $translationHandler = $this->getService('handler.translation');

        /** @var PGIntlServicesManagersTranslationManager $translationManager */
        $translationManager = $this->getService('manager.translation');

        $values = $this->getRequest()->getAll();

        /** @var PGFormInterfacesFormInterface $form */
        $form = $translationFormBuilder->build($values);

        if ($form->isValid()) {
            foreach ($translationHandler->getCodes() as $code) {
                $translationManager->saveByCode($code, $form->getValue($code), null, true);
            }

            $this->success('pages.translations.result.success');

            return $this->redirect($this->getLinker()->buildBackOfficeUrl('backoffice.translations.display'));
        } else {
            $this->failure('pages.translations.result.failure');

            return $this->forward('display@backoffice.translations', array('form' => $form));
        }
    }
}
