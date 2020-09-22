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
 * @version   1.1.1
 */

class PGServerComponentsResponsesHTMLResponse extends PGServerFoundationsAbstractResponse
{
    private static $VALID_RESOURCE_TYPES = array('JS', 'CSS');

    private $resources = array();

    private $content = '';

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
     * @param string $type
     * @param string $src
     * @return self
     */
    public function addResource($type, $src)
    {
        $this->validResourceType($type);

        $this->resources[] = array(
            'type' => strtoupper($type),
            'src' => $src
        );

        return $this;
    }

    /**
     * @param string|null $type
     * @return array
     */
    public function getResources($type = null)
    {
        if ($type === null) {
            return $this->resources;
        } else {
            $this->validResourceType($type);

            $resources = array();

            foreach ($this->resources as $resource) {
                if ($resource['type'] === $type) {
                    $resources[] = $resource;
                }
            }

            return $resources;
        }
    }

    /**
     * @param string|null $type
     * @return int
     */
    public function count($type = null)
    {
        if ($type === null) {
            return count($this->resources);
        } else {
            $this->validResourceType($type);

            $nb = 0;

            foreach ($this->resources as $resource) {
                if ($resource['type'] === $type) {
                    $nb++;
                }
            }

            return $nb;
        }
    }

    protected function validResourceType($type)
    {
        if (!in_array(strtoupper($type), self::$VALID_RESOURCE_TYPES)) {
            throw new LogicException("Unrecognized link type : '$type'.");
        }
    }
}
