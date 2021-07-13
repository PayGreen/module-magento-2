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
 * @version   2.1.1
 *
 */

/**
 * Class BOModuleServicesHandlersMenuHandler
 * @package BOModule\Services\Handlers
 */
class BOModuleServicesHandlersMenuHandler
{
    /** @var PGSystemComponentsBag[] */
    private $config = array();

    /** @var PGServerServicesHandlersRouteHandler */
    private $routeHandler;

    /** @var PGServerServicesHandlersLink */
    private $linkHandler;

    private $entries = array();

    private $default;

    public function __construct(
        PGServerServicesHandlersRouteHandler $routeHandler,
        PGServerServicesHandlersLink $linkHandler,
        array $config
    ) {
        $this->routeHandler = $routeHandler;
        $this->linkHandler = $linkHandler;

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
            $entry = new PGSystemComponentsBag($entry);

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

                    $compiledEntry['href'] = $this->linkHandler->buildBackofficeUrl($entry['action']);
                } elseif ($entry['children']) {
                    $compiledEntry['children'] = $this->compileEntries($entry['children']);
                }

                $compiledEntries[] = $compiledEntry;
            }
        }

        return $compiledEntries;
    }

    /**
     * @param PGSystemComponentsBag $entry
     * @return bool
     * @throws Exception
     */
    protected function isDisplayed(PGSystemComponentsBag $entry)
    {
        $isDisplayed = false;

        if ($entry['action']) {
            $isDisplayed = $this->routeHandler->areRequirementsFulfilled($entry['action']);
        } elseif ($entry['children']) {
            foreach ($entry['children'] as $child) {
                $child = new PGSystemComponentsBag($child);

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
