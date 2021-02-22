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
 * @version   1.2.4
 *
 */

/**
 * Class PGDomainServicesHandlersFingerprintHandler
 * @package PGFramework\Services\Handlers
 */
class PGDomainServicesHandlersFingerprintHandler extends PGFrameworkFoundationsAbstractObject
{
    const FINGERPRINT_COOKIE_NAME = 'pgFingerPrintSession';

    public static $REQUIRED_CLIENT_DATA = array('client', 'startAt', 'useTime', 'nbImage', 'device', 'browser');

    /** @var PGFrameworkServicesLogger */
    private $logger;

    /** @var PGDomainServicesManagersFingerPrintManager */
    private $fingerPrintManager;

    /** @var PGFrameworkServicesHandlersCookieHandler */
    private $cookieHandler;

    /**
     * PGDomainServicesHandlersFingerprintHandler constructor.
     * @param PGDomainServicesManagersFingerPrintManager $fingerPrintManager
     * @param PGFrameworkServicesHandlersCookieHandler $cookieHandler
     * @param PGFrameworkServicesLogger $logger
     */
    public function __construct(
        PGDomainServicesManagersFingerPrintManager $fingerPrintManager,
        PGFrameworkServicesHandlersCookieHandler $cookieHandler,
        PGFrameworkServicesLogger $logger
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
     * @param PGDomainInterfacesPrePaymentProvisionerInterface $prePaymentProvisioner
     * @return PGClientEntitiesResponse|null
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function generateFingerprintDatas(PGDomainInterfacesPrePaymentProvisionerInterface $prePaymentProvisioner)
    {
        /** @var PGClientServicesApiFacade $apiFacade */
        $apiFacade = $this->getService('paygreen.facade')->getApiFacade();

        $session = $this->cookieHandler->get(self::FINGERPRINT_COOKIE_NAME);

        if (empty($session)) {
            $this->getService('logger')->error("Empty fingerprint cookie.");
            return null;
        }

        /** @var PGDomainEntitiesFingerPrint|null $storedFingerPrint */
        $storedFingerPrint = $this->fingerPrintManager->getBySession($session);

        if ($storedFingerPrint === null) {
            $this->getService('logger')->error("Empty fingerprint data.");
            return null;
        }

        $aggregatedFingerPrint = array();

        $totalWeight = $prePaymentProvisioner->getShippingWeight();

        /** @var PGDomainInterfacesEntitiesCartItemInterface $item */
        foreach ($prePaymentProvisioner->getItems() as $item) {
            if (!$item->getProduct()->getWeight()) {
                $totalWeight ++;
            }
        }

        $aggregatedFingerPrint['deviceType'] = $storedFingerPrint->getDevice();
        $aggregatedFingerPrint['browser'] = $storedFingerPrint->getBrowser();
        $aggregatedFingerPrint['nbPage'] = (int) $storedFingerPrint->getPages();
        $aggregatedFingerPrint['useTime'] = (float) $storedFingerPrint->getTime() / 1000;
        $aggregatedFingerPrint['nbImage'] = (int) $storedFingerPrint->getPictures();
        $aggregatedFingerPrint['fingerprint'] = (int) $session;
        $aggregatedFingerPrint['carrier'] = (string) $prePaymentProvisioner->getShippingName();
        $aggregatedFingerPrint['weight'] = (float) $totalWeight;
        $aggregatedFingerPrint['nbPackage'] = (int) 1;
        $aggregatedFingerPrint['clientAddress'] = (string)
            $prePaymentProvisioner->getAddressLineOne() . ',' .
            $prePaymentProvisioner->getAddressLineTwo() . ',' .
            $prePaymentProvisioner->getZipCode() . ',' .
            $prePaymentProvisioner->getCity() . ',' .
            $prePaymentProvisioner->getCountry()
        ;

        $this->fingerPrintManager->delete($storedFingerPrint);

        foreach ($aggregatedFingerPrint as $key => $value) {
            if (empty($value)) {
                $this->getService('logger')->error("Empty value in fingerprint data : '$key'.", $aggregatedFingerPrint);
                return null;
            }
        }

        return $apiFacade->sendFingerprintDatas($aggregatedFingerPrint);
    }
}
