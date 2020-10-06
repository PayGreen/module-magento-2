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
 * @version   1.2.0
 */

/**
 * Class PGDomainServicesHandlersPaymentButtonHandler
 * @package PGFramework\Services\Handlers
 */
class PGDomainServicesHandlersPaymentButtonHandler
{
    /** @var PGFrameworkServicesLogger */
    private $logger;

    /** @var PGFrameworkServicesHandlersPictureHandler */
    private $pictureHandler;

    /** @var PGFrameworkServicesHandlersStaticFileHandler */
    private $staticFileHandler;

    /** @var array */
    private $paymentDefaultPictures;

    public function __construct(
        PGFrameworkServicesLogger $logger,
        PGFrameworkServicesHandlersPictureHandler $pictureHandler,
        PGFrameworkServicesHandlersStaticFileHandler $staticFileHandler,
        array $paymentDefaultPictures = array()
    ) {
        $this->logger = $logger;
        $this->pictureHandler = $pictureHandler;
        $this->staticFileHandler = $staticFileHandler;
        $this->paymentDefaultPictures = $paymentDefaultPictures;
    }

    /**
     * @param PGDomainInterfacesEntitiesButtonInterface $button
     * @return string
     */
    public function getButtonFinalUrl(PGDomainInterfacesEntitiesButtonInterface $button)
    {
        $filename = $button->getImageSrc();

        if (empty($filename) || !$this->pictureHandler->isStored($filename)) {
            $filename = $this->getDefaultButtonPicture($button);
            return $this->staticFileHandler->getUrl($filename);
        }

        return $this->pictureHandler->getUrl($filename);
    }

    protected function getDefaultButtonPicture(PGDomainInterfacesEntitiesButtonInterface $button)
    {
        $buttonPaymentType = strtolower($button->getPaymentType());

        $buttonDefaultPicture = (array_key_exists($buttonPaymentType, $this->paymentDefaultPictures)) ? $this->paymentDefaultPictures[$buttonPaymentType] : $this->paymentDefaultPictures['default'];

        $this->logger->debug("Use default picture {$buttonDefaultPicture} for button #{$button->id()} with payment type {$buttonPaymentType}");

        return "/pictures/PGDomain/payment-buttons/{$buttonDefaultPicture}";
    }
}
