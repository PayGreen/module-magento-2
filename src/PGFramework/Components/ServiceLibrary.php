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
 * @version   1.0.0
 */

class PGFrameworkComponentsServiceLibrary implements arrayaccess
{
    /** @var PGFrameworkComponentsBag  */
    private $definitions;

    /** @var array */
    private $compiledDefinitions = array();

    /** @var array */
    private $files = array();

    /**
     * PGFrameworkContainer constructor.
     */
    public function __construct()
    {
        $this->buildDefinitionBag();
    }

    private function buildDefinitionBag()
    {
        $this->definitions = new PGFrameworkComponentsBag();

        $this->definitions->setDotSeparator(false);
    }

    public function toArray()
    {
        return $this->definitions->toArray();
    }

    public function reset()
    {
        $this->buildDefinitionBag();

        $filenames = $this->files;

        $this->files = array();

        foreach ($filenames as $filename) {
            $this->addConfigurationFile($filename);
        }
    }

    /**
     * @param string $filename
     * @return $this
     * @throws Exception
     */
    public function addConfigurationFile($filename)
    {
        $data = json_decode(file_get_contents($filename), true);

        $this->files[] = $filename;

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
     * @throws LogicException
     * @throws Exception
     */
    public function getTaggedServices($tagName)
    {
        $findedTags = array();

        $names = array_keys($this->definitions->toArray());

        foreach ($names as $name) {
            $definition = $this->getDefinition($name);

            $isAbstract = array_key_exists('abstract', $definition) && $definition['abstract'];

            if (!$isAbstract && array_key_exists('tags', $definition)) {
                $tags = $definition['tags'];

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

    /**
     * @param string $name
     * @return array|null
     * @throws Exception
     */
    private function getDefinition($name)
    {
        if (array_key_exists($name, $this->compiledDefinitions)) {
            $definition = $this->compiledDefinitions[$name];
        } else {
            $definition = $this->definitions[$name];

            if (array_key_exists('extends', $definition)) {
                $parentName = $definition['extends'];

                if (!isset($this[$parentName])) {
                    throw new Exception("Unknown parent service : $parentName");
                }

                $parentDefinition = $this->getDefinition($parentName);

                $bag = new PGFrameworkComponentsBag($parentDefinition);
                $bag->setDotSeparator(false);

                $bag->merge(array('abstract' => false));
                $bag->merge($definition);

                $definition = $bag->toArray();

                $this->compiledDefinitions[$name] = $definition;
            }
        }

        return $definition;
    }

    public function isAbstract($name)
    {
        $definition = $this[$name];

        return (array_key_exists('abstract', $definition) && ($definition['abstract'] === true));
    }

    public function isShared($name)
    {
        $definition = $this->getDefinition($name);

        return (!array_key_exists('shared', $definition) || ($definition['shared'] !== false));
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
        if (!isset($this[$var])) {
            throw new Exception("Unknown service definition : $var");
        }

        return $this->getDefinition($var);
    }
}
