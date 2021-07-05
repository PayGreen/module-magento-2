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
 * Class PGTreeServicesListenersCarbonOffsettingComputingWeb
 * @package PGTree\Services\Listeners
 */
class PGTreeServicesListenersCarbonOffsettingComputingWeb
{
    /** @var APITreeServicesApiFacade */
    private $treeAPIFacade;

    /** @var PGTreeServicesHandlersPageCounter */
    private $pageCounter;

    /** @var PGModuleServicesLogger */
    private $logger;

    public function __construct(
        APITreeServicesApiFacade $treeAPIFacade,
        PGTreeServicesHandlersPageCounter $pageCounter,
        PGModuleServicesLogger $logger
    ) {
        $this->treeAPIFacade = $treeAPIFacade;
        $this->pageCounter = $pageCounter;
        $this->logger = $logger;
    }

    /**
     * @param PGTreeComponentsEventsCarbonOffsettingComputing $event
     */
    public function listen(PGTreeComponentsEventsCarbonOffsettingComputing $event)
    {
        if ($this->pageCounter->get() > 0) {
            try {
                $this->treeAPIFacade->addWebCarbonEmission(
                    $event->getCarbonOffsettingComputing()->getFingerPrintPrimary(),
                    $this->pageCounter->get(),
                    $_SERVER['HTTP_USER_AGENT'] // @todo Récupération du user agent à revoir
                );

                $this->pageCounter->raz();

                $this->logger->notice('Successfully add web browsing footprint.');
            } catch (Exception $exception) {
                $this->logger->error("Unable to add web carbon emission : " . $exception->getMessage());
            }
        }
    }
}
