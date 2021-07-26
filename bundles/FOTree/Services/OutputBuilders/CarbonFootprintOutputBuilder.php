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

namespace PGI\Module\FOTree\Services\OutputBuilders;

use PGI\Module\PGIntl\Services\Handlers\LocaleHandler;
use PGI\Module\PGModule\Components\Output as OutputComponent;
use PGI\Module\PGModule\Foundations\AbstractOutputBuilder;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use PGI\Module\PGTree\Interfaces\Entities\CarbonDataEntityInterface;
use PGI\Module\PGTree\Services\Managers\CarbonDataManager;
use Exception;
use NumberFormatter;

/**
 * Class CarbonFootprintOutputBuilder
 * @package FOTree\Services\OutputBuilders
 */
class CarbonFootprintOutputBuilder extends AbstractOutputBuilder
{
    /** @var CarbonDataManager */
    private $carbonDataManager;

    /** @var LocaleHandler */
    private $localeHandler;

    /** @var Logger */
    private $logger;

    public function __construct(
        CarbonDataManager $carbonDataManager,
        LocaleHandler $localeHandler,
        Logger $logger
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
        } elseif (! $data['order'] instanceof OrderEntityInterface) {
            throw new Exception("Order must be an instance of OrderEntityInterface.");
        }

        /** @var OrderEntityInterface $order */
        $order = $data['order'];
        $output = new OutputComponent();

        /** @var CarbonDataEntityInterface|null $carbonData */
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
