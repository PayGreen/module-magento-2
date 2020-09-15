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
 * @version   1.1.0
 */

/**
 * Class PGIntlServicesRepositoriesTranslationRepository
 * @package PGFramework\Services\Repositories
 */
class PGIntlServicesRepositoriesTranslationRepository extends PGFrameworkFoundationsAbstractRepositoryDatabase implements PGIntlInterfacesRepositoriesTranslationRepositoryInterface
{
    /** @var PGModuleServicesHandlersShopHandler */
    private $shopHandler;

    public function __construct(
        PGFrameworkServicesHandlersDatabaseHandler $databaseHandler,
        array $config,
        PGModuleServicesHandlersShopHandler $shopHandler
    ) {
        parent::__construct($databaseHandler, $config);

        $this->shopHandler = $shopHandler;
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function findByCode($code, PGDomainInterfacesEntitiesShopInterface $shop = null)
    {
        $id_shop = $shop ? $shop->id() : $this->shopHandler->getCurrentShopPrimary();

        $code = $this->getRequester()->quote($code);

        return $this->findAllEntities("`id_shop` = '$id_shop' AND `code` = '$code'");
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function findByPattern($pattern, PGDomainInterfacesEntitiesShopInterface $shop = null)
    {
        $id_shop = $shop ? $shop->id() : $this->shopHandler->getCurrentShopPrimary();

        $pattern = $this->getRequester()->quote($pattern);

        /** @var PGDomainInterfacesEntitiesTransactionInterface $result */
        $result = $this->findAllEntities("`id_shop` = '$id_shop' AND `code` LIKE '$pattern%'");

        return $result;
    }

    /**
     * @inheritDoc
     * @return PGIntlInterfacesEntitiesTranslationInterface
     * @throws Exception
     */
    public function create($code, $language, PGDomainInterfacesEntitiesShopInterface $shop = null)
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
