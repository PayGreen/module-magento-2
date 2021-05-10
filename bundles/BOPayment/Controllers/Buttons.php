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
 * Class BOPaymentControllersButtons
 * @package BOPayment\Controllers
 */
class BOPaymentControllersButtons extends BOModuleFoundationsAbstractBackofficeController
{
    /** @var PGPaymentServicesManagersButtonManager */
    private $buttonManager;

    /** @var PGPaymentServicesHandlersPaymentButtonHandler */
    private $paymentButtonHandler;

    /** @var PGFrameworkServicesHandlersPictureHandler */
    private $pictureHandler;

    /** @var PGIntlServicesManagersTranslationManager */
    private $translationManager;

    /** @var PGFrameworkServicesHandlersUploadHandler */
    private $uploadHandler;

    /** @var PGModuleServicesHandlersStaticFile */
    private $staticFileHandler;

    public function __construct(
        PGPaymentServicesManagersButtonManager $buttonManager,
        PGPaymentServicesHandlersPaymentButtonHandler $paymentButtonHandler,
        PGFrameworkServicesHandlersPictureHandler $pictureHandler,
        PGIntlServicesManagersTranslationManager $translationManager,
        PGFrameworkServicesHandlersUploadHandler $uploadHandler,
        PGModuleServicesHandlersStaticFile $staticFileHandler
    ) {
        $this->buttonManager = $buttonManager;
        $this->paymentButtonHandler = $paymentButtonHandler;
        $this->pictureHandler = $pictureHandler;
        $this->translationManager = $translationManager;
        $this->uploadHandler = $uploadHandler;
        $this->staticFileHandler = $staticFileHandler;
    }

    /**
     * @return PGServerComponentsResponsesTemplateResponse
     * @throws Exception
     */
    public function displayListAction()
    {
        $buttons = array();

        /**
         * @var int $key
         * @var PGPaymentInterfacesEntitiesButtonInterface $button
         */
        foreach ($this->buttonManager->getAll() as $button) {
            $data = $button->toArray();

            $data['errors'] = $this->buttonManager->check($button);
            $data['imageUrl'] = $this->paymentButtonHandler->getButtonFinalUrl($button);

            $buttons[] = $data;
        }

        return $this->buildTemplateResponse('button/buttons-list', array(
            'buttons' => $buttons
        ));
    }

    /**
     * @return PGServerFoundationsAbstractResponse
     * @throws Exception
     */
    public function displayUpdateFormAction()
    {
        $error = null;
        $button = null;
        $id = (int) $this->getRequest()->get('id');

        if (!$id) {
            $error = "actions.button.update.errors.id_not_found";
        } else {
            $button = $this->buttonManager->getByPrimary($id);

            if ($button === null) {
                $error = "actions.button.update.errors.button_not_found";
            }
        }

        if ($button === null) {
            $this->failure($error);
            $response =  $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.buttons.display'));
        } else {
            if ($this->getRequest()->has('form')) {
                /** @var PGFormInterfacesFormViewInterface $view */
                $view = $this->getRequest()->get('form')->buildView();
            } else {
                $imageSrc = $button->getImageSrc();
                if (empty($imageSrc)) {
                    $picture = array(
                        'image' => '',
                        'reset' => true
                    );
                } else {
                    $picture = array(
                        'image' => $this->pictureHandler->getUrl($imageSrc),
                        'reset' => false
                    );
                }

                $values = array(
                    'id' => $button->id(),
                    'payment_type' => $button->getPaymentType(),
                    'display_type' => $button->getDisplayType(),
                    'position' => $button->getPosition(),
                    'picture' => $picture,
                    'height' => $button->getImageHeight(),
                    'integration' => $button->getIntegration(),
                    'payment_mode' => $button->getPaymentMode(),
                    'payment_number' => $button->getPaymentNumber(),
                    'first_payment_part' => $button->getFirstPaymentPart(),
                    'order_repeated' => $button->isOrderRepeated(),
                    'payment_report' => $button->getPaymentReport()
                );

                $translations = $this->translationManager->getByCode('button-' . $button->id(), true);
                if (!empty($translations)) {
                    $values['label'] = $translations;
                }

                /** @var PGFormInterfacesFormViewInterface $view */
                $view = $this->buildForm('button_update', $values)->buildView();
            }

            $action = $this->getLinkHandler()->buildBackOfficeUrl('backoffice.buttons.update');

            $view->setAction($action);

            $response = $this->buildTemplateResponse('page-button-update', array(
                'button' => $button->toArray(),
                'errors' => $this->buttonManager->check($button),
                'form' => new PGViewComponentsBox($view)
            ));

            $response->addResource($this->createDefaultButtonPicturesResource());
            $response->addResource(new PGServerComponentsResourcesScriptFileResource('/js/page-buttons.js'));
        }

        return $response;
    }

