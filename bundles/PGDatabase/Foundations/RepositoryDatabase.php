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
 * @version   2.1.0
 *
 */

/**
 * Class PGDatabaseFoundationsRepositoryPaygreen
 * @package PGDatabase\Foundations
 */
abstract class PGDatabaseFoundationsRepositoryDatabase extends PGDatabaseFoundationsRepository implements PGDatabaseInterfacesRepository
{
    /** @var PGDatabaseServicesDatabaseHandler */
    private $databaseHandler;

    private $config;

    public function __construct(PGDatabaseServicesDatabaseHandler $databaseHandler, array $config)
    {
        $this->databaseHandler = $databaseHandler;
        $this->config = new PGSystemComponentsBag($config);
    }

    /**
     * @return PGDatabaseServicesDatabaseHandler
     */
    protected function getRequester()
    {
        return $this->databaseHandler;
    }

    /**
     * @param array $data
     * @return PGDatabaseInterfacesEntityPersisted
     * @throws Exception
     */
    protected function wrapEntity(array $data = array())
    {
        $className = $this->config['class'];

        $data = $this->unserializeData($data);

        return new $className($data);
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    protected function unserializeData(array $data)
    {
        foreach ($data as $index => $value) {
            if (is_string($index) && (!empty($value))) {
                $data[$index] = $this->unserializeField($index, $value);
            }
        }

        return $data;
    }

    /**
     * @param $key
     * @param $value
     * @throws Exception
     * @return mixed
     */
    protected function unserializeField($key, $value)
    {
        /** @var PGModuleServicesLogger $logger */
        $logger = $this->getService('logger');

        $type = $this->config['fields'][$key]['type'];

        switch ($type) {
            case 'array':
                try {
                    if (is_string($value) && (empty($value))) {
                        return array();
                    }

                    $value = unserialize($value);
                } catch (Exception $exception) {
                    $logger->error("An error occured during '$key' unserialization process.");
                    $value = array();
                }

                if (!is_array($value)) {
                    $type = gettype($value);
                    throw new Exception("Unserialized value is not an array. '$type' found.");
                }
        }

        return $value;
    }

    /**
     * @param $key
     * @param $value
     * @return string|null
     */
    protected function serializeField($key, $value)
    {
        $type = $this->config['fields'][$key]['type'];

        switch ($type) {
            case 'array':
                $value = serialize($value);
                break;
            case 'int':
                $value = (int) $value;
        }

        return $value;
    }

    /**
     * @param array $list
     * @return PGDatabaseInterfacesEntityPersisted[]
     * @throws Exception
     */
    protected function wrapEntities(array $list)
    {
        $entities = array();

        foreach ($list as $data) {
            $entities[] = $this->wrapEntity($data);
        }

        return $entities;
    }

    /**
     * @param int $id
     * @return PGDatabaseInterfacesEntityPersisted|null
     * @throws Exception
     */
    public function findByPrimary($id)
    {
        $where = "`{$this->getPrimaryColumn()}` = '$id'";

        return $this->findOneEntity($where);
    }

    /**
     * @param null $where
     * @return PGDatabaseInterfacesEntityPersisted|null
     * @throws Exception
     */
    protected function findOneEntity($where = null)
    {
        if ($where === null) {
            $where = 1;
        }

        $sql = "SELECT * FROM `{$this->getTableName()}` WHERE $where LIMIT 1;";

        $data = $this->databaseHandler->fetchLine($sql);

        return ($data === null) ? null : $this->wrapEntity($data);
    }

    /**
     * @param null $where
     * @return PGDatabaseInterfacesEntityPersisted[]
     * @throws Exception
     */
    protected function findAllEntities($where = null)
    {
        if ($where === null) {
            $where = 1;
        }

        $sql = "SELECT * FROM `{$this->getTableName()}` WHERE $where;";

        $data = $this->databaseHandler->fetchArray($sql);

        return $this->wrapEntities($data);
    }

    /**
     * @param PGDatabaseInterfacesEntityPersisted $entity
     * @return bool
     * @throws Exception
     */
    protected function insertEntity(PGDatabaseInterfacesEntityPersisted $entity)
    {
        /** @var PGModuleServicesLogger $logger */
        $logger = $this->getService('logger');

        try {
            if ($entity->id() > 0) {
                throw new Exception("Entity already exists : '{$this->getClassName()}#{$entity->id()}'.");
            }

            $columnStatements = array();
            $valueStatements = array();

            $data = $entity->toArray();

            foreach ($this->config['fields'] as $key => $config) {
                $isPrimaryColumn = ($key === $this->getPrimaryColumn());
                $hasCustomValue = array_key_exists($key, $data);
                $hasDefaultValue = array_key_exists('default', $config);

                if (!$isPrimaryColumn && ($hasCustomValue || $hasDefaultValue)) {
                    $value = $hasCustomValue ? $data[$key] : $config['default'];

                    $value = $this->serializeField($key, $value);

                    $columnStatements[] = "`$key`";

                    if ($value === null) {
                        $valueStatements[] = "NULL";
                    } else {
                        $quotedVal = $this->databaseHandler->quote($value);
                        $valueStatements[] = "'$quotedVal'";
                    }
                }
            }

            $columnStatement = join(', ', $columnStatements);
            $valueStatement = join(', ', $valueStatements);

            $sql = "INSERT INTO `{$this->getTableName()}` ($columnStatement) VALUES ($valueStatement);";

            $id = $this->databaseHandler->insert($sql);

            $entity->setPrimary($id);
        } catch (Exception $exception) {
            $logger->critical("Error during inserting entity : " . $exception->getMessage(), $exception);

            throw $exception;
        }

        return true;
    }

    /**
     * @param PGDatabaseInterfacesEntityPersisted $entity
     * @return bool
     * @throws Exception
     */
    protected function updateEntity(PGDatabaseInterfacesEntityPersisted $entity)
    {
        /** @var PGModuleServicesLogger $logger */
        $logger = $this->getService('logger');

        try {
            if (!$entity->id()) {
                throw new Exception("Entity never created : '{$this->getClassName()}'.");
            }

            $updateStatements = array();

            $data = $entity->toArray();

            foreach ($this->config['fields'] as $key => $config) {
                $isPrimaryColumn = ($key === $this->getPrimaryColumn());
                $hasCustomValue = array_key_exists($key, $data);
                $hasDefaultValue = array_key_exists('default', $config);

                if (!$isPrimaryColumn && ($hasCustomValue || $hasDefaultValue)) {
                    $value = $hasCustomValue ? $data[$key] : $config['default'];

                    $value = $this->serializeField($key, $value);

                    if ($value === null) {
                        $updateStatements[] = "`$key` = NULL";
                    } else {
                        $quotedVal = $this->databaseHandler->quote($value);
                        $updateStatements[] = "`$key` = '$quotedVal'";
                    }
                }
            }

            $updateStatement = join(', ', $updateStatements);

            $sql = "
                UPDATE `{$this->getTableName()}`
                SET $updateStatement
                WHERE `{$this->getPrimaryColumn()}` = {$entity->id()};";

            return $this->databaseHandler->execute($sql);
        } catch (Exception $exception) {
            $logger->critical("Error during updating entity : " . $exception->getMessage(), $exception);

            throw $exception;
        }
    }

    /**
     * @param PGDatabaseInterfacesEntityPersisted $entity
     * @return bool
     * @throws Exception
     */
    protected function deleteEntity(PGDatabaseInterfacesEntityPersisted $entity)
    {
        /** @var PGModuleServicesLogger $logger */
        $logger = $this->getService('logger');

        try {
            if (!$entity->id()) {
                throw new Exception("Entity never created : '{$this->getClassName()}'.");
            }

            $sql = "DELETE FROM `{$this->getTableName()}` WHERE `{$this->getPrimaryColumn()}` = {$entity->id()};";

            return $this->databaseHandler->execute($sql);
        } catch (Exception $exception) {
            $logger->critical(
                "Error during deleting entity '{$this->getClassName()}' : " . $exception->getMessage(),
                $exception
            );

            throw $exception;
        }
    }

    protected function getTableName()
    {
        return $this->config['table'];
    }

    private function getClassName()
    {
        return $this->config['class'];
    }

    private function getPrimaryColumn()
    {
        return $this->config['primary'];
    }
}
