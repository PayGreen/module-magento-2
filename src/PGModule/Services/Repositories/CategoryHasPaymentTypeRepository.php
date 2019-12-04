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
 * @version   0.3.2
 */

class PGModuleServicesRepositoriesCategoryHasPaymentTypeRepository extends PGModuleFoundationsAbstractMagentoRepository implements PGDomainInterfacesRepositoriesCategoryHasPaymentTypeRepositoryInterface
{
    const ENTITY = 'Paygreen\Payment\Model\CategoryHasPaymentType';
    const RESOURCE = 'Paygreen\Payment\Model\ResourceModel\CategoryHasPaymentType';

    /**
     * @param PGLocalEntitiesCategoryHasPaymentType $localEntity
     * @return PGModuleEntitiesCategoryHasPaymentType
     */
    public function wrapEntity($localEntity)
    {
        return new PGModuleEntitiesCategoryHasPaymentType($localEntity);
    }

    public function findAll()
    {
        /** @var Paygreen\Payment\Model\ResourceModel\CategoryHasPaymentType\Collection $collection */
        $collection = $this->getService('magento')->create('Paygreen\Payment\Model\ResourceModel\CategoryHasPaymentType\Collection');

        return $this->wrapEntities($collection);
    }

    /**
     * @inheritdoc
     */
    public function findCategoriesByPaymentType($mode)
    {
        $sql = "SELECT id_category FROM {$this->getTable()} WHERE payment = '$mode'";

        return $this->getDatabaseHandler()->fetchColumn($sql);
    }

    /**
     * @inheritdoc
     */
    public function truncate()
    {
        $sql = "TRUNCATE {$this->getTable()}";

        return $this->getDatabaseHandler()->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function saveAll($data)
    {
        $sql = "INSERT INTO {$this->getTable()} (`id_category`, `payment`) VALUES ";

        $values = array();
        foreach ($data as $row) {
            $values[] = "('{$row['id_category']}', '{$row['payment']}')";
        }

        $sql .= implode(', ', $values);

        return !empty($values) ? $this->getDatabaseHandler()->execute($sql) : true;
    }
}
