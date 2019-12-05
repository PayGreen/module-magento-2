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
 * @version   0.3.4
 */

namespace Paygreen\Payment\Controller\Adminhtml\Eligibleamount;

use PGFrameworkServicesLogger;
use PGFrameworkServicesHandlersCacheHandler;
use PGDomainServicesManagersCategoryHasPaymentTypeManager;
use Paygreen\Payment\Foundations\AbstractActionAdmin;
use Magento\Framework\Controller\ResultFactory;

class Save extends AbstractActionAdmin
{
    public function execute()
    {
        $post = (array) $this->getRequest()->getPost();

        /** @var PGFrameworkServicesHandlersCacheHandler $cacheHandler */
        $cacheHandler = $this->getService('handler.cache');

        /** @var PGDomainServicesManagersCategoryHasPaymentTypeManager $categoryPaymentManager */
        $categoryPaymentManager = $this->getService('manager.category_has_payment_type');

        $data = isset($post['paygreen_category_payments']) ? $post['paygreen_category_payments'] : [];

        $categoryPaymentManager->saveCategoryPayments($data);

        $this->addSuccessMessage('config.result.success');

        $cacheHandler->clearCache();

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*/index');

        return $resultRedirect;
    }
}
