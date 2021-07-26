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

namespace PGI\Module\PGPayment\Services\ResponsabilityChains;

use PGI\Module\PGPayment\Components\PaymentProject as PaymentProjectComponent;
use PGI\Module\PGPayment\Interfaces\Entities\ButtonEntityInterface;
use PGI\Module\PGPayment\Interfaces\PaymentCreationChainLinkInterface;
use PGI\Module\PGShop\Interfaces\Provisioners\PrePaymentProvisionerInterface;
use Exception;

/**
 * Class PaymentCreationResponsabilityChain
 * @package PGPayment\Services\ResponsabilityChains
 */
class PaymentCreationResponsabilityChain
{
    /** @var PaymentCreationChainLinkInterface|null  */
    private $firstChainLinks = null;

    /** @var PaymentCreationChainLinkInterface|null  */
    private $lastChainLinks = null;

    public function addChainLink(PaymentCreationChainLinkInterface $chainLink)
    {
        if ($this->firstChainLinks === null) {
            $this->firstChainLinks = $chainLink;
            $this->lastChainLinks = $chainLink;
        } else {
            $this->lastChainLinks->setNext($chainLink);
            $this->lastChainLinks = $chainLink;
        }
    }

    public function buildPaymentCreationData(
        ButtonEntityInterface $button,
        PrePaymentProvisionerInterface $prePaymentProvisionner
    ) {
        if ($this->firstChainLinks === null) {
            throw new Exception("Payment creation responsability chain is empty.");
        }

        $paymentProject = new PaymentProjectComponent($button, $prePaymentProvisionner);

        $this->firstChainLinks->run($paymentProject);

        return $paymentProject->toArray();
    }
}
