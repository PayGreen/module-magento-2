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

namespace PGI\Module\PGMagento\Services\Officers;

use Magento\Framework\DB\Adapter\AdapterInterface as LocalAdapterInterface;
use Magento\Framework\App\ObjectManager as LocalObjectManager;
use PGI\Module\PGDatabase\Interfaces\DatabaseOfficerInterface;
use Exception;

class DatabaseOfficer implements DatabaseOfficerInterface
{
    /** @var LocalAdapterInterface */
    private $databaseAdapter;

    public function __construct(LocalObjectManager $magento)
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
