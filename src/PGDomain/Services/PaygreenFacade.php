<?php
/**
 * 2014 - 2020 Watt Is It
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
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.1.0
 */

/**
 * Class PGDomainServicesPaygreenFacade
 * @package PGDomain\Services
 */
class PGDomainServicesPaygreenFacade extends PGFrameworkFoundationsAbstractObject
{
    const VERSION = '2.7.0';
    const CURRENCY_EUR = 'EUR';

    const STATUS_WAITING = 'WAITING';
    const STATUS_PENDING = 'PENDING';
    const STATUS_EXPIRED = 'EXPIRED';
    const STATUS_PENDING_EXEC = 'PENDING_EXEC';
    const STATUS_WAITING_EXEC = 'WAITING_EXEC';
    const STATUS_CANCELLING = 'CANCELLED';
    const STATUS_REFUSED = 'REFUSED';
    const STATUS_SUCCESSED = 'SUCCESSED';
    const STATUS_RESETED = 'RESETED';
    const STATUS_REFUNDED = 'REFUNDED';
    const STATUS_FAILED = 'FAILED';

    /** @var PGFrameworkServicesHandlersHTTPHandler */
    private $httpHandler;

    /** @var PGFrameworkInterfacesApiFactoryInterface */
    private $apiFactory;

    /** @var PGClientServicesApiFacade|null */
    private $apiFacade = null;

    public function __construct(
        PGFrameworkInterfacesApiFactoryInterface $apiFactory,
        PGFrameworkServicesHandlersHTTPHandler $httpHandler
    ) {
        $this->apiFactory = $apiFactory;
        $this->httpHandler = $httpHandler;
    }

    /**
     * @return bool
     */
    public function isConfigured()
    {
        /** @var PGFrameworkInterfacesModuleFacadeInterface $moduleFacade */
        $moduleFacade = $this->getService('facade.module');

        list($public_key, $private_key) = $moduleFacade->getAPICredentials();

        return (!empty($public_key) && !empty($private_key));
    }

    /**
     * @return bool
     */
    public function isConnected()
    {
        return ($this->isConfigured() && ($this->getStatusShop() !== null));
    }

    /**
     * @return null|PGClientServicesApiFacade
     */
    public function getApiFacade()
    {
        if ($this->apiFacade === null) {
            $this->apiFacade = $this->apiFactory->buildApiFacade();
        }

        return $this->apiFacade;
    }

    public function resetApiFacade()
    {
        $this->apiFacade = null;
    }

    /**
     * @return array|null
     * @throws Exception
     */
    public function getStatusShop()
    {
        /** @var PGFrameworkServicesHandlersCacheHandler $cacheHandler */
        $cacheHandler = PGFrameworkContainer::getInstance()->get('handler.cache');

        $data = $cacheHandler->loadEntry('status-shop');

        if ($data === null) {
            try {
                $response = $this->getApiFacade()->getStatus('shop');

                if ($response->isSuccess()) {
                    $data = $response->data;

                    if (
                        isset($data->availableMode) &&
                        is_array($data->availableMode) &&
                        in_array('RECURRING', $data->availableMode) &&
                        !in_array('XTIME', $data->availableMode)
                    ) {
                        $data->availableMode[] = 'XTIME';
                    }
                } else {
                    $data = null;
                }
                /**
                 * @todo remove specific code after API correction
                 * $data = $response->isSuccess() ? $response->data : null;
                 */

                $cacheHandler->saveEntry('status-shop', $data);
            } catch (Exception $exception) {
                $this->getService('logger')->alert("Unable to retrieve shop status.", $exception);
            }
        }

        return $data;
    }

    /**
     * @param string $name
     * @return bool
     * @throws Exception
     */
    public function hasModule($name)
    {
        $statusShop = $this->getStatusShop();
        $result = false;

        if ($statusShop !== null) {
            foreach ($statusShop->modules as $module) {
                $hasSameName = (strtolower($module->name) === strtolower($name));

                if ($hasSameName && $module->active && $module->enable) {
                    $result = true;
                    break;
                }
            }
        }

        return $result;
    }

