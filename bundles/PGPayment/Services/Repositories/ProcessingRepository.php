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

namespace PGI\Module\PGPayment\Services\Repositories;

use PGI\Module\PGDatabase\Foundations\AbstractRepositoryDatabase;
use PGI\Module\PGPayment\Interfaces\Entities\ProcessingEntityInterface;
use PGI\Module\PGPayment\Interfaces\Repositories\ProcessingRepositoryInterface;
use Exception;

/**
 * Class ProcessingRepository
 * @package PGPayment\Services\Repositories
 */
class ProcessingRepository extends AbstractRepositoryDatabase implements ProcessingRepositoryInterface
{
    /**
     * @inheritdoc
     * @throws Exception
     */
    public function findSuccessedProcessingByReference($reference)
    {
        $quotedReference = $this->getRequester()->quote($reference);

        return $this->findOneEntity("`reference` = '$quotedReference' AND `success` = 1");
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function create(array $data)
    {
        /** @var ProcessingEntityInterface $result */
        $result = $this->wrapEntity($data);

        return $result;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function insert(ProcessingEntityInterface $processing)
    {
        return $this->insertEntity($processing);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function update(ProcessingEntityInterface $processing)
    {
        return $this->updateEntity($processing);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function delete(ProcessingEntityInterface $processing)
    {
        return $this->deleteEntity($processing);
    }
}
