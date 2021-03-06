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
 * @version   1.2.5
 *
 */

/**
 * Class PGFrameworkServicesHandlersOutputHandler
 * @package PGFramework\Services\Handlers
 */
class PGFrameworkServicesHandlersOutputHandler
{
    /** @var PGServerComponentsResourceBag */
    private $resources;

    private $content = '';

    private $staticFileHandler;

    public function __construct(PGFrameworkServicesHandlersStaticFileHandler $staticFileHandler)
    {
        $this->staticFileHandler = $staticFileHandler;

        $this->resources = new PGServerComponentsResourceBag();
    }

    /**
     * @param PGServerFoundationsAbstractResource $resource
     * @return $this
     * @throws Exception
     */
    public function addResource(PGServerFoundationsAbstractResource $resource)
    {

        $this->resources->add($resource);

        return $this;
    }

    /**
     * @param array $resources
     * @return $this
     * @throws Exception
     */
    public function addResources(PGServerComponentsResourceBag $resources)
    {
        $this->resources->merge($resources);

        return $this;
    }

    /**
     * @return PGServerComponentsResourceBag
     */
    public function getResources()
    {
        return $this->resources;
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
