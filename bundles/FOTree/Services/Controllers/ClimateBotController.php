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

namespace PGI\Module\FOTree\Services\Controllers;

use PGI\Module\APITree\Components\Replies\CarbonFootprint as CarbonFootprintReplyComponent;
use PGI\Module\BOModule\Foundations\Controllers\AbstractBackofficeController;
use PGI\Module\PGIntl\Services\Handlers\LocaleHandler;
use PGI\Module\PGServer\Components\Responses\HTTP as HTTPResponseComponent;
use PGI\Module\PGShop\Services\Managers\CartManager;
use PGI\Module\PGShop\Services\Managers\CustomerManager;
use PGI\Module\PGTree\Services\Handlers\TreeCarbonOffsettingHandler;
use PGI\Module\PGView\Services\Handlers\ViewHandler;
use Exception;
use NumberFormatter;

/**
 * Class ClimateBotController
 * @package FOTree\Services\Controllers
 */
class ClimateBotController extends AbstractBackofficeController
{
    /** @var CustomerManager */
    private $customerManager;

    /** @var CartManager */
    private $cartManager;

    /** @var ViewHandler */
    private $viewHandler;

    /** @var TreeCarbonOffsettingHandler */
    private $carbonOffsettingHandler;

    /** @var LocaleHandler */
    private $localeHandler;

    public function __construct(
        CustomerManager $customerManager,
        CartManager $cartManager,
        ViewHandler $viewHandler,
        TreeCarbonOffsettingHandler $carbonOffsettingHandler,
        LocaleHandler $localeHandler
    ) {
        $this->customerManager = $customerManager;
        $this->cartManager = $cartManager;
        $this->viewHandler = $viewHandler;
        $this->carbonOffsettingHandler = $carbonOffsettingHandler;
        $this->localeHandler = $localeHandler;
    }

    /**
     * @return HTTPResponseComponent
     * @throws Exception
     */
    public function displayAction()
    {
        $this->carbonOffsettingHandler->computeCarbonOffsetting(
            $this->cartManager->getCurrent(),
            $this->customerManager->getCurrent()
        );

        /** @var CarbonFootprintReplyComponent $carbonFootprint */
        $carbonFootprint = $this->carbonOffsettingHandler->getCarbonOffsetting();

        $formatter = new NumberFormatter( $this->localeHandler->getLanguage(), NumberFormatter::DECIMAL );

        $templateContent = $this->viewHandler->renderTemplate('tree-bot', array(
            'color' => $this->getSettings()->get("tree_bot_color"),
            'position' => $this->getSettings()->get("tree_bot_side"),
            'corner' => $this->getSettings()->get("tree_bot_corner"),
            'isDetailsActivated' => $this->getSettings()->get('tree_bot_details_activated'),
            'detailsUrl' => $this->getSettings()->get('tree_details_url'),
            'carbonEmittedTotal' => $formatter->format(
                $this->convertTonToKiloGram(
                    $carbonFootprint->getEstimatedCarbon()
                )
            ),
            'carbonEmittedFromDigital' => $formatter->format(
                $this->convertTonToGram(
                    $carbonFootprint->getCarbonEmittedFromDigital()
                )
            ),
            'carbonEmittedFromTransportation' => $formatter->format(
                $this->convertTonToGram(
                    $carbonFootprint->getCarbonEmittedFromTransportation()
                )
            ),
            'carbonEmittedFromProduct' => $formatter->format(
                $this->convertTonToKiloGram(
                    $carbonFootprint->getCarbonEmittedFromProduct()
                )
            )
        ));

        $response = new HTTPResponseComponent($this->getRequest());
        $response->setContent($templateContent);

        return  $response;
    }

    private function convertTonToGram($carbonEmission)
    {
        return ($carbonEmission * 1000000);
    }

    private function convertTonToKiloGram($carbonEmission)
    {
        return ($carbonEmission * 1000);
    }
}