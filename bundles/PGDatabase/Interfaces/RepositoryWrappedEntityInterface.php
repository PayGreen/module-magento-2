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

namespace PGI\Module\PGDatabase\Interfaces;

use PGI\Module\PGDatabase\Interfaces\EntityWrappedInterface;
use PGI\Module\PGDatabase\Interfaces\RepositoryInterface;

/**
 * Interface RepositoryWrappedEntityInterface
 * @package PGDatabase\Interfaces
 */
interface RepositoryWrappedEntityInterface extends RepositoryInterface
{
    /**
     * @param mixed $localEntity
     * @return EntityWrappedInterface
     */
    public function wrapEntity($localEntity);

    /**
     * @param iterable $localEntities
     * @return EntityWrappedInterface[]
     */
    public function wrapEntities($localEntities);
}
