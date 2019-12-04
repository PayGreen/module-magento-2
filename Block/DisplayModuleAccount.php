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

namespace Paygreen\Payment\Block;

use DateTime;
use Exception;
use Paygreen\Payment\Foundations\AbstractTemplate;
use PGDomainServicesPaygreenFacade;
use PGFrameworkServicesSettings;

class DisplayModuleAccount extends AbstractTemplate
{
    public function isValidShop()
    {
        return ($this->getService('paygreen.facade')->getStatusShop() !== null);
    }

    public function getAccountInfos()
    {
        /** @var PGDomainServicesPaygreenFacade $paygreenFacade */
        $paygreenFacade = $this->getService('paygreen.facade');

        try {
            $accountInfos = $paygreenFacade->getAccountInfos();
        } catch (Exception $exception) {
            $this->getService('logger')->error("Unable to retrieve account infos : " . $exception->getMessage());
            $accountInfos = null;
        }

        return $accountInfos;
    }

    public function isActivated()
    {
        /** @var PGDomainServicesPaygreenFacade $paygreenFacade */
        $paygreenFacade = $this->getService('paygreen.facade');

        $statusShop = $paygreenFacade->getStatusShop();

        return $statusShop ? $statusShop->activate : false;
    }

    public function getValidatedAt()
    {
        /** @var PGDomainServicesPaygreenFacade $paygreenFacade */
        $paygreenFacade = $this->getService('paygreen.facade');

        $statusShop = $paygreenFacade->getStatusShop();

        $validatedAt = $statusShop ? (int) $statusShop->validatedAt : null;

        return $validatedAt ? new DateTime($validatedAt) : null;
    }

    public function getParameters()
    {
        /** @var PGFrameworkServicesSettings $settings */
        $settings = $this->getService('settings');

        return [
            'public_key' => $settings->get('public_key'),
            'private_key' => $settings->get('private_key')
        ];
    }
}
