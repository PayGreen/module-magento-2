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

namespace PGI\Module\PGServer\Services\Acceptors;

use PGI\Module\PGServer\Foundations\AbstractAcceptor;
use PGI\Module\PGServer\Foundations\AbstractResponse;

/**
 * Class InstanceAcceptor
 * @package PGServer\Services\Acceptors
 */
class InstanceAcceptor extends AbstractAcceptor
{
    public function isValid(AbstractResponse $response, $config)
    {
        $result = false;

        if (is_array($config)) {
            foreach ($config as $class) {
                if ($response instanceof $class) {
                    $result = true;
                    break;
                }
            }
        } else {
            $result = ($response instanceof $config);
        }

        return $result;
    }
}
