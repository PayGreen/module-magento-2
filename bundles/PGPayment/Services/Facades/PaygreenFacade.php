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
 * @version   2.4.0
 *
 */

namespace PGI\Module\PGPayment\Services\Facades;

use PGI\Module\APIPayment\Components\Response as ResponseComponent;
use PGI\Module\APIPayment\Exceptions\Payment as PaymentException;
use PGI\Module\APIPayment\Services\Facades\ApiFacade;
use PGI\Module\PGClient\Exceptions\Response as ResponseException;
use PGI\Module\PGClient\Exceptions\ResponseFailed as ResponseFailedException;
use PGI\Module\PGFramework\Interfaces\Factories\ApiFactoryInterface;
use PGI\Module\PGFramework\Services\Handlers\CacheHandler;
use PGI\Module\PGFramework\Services\Handlers\HTTPHandler;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGPayment\Entities\PaymentType;
use PGI\Module\PGPayment\Exceptions\PaygreenAccount as PaygreenAccountException;
use PGI\Module\PGPayment\Services\Managers\PaymentTypeManager;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use PGI\Module\PGSystem\Services\Container;
use Exception;

/**
 * Class PaygreenFacade
 * @package PGPayment\Services\Facades
 */
class PaygreenFacade extends AbstractObject
{
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

    /** @var HTTPHandler */
    private $httpHandler;

    /** @var ApiFactoryInterface */
    private $apiFactory;

    /** @var ApiFacade|null */
    private $apiFacade = null;

    /** @var PaymentTypeManager */
    private $paymentTypeManager;

    public function __construct(
        ApiFactoryInterface $apiFactory,
        HTTPHandler $httpHandler,
        PaymentTypeManager $paymentTypeManager
    ) {
        $this->apiFactory = $apiFactory;
        $this->httpHandler = $httpHandler;
        $this->paymentTypeManager = $paymentTypeManager;
    }

    /**
     * @return bool
     */
    public function isConfigured()
    {
        /** @var Settings $settings */
        $settings = $this->getService('settings');

        $public_key = $settings->get('public_key');
        $private_key = $settings->get('private_key');

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
     * @return null|ApiFacade
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
        /** @var CacheHandler $cacheHandler */
        $cacheHandler = Container::getInstance()->get('handler.cache');

        $data = $cacheHandler->loadEntry('status-shop');

        if ($data === null) {
            try {
                $response = $this->getApiFacade()->getStatus('shop');

                if ($response->isSuccess()) {
                    $data = $response->data;

                    $isArray = (isset($data->availableMode) && (is_array($data->availableMode)));

                    $isRecurringAvailable = ($isArray) ? in_array('RECURRING', $data->availableMode) : false;
                    $isXtimeAvailable = ($isArray) ? in_array('XTIME', $data->availableMode) : false;

                    if ($isArray && ($isRecurringAvailable && (!$isXtimeAvailable))) {
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
        /** @var Logger $logger */
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
     * @throws PaymentException
     * @throws ResponseException
     * @throws PaygreenAccountException
     */
    public function getAccountInfos()
    {
        /** @var CacheHandler $cacheHandler */
        $cacheHandler = Container::getInstance()->get('handler.cache');

        $data = $cacheHandler->loadEntry('account-infos');

        if ($data === null) {
            try {
                $response = $this->getApiFacade()->getStatus('account');

                if (empty($response->data)) {
                    throw new PaygreenAccountException(
                        'Account data is empty.',
                        PaygreenAccountException::ACCOUNT_NOT_FOUND
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
                    throw new PaygreenAccountException(
                        'Shop is empty.',
                        PaygreenAccountException::EMPTY_SHOP_DATA
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
            } catch (ResponseFailedException $exception) {
                throw new PaygreenAccountException(
                    "Could not load account data.",
                    PaygreenAccountException::ACCOUNT_NOT_FOUND,
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

                if (is_array($data->availablePaymentModes)) {
                    $availablePaymentModes = $data->availablePaymentModes;
                } else {
                    $availablePaymentModes = array();
                }
            }
        } catch (Exception $exception) {
            /** @var Logger $logger */
            $logger = $this->getService('logger');

            $logger->error(
                "An error occurred during available payment modes agregation: " . $exception->getMessage()
            );
        }

        return $availablePaymentModes;
    }

    /**
     * @return array
     */
    public function getAvailablePaymentTypes()
    {
        $paymentTypes = array();

        try {
            $paymentTypes = $this->paymentTypeManager->getAll();
        } catch (Exception $exception) {
            /** @var Logger $logger */
            $logger = $this->getService('logger');

            $logger->error("An error occurred during available payment types agregation: " . $exception->getMessage());
        }

        return $paymentTypes;
    }
}
