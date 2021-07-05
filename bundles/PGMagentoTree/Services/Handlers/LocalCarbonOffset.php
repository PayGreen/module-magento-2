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
 * @version   2.1.0
 *
 */

/**
 * Class PGMagentoTreeServicesHandlersLocalCarbonOffset
 * @package PGMagentoTree\Services\Handlers
 */
class PGMagentoTreeServicesHandlersLocalCarbonOffset
{
    const CARBON_OFFSET_SESSION_NAME = 'pgCarbonOffsetSession';

    /** @var PGTreeServicesHandlersTreeCarbonOffsetting */
    private $carbonOffsettingHandler;

    /** @var PGFrameworkInterfacesHandlersSessionHandlerInterface */
    private  $sessionHandler;

    /** @var PGModuleServicesLogger */
    private  $logger;

    public function __construct(
        PGTreeServicesHandlersTreeCarbonOffsetting $carbonOffsettingHandler,
        PGFrameworkInterfacesHandlersSessionHandlerInterface $sessionHandler,
        PGModuleServicesLogger $logger
    ) {
        $this->carbonOffsettingHandler = $carbonOffsettingHandler;
        $this->sessionHandler = $sessionHandler;
        $this->logger = $logger;
    }

    /**
     * @param $localOrder
     * @return void
     * @throws Exception
     */
    public function computeCarbonOffset($localOrder)
    {
        $order = new PGMagentoEntitiesOrder($localOrder);
        $postOrderProvisionner = new PGMagentoProvisionersPostOrderProvisioner($order);

        $carbonOffsetOutput = $this->carbonOffsettingHandler->displayCarbonOffsetting($postOrderProvisionner);

        if (!empty($carbonOffsetOutput)) {
            $this->storeCarbonOffsetOutput($carbonOffsetOutput);
        }
    }

    /**
     * @param $carbonOffsetOutput
     * @return void
     */
    public function storeCarbonOffsetOutput($carbonOffsetOutput)
    {
        $this->logger->debug('Store carbon offset output in session.');

        $this->sessionHandler->set(self::CARBON_OFFSET_SESSION_NAME, $carbonOffsetOutput);
    }

    /**
     * @return string
     */
    public function getCarbonOffsetOutput()
    {
        $output = '';

        if ($this->sessionHandler->has(self::CARBON_OFFSET_SESSION_NAME)) {
            $this->logger->debug('Fetching carbon offset output from session.');
            $output = $this->sessionHandler->get(self::CARBON_OFFSET_SESSION_NAME);

            $this->logger->debug('Remove carbon offset output from session.');
            $this->sessionHandler->rem(self::CARBON_OFFSET_SESSION_NAME);
        } else {
            $this->logger->debug('Carbon offset output not found in session.');
        }

        return $output;
    }
}