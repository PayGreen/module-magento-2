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
 */

namespace Paygreen\Payment\Controller\Adminhtml\Buttons;

use Exception;
use Paygreen\Payment\Foundations\AbstractActionAdmin;
use PGDomainServicesManagersButtonManager;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\RequestInterface;

class Delete extends AbstractActionAdmin
{
    public function execute()
    {
        /** @var RequestInterface $request */
        $request = $this->getRequest();

        /** @var \PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        /** @var PGDomainServicesManagersButtonManager $buttonManager */
        $buttonManager = $this->getService('manager.button');

        $id = (int) $request->getParam('id', false);

        if ($id) {
            $button = $buttonManager->getByPrimary($id);

            if ($button === null) {
                throw new Exception("Button not found : #$id.");
            }
        } else {
            throw new Exception("Button primary not found.");
        }

        $logger->notice("Deleting button #$id.");

        if ($buttonManager->delete($button)) {
            $this->addSuccessMessage('button.actions.delete.result.success');
        } else {
            $this->addErrorMessage('button.actions.delete.result.failure');
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*/index');

        return $resultRedirect;
    }
}
