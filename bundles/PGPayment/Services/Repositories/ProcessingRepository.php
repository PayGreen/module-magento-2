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
 * @version   2.1.1
 *
 */

/**
 * Class PGPaymentServicesRepositoriesProcessingRepository
 * @package PGPayment\Services\Repositories
 */
class PGPaymentServicesRepositoriesProcessingRepository extends PGDatabaseFoundationsRepositoryDatabase implements PGPaymentInterfacesRepositoriesProcessingRepositoryInterface
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
        /** @var PGPaymentInterfacesEntitiesProcessingInterface $result */
        $result = $this->wrapEntity($data);

        return $result;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function insert(PGPaymentInterfacesEntitiesProcessingInterface $processing)
    {
        return $this->insertEntity($processing);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function update(PGPaymentInterfacesEntitiesProcessingInterface $processing)
    {
        return $this->updateEntity($processing);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function delete(PGPaymentInterfacesEntitiesProcessingInterface $processing)
    {
        return $this->deleteEntity($processing);
    }
}
