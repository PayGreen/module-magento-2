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
 * @version   2.5.0
 *
 */

namespace PGI\Module\BOModule\Services\OutputBuilders;

use PGI\Module\BOModule\Services\Handlers\MenuHandler;
use PGI\Module\PGFramework\Services\Handlers\OutputHandler;
use PGI\Module\PGModule\Foundations\AbstractOutputBuilder;
use PGI\Module\PGServer\Components\Resources\ScriptFile as ScriptFileResourceComponent;
use PGI\Module\PGServer\Components\Resources\StyleFile as StyleFileResourceComponent;
use PGI\Module\PGServer\Services\Server;

/**
 * Class BackOfficeOutputBuilder
 * @package BOModule\Services\OutputBuilders
 */
class BackOfficeOutputBuilder extends AbstractOutputBuilder
{
    /** @var Server */
    private $server;

    /** @var OutputHandler */
    private $outputhandler;

    /** @var MenuHandler $menuHandler */
    private $menuHandler;

    public function __construct(
        Server $server,
        OutputHandler $outputhandler,
        MenuHandler $menuHandler
    ) {
        parent::__construct();
        $this->server = $server;
        $this->outputhandler = $outputhandler;
        $this->menuHandler = $menuHandler;
    }

    /**
     * @inheritDoc
     */
    public function build(array $data = array())
    {
        $this->server->getRequestBuilder()->setConfig('default_action', $this->menuHandler->getDefaultAction());
        $this->server->run();

        $this->outputhandler->addResource(new ScriptFileResourceComponent('/js/backoffice.js'));
        $this->outputhandler->addResource(new StyleFileResourceComponent('/css/backoffice.css'));

        return $this->outputhandler;
    }
}
