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
 * @version   2.5.0
 *
 */

namespace PGI\Module\PGPayment\Services\Handlers;

use PGI\Module\PGFramework\Services\Handlers\PictureHandler;
use PGI\Module\PGModule\Services\Handlers\StaticFileHandler;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGPayment\Interfaces\Entities\ButtonEntityInterface;

/**
 * Class PaymentButtonHandler
 * @package PGPayment\Services\Handlers
 */
class PaymentButtonHandler
{
    /** @var Logger */
    private $logger;

    /** @var PictureHandler */
    private $pictureHandler;

    /** @var StaticFileHandler */
    private $staticFileHandler;

    /** @var array */
    private $paymentDefaultPictures;

    public function __construct(
        Logger $logger,
        PictureHandler $pictureHandler,
        StaticFileHandler $staticFileHandler,
        array $paymentDefaultPictures = array()
    ) {
        $this->logger = $logger;
        $this->pictureHandler = $pictureHandler;
        $this->staticFileHandler = $staticFileHandler;
        $this->paymentDefaultPictures = $paymentDefaultPictures;
    }

    /**
     * @param ButtonEntityInterface $button
     * @return string
     */
    public function getButtonFinalUrl(ButtonEntityInterface $button)
    {
        $filename = $button->getImageSrc();

        if (empty($filename) || !$this->pictureHandler->isStored($filename)) {
            $filename = $this->getDefaultButtonPicture($button);
            return $this->staticFileHandler->getUrl($filename);
        }

        return $this->pictureHandler->getUrl($filename);
    }

    protected function getDefaultButtonPicture(ButtonEntityInterface $button)
    {
        $buttonPaymentType = strtolower($button->getPaymentType());

        if (array_key_exists($buttonPaymentType, $this->paymentDefaultPictures)) {
            $buttonDefaultPicture = $this->paymentDefaultPictures[$buttonPaymentType];
        } else {
            $buttonDefaultPicture = $this->paymentDefaultPictures['default'];
        }

        $details = "payment type {$buttonPaymentType}";
        $text = "Use default picture {$buttonDefaultPicture} for button #{$button->id()} with $details";
        $this->logger->debug($text);

        return "/pictures/PGPayment/payment-buttons/{$buttonDefaultPicture}";
    }
}
