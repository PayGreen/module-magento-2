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
 * @version   1.2.1
 *
 */

class PGFrameworkServicesRepositoriesSettingRepository extends PGFrameworkFoundationsAbstractRepositoryDatabase implements PGFrameworkInterfacesRepositoriesSettingRepositoryInterface
{
    /**
     * @inheritDoc
     * @return PGFrameworkInterfacesEntitiesSettingInterface[]
     * @throws Exception
     */
    public function findAllByPrimaryShop($id_shop = null)
    {
        if ($id_shop === null) {
            $where = "`id_shop` IS NULL";
        } else {
            $where = "`id_shop` = $id_shop";
        }

        /** @var PGFrameworkInterfacesEntitiesSettingInterface[] $result */
        $result = $this->findAllEntities($where);

        return $result;
    }

    /**
     * @inheritDoc
     * @return PGFrameworkInterfacesEntitiesSettingInterface
     * @throws Exception
     */
    public function findOneByNameAndPrimaryShop($name, $id_shop = null)
    {
        $name = $this->getRequester()->quote($name);

        if ($id_shop === null) {
            $where = "`name` = '$name' AND `id_shop` IS NULL";
        } else {
            $where = "`name` = '$name' AND `id_shop` = $id_shop";
        }

        /** @var PGFrameworkInterfacesEntitiesSettingInterface $result */
        $result = $this->findOneEntity($where);

        return $result;
    }

    /**
     * @inheritDoc
     * @return PGFrameworkInterfacesEntitiesSettingInterface
     */
    public function create($name, $id_shop = null)
    {
        /** @var PGFrameworkInterfacesEntitiesSettingInterface $result */
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
    public function update(PGFrameworkInterfacesEntitiesSettingInterface $setting)
    {
        return $this->updateEntity($setting);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function insert(PGFrameworkInterfacesEntitiesSettingInterface $setting)
    {
        return $this->insertEntity($setting);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function delete(PGFrameworkInterfacesEntitiesSettingInterface $setting)
    {
        return $this->deleteEntity($setting);
    }
}
