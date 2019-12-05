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
 * @version   0.3.4
 */

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\AbstractModel;

/**
 * Class PGModuleFoundationsAbstractMagentoRepository
 * @package PGModule\Foundations
 */
abstract class PGModuleFoundationsAbstractMagentoRepository extends PGFrameworkFoundationsAbstractObject implements PGFrameworkInterfacesRepositoryWrappedEntityInterface
{
    /**
     * @inheritdoc
     */
    abstract public function wrapEntity($localEntity);

    /**
     * @inheritdoc
     */
    public function wrapEntities($localEntities)
    {
        $entities = array();

        foreach ($localEntities as $localEmptyEntity) {
            $entities[] = $this->findByPrimary($localEmptyEntity->getId());
        }

        return $entities;
    }

    /**
     * @return string
     */
    protected function getEntityClass()
    {
        return static::ENTITY;
    }

    /**
     * @return AbstractDb
     */
    protected function getResourceObject()
    {
        /** @var ObjectManagerInterface $objectManager */
        $objectManager = $this->getService('magento');

        return $objectManager->get(static::RESOURCE);
    }

    /**
     * @inheritDoc
     */
    public function findByPrimary($id)
    {
        return $this->findByField(null, $id);
    }

    protected function findByField($field, $data)
    {
        /** @var AbstractDb $resource */
        $resource = $this->getResourceObject();

        $entity = $this->createLocalEntity();

        $resource->load($entity, $data, $field);

        return ($entity->getId() !== null) ? $this->wrapEntity($entity) : null;
    }

    protected function createLocalEntity(array $data = [])
    {
        /** @var ObjectManagerInterface $objectManager */
        $objectManager = $this->getService('magento');

        $factory = $objectManager->get($this->getEntityClass() . 'Factory');

        $localEntity = $factory->create();

        foreach($data as $key => $val) {
            $localEntity->setData($key, $val);
        }

        return $localEntity;
    }

    /**
     * @param AbstractModel $localEntity
     * @return bool
     * @throws Exception
     */
    protected function insertLocalEntity(AbstractModel $localEntity)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        /** @var AbstractDb $resource */
        $resource = $this->getResourceObject();

        $primaryColumn = $resource->getIdFieldName();
        $entityClass = $this->getEntityClass();

        try {
            if ($localEntity->getData($primaryColumn) > 0) {
                throw new Exception("Local entity already exists : '$entityClass#{$localEntity->getData($primaryColumn)}'.");
            }

            $resource->save($localEntity);
        } catch (Exception $exception) {
            $logger->critical("Error during inserting entity : " . $exception->getMessage(), $exception);

            throw $exception;
        }

        return true;
    }

    /**
     * @param AbstractModel $localEntity
     * @return bool
     * @throws Exception
     */
    protected function updateLocalEntity(AbstractModel $localEntity)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        /** @var AbstractDb $resource */
        $resource = $this->getResourceObject();

        $primaryColumn = $resource->getIdFieldName();
        $entityClass = $this->getEntityClass();

        try {
            if (!$localEntity->getData($primaryColumn)) {
                throw new Exception("Local entity never inserted : '$entityClass'.");
            }

            $resource->save($localEntity);
        } catch (Exception $exception) {
            $logger->critical("Error during updating entity : " . $exception->getMessage(), $exception);

            throw $exception;
        }

        return true;
    }

    /**
     * @param AbstractModel $localEntity
     * @return bool
     * @throws Exception
     */
    protected function deleteLocalEntity(AbstractModel $localEntity)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        /** @var AbstractDb $resource */
        $resource = $this->getResourceObject();

        $primaryColumn = $resource->getIdFieldName();
        $entityClass = $this->getEntityClass();

        try {
            if (!$localEntity->getData($primaryColumn)) {
                throw new Exception("Local entity never inserted : '$entityClass'.");
            }

            $resource->delete($localEntity);
        } catch (Exception $exception) {
            $logger->critical("Error during deleting entity : " . $exception->getMessage(), $exception);

            throw $exception;
        }

        return true;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getTable()
    {
        return $this->getResourceObject()->getMainTable();
    }

    /**
     * @return PGModuleServicesHandlersDatabaseHandler
     */
    protected function getDatabaseHandler()
    {
        /** @var PGModuleServicesHandlersDatabaseHandler $databaseHandler */
        $databaseHandler = $this->getService('handler.database');

        return $databaseHandler;
    }
}
