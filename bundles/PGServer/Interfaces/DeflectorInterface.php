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

namespace PGI\Module\PGServer\Interfaces;

use PGI\Module\PGServer\Exceptions\HTTP as HTTPException;
use PGI\Module\PGServer\Foundations\AbstractRequest;
use PGI\Module\PGServer\Foundations\AbstractResponse;
use Exception;

/**
 * Interface DeflectorInterface
 * @package PGServer\Interfaces
 */
interface DeflectorInterface
{
    /**
     * @param AbstractRequest $request
     * @return bool
     */
    public function isMatching(AbstractRequest $request);

    /**
     * @param AbstractRequest $request
     * @return AbstractResponse
     * @throws HTTPException
     * @throws Exception
     */
    public function process(AbstractRequest $request);
}
