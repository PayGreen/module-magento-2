<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.6.0
 *
 */

namespace PGI\Module\PGModule\Components\Events;

use PGI\Module\PGModule\Components\Output as OutputComponent;
use PGI\Module\PGModule\Foundations\AbstractEvent;
use Exception;

/**
 * Class Output
 * @package PGModule\Components\Events
 */
class Output extends AbstractEvent
{
    /** @var string */
    private $name;

    /** @var OutputComponent */
    private $output;

    private $data = array();

    public function __construct($type, OutputComponent $output, array $data = array())
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
     * @return OutputComponent
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
