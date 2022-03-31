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
 * @version   2.6.1
 *
 */

namespace PGI\Module\PGServer\Components;

use PGI\Module\PGServer\Foundations\AbstractAcceptor;
use PGI\Module\PGServer\Foundations\AbstractResponse;

/**
 * Class Trigger
 * @package PGServer\Components
 */
class Trigger
{
    private $steps = array();

    public function addAcceptor(AbstractAcceptor $acceptor, $config)
    {
        $this->steps[] = array($acceptor, $config);
    }

    public function isTriggered(AbstractResponse $response)
    {
        /** @var AbstractAcceptor $acceptor */
        foreach ($this->steps as $step) {
            /**
             * @var AbstractAcceptor $acceptor
             * @var array $config
             */
            list($acceptor, $config) = $step;

            if (!$acceptor->isValid($response, $config)) {
                return false;
            }
        }

        return true;
    }
}
