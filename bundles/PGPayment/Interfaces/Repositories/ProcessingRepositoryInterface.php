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
 * @version   2.3.0
 *
 */

namespace PGI\Module\PGPayment\Interfaces\Repositories;

use PGI\Module\PGDatabase\Interfaces\RepositoryInterface;
use PGI\Module\PGPayment\Interfaces\Entities\ProcessingEntityInterface;

/**
 * Interface ProcessingRepositoryInterface
 * @package PGPayment\Interfaces\Repositories
 */
interface ProcessingRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int $id
     * @return ProcessingEntityInterface|null
     */
    public function findByPrimary($id);

    /**
     * @param string $reference
     * @return ProcessingEntityInterface|null
     */
    public function findSuccessedProcessingByReference($reference);

    /**
     * @param array $data
     * @return ProcessingEntityInterface
     */
    public function create(array $data);

    /**
     * @param ProcessingEntityInterface $processing
     * @return bool
     */
    public function insert(ProcessingEntityInterface $processing);

    /**
     * @param ProcessingEntityInterface $processing
     * @return bool
     */
    public function update(ProcessingEntityInterface $processing);

    /**
     * @param ProcessingEntityInterface $processing
     * @return bool
     */
    public function delete(ProcessingEntityInterface $processing);
}
