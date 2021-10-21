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
 * @version   2.4.0
 *
 */

namespace PGI\Module\APICharity\Components\Replies;

use PGI\Module\PGClient\Foundations\AbstractReply;
use DateTime;
use Exception;

/**
 * Class CharityGift
 * @package APICharity\Components\Replies
 */
class CharityGift extends AbstractReply
{
    /** @var string */
    protected $idDonation;

    /** @var string */
    protected $donationReference;

    /** @var int */
    protected $donationAmount;

    /** @var int */
    protected $totalAmount;

    /** @var string */
    protected $idAssociation;

    /** @var string */
    protected $status;

    /** @var DateTime */
    protected $createdAt;

    /**
     * @throws Exception
     */
    protected function compile()
    {
        $this
            ->setScalar('idDonation', 'idDonation')
            ->setScalar('donationReference', 'donationReference')
            ->setScalar('donationAmount', 'donationAmount')
            ->setScalar('totalAmount', 'totalAmount')
            ->setScalar('idAssociation', 'idAssociation')
            ->setScalar('status', 'status')
        ;

        if ($this->hasRaw('createdAt') and ($this->getRaw('createdAt') > 0)) {
            $this->createdAt = $this->createDateTimeFromTimestamp($this->getRaw('createdAt'));
        }
    }

    /**
     * @param string $timestamp
     * @return DateTime
     * @throws Exception
     */
    protected function createDateTimeFromTimestamp($timestamp)
    {
        if ($timestamp <= 0) {
            throw new Exception('Invalid timestamp.');
        }

        $date = new DateTime();
        $date->setTimestamp($timestamp);
        return $date;
    }

    /**
     * @return string
     */
    public function getIdDonation()
    {
        return $this->idDonation;
    }

    /**
     * @return string
     */
    public function getDonationReference()
    {
        return $this->donationReference;
    }

    /**
     * @return int
     */
    public function getDonationAmount()
    {
        return $this->donationAmount;
    }

    /**
     * @return int
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @return string
     */
    public function getIdAssociation()
    {
        return $this->idAssociation;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
