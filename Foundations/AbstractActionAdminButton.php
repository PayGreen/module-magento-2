<?php
/**
 * 2014 - 2019 Watt Is It
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
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.2
 */

namespace Paygreen\Payment\Foundations;

use PGDomainInterfacesEntitiesButtonInterface;
use PGDomainServicesManagersButtonManager;
use PGFrameworkServicesHandlersPictureHandler;
use Magento\Framework\App\RequestInterface;

abstract class AbstractActionAdminButton extends AbstractActionAdmin
{
    protected function settingButtonData(PGDomainInterfacesEntitiesButtonInterface $button, RequestInterface $request)
    {
        /** @var PGDomainServicesManagersButtonManager $buttonManager */
        $buttonManager = $this->getService('manager.button');

        if ((bool) $request->getParam('defaultimg', false)) {
            $button->setImageSrc(null);
        } elseif (array_key_exists('image', $_FILES) && is_array($_FILES['image'])) {
            if ($_FILES['image']['error'] === 0) {
                /** @var PGFrameworkServicesHandlersPictureHandler $mediaHandler */
                $mediaHandler = $this->getService('handler.picture');

                $picture = $mediaHandler->store($_FILES['image']['tmp_name'], $_FILES['image']['name']);

                $button->setImageSrc($picture->getFilename());

                $this->addSuccessMessage('button.form.result.success.picture');
            } elseif ($_FILES['image']['error'] !== 4) {
                $this->addErrorMessage('button.form.errors.upload_picture_error');
            }
        }

        $button
            ->setLabel($request->getParam('label'))
            ->setImageHeight($request->getParam('height', 60))
            ->setMinAmount($request->getParam('minAmount', 0))
            ->setMaxAmount($request->getParam('maxAmount', 0))
            ->setIntegration($request->getParam('integration', 'EXTERNAL'))
            ->setDisplayType($request->getParam('displayType', 'DEFAULT'))
            ->setPosition($request->getParam('position', 0))
            ->setPaymentMode($request->getParam('paymentMode'))
            ->setPaymentType($request->getParam('paymentType'))
            ->setPaymentNumber($request->getParam('paymentNumber', 1))
            ->setFirstPaymentPart($request->getParam('firstPaymentPart', 0))
            ->setOrderRepeated($request->getParam('orderRepeated', false))
            ->setPaymentReport($request->getParam('paymentReport', 0))
            ->setDiscount($request->getParam('discount', 0))
        ;

        if (!$button->getPosition()) {
            $button->setPosition($buttonManager->count() + 1);
        }
    }
}
