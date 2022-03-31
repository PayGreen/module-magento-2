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
 * @version   2.6.1
 *
 */

namespace PGI\Module\FOModule\Services\OutputBuilders;

use Exception;
use PGI\Module\PGModule\Components\Output as OutputComponent;
use PGI\Module\PGModule\Foundations\AbstractOutputBuilder;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGServer\Components\Resources\Data as DataResourceComponent;
use PGI\Module\PGServer\Components\Resources\ScriptFile as ScriptFileResourceComponent;
use PGI\Module\PGServer\Services\Handlers\LinkHandler;

/**
 * Class CronLauncherOutputBuilder
 * @package FOModule\Services\OutputBuilders
 */
class CronLauncherOutputBuilder extends AbstractOutputBuilder
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
     * @throws Exception
     */
    public function build(array $data = array())
    {
        /** @var OutputComponent $output */
        $output = new OutputComponent();

        if ($this->settings->get('cron_activation_mode') === 'AJAX') {
            $output->addResource(new DataResourceComponent(array(
                'paygreen_cron_url' => $this->linkHandler->buildFrontOfficeUrl(
                    'front.cron.run'
                ),
                'paygreen_cron_rate' => 1000
            )));

            $output->addResource(new ScriptFileResourceComponent('/js/cron-launcher.js'));
        }

        return $output;
    }
}
