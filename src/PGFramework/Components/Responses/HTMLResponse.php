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
 */

class PGFrameworkComponentsResponsesHTMLResponse extends PGFrameworkComponentsResponsesPlainTextResponse
{
    private static $VALID_TYPES = array('JS', 'CSS');

    private $links = array();

    public function addLink($type, $src)
    {
        if (!in_array(strtoupper($type), self::$VALID_TYPES)) {
            throw new LogicException("Unrecognized link type : '$type'.");
        }

        $this->links[] = array(
            'type' => strtoupper($type),
            'src' => $src
        );

        return $this;
    }

    /**
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    public function count($type)
    {
        if (!in_array(strtoupper($type), self::$VALID_TYPES)) {
            throw new LogicException("Unrecognized link type : '$type'.");
        }

        $nb = 0;

        foreach ($this->links as $link) {
            if ($link['type'] === $type) {
                $nb ++;
            }
        }

        return $nb;
    }
}
