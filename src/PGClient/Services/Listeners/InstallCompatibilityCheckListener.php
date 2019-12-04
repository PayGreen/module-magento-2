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


class PGClientServicesListenersInstallCompatibilityCheckListener
{
    /** @var PGFrameworkServicesLogger */
    private $logger;

    /** @var PGDomainServicesPaygreenFacade */
    private $paygreenFacade;

    public function __construct(PGDomainServicesPaygreenFacade $paygreenFacade, PGFrameworkServicesLogger $logger)
    {
        $this->paygreenFacade = $paygreenFacade;
        $this->logger = $logger;
    }

    public function checkCompatibility()
    {
        if (!$this->paygreenFacade->getApiFacade()->getRequestSender()->checkCompatibility()) {
            $error = "Your server is not able to contact the Paygreen API.";
            $error .= " You must install the CURL extension or allow url fopen.";

            throw new Exception($error);
        }
    }
}