    /**
     * @return PGServerFoundationsAbstractResponse
     * @throws PGClientExceptionsResponse
     * @throws Exception
     */
    public function updateButtonAction()
    {
        /** @var PGFormInterfacesFormInterface $form */
        $form = $this->buildForm('button_update', $this->getRequest()->getAll());

        $result = null;

        $button = $this->buttonManager->getByPrimary($form->getValue('id'));
        if ($button === null) {
            $this->failure("actions.button.update.errors.button_not_found");
            $result = $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.buttons.display'));
        }

        if ($form->isValid()) {
            if ($this->saveButton($button, $form)) {
                $this->success('actions.button.update.result.success');
                $result = $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.buttons.display'));
            } else {
                $this->failure('actions.button.update.result.failure');
            }
        } else {
            $this->failure('actions.button.update.result.invalid');
        }

        if ($result === null) {
            $result = $this->forward('displayUpdateForm@backoffice.buttons', array(
                'form' => $form,
                'id' => $button->id()
            ));
        }

        return $result;
    }

    /**
     * @return PGServerFoundationsAbstractResponse
     * @throws Exception
     */
    public function displayInsertFormAction()
    {
        if ($this->getRequest()->has('form')) {
            /** @var PGFormInterfacesFormViewInterface $view */
            $view = $this->getRequest()->get('form')->buildView();
        } else {
            $defaultValues = array(
                'picture' => array(
                    'image' => '',
                    'reset' => true
                )
            );

            /** @var PGFormInterfacesFormViewInterface $view */
            $view = $this->buildForm('button', $defaultValues)->buildView();
        }

        $action = $this->getLinkHandler()->buildBackOfficeUrl('backoffice.buttons.insert');

        $view->setAction($action);

        $response = $this->buildTemplateResponse('page-button-insert', array(
            'form' => new PGViewComponentsBox($view)
        ));

        $response->addResource($this->createDefaultButtonPicturesResource());
        $response->addResource(new PGServerComponentsResourcesScriptFileResource('/js/page-buttons.js'));

        return $response;
    }

    /**
     * @return PGServerFoundationsAbstractResponse
     * @throws PGClientExceptionsResponse
     * @throws Exception
     */
    public function insertButtonAction()
    {
        /** @var PGFormInterfacesFormInterface $form */
        $form = $this->buildForm('button', $this->getRequest()->getAll());

        $result = null;

        if ($form->isValid()) {
            $button = $this->buttonManager->getNew();

            if ($this->saveButton($button, $form)) {
                $this->success('actions.button.insert.result.success');
                $result = $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.buttons.display'));
            } else {
                $this->failure('actions.button.insert.result.failure');
            }
        } else {
            $this->failure('actions.button.insert.result.invalid');
        }

        if ($result === null) {
            $result = $this->forward('displayInsertForm@backoffice.buttons', array(
                'form' => $form
            ));
        }

        return $result;
    }

    /**
     * @return PGServerFoundationsAbstractResponse
     * @throws Exception
     */
    public function displayFiltersFormAction()
    {
        $error = null;
        $button = null;
        $id = (int) $this->getRequest()->get('id');

        if (!$id) {
            $error = "actions.button.filters.errors.id_not_found";
        } else {
            $button = $this->buttonManager->getByPrimary($id);

            if ($button === null) {
                $error = "actions.button.filters.errors.button_not_found";
            }
        }

        if ($button === null) {
            $this->failure($error);
            $response =  $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.buttons.display'));
        } else {
            if ($this->getRequest()->has('form')) {
                /** @var PGFormInterfacesFormViewInterface $view */
                $view = $this->getRequest()->get('form')->buildView();
            } else {
                $data = array(
                    'id' => $button->id(),
                    'categories_filtering_mode' => $button->getFilteredCategoryMode(),
                    'filtered_categories' => $button->getFilteredCategoryPrimaries(),
                    'cart_amount_limits' => array(
                        'min' => $button->getMinAmount(),
                        'max' => $button->getMaxAmount()
                    )
                );

                $view = $this->buildForm('button_filters', $data)->buildView();
            }

            $action = $this->getLinkHandler()->buildBackOfficeUrl('backoffice.buttons.update_filters');
            $view->setAction($action);

            $scriptResource = new PGServerComponentsResourcesScriptFileResource('/js/page-buttons-filters.js');

            $response = $this->buildTemplateResponse('page-button-filters', array(
                'form' => new PGViewComponentsBox($view)
            ));

            $response->addResource($scriptResource);
        }

        return $response;
    }

    /**
     * @return PGServerFoundationsAbstractResponse
     * @throws Exception
     */
    public function updateButtonFiltersAction()
    {
        $form = $this->buildForm('button_filters', $this->getRequest()->getAll());

        $result = null;
        $button = null;

        if (!$form->getValue('id')) {
            $this->failure("actions.button.filters.errors.id_not_found");
        } else {
            $button = $this->buttonManager->getByPrimary($form->getValue('id'));

            if ($button === null) {
                $this->failure("actions.button.filters.errors.button_not_found");
            }
        }

        if ($button === null) {
            return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.buttons.display'));
        }

        if ($form->isValid()) {
            if ($this->saveButtonFilter($button, $form)) {
                $this->success('actions.button.filters.result.success');
                $result = $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.buttons.display'));
            } else {
                $this->failure('actions.button.filters.result.failure');
            }
        } else {
            $this->failure('actions.button.filters.result.invalid');
        }

        if ($result === null) {
            $result = $this->forward('displayFiltersForm@backoffice.buttons', array(
                'form' => $form,
                'id' => $button->id()
            ));
        }

        return $result;
    }

