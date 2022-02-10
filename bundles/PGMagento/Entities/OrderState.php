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
 * @version   2.5.2
 *
 */

namespace PGI\Module\PGMagento\Entities;

use PGI\Module\PGDatabase\Foundations\AbstractEntityWrapped;
use PGI\Module\PGShop\Interfaces\Entities\OrderStateEntityInterface;

/**
 * Class OrderState
 *
 * @package PGMagento\Entities
 * @method Magento\Sales\Model\Order\Status getLocalEntity()
 */
class OrderState extends AbstractEntityWrapped implements OrderStateEntityInterface
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
        return (int) $this->getLocalEntity()->getId();
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->getLocalEntity()->getLabel();
    }

    /**
     * @inheritdoc
     */
    public function getCode()
    {
        return $this->getLocalEntity()->getStatus();
    }

    public function getMetadata()
    {
        return [
            'state' => $this->getLocalEntity()->getData('state')
        ];
    }
}
