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
 * @version   2.0.1
 *
 */

/**
 * Class PGTreeCommonServicesControllersCustomerNavigation
 * @package PGTreeCommon\Services\Controllers
 */
class PGTreeCommonServicesControllersCustomerNavigation extends PGServerFoundationsAbstractController
{
    /** @var PGTreeCommonServicesHandlersFingerPrint */
    private $fingerPrintHandler;

    public function __construct(PGTreeCommonServicesHandlersFingerPrint $fingerPrintHandler)
    {
        $this->fingerPrintHandler = $fingerPrintHandler;
    }

    /**
     * @return PGServerComponentsResponsesPaygreenModuleResponse
     * @throws Exception
     */
    public function saveNavigationDataAction()
    {
        /** @var PGModuleServicesLogger $logger */
        $logger = $this->getLogger();

        /** @var PGServerComponentsResponsesPaygreenModuleResponse $response */
        $response = new PGServerComponentsResponsesPaygreenModuleResponse($this->getRequest());

        try {
            $this->fingerPrintHandler->insertFingerprintData($this->getRequest()->getAll());
            $response->validate();
        } catch (Exception $exception) {
            $logger->error("Tree computing error : " . $exception->getMessage(), $exception);
        }

        return $response;
    }
}
