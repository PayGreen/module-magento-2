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
 * @version   1.2.3
 *
 */

/**
 * Class PGServerComponentsResponsesTemplateResponse
 * @todo Fusionner les notions de path et de name.
 */
class PGServerComponentsResponsesTemplateResponse extends PGServerFoundationsAbstractResponse
{
    /** @var PGServerComponentsResourceBag */
    private $resources;

    private $templatePath;

    private $templateName;

    private $data = array();

    public function __construct($previous)
    {
        parent::__construct($previous);

        $this->resources = new PGServerComponentsResourceBag();
    }

    /**
     * @param string $path
     * @param string $name
     * @return self
     */
    public function setTemplate($path, $name = null)
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
     * @param PGServerFoundationsAbstractResource $resource
     * @return self
     */
    public function addResource(PGServerFoundationsAbstractResource $resource)
    {
        $this->resources->add($resource);

        return $this;
    }

    /**
     * @return PGServerComponentsResourceBag
     */
    public function getResources()
    {
        return $this->resources;
    }
}
