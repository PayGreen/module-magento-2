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
 * @version   2.4.0
 *
 */

namespace PGI\Module\FOTree\Services\OutputBuilders;

use PGI\Module\FOTree\Services\Handlers\CarbonRounderHandler;
use PGI\Module\PGModule\Components\Output as OutputComponent;
use PGI\Module\PGModule\Foundations\AbstractOutputBuilder;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use PGI\Module\PGTree\Interfaces\Entities\CarbonDataEntityInterface;
use PGI\Module\PGTree\Services\Managers\CarbonDataManager;
use PGI\Module\PGModule\Services\Settings;
use Exception;

/**
 * Class CarbonFootprintOutputBuilder
 * @package FOTree\Services\OutputBuilders
 */
class CarbonFootprintOutputBuilder extends AbstractOutputBuilder
{
    /** @var CarbonDataManager */
    private $carbonDataManager;

    /** @var CarbonRounderHandler */
    private $carbonRounderHandler;

    /** @var Logger */
    private $logger;

    /** @var Settings */
    private $settings;

    public function __construct(
        CarbonDataManager $carbonDataManager,
        CarbonRounderHandler $carbonRounderHandler,
        Logger $logger,
        Settings $settings
    ) {
        parent::__construct();

        $this->carbonDataManager = $carbonDataManager;
        $this->carbonRounderHandler = $carbonRounderHandler;
        $this->logger = $logger;
        $this->settings = $settings;
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

        $isTreeTestModeActivated = $this->settings->get('tree_test_mode');

        if ($carbonData !== null) {
            $content = $this->getViewHandler()->renderTemplate('carbon-offset-merchant', array(
                "carbon_offset" => $this->carbonRounderHandler->roundNumber(
                    $carbonData->getFootprint()
                ),
                'isTreeTestModeActivated' => $isTreeTestModeActivated
            ));

            $output->setContent($content);
        } else {
            $this->logger->error("Unable to retrieve CarbonData for order #{$order->id()}.");
        }

        return $output;
    }
}
