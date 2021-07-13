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
 * @version   2.1.1
 *
 */

/**
 * Class PGPaymentServicesRepositoriesButtonRepository
 * @package PGPayment\Services\Repositories
 */
class PGPaymentServicesRepositoriesButtonRepository extends PGDatabaseFoundationsRepositoryDatabase implements PGPaymentInterfacesRepositoriesButtonRepositoryInterface
{
    /** @var PGShopInterfacesShopHandler */
    private $shopHandler;

    public function __construct(
        PGDatabaseServicesDatabaseHandler $databaseHandler,
        PGShopInterfacesShopHandler $shopHandler,
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
     * @inheritDoc
     * @throws Exception
     */
    public function findAll()
    {
        return $this->findAllEntities("`id_shop` = '{$this->getShopPrimary()}'");
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function create()
    {
        return $this->wrapEntity(array(
            'paymentNumber' => 1,
            'displayType' => 'DEFAULT',
            'integration' => 'EXTERNAL',
            'paymentType' => 'CB',
            'paymentMode' => 'CASH',
            'height' => 60,
            'id_shop' => $this->getShopPrimary()
        ));
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function insert(PGPaymentInterfacesEntitiesButtonInterface $button)
    {
        return $this->insertEntity($button);
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function update(PGPaymentInterfacesEntitiesButtonInterface $button)
    {
        return $this->updateEntity($button);
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function delete(PGPaymentInterfacesEntitiesButtonInterface $button)
    {
        return $this->deleteEntity($button);
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $table = "%{database.entities.button.table}";
        return (int) $this->getRequester()->fetchValue("SELECT COUNT(*) AS value FROM `$table`");
    }
}
