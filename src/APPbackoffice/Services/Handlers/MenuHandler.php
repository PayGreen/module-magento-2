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
 * @version   1.1.0
 */

class APPbackofficeServicesHandlersMenuHandler
{
    /** @var PGFrameworkComponentsBag[] */
    private $config = array();

    /** @var PGServerServicesHandlersRouteHandler */
    private $routeHandler;

    /** @var PGServerServicesLinker */
    private $linker;

    private $entries = array();

    private $default;

    public function __construct(
        PGServerServicesHandlersRouteHandler $routeHandler,
        PGServerServicesLinker $linker,
        array $config
    ) {
        $this->routeHandler = $routeHandler;
        $this->linker = $linker;

        $this->config = $config;
    }

    /**
     * @param array $entries
     * @throws Exception
     */
    protected function buildEntries(array $entries)
    {
        $this->default = null;
        $this->entries = $this->compileEntries($entries);
    }

    /**
     * @param array $entries
     * @return array
     * @throws Exception
     */
    protected function compileEntries(array $entries)
    {
        $compiledEntries = array();

        foreach ($entries as $code => $entry) {
            $entry = new PGFrameworkComponentsBag($entry);

            if ($this->isDisplayed($entry)) {
                $compiledEntry = array(
                    'code' => $code,
                    'name' => $entry['name'],
                    'title' => $entry['title']
                );

                if ($entry['action']) {
                    if ($this->default === null) {
                        $this->default = $entry['action'];
                    }

                    $compiledEntry['href'] = $this->linker->buildBackofficeUrl($entry['action']);
                } elseif ($entry['children']) {
                    $compiledEntry['children'] = $this->compileEntries($entry['children']);
                }

                $compiledEntries[] = $compiledEntry;
            }
        }

        return $compiledEntries;
    }

    /**
     * @param PGFrameworkComponentsBag $entry
     * @return bool
     * @throws Exception
     */
    protected function isDisplayed(PGFrameworkComponentsBag $entry)
    {
        $isDisplayed = false;

        if ($entry['action']) {
            $isDisplayed = $this->routeHandler->areRequirementsFulfilled($entry['action']);
        } elseif ($entry['children']) {
            foreach($entry['children'] as $child) {
                $child = new PGFrameworkComponentsBag($child);

                if ($this->isDisplayed($child)) {
                    $isDisplayed = true;
                    break;
                }
            }
        }

        return $isDisplayed;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getEntries()
    {
        if (empty($this->entries)) {
            $this->buildEntries($this->config['entries']);
        }

        return $this->entries;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getDefaultAction()
    {
        if (empty($this->entries)) {
            $this->buildEntries($this->config['entries']);
        }

        return $this->default;
    }

    public function isShopSelectorActivated()
    {
        return (bool) $this->config['shop_selector'];
    }
}