    public function isValidInsite()
    {
        return ($this->httpHandler->isSecureConnection() && $this->isValidInsiteModule());
    }

    public function isValidInsiteModule()
    {
        return $this->hasModule('insite');
    }

    public function verifyInsiteValidity()
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        $isHttps = $this->httpHandler->isSecureConnection();
        $isInsiteShop = $this->isValidInsiteModule();

        if (!$isHttps) {
            $logger->warning("Insite mode is only available with HTTPS connexion.");
        }

        if (!$isInsiteShop) {
            $logger->warning("Insite module is not activated.");
        }

        return ($isHttps && $isInsiteShop);
    }

    /**
     * Get account infos
     *
     * @return object
     * @throws Exception
     * @throws PGClientExceptionsPaymentException
     * @throws PGClientExceptionsPaymentRequestException
     * @throws PGDomainExceptionsPaygreenAccountException
     */
    public function getAccountInfos()
    {
        /** @var PGFrameworkServicesHandlersCacheHandler $cacheHandler */
        $cacheHandler = PGFrameworkContainer::getInstance()->get('handler.cache');

        $data = $cacheHandler->loadEntry('account-infos');

        if ($data === null) {
            try {
                $response = $this->getApiFacade()->getStatus('account');

                if (empty($response->data)) {
                    throw new PGDomainExceptionsPaygreenAccountException(
                        'Account data is empty.',
                        PGDomainExceptionsPaygreenAccountException::ACCOUNT_NOT_FOUND
                    );
                }

                $data['siret'] = $response->data->siret;

                $response = $this->getApiFacade()->getStatus('bank');

                $data['IBAN'] = null;

                if (!empty($response->data)) {
                    foreach ($response->data as $rib) {
                        if ($rib->isDefault == "1") {
                            $data['IBAN'] = $rib->iban;
                        }
                    }
                }

                $dataShop = $this->getStatusShop();

                if ($dataShop === null) {
                    throw new PGDomainExceptionsPaygreenAccountException(
                        'Shop is empty.',
                        PGDomainExceptionsPaygreenAccountException::EMPTY_SHOP_DATA
                    );
                }

                $data['url'] = $dataShop->url;
                $data['modules'] = $dataShop->modules;
                $data['activate'] = $dataShop->activate;
                $data['availablePaymentModes'] = $dataShop->availableMode;
                $data['solidarityType'] = $dataShop->extra->solidarityType;

                if (isset($dataShop->businessIdentifier)) {
                    $data['siret'] = $dataShop->businessIdentifier;
                }

                $data['valide'] = true;

                if (empty($data['url']) && empty($data['siret']) && empty($data['IBAN'])) {
                    $data['valide'] = false;
                }

                $data = json_decode(json_encode($data));

                $cacheHandler->saveEntry('account-infos', $data);
            } catch (PGClientExceptionsFailedResponseException $exception) {
                throw new PGDomainExceptionsPaygreenAccountException(
                    "Could not load account data.",
                    PGDomainExceptionsPaygreenAccountException::ACCOUNT_NOT_FOUND,
                    $exception
                );
            }
        }

        return $data;
    }

    public function getAvailablePaymentModes()
    {
        $availablePaymentModes = array();

        try {
            $data = $this->getAccountInfos();

            if (!empty($data)) {
                if (!is_array($data->availablePaymentModes)) {
                    throw new Exception("Payment modes must be an array.");
                }

                $availablePaymentModes = is_array($data->availablePaymentModes) ? $data->availablePaymentModes : array();
            }
        } catch (Exception $exception) {
            /** @var PGFrameworkServicesLogger $logger */
            $logger = $this->getService('logger');

            $logger->error("An error occurred during available payment modes agregation: " . $exception->getMessage());
        }

        return $availablePaymentModes;
    }
}
