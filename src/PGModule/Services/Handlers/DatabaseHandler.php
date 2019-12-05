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

use Magento\Framework\DB\Adapter\AdapterInterface;

class PGModuleServicesHandlersDatabaseHandler extends PGFrameworkFoundationsAbstractObject
{
    /** @var PGFrameworkComponentsParser */
    private $parser;

    /** @var PGFrameworkComponentsParameters */
    private $parameters;

    public function __construct(PGFrameworkComponentsParameters $parameters, PGFrameworkComponentsParser $parser)
    {
        $this->parameters = $parameters;
        $this->parser = $parser;
    }

    public function runScript($script)
    {
        /** @var PGFrameworkServicesPathfinder $pathfinder */
        $pathfinder = $this->getService('pathfinder');

        $src = $pathfinder->toAbsolutePath('module-resources', "/sql/$script");

        $sql = file_get_contents($src);

        $this->executeQueries($sql);

        return true;
    }

    /**
     * @param $sql
     * @return string
     * @throws Exception
     */
    public function parseQuery($sql)
    {
        $sql = $this->parser->parseStringParameters($sql);
        $sql = $this->parser->parseConstants($sql);

        return $sql;
    }

    public function executeQueries($sql)
    {
        if ($this->parameters['db.split']) {
            $queries = explode(';', $sql);

            foreach ($queries as $query) {
                $query = trim($query);

                if (!empty($query)) {
                    $this->execute($query);
                }
            }

            return true;
        } else {
            return $this->execute($sql);
        }
    }

    /**
     * @return AdapterInterface
     */
    private function getConnection()
    {
        return $this->getService('magento')
            ->get('Magento\Framework\App\ResourceConnection')
            ->getConnection();
    }

    public function execute($sql)
    {
        $sql = $this->parseQuery($sql);

        return $this->getConnection()->query($sql);
    }

    public function fetchAll($sql)
    {
        $sql = $this->parseQuery($sql);

        return $this->getConnection()->fetchAssoc($sql);
    }

    public function fetchLine($sql)
    {
        $sql = $this->parseQuery($sql);

        return $this->getConnection()->fetchRow($sql);
    }

    public function fetchColumn($sql)
    {
        $sql = $this->parseQuery($sql);

        return $this->getConnection()->fetchCol($sql);
    }

    public function fetchOne($sql)
    {
        $sql = $this->parseQuery($sql);

        return $this->getConnection()->fetchOne($sql);
    }
}
