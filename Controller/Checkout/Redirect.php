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

namespace Paygreen\Payment\Controller\Checkout;

use Exception;
use Magento\Framework\Controller\ResultFactory;
use Paygreen;
use Paygreen\Payment\Foundations\AbstractActionFrontCheckout;
use Magento\Framework\View\Result\Page;
use PGDomainInterfacesEntitiesButtonInterface;
use PGDomainServicesManagersButtonManager;
use PGFrameworkServicesLogger;

class Redirect extends AbstractActionFrontCheckout
{
    /**
     * @return Page
     */
    public function execute()
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        /** @var PGDomainServicesManagersButtonManager $buttonManager */
        $buttonManager = $this->getService('manager.button');

        $logger->info("Route : PGFront/Checkout/Redirect");

        try {
            $id_button = (int) $this->getRequest()->getParam('button');

            if (!$id_button) {
                throw new Exception("Button primary not found.");
            }

            /** @var PGDomainInterfacesEntitiesButtonInterface $button */
            $button = $buttonManager->getByPrimary($id_button);

            $url = $this->buildPayment($button);

            $page = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $page->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0', true);
            $page->setPath($url);
        } catch (Exception $exception) {
            $logger->error("Create payment error : " . $exception->getMessage(), $exception);

            $page = $this->displayError($exception);
        }

        return $page;
    }
}
