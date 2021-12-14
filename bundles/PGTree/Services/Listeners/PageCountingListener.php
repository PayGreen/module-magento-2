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

namespace PGI\Module\PGTree\Services\Listeners;

use PGI\Module\PGModule\Components\Events\Display as DisplayEventComponent;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGTree\Services\Handlers\PageCounterHandler;
use Exception;

/**
 * Class PageCountingListener
 * @package PGTree\Services\Listeners
 */
class PageCountingListener
{
    /** @var PageCounterHandler */
    private $pageCounterHandler;

    /** @var Logger */
    private $logger;

    private $bin;

    public function __construct(
        PageCounterHandler $pageCounterHandler,
        Logger $logger
    ) {
        $this->pageCounterHandler = $pageCounterHandler;
        $this->logger = $logger;
    }

    public function listen(DisplayEventComponent $event)
    {
        // Thrashing unused arguments
        $this->bin = $event;

        try {
            $this->pageCounterHandler->inc();
        } catch (Exception $exception) {
            $this->logger->error('An error occured during count page incrementation.', $exception);
        }
    }
}
