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

class PGServerComponentsResourceBag
{
    /** @var PGServerFoundationsAbstractResource[] */
    private $resources = array();

    /**
     * @param PGServerFoundationsAbstractResource $resource
     * @return self
     */
    public function add(PGServerFoundationsAbstractResource $resource)
    {
        $this->resources[] = $resource;

        return $this;
    }

    public function merge(PGServerComponentsResourceBag $resources)
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
