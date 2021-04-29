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
 * Class PGTreeCommonServicesHandlersFingerPrint
 * @package PGTreeCommon\Services\Handlers
 */
class PGTreeCommonServicesHandlersFingerPrint extends PGSystemFoundationsObject
{
    const FINGERPRINT_COOKIE_NAME = 'pgFingerPrintSession';

    public static $REQUIRED_CLIENT_DATA = array('client', 'startAt', 'useTime', 'nbImage', 'device', 'browser');

    /** @var PGModuleServicesLogger */
    private $logger;

    /** @var PGTreeCommonServicesManagersFingerPrint */
    private $fingerPrintManager;

    /** @var PGFrameworkServicesHandlersCookieHandler */
    private $cookieHandler;

    /**
     * PGTreeCommonServicesHandlersFingerPrint constructor.
     * @param PGTreeCommonServicesManagersFingerPrint $fingerPrintManager
     * @param PGFrameworkServicesHandlersCookieHandler $cookieHandler
     * @param PGModuleServicesLogger $logger
     */
    public function __construct(
        PGTreeCommonServicesManagersFingerPrint $fingerPrintManager,
        PGFrameworkServicesHandlersCookieHandler $cookieHandler,
        PGModuleServicesLogger $logger
    ) {
        $this->fingerPrintManager = $fingerPrintManager;
        $this->cookieHandler = $cookieHandler;
        $this->logger = $logger;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function isValidClientData(array $data)
    {
        foreach (self::$REQUIRED_CLIENT_DATA as $key) {
            if (!array_key_exists($key, $data) || empty($data[$key])) {
                $this->logger->error("Unable to save fingerprint. Client data not found : '$key'.");
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function insertFingerprintData(array $data)
    {
        if (!$this->isValidClientData($data)) {
            throw new Exception("Invalid client data.");
        }

        $this->cookieHandler->set(self::FINGERPRINT_COOKIE_NAME, $data['client']);

        $this->logger->debug("Saving client data for finger print computing.");

        return $this->fingerPrintManager->saveNavigationData(
            $data['client'],
            $data['browser'],
            $data['device'],
            $data['nbImage'],
            $data['useTime']
        );
    }

    /**
     * @param PGShopInterfacesProvisionersPrePayment $prePaymentProvisioner
     * @return array
     */
    public function generateFingerprintDatas(
        PGShopInterfacesShopable $shopable,
        PGShopInterfacesEntitiesCustomer $customer,
        PGShopInterfacesEntitiesCarrier $carrier
    ) {
        $session = $this->cookieHandler->get(self::FINGERPRINT_COOKIE_NAME);

        if (empty($session)) {
            $this->getService('logger')->error("Empty fingerprint cookie.");
            return null;
        }

        /** @var PGTreeCommonEntitiesFingerPrint|null $storedFingerPrint */
        $storedFingerPrint = $this->fingerPrintManager->getBySession($session);

        if ($storedFingerPrint === null) {
            $this->getService('logger')->error("Empty fingerprint data.");
            return null;
        }

        $aggregatedFingerPrint = array();
        
        $aggregatedFingerPrint['deviceType'] = $storedFingerPrint->getDevice();
        $aggregatedFingerPrint['browser'] = $storedFingerPrint->getBrowser();
        $aggregatedFingerPrint['nbPage'] = (int) $storedFingerPrint->getPages();
        $aggregatedFingerPrint['useTime'] = 30;
        $aggregatedFingerPrint['nbImage'] = (int) $storedFingerPrint->getPictures();
        $aggregatedFingerPrint['fingerprint'] = (int) $session;
        $aggregatedFingerPrint['carrier'] = (string) $carrier->getName();
        $aggregatedFingerPrint['weight'] = (float) $shopable->getShippingWeight();
        $aggregatedFingerPrint['nbPackage'] = (int) 1;
        $aggregatedFingerPrint['clientAddress'] = array(
            'address' => $customer->getShippingAddress()->getFullAddressLine(),
            'zipcode' => $customer->getShippingAddress()->getZipCode(),
            'city' =>$customer->getShippingAddress()->getCity(),
            'country' => $customer->getShippingAddress()->getCountry()
        );

        $this->fingerPrintManager->delete($storedFingerPrint);

        foreach ($aggregatedFingerPrint as $key => $value) {
            if (empty($value)) {
                $this->getService('logger')->error(
                    "Empty value in fingerprint data : '$key'.",
                    $aggregatedFingerPrint
                );
                return null;
            }
        }

        return $aggregatedFingerPrint;
    }
}
