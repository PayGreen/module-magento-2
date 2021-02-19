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

class PGDomainServicesRepositoriesCategoryHasPaymentTypeRepository extends PGFrameworkFoundationsAbstractRepositoryDatabase implements PGDomainInterfacesRepositoriesCategoryHasPaymentTypeRepositoryInterface
{
    /** @var PGDomainInterfacesShopHandlerInterface */
    private $shopHandler;

    public function __construct(
        PGFrameworkServicesHandlersDatabaseHandler $databaseHandler,
        PGDomainInterfacesShopHandlerInterface $shopHandler,
        array $config
    ) {
        parent::__construct($databaseHandler, $config);

        $this->shopHandler = $shopHandler;
    }

    protected function getShopPrimary()
    {
        return $this->shopHandler->getCurrentShopPrimary();
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function findAll()
    {
        return $this->findAllEntities("`id_shop` = '{$this->getShopPrimary()}'");
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function findCategoriesByPaymentType($mode)
    {
        $table = "%{database.entities.category_has_payment.table}";
        $sql = "SELECT id_category FROM `$table` WHERE `payment` = '$mode' AND `id_shop` = '{$this->getShopPrimary()}'";

        return $this->getRequester()->fetchColumn($sql);
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function truncate()
    {
        $table = "%{database.entities.category_has_payment.table}";
        $sql = "DELETE FROM `$table` WHERE `id_shop` = '{$this->getShopPrimary()}'";

        return $this->getRequester()->execute($sql);
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function saveAll($data)
    {
        $table = "%{database.entities.category_has_payment.table}";
        $sql = "INSERT INTO `$table` (`id_category`, `payment`, `id_shop`) VALUES ";

        $values = array();

        foreach ($data as $row) {
            $values[] = "('{$row['id_category']}', '{$row['payment']}', '{$this->getShopPrimary()}')";
        }

        $sql .= implode(', ', $values);

        return !empty($values) ? $this->getRequester()->execute($sql) : true;
    }
}
