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
 * @version   2.1.1
 *
 */

/**
 * Class PGModuleComponentsEventsOutput
 * @package PGModule\Components\Events
 */
class PGModuleComponentsEventsOutput extends PGModuleFoundationsEvent
{
    /** @var string */
    private $name;

    /** @var PGModuleComponentsOutput */
    private $output;

    private $data = array();

    public function __construct($type, PGModuleComponentsOutput $output, array $data = array())
    {
        $this->name = 'OUTPUT.' . strtoupper($type);

        $this->output = $output;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return PGModuleComponentsOutput
     */
    public function getOutput()
    {
        return $this->output;
    }

    public function getData($key)
    {
        if (!$this->hasData($key)) {
            throw new Exception("Unknown data key : '$key'.");
        }

        return $this->data[$key];
    }

    public function hasData($key)
    {
        return array_key_exists($key, $this->data);
    }
}
