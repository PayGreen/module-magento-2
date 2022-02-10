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

namespace PGI\Module\PGCharity\Services\Diagnostics;

use PGI\Module\PGCharity\Services\Handlers\CharityGiftHandler;
use PGI\Module\PGFramework\Foundations\AbstractDiagnostic;
use PGI\Module\PGModule\Services\Logger;
use Exception;

/**
 * Class CharityGiftDiagnostic
 * @package PGCharity\Services\Diagnostics
 */
class CharityGiftDiagnostic extends AbstractDiagnostic
{
    /** @var CharityGiftHandler */
    private $charityGiftHandler;

    /** @var Logger */
    private $logger;

    public function __construct(CharityGiftHandler $charityGiftHandler, Logger $logger)
    {
        $this->charityGiftHandler = $charityGiftHandler;
        $this->logger = $logger;
    }

    /**
     * @throws Exception
     */
    public function isValid()
    {
        $result = false;

        try {
            $result = $this->charityGiftHandler->isValid();
        } catch (Exception $exception) {
            $this->logger->error("Critical error during charity gift diagnostic : " . $exception->getMessage());
        }

        return $result;
    }

    public function resolve()
    {
        try {
            $this->logger->info('Re-install charity gift product.');

            $this->charityGiftHandler->install();

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                "Error during charity gift re-installation : " . $exception->getMessage(),
                $exception
            );
        }

        return false;
    }
}
