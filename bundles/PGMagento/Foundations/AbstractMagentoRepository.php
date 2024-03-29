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

namespace PGI\Module\PGMagento\Foundations;

use Magento\Framework\Exception\LocalizedException as LocalLocalizedException;
use Magento\Framework\ObjectManagerInterface as LocalObjectManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb as LocalAbstractDb;
use Magento\Framework\Model\AbstractModel as LocalAbstractModel;
use PGI\Module\PGDatabase\Interfaces\RepositoryWrappedEntityInterface;
use PGI\Module\PGDatabase\Services\Handlers\DatabaseHandler;
use PGI\Module\PGLog\Interfaces\LoggerInterface;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use Exception;

/**
 * Class AbstractMagentoRepository
 * @package PGMagento\Foundations
 */
abstract class AbstractMagentoRepository extends AbstractObject implements RepositoryWrappedEntityInterface
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

        /** @var LocalAbstractModel $localEmptyEntity */
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
     * @return LocalAbstractDb
     */
    protected function getResourceObject()
    {
        /** @var LocalObjectManagerInterface $objectManager */
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
        /** @var LocalAbstractDb $resource */
        $resource = $this->getResourceObject();

        /** @var LocalAbstractModel $entity */
        $entity = $this->createLocalEntity();

        $resource->load($entity, $data, $field);

        return ($entity->getId() !== null) ? $this->wrapEntity($entity) : null;
    }

    protected function createLocalEntity(array $data = [])
    {
        /** @var LocalObjectManagerInterface $objectManager */
        $objectManager = $this->getService('magento');

        $factory = $objectManager->get($this->getEntityClass() . 'Factory');

        /** @var LocalAbstractModel $localEntity */
        $localEntity = $factory->create();

        foreach($data as $key => $val) {
            $localEntity->setData($key, $val);
        }

        return $localEntity;
    }

    /**
     * @param LocalAbstractModel $localEntity
     * @return bool
     * @throws Exception
     */
    protected function insertLocalEntity(LocalAbstractModel $localEntity)
    {
        /** @var LoggerInterface $logger */
        $logger = $this->getService('logger');

        /** @var LocalAbstractDb $resource */
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
     * @param LocalAbstractModel $localEntity
     * @return bool
     * @throws Exception
     */
    protected function updateLocalEntity(LocalAbstractModel $localEntity)
    {
        /** @var LoggerInterface $logger */
        $logger = $this->getService('logger');

        /** @var LocalAbstractDb $resource */
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
     * @param LocalAbstractModel $localEntity
     * @return bool
     * @throws Exception
     */
    protected function deleteLocalEntity(LocalAbstractModel $localEntity)
    {
        /** @var LoggerInterface $logger */
        $logger = $this->getService('logger');

        /** @var LocalAbstractDb $resource */
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
     * @throws LocalLocalizedException
     */
    protected function getTable()
    {
        return $this->getResourceObject()->getMainTable();
    }

    /**
     * @return DatabaseHandler
     */
    protected function getDatabaseHandler()
    {
        /** @var DatabaseHandler $databaseHandler */
        $databaseHandler = $this->getService('handler.database');

        return $databaseHandler;
    }
}
