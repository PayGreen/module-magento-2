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
 * @version   2.0.1
 *
 */

/**
 * Class APITreeComponentsRepliesCarbonFootprint
 * @package APITree\Components\Replies
 */
class APITreeComponentsRepliesCarbonFootprint extends PGClientFoundationsReply
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

        if ($this->hasRaw('createdAt') and ($this->getRaw('createdAt') > 0)) {
            $this->createdAt = $this->createDateTimeFromTimestamp($this->getRaw('createdAt'));
        }

        if ($this->hasRaw('updatedAt') and ($this->getRaw('updatedAt') > 0)) {
            $this->createdAt = $this->createDateTimeFromTimestamp($this->getRaw('updatedAt'));
        }
    }

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