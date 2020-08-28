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
 * @version   1.0.1
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
        /** @var PGFrameworkServicesHandlersPictureHandler $mediaHandler */
        $mediaHandler = $this->getService('handler.picture');

        $buttons = array();

        /**
         * @var int $key
         * @var PGModuleEntitiesButton $button
         */
        foreach ($this->buttonManager->getAll() as $button) {
            $data = $button->toArray();

            $data['errors'] = $this->buttonManager->check($button);
            $data['imageUrl'] = $mediaHandler->getUrl($button->getImageSrc());

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
        /** @var PGFrameworkServicesHandlersPictureHandler $mediaHandler */
        $mediaHandler = $this->getService('handler.picture');

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
                        'image' => $button->getImageSrc(),
                        'reset' => false
                    );
                }

                $values = array(
                    'id' => $button->id(),
                    'label' => $button->getLabel(),
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

                /** @var PGFormInterfacesFormViewInterface $view */
                $view = $this->buildForm('button_update', $values)->buildView();
            }

            $action = $this->getLinker()->buildBackOfficeUrl('backoffice.buttons.update');

            $view->setAction($action);

            $response = $this->buildTemplateResponse('page-button-update', array(
                'button' => $button->toArray(),
                'errors' => $this->buttonManager->check($button),
                'imageUrl' => $mediaHandler->getUrl($button->getImageSrc()),
                'form' => new PGViewComponentsBox($view)
            ));
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

        if (!$form->isValid()) {
            $this->failure('button.actions.update.result.invalid');

            return $this->forward('displayUpdateForm@backoffice.buttons', array(
                'id' => $form->getValue('id'),
                'form' => $form
            ));
        } elseif (!$form->getValue('id')) {
            $this->failure("button.actions.update.errors.id_not_found");
        } else {
            $button = $this->buttonManager->getByPrimary($form->getValue('id'));

            if ($button === null) {
                $this->failure("button.actions.update.errors.button_not_found");
            } elseif (!$this->saveButton($button, $form)) {
                $this->failure("button.actions.update.result.failure");
            } else {
                $this->success("button.actions.update.result.success");
            }
        }

        return $this->redirect($this->getLinker()->buildBackOfficeUrl('backoffice.buttons.display'));
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
            /** @var PGFormInterfacesFormViewInterface $view */
            $view = $this->buildForm('button')->buildView();
        }

        $action = $this->getLinker()->buildBackOfficeUrl('backoffice.buttons.insert');

        $view->setAction($action);

        return $this->buildTemplateResponse('page-button-insert', array(
            'form' => new PGViewComponentsBox($view)
        ));
    }

    /**
     * @return PGServerFoundationsAbstractResponse
     * @throws PGClientExceptionsPaymentRequestException
     * @throws Exception
     * @todo Prendre en compte le retour de la méthode saveButton
     */
    public function insertButtonAction()
    {
        /** @var PGFormInterfacesFormInterface $form */
        $form = $this->buildForm('button', $this->getRequest()->getAll());

        if ($form->isValid()) {
            $button = $this->buttonManager->getNew();

            $this->saveButton($button, $form);
        } else {
            $this->failure('button.actions.insert.result.invalid');

            return $this->forward('displayInsertForm@backoffice.buttons', array(
                'form' => $form
            ));
        }

        return $this->redirect($this->getLinker()->buildBackOfficeUrl('backoffice.buttons.display'));
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

        /** @var PGFrameworkServicesHandlersUploadHandler $uploadHandler */
        $uploadHandler = $this->getService('handler.upload');

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
            ->setLabel($form->getValue('label'))
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

        $errors = $this->buttonManager->check($button);
        foreach ($errors as $error) {
            $this->failure($error);
        }

        if (count($errors) === 0) {
            return $this->buttonManager->save($button);
        }

        return false;
    }

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
}
