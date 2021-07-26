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
 * @version   2.2.0
 *
 */

namespace PGI\Module\PGPayment\Services\ChainLinks;

use PGI\Module\PGPayment\Components\PaymentProject as PaymentProjectComponent;
use PGI\Module\PGPayment\Data;
use PGI\Module\PGPayment\Foundations\AbstractPaymentCreationChainLink;
use PGI\Module\PGPayment\Interfaces\Entities\ButtonEntityInterface;
use Exception;

/**
 * Class AddXTimeDataChainLink
 * @package PGPayment\Services\ChainLinks
 */
class AddXTimeDataChainLink extends AbstractPaymentCreationChainLink
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function process(PaymentProjectComponent $paymentProject)
    {
        /** @var ButtonEntityInterface $button */
        $button = $paymentProject->getButton();

        if ($button->getPaymentMode() === Data::MODE_XTIME) {
            $paymentNumber = $button->getPaymentNumber();
            $amount = $paymentProject->getPrePaymentProvisionner()->getTotalAmount();

            if ($button->getFirstPaymentPart() > 0) {
                $firstAmount = ceil($amount / 100 * $button->getFirstPaymentPart());
            } else {
                $firstAmount = ceil($amount / $paymentNumber);
            }

            if (!is_array($paymentProject['orderDetails'])) {
                $paymentProject['orderDetails'] = array();
            }

            $paymentProject['orderDetails'] += array(
                'cycle' => Data::RECURRING_MONTHLY,
                'count' => $paymentNumber,
                'firstAmount' => $firstAmount
            );
        }
    }
}
