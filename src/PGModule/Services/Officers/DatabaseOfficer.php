<?php
/**
 * 2014 - 2020 Watt Is It
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
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.0.0
 */

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\App\ObjectManager;

class PGModuleServicesOfficersDatabaseOfficer implements PGFrameworkInterfacesOfficersDatabaseOfficerInterface
{
    /** @var AdapterInterface */
    private $databaseAdapter;

    public function __construct(ObjectManager $magento)
    {
        $this->databaseAdapter = $magento->get('Magento\Framework\App\ResourceConnection')->getConnection();
    }

    public function quote($value)
    {
        $value = $this->databaseAdapter->quote($value);

        if (preg_match("/^'.*'$/", $value)) {
            return substr($value, 1, strlen($value) - 2);
        } else {
            return $value;
        }
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function execute($sql)
    {
        return $this->databaseAdapter->query($sql);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function insert($sql)
    {
        $this->databaseAdapter->query($sql);

        return $this->databaseAdapter->lastInsertId();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function fetch($sql)
    {
        return $this->databaseAdapter->fetchAll($sql);
    }
}
