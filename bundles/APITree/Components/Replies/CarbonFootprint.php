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

namespace PGI\Module\APITree\Components\Replies;

use PGI\Module\PGClient\Foundations\AbstractReply;
use DateTime;
use Exception;
use stdClass;

/**
 * Class CarbonFootprint
 * @package APITree\Components\Replies
 */
class CarbonFootprint extends AbstractReply
{
    /** @var string */
    protected $fingerprint;

    /** @var string */
    protected $idFootprint;
    
    /** @var string */
    protected $idAccount;

    /** @var string */
    protected $idUser;

    /** @var string */
    protected $estimatedCarbon;

    /** @var string */
    protected $estimatedPrice;

    /** @var stdClass */
    protected $emissionDistribution;

    /** @var DateTime */
    protected $createdAt;

    /** @var DateTime */
    protected $updatedAt;

    /** @var string */
    protected $status;

    /**
     * @throws Exception
     */
    protected function compile()
    {
        $this
            ->setScalar('fingerprint', 'fingerprint')
            ->setScalar('idFootprint', 'idFootprint')
            ->setScalar('idAccount', 'idAccount')
            ->setScalar('idUser', 'idUser')
            ->setScalar('estimatedCarbon', 'estimatedCarbon')
            ->setScalar('estimatedPrice', 'estimatedPrice')
            ->setScalar('status', 'status')
        ;
        
        if ($this->hasRaw('emissionDistribution')) {
            $this->setScalar('emissionDistribution', 'emissionDistribution');
        }

        if ($this->hasRaw('createdAt') and ($this->getRaw('createdAt') > 0)) {
            $this->createdAt = $this->createDateTimeFromTimestamp($this->getRaw('createdAt'));
        }

        if ($this->hasRaw('updatedAt') and ($this->getRaw('updatedAt') > 0)) {
            $this->createdAt = $this->createDateTimeFromTimestamp($this->getRaw('updatedAt'));
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
    public function getFingerprint()
    {
        return $this->fingerprint;
    }

    /**
     * @return string
     */
    public function getIdFootprint()
    {
        return $this->idFootprint;
    }

    /**
     * @return string
     */
    public function getIdAccount()
    {
        return $this->idAccount;
    }

    /**
     * @return string
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @return string
     */
    public function getEstimatedCarbon()
    {
        return $this->estimatedCarbon;
    }

    /**
     * @return string
     */
    public function getEstimatedPrice()
    {
        return $this->estimatedPrice;
    }

    /**
     * @return stdClass
     */
    public function getEmissionDistribution()
    {
        return $this->emissionDistribution;
    }

    /**
     * @return float
     */
    public function getCarbonEmittedFromDigital()
    {
        if ($this->emissionDistribution !== null) {
            return $this->emissionDistribution->carbonEmittedFromDigital;
        }

        return 0;
    }

    /**
     * @return float
     */
    public function getCarbonEmittedFromTransportation()
    {
        if ($this->emissionDistribution !== null) {
            return $this->emissionDistribution->carbonEmittedFromTransportation;
        }

        return 0;
    }

    /**
     * @return float
     */
    public function getCarbonEmittedFromProduct()
    {
        if ($this->emissionDistribution !== null) {
            return $this->emissionDistribution->carbonEmittedFromProduct;
        }

        return 0;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
