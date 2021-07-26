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
 * @version   2.2.0
 *
 */

namespace PGI\Module\PGPayment\Interfaces\Repositories;

use PGI\Module\PGDatabase\Interfaces\RepositoryInterface;
use PGI\Module\PGPayment\Interfaces\Entities\ButtonEntityInterface;

/**
 * Interface ButtonRepositoryInterface
 * @package PGPayment\Interfaces\Repositories
 */
interface ButtonRepositoryInterface extends RepositoryInterface
{
    /**
     * @param $id int
     * @return ButtonEntityInterface|null
     */
    public function findByPrimary($id);

    /**
     * @return ButtonEntityInterface[]
     */
    public function findAll();

    /**
     * @return ButtonEntityInterface
     */
    public function create();

    /**
     * @return int
     */
    public function count();

    /**
     * @param ButtonEntityInterface $button
     * @return bool
     */
    public function insert(ButtonEntityInterface $button);

    /**
     * @param ButtonEntityInterface $button
     * @return bool
     */
    public function update(ButtonEntityInterface $button);

    /**
     * @param ButtonEntityInterface $button
     * @return bool
     */
    public function delete(ButtonEntityInterface $button);
}
