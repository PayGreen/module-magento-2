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
 * @version   2.4.0
 *
 */

namespace PGI\Module\PGModule\Components;

use PGI\Module\PGServer\Components\ResourceBag as ResourceBagComponent;
use PGI\Module\PGServer\Foundations\AbstractResourceBasic;

/**
 * Class Output
 * @package PGModule\Components
 */
class Output
{
    /** @var ResourceBagComponent */
    private $resources;

    private $content = '';

    public function __construct($content = '')
    {
        $this->content = $content;
        $this->resources = new ResourceBagComponent();
    }

    /**
     * @param AbstractResourceBasic $resource
     * @return $this
     */
    public function addResource(AbstractResourceBasic $resource)
    {
        $this->resources->add($resource);

        return $this;
    }

    /**
     * @param ResourceBagComponent $resources
     * @return $this
     */
    public function addResources(ResourceBagComponent $resources)
    {
        $this->resources->merge($resources);

        return $this;
    }

    /**
     * @return ResourceBagComponent
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
    public function addContentBefore($content)
    {
        $this->content = $content . $this->content;

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

    /**
     * @param Output $output
     */
    public function merge(Output $output)
    {
        $this->addContent($output->getContent());
        $this->addResources($output->getResources());
    }
}
