<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.5.2
 *
 */

namespace PGI\Module\FOTree\Services\OutputBuilders;

use PGI\Module\PGModule\Components\Output as OutputComponent;
use PGI\Module\PGModule\Foundations\AbstractOutputBuilder;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGServer\Components\Resources\Data as DataResourceComponent;
use PGI\Module\PGServer\Components\Resources\ScriptFile as ScriptFileResourceComponent;
use PGI\Module\PGServer\Services\Handlers\LinkHandler;

/**
 * Class CarbonBotOutputBuilder
 * @package FOTree\Services\OutputBuilders
 */
class CarbonBotOutputBuilder extends AbstractOutputBuilder
{
    /** @var LinkHandler */
    private $linkHandler;

    /** @var Settings */
    private $settings;

    public function __construct(LinkHandler $linkHandler, Settings $settings)
    {
        parent::__construct();

        $this->linkHandler = $linkHandler;
        $this->settings = $settings;
    }

    /**
     * @inheritDoc
     */
    public function build(array $data = array())
    {
        /** @var OutputComponent $output */
        $output = new OutputComponent();

        $output->addResource(new DataResourceComponent(array(
            'paygreen_tree_climatebot_url' => $this->linkHandler->buildFrontOfficeUrl(
                'front.climatebot.display'
            ),
            'paygreen_tree_climatebot_color' => $this->settings->get("tree_bot_color"),
            'paygreen_tree_climatebot_position' => $this->settings->get("tree_bot_side"),
            'paygreen_tree_climatebot_corner' => $this->settings->get("tree_bot_corner"),
            'paygreen_tree_climatebot_mobile' => $this->settings->get("tree_bot_mobile_activated")
        )));

        $output->addResource(new ScriptFileResourceComponent('/js/climatebot.js'));

        return $output;
    }
}
