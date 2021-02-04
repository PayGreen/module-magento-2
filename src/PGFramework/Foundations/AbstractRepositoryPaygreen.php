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
 * @version   1.2.2
 *
 */

/**
 * Class PGFrameworkFoundationsAbstractRepositoryPaygreen
 * @package PGFramework\Foundations
 */
abstract class PGFrameworkFoundationsAbstractRepositoryPaygreen extends PGFrameworkFoundationsAbstractRepository implements PGFrameworkInterfacesRepositoryWrappedEntityInterface
{
    /**
     * @return PGClientServicesApiFacade
     */
    protected function getApiFacade()
    {
        return $this->getService('paygreen.facade')->getApiFacade();
    }

    /**
     * @return string
     */
    abstract public function getModelClassName();

    /**
     * @inheritdoc
     */
    public function wrapEntity($localEntity)
    {
        $className = $this->getModelClassName();

        return new $className((array) $localEntity);
    }

    /**
     * @inheritdoc
     */
    public function wrapEntities($localEntities)
    {
        $entities = array();

        if ($localEntities === null) {
            $localEntities = array();
        }

        foreach ($localEntities as $localEntity) {
            $entities[] = $this->wrapEntity($localEntity);
        }

        return $entities;
    }
}
