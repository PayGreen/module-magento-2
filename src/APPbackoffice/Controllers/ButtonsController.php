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
 * @version   1.2.5
 *
 */

class APPbackofficeControllersButtonsController extends APPbackofficeFoundationsAbstractBackofficeController
{
    /** @var PGDomainServicesManagersButtonManager */
    private $buttonManager;

    public function __construct(
        PGFrameworkServicesNotifier $notifier,
        PGFrameworkServicesLogger $logger,
        PGServerServicesLinker $linker,
        PGDomainServicesManagersButtonManager $buttonManager
    ) {
        parent::__construct($notifier, $logger, $linker);

        $this->buttonManager = $buttonManager;
    }

    /**
     * @return PGServerComponentsResponsesTemplateResponse
     * @throws Exception
     */
    public function displayListAction()
    {
        /** @var PGFrameworkServicesHandlersPaymentButtonHandler $paymentButtonHandler */
        $paymentButtonHandler = $this->getService('handler.payment_button');

        $buttons = array();

        /**
         * @var int $key
         * @var PGModuleEntitiesButton $button
         */
        foreach ($this->buttonManager->getAll() as $button) {
            $data = $button->toArray();

            $data['errors'] = $this->buttonManager->check($button);
            $data['imageUrl'] = $paymentButtonHandler->getButtonFinalUrl($button);

            $buttons[] = $data;
        }

        return $this->buildTemplateResponse('page-button-list', array(
            'buttons' => $buttons
        ));
    }

    /**
     * @return PGServerFoundationsAbstractResponse
     * @throws Exception
     */
    public function displayUpdateFormAction()
    {
        /** @var PGFrameworkServicesHandlersPictureHandler $pictureHandler */
        $pictureHandler = $this->getService('handler.picture');

        /** @var PGIntlServicesManagersTranslationManager $translationManager */
        $translationManager = $this->getService('manager.translation');

        $error = null;
        $button = null;
        $id = (int) $this->getRequest()->get('id');

        if (!$id) {
            $error = "button.actions.delete.errors.id_not_found";
        } else {
            $button = $this->buttonManager->getByPrimary($id);

            if ($button === null) {
                $error = "button.actions.delete.errors.button_not_found";
            }
        }

        if ($button === null) {
            $this->failure($error);
            $response =  $this->redirect($this->getLinker()->buildBackOfficeUrl('backoffice.buttons.display'));
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
                        'image' => $pictureHandler->getUrl($imageSrc),
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
                    'cart_amount_limits' => array(
                        'min' => $button->getMinAmount(),
                        'max' => $button->getMaxAmount()
                    ),
                    'payment_mode' => $button->getPaymentMode(),
                    'payment_number' => $button->getPaymentNumber(),
                    'first_payment_part' => $button->getFirstPaymentPart(),
                    'order_repeated' => $button->isOrderRepeated(),
                    'payment_report' => $button->getPaymentReport()
                );

                $translations = $translationManager->getByCode('button-' . $button->id(), true);
                if (!empty($translations)) {
                    $values['label'] = $translations;
                }

                /** @var PGFormInterfacesFormViewInterface $view */
                $view = $this->buildForm('button_update', $values)->buildView();
            }

            $action = $this->getLinker()->buildBackOfficeUrl('backoffice.buttons.update');

            $view->setAction($action);

            $response = $this->buildTemplateResponse('page-button-update', array(
                'button' => $button->toArray(),
                'errors' => $this->buttonManager->check($button),
                'form' => new PGViewComponentsBox($view)
            ));

