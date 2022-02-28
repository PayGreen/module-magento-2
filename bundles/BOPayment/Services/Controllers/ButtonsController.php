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
 * @version   2.6.0
 *
 */

namespace PGI\Module\BOPayment\Services\Controllers;

use PGI\Module\BOModule\Foundations\Controllers\AbstractBackofficeController;
use PGI\Module\PGClient\Exceptions\Response as ResponseException;
use PGI\Module\PGForm\Interfaces\FormInterface;
use PGI\Module\PGForm\Interfaces\Views\FormViewInterface;
use PGI\Module\PGFramework\Components\UploadedFile as UploadedFileComponent;
use PGI\Module\PGFramework\Services\Handlers\PictureHandler;
use PGI\Module\PGFramework\Services\Handlers\UploadHandler;
use PGI\Module\PGIntl\Services\Managers\TranslationManager;
use PGI\Module\PGModule\Services\Handlers\StaticFileHandler;
use PGI\Module\PGPayment\Interfaces\Entities\ButtonEntityInterface;
use PGI\Module\PGPayment\Services\Handlers\PaymentButtonHandler;
use PGI\Module\PGPayment\Services\Managers\ButtonManager;
use PGI\Module\PGPayment\Services\Managers\PaymentTypeManager;
use PGI\Module\PGServer\Components\Resources\Data as DataResourceComponent;
use PGI\Module\PGServer\Components\Resources\ScriptFile as ScriptFileResourceComponent;
use PGI\Module\PGServer\Components\Responses\Redirection as RedirectionResponseComponent;
use PGI\Module\PGServer\Components\Responses\Template as TemplateResponseComponent;
use PGI\Module\PGServer\Foundations\AbstractResponse;
use PGI\Module\PGServer\Services\Handlers\LinkHandler;
use PGI\Module\PGSystem\Components\Bag as BagComponent;
use PGI\Module\PGView\Components\Box as BoxComponent;
use PGI\Module\PGServer\Components\Responses\PaygreenModule as PaygreenModuleResponseComponent;
use Exception;

/**
 * Class ButtonsController
 * @package BOPayment\Services\Controllers
 */
class ButtonsController extends AbstractBackofficeController
{
    /** @var ButtonManager */
    private $buttonManager;

    /** @var PaymentButtonHandler */
    private $paymentButtonHandler;

    /** @var PictureHandler */
    private $pictureHandler;

    /** @var TranslationManager */
    private $translationManager;

    /** @var UploadHandler */
    private $uploadHandler;

    /** @var StaticFileHandler */
    private $staticFileHandler;

    /** @var LinkHandler */
    private $linkHandler;

    /** @var PaymentTypeManager $paymentTypeManager */
    private $paymentTypeManager;

    public function __construct(
        ButtonManager $buttonManager,
        PaymentButtonHandler $paymentButtonHandler,
        PictureHandler $pictureHandler,
        TranslationManager $translationManager,
        UploadHandler $uploadHandler,
        StaticFileHandler $staticFileHandler,
        LinkHandler $linkHandler,
        PaymentTypeManager $paymentTypeManager
    ) {
        $this->buttonManager = $buttonManager;
        $this->paymentButtonHandler = $paymentButtonHandler;
        $this->pictureHandler = $pictureHandler;
        $this->translationManager = $translationManager;
        $this->uploadHandler = $uploadHandler;
        $this->staticFileHandler = $staticFileHandler;
        $this->linkHandler = $linkHandler;
        $this->paymentTypeManager = $paymentTypeManager;
    }

    /**
     * @return TemplateResponseComponent
     * @throws Exception
     */
    public function displayListAction()
    {
        $buttons = array();

        /**
         * @var int $key
         * @var ButtonEntityInterface $button
         */
        foreach ($this->buttonManager->getAll() as $button) {
            $data = $button->toArray();

            $data['errors'] = $this->buttonManager->check($button);
            $data['imageUrl'] = $this->paymentButtonHandler->getButtonFinalUrl($button);

            $buttons[$button->getPosition()] = $data;
        }

        ksort($buttons);

        $response = $this->buildTemplateResponse('button/buttons-list', array(
            'buttons' => $buttons,
        ));

        $response->addResource(new DataResourceComponent(array(
            'paygreen_update_buttons_position_url' => $this->linkHandler->buildBackOfficeUrl(
                'backoffice.buttons.update_position'
            )
        )));

        $response->addResource(new ScriptFileResourceComponent('/js/page-buttons-dragndrop.js'));

        return $response;
    }

