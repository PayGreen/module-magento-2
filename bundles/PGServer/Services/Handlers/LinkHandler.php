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

namespace PGI\Module\PGServer\Services\Handlers;

use PGI\Module\PGFramework\Components\Aggregator as AggregatorComponent;
use PGI\Module\PGFramework\Tools\Query as QueryTool;
use PGI\Module\PGModule\Interfaces\ModuleFacadeInterface;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGServer\Interfaces\LinkerInterface;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use Exception;

/**
 * Class LinkHandler
 * @package PGServer\Services\Handlers
 */
class LinkHandler extends AbstractObject
{
    /** @var Logger */
    private $logger;

    /** @var ModuleFacadeInterface */
    private $moduleFacade;

    /** @var AggregatorComponent */
    private $linkerAggregator;

    public function __construct(
        AggregatorComponent $linkerAggregator,
        Logger $logger,
        ModuleFacadeInterface $moduleFacade
    ) {
        $this->linkerAggregator = $linkerAggregator;
        $this->logger = $logger;
        $this->moduleFacade = $moduleFacade;
    }

    /**
     * @param string $target
     * @return string
     * @throws Exception
     */
    public function buildUrl($target)
    {
        list($type, $data) = explode(':', $target, 2);

        switch ($type) {
            case 'link':
                $url = $this->buildLocalUrl($data);
                break;
            case 'front':
                $url = $this->buildFrontOfficeUrl($data);
                break;
            case 'back':
                $url = $this->buildBackOfficeUrl($data);
                break;
            case 'url':
                $url = $data;
                break;
            default:
                throw new Exception("Unknown URL type : '$type'.");
        }

        return $url;
    }

    /**
     * @param string $name
     * @param array $data
     * @return string
     * @throws Exception
     */
    public function buildLocalUrl($name, array $data = array())
    {
        /** @var LinkerInterface $localLinker */
        $localLinker = $this->linkerAggregator->getService($name);

        return $localLinker->buildUrl($data);
    }

    /**
     * @param string|null $action
     * @param array $data
     * @return string
     * @throws Exception
     */
    public function buildBackOfficeUrl($action = null, array $data = array())
    {
        /** @var LinkerInterface $localLinker */
        $localLinker = $this->linkerAggregator->getService('backoffice');

        return $this->buildOfficeUrl($localLinker->buildUrl(), $action, $data);
    }

    /**
     * @param string|null $action
     * @param array $data
     * @return string
     * @throws Exception
     */
    public function buildFrontOfficeUrl($action = null, array $data = array())
    {
        /** @var LinkerInterface $localLinker */
        $localLinker = $this->linkerAggregator->getService('frontoffice');

        return $this->buildOfficeUrl($localLinker->buildUrl(), $action, $data);
    }

    protected function buildOfficeUrl($base, $action = null, array $data = array())
    {
        $parameters = array();

        if (!empty($action)) {
            $parameters['pgaction'] = $action;
        }

        if (!empty($data)) {
            $parameters['pgdata'] = $data;
        }

        return QueryTool::addParameters($base, $parameters);
    }
}
