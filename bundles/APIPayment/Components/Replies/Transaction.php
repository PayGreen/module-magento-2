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
 * @version   2.5.1
 *
 */

namespace PGI\Module\APIPayment\Components\Replies;

use PGI\Module\APIPayment\Components\Replies\TransactionResult as TransactionResultReplyComponent;
use PGI\Module\PGClient\Exceptions\ResponseMalformed as ResponseMalformedException;
use PGI\Module\PGClient\Foundations\AbstractReply;
use DateTime;
use Exception;

/**
 * Class Transaction
 * @package APIPayment\Components\Replies
 */
class Transaction extends AbstractReply
{
    /** @var int */
    protected $id;

    /** @var int|null  */
    protected $orderPrimary = null;

    /** @var string|null */
    protected $paymentFolder = null;

    /** @var int */
    protected $amount;

    /** @var string */
    protected $currency;

    /** @var string */
    protected $mode;

    /** @var string */
    protected $type;

    /** @var string */
    protected $url;

    /** @var bool */
    protected $testing;

    /** @var int */
    protected $fingerPrintPrimary;

    /** @var int */
    protected $rank;

    /** @var DateTime */
    protected $createdAt;

    /** @var DateTime */
    protected $valueAt;

    /** @var DateTime */
    protected $answeredAt;

    /** @var TransactionResultReplyComponent|null  */
    protected $result = null;

    protected $card = null;

    protected $buyer = null;

    protected $donations = null;

    /** @var array  */
    protected $metadata = array();

    protected $schedules = array();

    /** @var string */
    private $transactionType;

    /** @var string */
    private $pid;

    /**
     * @throws Exception
     */
    protected function compile()
    {
        $this
            ->setScalar('id', 'id')
            ->setScalar('orderId', 'orderPrimary', 'int')
            ->setScalar('amount', 'amount', 'int')
            ->setScalar('currency', 'currency')
            ->setScalar('type', 'mode')
            ->setScalar('paymentType', 'type')
            ->setScalar('url', 'url')
            ->setScalar('testMode', 'testing', 'bool')
            ->setScalar('idFingerprint', 'fingerPrintPrimary', 'int')
            ->setScalar('rank', 'rank', 'int', false)
            ->setObject('result', 'result', 'PGI\Module\APIPayment\Components\Replies\TransactionResult')
        ;

        if ($this->hasRaw('paymentFolder')) {
            $paymentFolder = $this->getRaw('paymentFolder');
            if (!empty($paymentFolder)) {
                $this->setScalar('paymentFolder', 'paymentFolder');
            }
        }

        $this->metadata = $this->hasRaw('metadata') ?
            (array) $this->getRaw('metadata') :
            array()
        ;

        $this->pid = substr($this->getId(), 2);
        $this->transactionType = strtoupper(substr($this->getId(), 0, 2));

        // fix api legacy
        $exploded = explode('-', $this->pid);
        if (count($exploded) === 2) {
            $this->pid = $exploded[0];
            $this->rank = $exploded[1];
        }

        if (!in_array($this->getTransactionType(), array('TR', 'PR'))) {
            $message = "Unknown transaction type: '{$this->getTransactionType()}'.";
            throw new ResponseMalformedException($message);
        }

        if (!in_array($this->getMode(), array('CASH', 'RECURRING', 'XTIME', 'TOKENIZE'))) {
            $message = "Unknown payment mode: '{$this->getMode()}'.";
            throw new ResponseMalformedException($message);
        }

        if ($this->hasRaw('createdAt') and ($this->getRaw('createdAt') > 0)) {
            $this->createdAt = new DateTime($this->getRaw('createdAt'));
        }

        if ($this->hasRaw('answeredAt') and ($this->getRaw('answeredAt') > 0)) {
            $this->answeredAt = new DateTime($this->getRaw('answeredAt'));
        }

        if ($this->hasRaw('valueAt') and ($this->getRaw('valueAt') > 0)) {
            $this->valueAt = new DateTime($this->getRaw('valueAt'));
        }
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getPaymentFolder()
    {
        return $this->paymentFolder;
    }

    /**
     * @return int|null
     */
    public function getOrderPrimary()
    {
        return $this->orderPrimary;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getUserAmount()
    {
        return (float) $this->amount / 100;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return bool
     */
    public function isTesting()
    {
        return (bool) $this->testing;
    }

    /**
     * @return int
     */
    public function getFingerPrintPrimary()
    {
        return $this->fingerPrintPrimary;
    }

    /**
     * @return int
     */
    public function getRank()
    {
        return $this->rank;
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
    public function getValueAt()
    {
        return $this->valueAt;
    }

    /**
     * @return DateTime
     */
    public function getAnsweredAt()
    {
        return $this->answeredAt;
    }

    public function getMetadata($name)
    {
        return $this->hasMetadata($name) ? $this->metadata[$name] : null;
    }

    public function hasMetadata($name)
    {
        return array_key_exists($name, $this->metadata);
    }

    /**
     * @return string
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @return string
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @return TransactionResultReplyComponent|null
     */
    public function getResult()
    {
        return $this->result;
    }
}
