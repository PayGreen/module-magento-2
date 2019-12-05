<?php
/**
 * 2014 - 2019 Watt Is It
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
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.5
 */

class PGFrameworkComponentsResponsesTemplateResponse
{
    private static $VALID_RESOURCE_TYPES = array('JS', 'CSS');

    private $resources = array();

    private $templatePath;

    private $templateName;

    private $data = array();

    private $namespace;

    /**
     * PGFrameworkComponentsResponsesHTMLResponse constructor.
     * @param string|null $namespace
     */
    public function __construct($namespace = null)
    {
        $this->namespace = $namespace;
    }

    /**
     * @param string $path
     * @param string $name
     * @return self
     */
    public function setTemplate($path, $name)
    {
        $this->templatePath = $path;
        $this->templateName = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    /**
     * @return mixed
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }

    /**
     * @param array $data
     * @return self
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param string $key
     * @param mixed $val
     * @return self
     */
    public function addData($key, $val)
    {
        $this->data[$key] = $val;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string|null
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $type
     * @param string $src
     * @return self
     */
    public function addResource($type, $src)
    {
        if (!in_array(strtoupper($type), self::$VALID_RESOURCE_TYPES)) {
            throw new LogicException("Unrecognized resource type : '$type'.");
        }

        $this->resources[] = array(
            'type' => strtoupper($type),
            'src' => $src
        );

        return $this;
    }

    /**
     * @return array
     */
    public function getResources()
    {
        return $this->resources;
    }

    public function count($type)
    {
        if (!in_array(strtoupper($type), self::$VALID_RESOURCE_TYPES)) {
            throw new LogicException("Unrecognized link type : '$type'.");
        }

        $nb = 0;

        foreach ($this->resources as $resource) {
            if ($resource['type'] === $type) {
                $nb ++;
            }
        }

        return $nb;
    }
}