    /**
     * @param PGPaymentInterfacesEntitiesButtonInterface $button
     * @param PGFormInterfacesFormInterface $form
     * @return bool
     * @throws PGClientExceptionsResponse
     * @throws Exception
     */
    protected function saveButtonFilter(
        PGPaymentInterfacesEntitiesButtonInterface $button,
        PGFormInterfacesFormInterface $form
    ) {
        $success = false;

        $categories_filtering_mode = $form->getValue('categories_filtering_mode');
        $filtered_categories = $form->getValue('filtered_categories');
        $cart_amount_limits = $form->getValue('cart_amount_limits');

        $button
            ->setMinAmount($cart_amount_limits['min'])
            ->setMaxAmount($cart_amount_limits['max'])
            ->setFilteredCategoryPrimaries($filtered_categories)
        ;

        if (!empty($filtered_categories)) {
            $button->setFilteredCategoryMode($categories_filtering_mode);

        } else {
            $button->setFilteredCategoryMode('NONE');
        }

        $errors = $this->buttonManager->checkFilters($button);
        foreach ($errors as $error) {
            $this->failure($error);
        }

        if (count($errors) === 0) {
            $success = $this->buttonManager->save($button);
        }

        return $success;
    }

    /**
     * @param PGPaymentInterfacesEntitiesButtonInterface $button
     * @param PGFormInterfacesFormInterface $form
     * @return bool
     * @throws PGClientExceptionsResponse
     * @throws Exception
     */
    protected function saveButton(
        PGPaymentInterfacesEntitiesButtonInterface $button,
        PGFormInterfacesFormInterface $form
    ) {
        $success = false;

        $picture = $form->getValue('picture');
        $uploadedFile = $this->uploadHandler->getFile('picture.image');

        if ($picture['reset']) {
            $button->setImageSrc(null);
        } elseif (($uploadedFile !== null) && ($uploadedFile instanceof PGFrameworkComponentsUploadedFile)) {
            if (!$uploadedFile->hasError()) {
                $picture = $this->pictureHandler->store(
                    $uploadedFile->getTemporaryName(),
                    $uploadedFile->getRealName()
                );

                $button->setImageSrc($picture->getFilename());

                $this->success("actions.button.save.result.success.picture");
            } elseif ($uploadedFile->getError() !== 4) {
                $this->failure("actions.button.save.errors.upload_picture_error");
            }
        }

        $button
            ->setIntegration($form->getValue('integration'))
            ->setDisplayType($form->getValue('display_type'))
            ->setPosition($form->getValue('position'))
            ->setPaymentMode($form->getValue('payment_mode'))
            ->setPaymentType($form->getValue('payment_type'))
            ->setPaymentNumber($form->getValue('payment_number'))
            ->setFirstPaymentPart($form->getValue('first_payment_part'))
            ->setPaymentReport($form->getValue('payment_report'))
        ;

        if ($form->hasField('order_repeated')) {
            $button->setOrderRepeated($form->getValue('order_repeated'));
        }
        if ($form->hasField('height')) {
            $button->setImageHeight($form->getValue('height'));
        }


        if (!$button->getPosition()) {
            $button->setPosition($this->buttonManager->count() + 1);
        }

        $skipCompositeTests = !$button->id();
        $errors = $this->buttonManager->check($button, $skipCompositeTests);
        foreach ($errors as $error) {
            $this->failure($error);
        }

        if (count($errors) === 0) {
            $success = $this->buttonManager->save($button);
        }

        if ($success) {
            $code = 'button-' . $button->id();
            $value = $form->getValue('label');
            $this->translationManager->saveByCode($code, $value, null, true);
        }

        return $success;
    }

    /**
     * @return PGServerComponentsResponsesRedirectionResponse
     * @throws Exception
     */
    public function deleteButtonAction()
    {
        $id = (int) $this->getRequest()->get('id');

        if (!$id) {
            $this->failure("actions.button.delete.errors.id_not_found");
        } else {
            $button = $this->buttonManager->getByPrimary($id);

            if ($button === null) {
                $this->failure("actions.button.delete.errors.button_not_found");
            } elseif ($this->buttonManager->delete($button)) {
                $this->success("actions.button.delete.result.success");
            } else {
                $this->failure("actions.button.delete.result.failure");
            }
        }

        return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.buttons.display'));
    }

    /**
     * @return PGServerComponentsResourcesDataResource
     */
    protected function createDefaultButtonPicturesResource()
    {
        /** @var PGSystemComponentsBag $parameters */
        $parameters = $this->getParameters();

        $defaultButtonPictures = array();

        foreach ($parameters['payment.pictures'] as $type => $filename) {
            $defaultButtonPictures[$type] = $this->staticFileHandler->getUrl(
                "/pictures/PGPayment/payment-buttons/$filename"
            );
        }

        return new PGServerComponentsResourcesDataResource(array(
            'default_button_pictures' => $defaultButtonPictures
        ));
    }
}
