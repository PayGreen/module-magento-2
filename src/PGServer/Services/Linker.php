<?php
/**
 * 2014 - 2020 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.2.0
 */

/**
 * Class PGServerServicesLinker
 * @package PGServer\Services\Handlers
 */
class PGServerServicesLinker extends PGFrameworkFoundationsAbstractObject
{
    /** @var PGFrameworkServicesLogger */
    private $logger;

    /** @var PGFrameworkInterfacesModuleFacadeInterface */
    private $moduleFacade;

    /** @var PGServerServicesFactoriesLinkerFactory */
    private $linkerFactory;

    public function __construct(PGServerServicesFactoriesLinkerFactory $linkerFactory, PGFrameworkServicesLogger $logger, PGFrameworkInterfacesModuleFacadeInterface $moduleFacade)
    {
        $this->linkerFactory = $linkerFactory;
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
        /** @var PGServerInterfacesLinkerInterface $localLinker */
        $localLinker = $this->linkerFactory->getLocalLinker($name);

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
        /** @var PGServerInterfacesLinkerInterface $localLinker */
        $localLinker = $this->linkerFactory->getLocalLinker('backoffice');

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
        /** @var PGServerInterfacesLinkerInterface $localLinker */
        $localLinker = $this->linkerFactory->getLocalLinker('frontoffice');

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

        return PGFrameworkToolsQuery::addParameters($base, $parameters);
    }
}
