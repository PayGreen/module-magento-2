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

use PGI\Module\PGFramework\Services\Handlers\OutputHandler;
use PGI\Module\PGModule\Foundations\AbstractOutputBuilder;
use PGI\Module\PGServer\Components\Resources\StyleFile as StyleFileResourceComponent;
use PGI\Module\PGServer\Services\Server;

/**
 * Class FrontOfficeOutputBuilder
 * @package FOModule\Services\OutputBuilders
 */
class FrontOfficeOutputBuilder extends AbstractOutputBuilder
{
    /** @var Server */
    private $server;

    /** @var OutputHandler */
    private $outputhandler;

    public function __construct(
        Server $server,
        OutputHandler $outputhandler
    ) {
        parent::__construct();
        $this->server = $server;
        $this->outputhandler = $outputhandler;
    }

    /**
     * @inheritDoc
     */
    public function build(array $data = array())
    {
        $this->server->run();

        $this->outputhandler->addResource(new StyleFileResourceComponent('/css/frontoffice.css'));

        return $this->outputhandler;
    }
}
