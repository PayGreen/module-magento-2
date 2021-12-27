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

use PGI\Module\PGClient\Foundations\AbstractReply;
use Exception;

/**
 * Class TransactionResult
 * @package APIPayment\Components\Replies
 */
class TransactionResult extends AbstractReply
{
    /** @var string */
    protected $status;

    /** @var string|null  */
    protected $threeDSecureStatus = null;

    /** @var string|null */
    protected $errorStatus;

    /**
     * @throws Exception
     */
    protected function compile()
    {
        $this
            ->setScalar('status', 'status')
            ->setScalar('threeDSecureStatus', 'threeDSecureStatus')
            ->setScalar('paymentErrorStatus', 'errorStatus')
        ;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getThreeDSecureStatus()
    {
        return $this->threeDSecureStatus;
    }

    /**
     * @return string|null
     */
    public function getErrorStatus()
    {
        return $this->errorStatus;
    }
}
