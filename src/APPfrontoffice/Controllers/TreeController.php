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
 * @version   1.2.3
 *
 */

class APPfrontofficeControllersTreeController extends PGServerFoundationsAbstractController
{
    /**
     * @return PGServerComponentsResponsesArrayResponse
     * @throws Exception
     */
    public function saveNavigationDataAction()
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        /** @var PGDomainServicesHandlersFingerprintHandler $fingerPrintHandler */
        $fingerPrintHandler = $this->getService('handler.fingerprint');

        /** @var PGServerComponentsResponsesPaygreenModuleResponse $response */
        $response = new PGServerComponentsResponsesPaygreenModuleResponse($this->getRequest());

        try {
            $fingerPrintHandler->insertFingerprintData($this->getRequest()->getAll());

            $response->validate();
        } catch (Exception $exception) {
            $logger->error("Tree computing error : " . $exception->getMessage(), $exception);
        }

        return $response;
    }
}