            $response->addResource($this->createDefaultButtonPicturesResource());
        }

        return $response;
    }

    /**
     * @return PGServerFoundationsAbstractResponse
     * @throws PGClientExceptionsPaymentRequestException
     * @throws Exception
     */
    public function updateButtonAction()
    {
        /** @var PGFormInterfacesFormInterface $form */
        $form = $this->buildForm('button_update', $this->getRequest()->getAll());

        $result = null;

        if ($form->isValid()) {
            $button = $this->buttonManager->getByPrimary($form->getValue('id'));

            if ($button === null) {
                $this->failure("button.actions.update.errors.button_not_found");
                $result = $this->redirect($this->getLinker()->buildBackOfficeUrl('backoffice.buttons.display'));
            } elseif ($this->saveButton($button, $form)) {
                $this->success('button.actions.update.result.success');
                $result = $this->redirect($this->getLinker()->buildBackOfficeUrl('backoffice.buttons.display'));
            } else {
                $this->failure('button.actions.update.result.failure');
            }
        } else {
            $this->failure('button.actions.update.result.invalid');
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

        $action = $this->getLinker()->buildBackOfficeUrl('backoffice.buttons.insert');

        $view->setAction($action);

        $response = $this->buildTemplateResponse('page-button-insert', array(
            'form' => new PGViewComponentsBox($view)
        ));

        $response->addResource($this->createDefaultButtonPicturesResource());

        return $response;
    }

    /**
     * @return PGServerFoundationsAbstractResponse
     * @throws PGClientExceptionsPaymentRequestException
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
                $this->success('button.actions.insert.result.success');
                $result = $this->redirect($this->getLinker()->buildBackOfficeUrl('backoffice.buttons.display'));
            } else {
                $this->failure('button.actions.insert.result.failure');
            }
        } else {
            $this->failure('button.actions.insert.result.invalid');
        }

        if ($result === null) {
            $result = $this->forward('displayInsertForm@backoffice.buttons', array(
                'form' => $form
            ));
        }

        return $result;
    }

    /**
     * @param PGDomainInterfacesEntitiesButtonInterface $button
     * @param PGFormInterfacesFormInterface $form
     * @return bool
     * @throws PGClientExceptionsPaymentRequestException
     * @throws Exception
     */
    protected function saveButton(PGDomainInterfacesEntitiesButtonInterface $button, PGFormInterfacesFormInterface $form)
    {
        /** @var PGFrameworkServicesHandlersPictureHandler $mediaHandler */
        $mediaHandler = $this->getService('handler.picture');

        /** @var PGIntlServicesManagersTranslationManager $translationManager */
        $translationManager = $this->getService('manager.translation');

        /** @var PGFrameworkServicesHandlersUploadHandler $uploadHandler */
        $uploadHandler = $this->getService('handler.upload');

        $success = false;

        $picture = $form->getValue('picture');
        $uploadedFile = $uploadHandler->getFile('picture.image');

        if ($picture['reset']) {
            $button->setImageSrc(null);
        } elseif (($uploadedFile !== null) && ($uploadedFile instanceof PGFrameworkComponentsUploadedFile)) {
            if (!$uploadedFile->hasError()) {
                $picture = $mediaHandler->store($uploadedFile->getTemporaryName(), $uploadedFile->getRealName());

                $button->setImageSrc($picture->getFilename());

                $this->success("button.form.result.success.picture");
            } elseif ($uploadedFile->getError() !== 4) {
                $this->failure("button.form.errors.upload_picture_error");
            }
        }

        $cart_amount_limits = $form->getValue('cart_amount_limits');

        $button
            ->setMinAmount($cart_amount_limits['min'])
            ->setMaxAmount($cart_amount_limits['max'])
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
            $translationManager->saveByCode($code, $value, null, true);
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
            $this->failure("button.actions.delete.errors.id_not_found");
        } else {
            $button = $this->buttonManager->getByPrimary($id);

            if ($button === null) {
                $this->failure("button.actions.delete.errors.button_not_found");
            } elseif ($this->buttonManager->delete($button)) {
                $this->success("button.actions.delete.result.success");
            } else {
                $this->failure("button.actions.delete.result.failure");
            }
        }

        return $this->redirect($this->getLinker()->buildBackOfficeUrl('backoffice.buttons.display'));
    }

    /**
     * @return PGServerComponentsResourcesDataResource
     */
    protected function createDefaultButtonPicturesResource()
    {
        /** @var PGFrameworkComponentsBag $parameters */
        $parameters = $this->getService('parameters');

        /** @var PGFrameworkServicesHandlersStaticFileHandler $staticFileHandler */
        $staticFileHandler = $this->getService('handler.static_file');

        $defaultButtonPictures = array();

        foreach($parameters['payment.pictures'] as $type => $filename) {
            $defaultButtonPictures[$type] = $staticFileHandler->getUrl("/pictures/PGDomain/payment-buttons/$filename");
        }

        return new PGServerComponentsResourcesDataResource(array(
            'default_button_pictures' => $defaultButtonPictures
        ));
    }
}