    /**
     * @return AbstractResponse
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
                /** @var FormViewInterface $view */
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

                /** @var FormViewInterface $view */
                $view = $this->buildForm('button_update', $values)->buildView();
                $view->setData(array('payment_codes' => $this->paymentTypeManager->getCodes()));
            }

            $action = $this->getLinkHandler()->buildBackOfficeUrl('backoffice.buttons.update');

            $view->setAction($action);

            $response = $this->buildTemplateResponse('page-button-update', array(
                'button' => $button->toArray(),
                'form' => new BoxComponent($view)
            ));

            $response->addResource($this->createDefaultButtonPicturesResource());
            $response->addResource(new ScriptFileResourceComponent('/js/page-buttons.js'));
        }

        return $response;
    }

    /**
     * @return AbstractResponse
     * @throws ResponseException
     * @throws Exception
     */
    public function updateButtonAction()
    {
        /** @var FormInterface $form */
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
     * @return AbstractResponse
     * @throws Exception
     */
    public function displayInsertFormAction()
    {
        if ($this->getRequest()->has('form')) {
            /** @var FormViewInterface $view */
            $view = $this->getRequest()->get('form')->buildView();
        } else {
            $defaultValues = array(
                'picture' => array(
                    'image' => '',
                    'reset' => true
                )
            );

            /** @var FormViewInterface $view */
            $view = $this->buildForm('button', $defaultValues)->buildView();
            $view->setData(array('payment_codes' => $this->paymentTypeManager->getCodes()));
        }

        $action = $this->getLinkHandler()->buildBackOfficeUrl('backoffice.buttons.insert');

        $view->setAction($action);

        $response = $this->buildTemplateResponse('page-button-insert', array(
            'form' => new BoxComponent($view)
        ));

        $response->addResource($this->createDefaultButtonPicturesResource());
        $response->addResource(new ScriptFileResourceComponent('/js/page-buttons.js'));

        return $response;
    }

    /**
     * @return AbstractResponse
     * @throws ResponseException
     * @throws Exception
     */
    public function insertButtonAction()
    {
        /** @var FormInterface $form */
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
     * @return AbstractResponse
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
                /** @var FormViewInterface $view */
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

            $scriptResource = new ScriptFileResourceComponent('/js/page-buttons-filters.js');

            $response = $this->buildTemplateResponse('page-button-filters', array(
                'button' => $button->toArray(),
                'form' => new BoxComponent($view)
            ));

            $response->addResource($scriptResource);
        }

        return $response;
    }

    /**
     * @return AbstractResponse
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
     * @param ButtonEntityInterface $button
     * @param FormInterface $form
     * @return bool
     * @throws ResponseException
     * @throws Exception
     */
    protected function saveButtonFilter(
        ButtonEntityInterface $button,
        FormInterface $form
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
     * @param ButtonEntityInterface $button
     * @param FormInterface $form
     * @return bool
     * @throws ResponseException
     * @throws Exception
     */
    protected function saveButton(
        ButtonEntityInterface $button,
        FormInterface $form
    ) {
        $success = false;

        $picture = $form->getValue('picture');
        $uploadedFile = $this->uploadHandler->getFile('picture.image');

        if ($picture['reset']) {
            $button->setImageSrc(null);
        } elseif (($uploadedFile !== null) && ($uploadedFile instanceof UploadedFileComponent)) {
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
            ->setDisplayType($form->getValue('display_type'))
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
     * @return RedirectionResponseComponent
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
     * @return DataResourceComponent
     */
    protected function createDefaultButtonPicturesResource()
    {
        /** @var BagComponent $parameters */
        $parameters = $this->getParameters();

        $defaultButtonPictures = array();

        foreach ($parameters['payment.pictures'] as $type => $filename) {
            $defaultButtonPictures[$type] = $this->staticFileHandler->getUrl(
                "/pictures/PGPayment/payment-buttons/$filename"
            );
        }

        return new DataResourceComponent(array(
            'default_button_pictures' => $defaultButtonPictures
        ));
    }

    /**
     * @return AbstractResponse
     * @throws ResponseException
     * @throws Exception
     */
    public function updateButtonsPositionAction()
    {
        $i = 1;
        foreach ($this->getRequest()->getAll() as $buttonPosition => $buttonId) {
            if ($buttonPosition === $i) {
                $button = $this->buttonManager->getByPrimary($buttonId);
                $button->setPosition($buttonPosition);
                $this->buttonManager->save($button);
            }

            $i++;
        }

        $response = new PaygreenModuleResponseComponent($this->getRequest());

        return $response->validate();
    }
}
