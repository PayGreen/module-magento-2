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
 * Class PGIntlServicesRepositoriesTranslationRepository
 * @package PGIntl\Services\Repositories
 */
class PGIntlServicesRepositoriesTranslationRepository extends PGDatabaseFoundationsRepositoryDatabase implements PGIntlInterfacesRepositoriesTranslationRepositoryInterface
{
    /** @var PGShopInterfacesShopHandler */
    private $shopHandler;

    public function __construct(
        PGDatabaseServicesDatabaseHandler $databaseHandler,
        array $config,
        PGShopInterfacesShopHandler $shopHandler
    ) {
        parent::__construct($databaseHandler, $config);

        $this->shopHandler = $shopHandler;
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function findByCode($code, PGShopInterfacesEntitiesShop $shop = null)
    {
        $id_shop = $shop ? $shop->id() : $this->shopHandler->getCurrentShopPrimary();

        $code = $this->getRequester()->quote($code);

        return $this->findAllEntities("`id_shop` = '$id_shop' AND `code` = '$code'");
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function findByPattern($pattern, PGShopInterfacesEntitiesShop $shop = null)
    {
        $id_shop = $shop ? $shop->id() : $this->shopHandler->getCurrentShopPrimary();

        $pattern = $this->getRequester()->quote($pattern);

        /** @var PGPaymentInterfacesEntitiesTransactionInterface $result */
        $result = $this->findAllEntities("`id_shop` = '$id_shop' AND `code` LIKE '$pattern%'");

        return $result;
    }

    /**
     * @inheritDoc
     * @return PGIntlInterfacesEntitiesTranslationInterface
     * @throws Exception
     */
    public function create($code, $language, PGShopInterfacesEntitiesShop $shop = null)
    {
        $id_shop = $shop ? $shop->id() : $this->shopHandler->getCurrentShopPrimary();

        /** @var PGIntlInterfacesEntitiesTranslationInterface $translation */
        $translation = $this->wrapEntity(array(
            'code' => $code,
            'language' => $language,
            'id_shop' => $id_shop
        ));

        return $translation;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function insert(PGIntlInterfacesEntitiesTranslationInterface $translation)
    {
        return $this->insertEntity($translation);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function update(PGIntlInterfacesEntitiesTranslationInterface $translation)
    {
        return $this->updateEntity($translation);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function delete(PGIntlInterfacesEntitiesTranslationInterface $translation)
    {
        return $this->deleteEntity($translation);
    }
}
