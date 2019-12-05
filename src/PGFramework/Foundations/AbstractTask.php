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

/**
 * Class PGFrameworkFoundationsAbstractTask
 * @package PGFramework\Foundations
 */
abstract class PGFrameworkFoundationsAbstractTask implements PGFrameworkInterfacesTaskInterface
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
}
