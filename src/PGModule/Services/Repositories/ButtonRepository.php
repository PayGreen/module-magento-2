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
 */

/**
 * Class PGModuleServicesRepositoriesButtonRepository
 * @package PGModule\Services\Repositories
 */
class PGModuleServicesRepositoriesButtonRepository extends PGModuleFoundationsAbstractMagentoRepository implements PGDomainInterfacesRepositoriesButtonRepositoryInterface
{
    const ENTITY = 'Paygreen\Payment\Model\Button';
    const RESOURCE = 'Paygreen\Payment\Model\ResourceModel\Button';

    public function wrapEntity($localEntity)
    {
        return new PGModuleEntitiesButton($localEntity);
    }

    public function findAll()
    {
        /** @var Paygreen\Payment\Model\ResourceModel\Button\Collection $collection */
        $collection = $this->getService('magento')->create('Paygreen\Payment\Model\ResourceModel\Button\Collection');

        return $this->wrapEntities($collection);
    }

    /**
     * @return PGDomainInterfacesEntitiesButtonInterface
     */
    public function create()
    {
        $localEntity = $this->createLocalEntity([
            'paymentNumber' => 1,
            'displayType' => 'DEFAULT',
            'integration' => 'EXTERNAL',
            'paymentType' => 'CB',
            'paymentMode' => 'CASH',
            'height' => 60
        ]);

        return $this->wrapEntity($localEntity);
    }

    /**
     * @param PGDomainInterfacesEntitiesButtonInterface $button
     * @return bool
     * @throws Exception
     */
    public function insert(PGDomainInterfacesEntitiesButtonInterface $button)
    {
        return $this->insertLocalEntity($button->getLocalEntity());
    }

    /**
     * @param PGDomainInterfacesEntitiesButtonInterface $button
     * @return bool
     * @throws Exception
     */
    public function update(PGDomainInterfacesEntitiesButtonInterface $button)
    {
        return $this->updateLocalEntity($button->getLocalEntity());
    }

    /**
     * @param PGDomainInterfacesEntitiesButtonInterface $button
     * @return bool
     * @throws Exception
     */
    public function delete(PGDomainInterfacesEntitiesButtonInterface $button)
    {
        return $this->deleteLocalEntity($button->getLocalEntity());
    }

    public function count()
    {
        return (int) $this->getDatabaseHandler()->fetchOne("SELECT COUNT(*) AS value FROM {$this->getTable()}");
    }
}
