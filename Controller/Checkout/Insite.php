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

namespace Paygreen\Payment\Controller\Checkout;

use Exception;
use Magento\Framework\App\ResponseInterface;
use Paygreen;
use Paygreen\Payment\Foundations\AbstractActionFrontCheckout;
use PGDomainInterfacesEntitiesButtonInterface;
use PGDomainServicesManagersButtonManager;
use PGDomainServicesPaygreenFacade;
use PGFrameworkServicesLogger;
use PGDomainServicesManagersPaymentTypeManager;

class Insite extends AbstractActionFrontCheckout
{
    /**
     * @return ResponseInterface
     */
    public function execute()
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        /** @var PGDomainServicesManagersButtonManager $buttonManager */
        $buttonManager = $this->getService('manager.button');

        $logger->info("Route : PGFront/Checkout/Insite");

        try {
            $id_button = (int) $this->getRequest()->getParam('button');

            if (!$id_button) {
                throw new Exception("Button primary not found.");
            }

            /** @var PGDomainInterfacesEntitiesButtonInterface $button */
            $button = $buttonManager->getByPrimary($id_button);

            $url = $this->buildPayment($button);

            $result = $this->displayInsite($button, $url);
        } catch (Exception $exception) {
            $logger->error("Create payment error : " . $exception->getMessage(), $exception);

            $this->messageManager->addErrorMessage('An error occured during payment preparation !');

            $result = $this->_redirect('checkout/onepage/failure');
        }

        return $result;
    }

    /**
     * @param PGDomainInterfacesEntitiesButtonInterface $button
     * @param string $url
     * @throws Exception
     * @return ResponseInterface
     */
    protected function displayInsite(PGDomainInterfacesEntitiesButtonInterface $button, $url)
    {
        /** @var PGDomainServicesPaygreenFacade $paygreenFacade */
        $paygreenFacade = $this->getService('paygreen.facade');

        /** @var PGDomainServicesManagersPaymentTypeManager $paymentTypeManager */
        $paymentTypeManager = $this->getService('manager.payment_type');

        $shopInfo = $paygreenFacade->getAccountInfos();

        $iframeSize = $paymentTypeManager->getSizeIFrameFromPayment(
            isset($shopInfo->solidarityType) ? $shopInfo->solidarityType : null,
            $button->getPaymentType(),
            $button->getPaymentMode()
        );

        $page = $this->getResultPageFactory()->create();

        $page->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0', true);
        $page->setHeader('X-Frame-Options', 'ALLOW', true);

        $block = $page->getLayout()->getBlock('insite');

        pgLog('$iframeSize', $iframeSize);

        $block
            ->setData('url', $url)
            ->setData('minWidthIframe', $iframeSize['minWidth'])
            ->setData('minHeightIframe', $iframeSize['minHeight'])
            ->addData(['cache_lifetime' => null])
            ->setCacheable(false)
        ;

        return $page;
    }
}
