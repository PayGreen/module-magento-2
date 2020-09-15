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

/**
 * Class PGFrameworkServicesHandlersOutputHandler
 * @package PGFramework\Services\Handlers
 */
class PGFrameworkServicesHandlersOutputHandler
{
    private $resources = array();

    private $content = '';

    private $staticFileHandler;

    public function __construct(PGFrameworkServicesHandlersStaticFileHandler $staticFileHandler)
    {
        $this->staticFileHandler = $staticFileHandler;
    }

    /**
     * @param string $type
     * @param string $src
     * @return $this
     * @throws Exception
     */
    public function addResource($type, $src)
    {
        $url = $this->staticFileHandler->getUrl($src);

        $this->resources[] = array(
            'type' => $type,
            'url' => $url
        );

        return $this;
    }

    /**
     * @param array $resources
     * @return $this
     * @throws Exception
     */
    public function addResources(array $resources)
    {
        foreach ($resources as $resource) {
            $this->addResource($resource['type'], $resource['src']);
        }

        return $this;
    }

    /**
     * @param string $type
     * @return array
     */
    public function getResources($type)
    {
        $resources = array();

        foreach ($this->resources as $resource) {
            if ($resource['type'] === $type) {
                $resources[] = $resource['url'];
            }
        }

        return $resources;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function addContent($content)
    {
        $this->content .= $content;

        return $this;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
