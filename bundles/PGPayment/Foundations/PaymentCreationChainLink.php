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
 * Class PGPaymentFoundationsPaymentCreationChainLink
 * @package PGPayment\Foundations
 */
abstract class PGPaymentFoundationsPaymentCreationChainLink implements PGPaymentInterfacesPaymentCreationChainLink
{
    /** @var PGPaymentInterfacesPaymentCreationChainLink|null */
    private $next = null;

    /** @var PGModuleServicesLogger */
    private $logger;

    /**
     * @param PGPaymentInterfacesPaymentCreationChainLink $next
     */
    public function setNext(PGPaymentInterfacesPaymentCreationChainLink $next)
    {
        $this->next = $next;
    }

    /**
     * @param PGModuleServicesLogger $logger
     */
    public function setLogger(PGModuleServicesLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return PGModuleServicesLogger
     */
    protected function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param PGPaymentComponentsPaymentProject $paymentProject
     */
    public function run(PGPaymentComponentsPaymentProject $paymentProject)
    {
        $className = get_class($this);
        $this->logger->debug("Processing payment creation chain link : '$className'.");

        $this->process($paymentProject);

        if ($this->next !== null) {
            $this->next->run($paymentProject);
        }
    }

    abstract protected function process(PGPaymentComponentsPaymentProject $paymentProject);
}
