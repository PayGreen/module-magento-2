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
 * @version   0.3.2
 */

use Paygreen\Payment\Model\CategoryHasPaymentType;

/**
 * Class PGModuleEntitiesLock
 *
 * @package PGModule\Entities
 * @method CategoryHasPaymentType getLocalEntity()
 */
class PGModuleEntitiesCategoryHasPaymentType extends PGDomainFoundationsEntitiesAbstractCategoryHasPaymentType
{
    /**
     * @inheritdoc
     */
    protected function hydrateFromLocalEntity($localEntity)
    {
        // Do nothing.
    }

    /**
     * @inheritdoc
     */
    public function id()
    {
        return (int) $this->getLocalEntity()->getData('id');
    }

    /**
     * @inheritdoc
     */
    public function getCategoryId()
    {
        return (int) $this->getLocalEntity()->getData('id_category');
    }

    /**
     * @inheritdoc
     */
    public function getPaymentType()
    {
        return (string) $this->getLocalEntity()->getData('payment');
    }
}
