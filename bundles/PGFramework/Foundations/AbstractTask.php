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
 * @version   2.5.2
 *
 */

namespace PGI\Module\PGFramework\Foundations;

use PGI\Module\PGFramework\Interfaces\TaskInterface;
use Exception;
use ReflectionClass;

/**
 * Class AbstractTask
 * @package PGFramework\Foundations
 */
abstract class AbstractTask implements TaskInterface
{
    private $status = null;

    /**
     * @inheritdoc
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @inheritdoc
     */
    public function setStatus($code)
    {
        $this->status = $code;
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function getStatusName($code)
    {
        $reflexion = new ReflectionClass($this);

        $constantNames = array_flip($reflexion->getConstants());

        return $constantNames[$code];
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function getStatusCode($name)
    {
        $reflexion = new ReflectionClass($this);

        $constantCodes = $reflexion->getConstants();

        return $constantCodes[$name];
    }
}
