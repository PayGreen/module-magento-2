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

namespace PGI\Module\PGServer\Services;

use PGI\Module\PGFramework\Components\Aggregator as AggregatorComponent;
use PGI\Module\PGLog\Interfaces\LoggerInterface;
use PGI\Module\PGServer\Foundations\AbstractRequest;
use PGI\Module\PGServer\Foundations\AbstractResponse;
use PGI\Module\PGServer\Interfaces\DeflectorInterface;
use Exception;

/**
 * Class Derouter
 * @package PGServer\Services
 */
class Derouter
{
    /** @var AggregatorComponent */
    private $deflectorAggregator;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        AggregatorComponent $deflectorAggregator,
        LoggerInterface $logger
    ) {
        $this->deflectorAggregator = $deflectorAggregator;
        $this->logger = $logger;
    }

    /**
     * @param AbstractRequest $request
     * @param array $deflectorNames
     * @return DeflectorInterface|null
     */
    public function getMatchingDeflector(AbstractRequest $request, array $deflectorNames)
    {
        try {
            foreach ($deflectorNames as $deflectorName) {
                /** @var DeflectorInterface $deflector */
                $deflector = $this->deflectorAggregator->getService($deflectorName);

                if ($deflector->isMatching($request)) {
                    $this->logger->debug("Found matching deflector : '$deflectorName'.");
                    return $deflector;
                }
            }
        } catch (Exception $exception) {
            $list = implode(', ', $deflectorNames);
            $this->logger->error(
                "An error occurred when select matching deflector in list [$list] : " . $exception->getMessage(),
                $exception
            );
        }

        return null;
    }

    /**
     * @param AbstractRequest $request
     * @param array $deflectorNames
     * @return AbstractResponse
     * @throws Exception
     */
    public function preprocess(AbstractRequest $request, array $deflectorNames)
    {
        foreach ($deflectorNames as $deflectorName) {
            /** @var DeflectorInterface $deflector */
            $deflector = $this->deflectorAggregator->getService($deflectorName);

            $response = $deflector->process($request);
        }

        return $response;
    }
}
