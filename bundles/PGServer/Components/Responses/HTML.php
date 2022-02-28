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

namespace PGI\Module\PGServer\Components\Responses;

use PGI\Module\PGServer\Components\ResourceBag as ResourceBagComponent;
use PGI\Module\PGServer\Foundations\AbstractResourceBasic;
use PGI\Module\PGServer\Foundations\AbstractResponse;

/**
 * Class HTML
 * @package PGServer\Components\Responses
 */
class HTML extends AbstractResponse
{
    /** @var ResourceBagComponent */
    private $resources;

    private $content = '';

    public function __construct($previous)
    {
        parent::__construct($previous);

        $this->resources = new ResourceBagComponent();
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    public function addContent($content)
    {
        $this->content .= $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param AbstractResourceBasic $resource
     * @return self
     */
    public function addResource(AbstractResourceBasic $resource)
    {
        $this->resources->add($resource);

        return $this;
    }

    /**
     * @return ResourceBagComponent
     */
    public function getResources()
    {
        return $this->resources;
    }
}
