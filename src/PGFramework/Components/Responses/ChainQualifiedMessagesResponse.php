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

class PGFrameworkComponentsResponsesChainQualifiedMessagesResponse
{
    const SUCCESS = 'SUCCESS';
    const NOTICE = 'NOTICE';
    const FAILURE = 'FAILURE';

    const DEFAULT_TYPE = self::SUCCESS;

    private static $VALID_TYPES = array(self::SUCCESS, self::NOTICE, self::FAILURE);

    private $messages = array();

    public function __construct($message = null)
    {
        if ($message !== null) {
            $this->add($message);
        }
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    public function add($type, $text = null)
    {
        if ($text === null) {
            $text = $type;
            $type = self::DEFAULT_TYPE;
        }

        if (!in_array(strtoupper($type), self::$VALID_TYPES)) {
            throw new LogicException("Unrecognized message type : '$type'.");
        }

        $this->messages[] = array(
            'type' => $type,
            'text' => $text
        );

        return $this;
    }

    public function count($type)
    {
        if (!in_array(strtoupper($type), self::$VALID_TYPES)) {
            throw new LogicException("Unrecognized message type : '$type'.");
        }

        $nb = 0;

        foreach ($this->messages as $message) {
            if ($message['type'] === $type) {
                $nb ++;
            }
        }

        return $nb;
    }
}
