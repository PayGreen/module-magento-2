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
 * @version   2.6.0
 *
 */

namespace PGI\Module\PGIntl\Services\Listeners;

use PGI\Module\PGIntl\Services\Handlers\CacheTranslationHandler;
use PGI\Module\PGModule\Components\Events\Module as ModuleEventComponent;
use PGI\Module\PGLog\Interfaces\LoggerInterface;
use Exception;

/**
 * Class ResetTranslationCacheListener
 * @package PGIntl\Services\Listeners
 */
class ResetTranslationCacheListener
{
    /** @var CacheTranslationHandler */
    private $cacheHandler;

    /** @var LoggerInterface */
    private $logger;

    private $bin;

    public function __construct(
        CacheTranslationHandler $cacheHandler,
        LoggerInterface $logger
    ) {
        $this->cacheHandler = $cacheHandler;
        $this->logger = $logger;
    }

    public function listen(ModuleEventComponent $event)
    {
        // Thrashing unused arguments
        $this->bin = $event;

        try {
            $this->logger->info("Reseting translation cache.");

            $this->cacheHandler->reset();

            $this->logger->notice("Translation cache successfully reseted.");
        } catch (Exception $exception) {
            $this->logger->error(
                "An error occured during translation cache reseting : " . $exception->getMessage(),
                $exception
            );
        }
    }
}
