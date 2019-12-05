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

class PGFrameworkComponentsServiceLibrary implements arrayaccess
{
    /** @var PGFrameworkComponentsBag  */
    private $definitions;

    /**
     * PGFrameworkContainer constructor.
     */
    public function __construct()
    {
        $this->definitions = new PGFrameworkComponentsBag();

        $this->definitions->setDotSeparator(false);
    }

    public function toArray()
    {
        return $this->definitions->toArray();
    }

    /**
     * @param string $filename
     * @return $this
     * @throws Exception
     */
    public function addConfigurationFile($filename)
    {
        $data = json_decode(file_get_contents($filename), true);

        if (!$data) {
            throw new Exception("Unable to load service definition file : '$filename'.");
        }

        $this->definitions->merge($data);

        return $this;
    }

    /**
     * @param string $path
     * @return $this
     * @throws Exception
     */
    public function addConfigurationFolder($path)
    {
        if (!is_dir($path)) {
            throw new Exception("Configuration folder not found : '$path'.");
        }

        foreach (glob($path . DIRECTORY_SEPARATOR . '*.json') as $filename) {
            $this->addConfigurationFile($filename);
        }

        return $this;
    }

    /**
     * @param string $tagName
     * @return array
     */
    public function getTaggedServices($tagName)
    {
        $findedTags = array();

        foreach ($this->definitions->toArray() as $name => $serviceDefinition) {
            if (array_key_exists('tags', $serviceDefinition)) {
                $tags = $serviceDefinition['tags'];

                if (!is_array($tags)) {
                    $message = "Target service definition has inconsistent 'tags' options : '$name'.";
                    throw new LogicException($message);
                }

                foreach ($tags as $tag) {
                    $findedTag = $this->getValidatedTag($name, $tag, $tagName);

                    if ($findedTag !== null) {
                        $findedTags[] = $findedTag;
                    }
                }
            }
        }

        return $findedTags;
    }

    protected function getValidatedTag($name, $tag, $searchedTag)
    {
        if (!is_array($tag)) {
            $message = "Target service definition has inconsistent tag : '$name'.";
            throw new LogicException($message);
        } elseif (!array_key_exists('name', $tag)) {
            $message = "Target service definition has tag without 'name' parameter : '$name'.";
            throw new LogicException($message);
        } elseif ($tag['name'] === $searchedTag) {
            if (array_key_exists('options', $tag)) {
                if (!is_array($tag)) {
                    $message = "Target service definition has tag with inconsistent 'options' parameter : '$name'.";
                    throw new LogicException($message);
                }

                $options = $tag['options'];
            } else {
                $options = array();
            }

            return array(
                'name' => $name,
                'options' => $options
            );
        }

        return null;
    }

    // ###################################################################
    // ###       sous-fonctions d'accès par tableau
    // ###################################################################

    public function offsetSet($var, $value)
    {
        throw new Exception("Can not manually add a service definition.");
    }
    public function offsetExists($var)
    {
        return isset($this->definitions[$var]);
    }
    public function offsetUnset($var)
    {
        throw new Exception("Can not manually delete a service definition.");
    }
    public function offsetGet($var)
    {
        return $this->definitions[$var];
    }
}