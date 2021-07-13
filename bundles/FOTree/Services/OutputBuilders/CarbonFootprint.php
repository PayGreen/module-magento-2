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
 * @version   2.1.1
 *
 */

/**
 * Class FOTreeServicesOutputBuildersCarbonFootprint
 * @package FOTree\Services\OutputBuilders
 */
class FOTreeServicesOutputBuildersCarbonFootprint extends PGModuleFoundationsOutputBuilder
{
    /** @var PGTreeServicesManagersCarbonData */
    private $carbonDataManager;

    /** @var PGIntlServicesHandlersLocaleHandler */
    private $localeHandler;

    /** @var PGModuleServicesLogger */
    private $logger;

    public function __construct(
        PGTreeServicesManagersCarbonData $carbonDataManager,
        PGIntlServicesHandlersLocaleHandler $localeHandler,
        PGModuleServicesLogger $logger
    ) {
        parent::__construct();

        $this->carbonDataManager = $carbonDataManager;
        $this->localeHandler = $localeHandler;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function build(array $data = array())
    {
        if (!array_key_exists('order', $data)) {
            throw new Exception("Order is required to build carbon footprint.");
        } elseif (! $data['order'] instanceof PGShopInterfacesEntitiesOrder) {
            throw new Exception("Order must be an instance of PGShopInterfacesEntitiesOrder.");
        }

        /** @var PGShopInterfacesEntitiesOrder $order */
        $order = $data['order'];
        $output = new PGModuleComponentsOutput();

        /** @var PGTreeInterfacesEntitiesCarbonData|null $carbonData */
        $carbonData = $this->carbonDataManager->getByOrderPrimary($order->id());

        $formatter = new NumberFormatter($this->localeHandler->getLanguage(), NumberFormatter::DECIMAL);

        if ($carbonData !== null) {
            $content = $this->getViewHandler()->renderTemplate('carbon-offset-merchant', array(
                "carbon_offset" => $formatter->format(
                    $this->convertTonToKiloGram($carbonData->getFootprint())
                )
            ));

            $output->setContent($content);
        } else {
            $this->logger->error("Unable to retrieve CarbonData for order #{$order->id()}.");
        }

        return $output;
    }

    private function convertTonToKiloGram($carbonEmission)
    {
        return ($carbonEmission * 1000);
    }
}
