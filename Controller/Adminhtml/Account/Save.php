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
 * @version   0.3.3
 */

namespace Paygreen\Payment\Controller\Adminhtml\Account;

use Exception;
use PGFrameworkServicesSettings;
use Paygreen\Payment\Foundations\AbstractActionAdmin;
use Magento\Framework\Controller\ResultFactory;

class Save extends AbstractActionAdmin
{
    public function execute()
    {
        $post = (array) $this->getRequest()->getPost();

        /** @var \PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        /** @var \PGFrameworkServicesHandlersCacheHandler $cacheHandler */
        $cacheHandler = $this->getService('handler.cache');

        try {
            /** @var PGFrameworkServicesSettings $settings */
            $settings = $this->getService('settings');

            foreach (['public_key', 'private_key'] as $name) {
                $value = isset($post[$name]) ? $post[$name] : '';

                $logger->debug("Setting '$name' parameter with value :", $value);

                $settings->set($name, $value);
            }

            $this->addSuccessMessage('backoffice.actions.credentials.save.result.success');
        } catch (Exception $exception) {
            $logger->alert("Error during saving credentials : " . $exception->getMessage());

            $this->addErrorMessage('backoffice.actions.credentials.save.result.failure');
        }

        $cacheHandler->clearCache();

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*/index');

        return $resultRedirect;
    }
}
