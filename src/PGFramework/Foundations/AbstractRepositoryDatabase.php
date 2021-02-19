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
 * @version   1.2.3
 *
 */

/**
 * Class PGFrameworkFoundationsAbstractRepositoryPaygreen
 * @package PGFramework\Foundations
 */
abstract class PGFrameworkFoundationsAbstractRepositoryDatabase extends PGFrameworkFoundationsAbstractRepository implements PGFrameworkInterfacesRepositoryInterface
{
    /** @var PGFrameworkServicesHandlersDatabaseHandler */
    private $databaseHandler;

    private $config;

    public function __construct(PGFrameworkServicesHandlersDatabaseHandler $databaseHandler, array $config)
    {
        $this->databaseHandler = $databaseHandler;
        $this->config = new PGFrameworkComponentsBag($config);
    }

    /**
     * @return PGFrameworkServicesHandlersDatabaseHandler
     */
    protected function getRequester()
    {
        return $this->databaseHandler;
    }

    /**
     * @param array $data
     * @return PGFrameworkInterfacesPersistedEntityInterface
     */
    protected function wrapEntity(array $data = array())
    {
        $className = $this->config['class'];

        return new $className($data);
    }

    /**
     * @param array $list
     * @return PGFrameworkInterfacesPersistedEntityInterface[]
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
     * @return PGFrameworkInterfacesPersistedEntityInterface|null
     * @throws Exception
     */
    public function findByPrimary($id)
    {
        $where = "`{$this->getPrimaryColumn()}` = '$id'";

        return $this->findOneEntity($where);
    }

    /**
     * @param null $where
     * @return PGFrameworkInterfacesPersistedEntityInterface|null
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
     * @return PGFrameworkInterfacesPersistedEntityInterface[]
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
     * @param PGFrameworkInterfacesPersistedEntityInterface $entity
     * @return bool
     * @throws Exception
     */
    protected function insertEntity(PGFrameworkInterfacesPersistedEntityInterface $entity)
    {
        /** @var PGFrameworkServicesLogger $logger */
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
     * @param PGFrameworkInterfacesPersistedEntityInterface $entity
     * @return bool
     * @throws Exception
     */
    protected function updateEntity(PGFrameworkInterfacesPersistedEntityInterface $entity)
    {
        /** @var PGFrameworkServicesLogger $logger */
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

                    if ($value === null) {
                        $updateStatements[] = "`$key` = NULL";
                    } else {
                        $quotedVal = $this->databaseHandler->quote($value);
                        $updateStatements[] = "`$key` = '$quotedVal'";
                    }
                }
            }

            $updateStatement = join(', ', $updateStatements);

            $sql = "UPDATE `{$this->getTableName()}` SET $updateStatement WHERE `{$this->getPrimaryColumn()}` = {$entity->id()};";

            return $this->databaseHandler->execute($sql);
        } catch (Exception $exception) {
            $logger->critical("Error during updating entity : " . $exception->getMessage(), $exception);

            throw $exception;
        }
    }

    /**
     * @param PGFrameworkInterfacesPersistedEntityInterface $entity
     * @return bool
     * @throws Exception
     */
    protected function deleteEntity(PGFrameworkInterfacesPersistedEntityInterface $entity)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        try {
            if (!$entity->id()) {
                throw new Exception("Entity never created : '{$this->getClassName()}'.");
            }

            $sql = "DELETE FROM `{$this->getTableName()}` WHERE `{$this->getPrimaryColumn()}` = {$entity->id()};";

            return $this->databaseHandler->execute($sql);
        } catch (Exception $exception) {
            $logger->critical("Error during deleting entity '{$this->getClassName()}' : " . $exception->getMessage(), $exception);

            throw $exception;
        }
    }

    private function getClassName()
    {
        return $this->config['class'];
    }

    private function getTableName()
    {
        return $this->config['table'];
    }

    private function getPrimaryColumn()
    {
        return $this->config['primary'];
    }
}
