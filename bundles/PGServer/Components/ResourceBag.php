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
 * @version   2.5.1
 *
 */

namespace PGI\Module\PGServer\Components;

use PGI\Module\PGServer\Foundations\AbstractResourceBasic;

/**
 * Class ResourceBag
 * @package PGServer\Components
 */
class ResourceBag
{
    /** @var AbstractResourceBasic[] */
    private $resources = array();

    /**
     * @param AbstractResourceBasic $resource
     * @return self
     */
    public function add(AbstractResourceBasic $resource)
    {
        $this->resources[] = $resource;

        return $this;
    }

    public function merge(ResourceBag $resources)
    {
        foreach ($resources->get() as $resource) {
            $this->add($resource);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this->resources;
    }

    /**
     * @param string $type
     * @return array
     */
    public function find($type)
    {
        $resources = array();

        foreach ($this->resources as $resource) {
            if ($resource::NAME === $type) {
                $resources[] = $resource;
            }
        }

        return $resources;
    }

    /**
     * @param string|null $type
     * @return int
     */
    public function count($type = null)
    {
        if ($type === null) {
            $nb = count($this->resources);
        } else {
            $nb = count($this->find($type));
        }

        return $nb;
    }
}
