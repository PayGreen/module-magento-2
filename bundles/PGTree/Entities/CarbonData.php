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
 * @version   2.3.0
 *
 */

namespace PGI\Module\PGTree\Entities;

use PGI\Module\PGDatabase\Foundations\AbstractEntityPersisted;
use PGI\Module\PGTree\Interfaces\Entities\CarbonDataEntityInterface;
use DateTime;

/**
 * Class CarbonData
 * @package PGTree\Entities
 */
class CarbonData extends AbstractEntityPersisted implements CarbonDataEntityInterface
{
    public function getOrderId()
    {
        return $this->get('id_order');
    }

    public function setOrderId($id_order)
    {
        $this->set('id_order', $id_order);

        return $this;
    }

    public function getUserId()
    {
        return $this->get('id_user');
    }

    public function setUserId($id_user)
    {
        $this->set('id_user', $id_user);

        return $this;
    }

    public function getFingerprintId()
    {
        return $this->get('id_fingerprint');
    }

    public function setFingerprintId($id_fingerprint)
    {
        $this->set('id_fingerprint', $id_fingerprint);

        return $this;
    }

    public function getFootprint()
    {
        return $this->get('footprint');
    }

    public function setFootprint($footprint)
    {
        $this->set('footprint', $footprint);

        return $this;
    }

    public function getCarbonOffset()
    {
        return $this->get('carbon_offset');
    }

    public function setCarbonOffset($carbonOffset)
    {
        $this->set('carbon_offset', $carbonOffset);

        return $this;
    }

    public function getCreatedAt()
    {
        $timestamp = (int) $this->get('created_at');

        $dt = new DateTime();

        return $dt->setTimestamp($timestamp);
    }

    public function setCreatedAt(DateTime $createdAt)
    {
        $this->set('created_at', $createdAt->getTimestamp());

        return $this;
    }
}
