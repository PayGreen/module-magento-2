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
 * @version   2.0.1
 *
 */

/**
 * Class PGSystemComponentsServiceLibrary
 * @package PGSystem\Components\Service
 */
class PGSystemComponentsServiceLibrary implements arrayaccess
{
    /** @var PGSystemComponentsBag  */
    private $definitions;

    /** @var string */
    private $source;

    private $bin;

    /**
     * PGSystemServicesContainer constructor.
     */
    public function __construct()
    {
        $this->buildDefinitionBag();
    }

    /**
     * @param string $source
     * @return self
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    private function buildDefinitionBag()
    {
        $this->definitions = new PGSystemComponentsBag();

        $this->definitions->setDotSeparator(false);
    }

    public function toArray()
    {
        return $this->definitions->toArray();
    }

    public function reset()
    {
        $this->buildDefinitionBag();

        $this->definitions->merge(require($this->source));
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
        return $this->definitions[$name];
    }

    public function isShared($name)
    {
        $definition = $this->getDefinition($name);

        return (!array_key_exists('shared', $definition) || ($definition['shared'] !== false));
    }

    public function isStatic($name)
    {
        $definition = $this->getDefinition($name);

        return (array_key_exists('static', $definition) && ($definition['static'] === true));
    }

    // ###################################################################
    // ###       sous-fonctions d'accès par tableau
    // ###################################################################

    public function offsetSet($var, $value)
    {
        // Thrashing unused arguments
        $this->bin = array($var, $value);

        throw new Exception("Can not manually add a service definition.");
    }
    public function offsetExists($var)
    {
        return isset($this->definitions[$var]);
    }
    public function offsetUnset($var)
    {
        // Thrashing unused arguments
        $this->bin = $var;

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
