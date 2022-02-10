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

namespace PGI\Module\BOModule\Services\Views\Blocks;

use PGI\Module\BOModule\Services\Handlers\ServerHandler;
use PGI\Module\PGView\Services\View;
use Exception;

/**
 * Class ServerBlockView
 * @package BOModule\Services\Views\Blocks
 */
class ServerBlockView extends View
{
    /** @var ServerHandler */
    private $serverHandler;

    public function __construct(ServerHandler $serverHandler)
    {
        $this->serverHandler = $serverHandler;
        $this->setTemplate('block-server');
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getData()
    {
        return array(
            'servers' => $this->serverHandler->getData()
        );
    }
}
