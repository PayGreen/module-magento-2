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

namespace PGI\Module\PGPayment\Foundations;

use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGPayment\Components\PaymentProject as PaymentProjectComponent;
use PGI\Module\PGPayment\Interfaces\PaymentCreationChainLinkInterface;

/**
 * Class AbstractPaymentCreationChainLink
 * @package PGPayment\Foundations
 */
abstract class AbstractPaymentCreationChainLink implements PaymentCreationChainLinkInterface
{
    /** @var PaymentCreationChainLinkInterface|null */
    private $next = null;

    /** @var Logger */
    private $logger;

    /**
     * @param PaymentCreationChainLinkInterface $next
     */
    public function setNext(PaymentCreationChainLinkInterface $next)
    {
        $this->next = $next;
    }

    /**
     * @param Logger $logger
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return Logger
     */
    protected function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param PaymentProjectComponent $paymentProject
     */
    public function run(PaymentProjectComponent $paymentProject)
    {
        $className = get_class($this);
        $this->logger->debug("Processing payment creation chain link : '$className'.");

        $this->process($paymentProject);

        if ($this->next !== null) {
            $this->next->run($paymentProject);
        }
    }

    abstract protected function process(PaymentProjectComponent $paymentProject);
}
