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
 * @version   2.2.0
 *
 */

namespace PGI\Module\PGIntl\Services\Repositories;

use PGI\Module\PGDatabase\Foundations\AbstractRepositoryDatabase;
use PGI\Module\PGDatabase\Services\Handlers\DatabaseHandler;
use PGI\Module\PGIntl\Interfaces\Entities\TranslationEntityInterface;
use PGI\Module\PGIntl\Interfaces\Repositories\TranslationRepositoryInterface;
use PGI\Module\PGPayment\Interfaces\Entities\TransactionEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\ShopEntityInterface;
use PGI\Module\PGShop\Interfaces\Handlers\ShopHandlerInterface;
use Exception;

/**
 * Class TranslationRepository
 * @package PGIntl\Services\Repositories
 */
class TranslationRepository extends AbstractRepositoryDatabase implements TranslationRepositoryInterface
{
    /** @var ShopHandlerInterface */
    private $shopHandler;

    public function __construct(
        DatabaseHandler $databaseHandler,
        array $config,
        ShopHandlerInterface $shopHandler
    ) {
        parent::__construct($databaseHandler, $config);

        $this->shopHandler = $shopHandler;
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function findByCode($code, ShopEntityInterface $shop = null)
    {
        $id_shop = $shop ? $shop->id() : $this->shopHandler->getCurrentShopPrimary();

        $code = $this->getRequester()->quote($code);

        return $this->findAllEntities("`id_shop` = '$id_shop' AND `code` = '$code'");
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function findByPattern($pattern, ShopEntityInterface $shop = null)
    {
        $id_shop = $shop ? $shop->id() : $this->shopHandler->getCurrentShopPrimary();

        $pattern = $this->getRequester()->quote($pattern);

        /** @var TransactionEntityInterface $result */
        $result = $this->findAllEntities("`id_shop` = '$id_shop' AND `code` LIKE '$pattern%'");

        return $result;
    }

    /**
     * @inheritDoc
     * @return TranslationEntityInterface
     * @throws Exception
     */
    public function create($code, $language, ShopEntityInterface $shop = null)
    {
        $id_shop = $shop ? $shop->id() : $this->shopHandler->getCurrentShopPrimary();

        /** @var TranslationEntityInterface $translation */
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
    public function insert(TranslationEntityInterface $translation)
    {
        return $this->insertEntity($translation);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function update(TranslationEntityInterface $translation)
    {
        return $this->updateEntity($translation);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function delete(TranslationEntityInterface $translation)
    {
        return $this->deleteEntity($translation);
    }
}
