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

namespace PGI\Module\PGModule\Services\Repositories;

use PGI\Module\PGDatabase\Foundations\AbstractRepositoryDatabase;
use PGI\Module\PGModule\Interfaces\Entities\SettingEntityInterface;
use PGI\Module\PGModule\Interfaces\Repositories\SettingRepositoryInterface;
use Exception;

/**
 * Class SettingRepository
 * @package PGModule\Services\Repositories
 */
class SettingRepository extends AbstractRepositoryDatabase implements SettingRepositoryInterface
{
    /**
     * @inheritDoc
     * @return SettingEntityInterface[]
     * @throws Exception
     */
    public function findAllByPrimaryShop($id_shop = null)
    {
        if ($id_shop === null) {
            $where = "`id_shop` IS NULL";
        } else {
            $where = "`id_shop` = $id_shop";
        }

        /** @var SettingEntityInterface[] $result */
        $result = $this->findAllEntities($where);

        return $result;
    }

    /**
     * @inheritDoc
     * @return SettingEntityInterface
     * @throws Exception
     */
    public function findOneByNameAndPrimaryShop($name, $id_shop = null)
    {
        $name = $this->getRequester()->quote($name);

        if ($id_shop === null) {
            $where = "`name` = '$name' AND `id_shop` IS NULL";
        } else {
            $id_shop = $this->getRequester()->quote($id_shop);
            $where = "`name` = '$name' AND `id_shop` = $id_shop";
        }

        /** @var SettingEntityInterface $result */
        $result = $this->findOneEntity($where);

        return $result;
    }

    /**
     * @inheritDoc
     * @return SettingEntityInterface
     */
    public function create($name, $id_shop = null)
    {
        /** @var SettingEntityInterface $result */
        $result = $this->wrapEntity(array(
            'name' => $name,
            'id_shop' => $id_shop
        ));

        return $result;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function update(SettingEntityInterface $setting)
    {
        return $this->updateEntity($setting);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function insert(SettingEntityInterface $setting)
    {
        return $this->insertEntity($setting);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function delete(SettingEntityInterface $setting)
    {
        return $this->deleteEntity($setting);
    }
}
