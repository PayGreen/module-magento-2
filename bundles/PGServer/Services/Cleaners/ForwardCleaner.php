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
 * @version   2.0.2
 *
 */

/**
 * Class PGServerServicesCleanersForwardCleaner
 * @package PGServer\Services\Cleaners
 */
class PGServerServicesCleanersForwardCleaner implements PGServerInterfacesCleanerInterface
{
    const FORWARD_RESPONSE_LIMIT = 3;

    private $target;

    private $data = array();

    /** @var PGServerComponentsResponsesForwardResponse[] */
    private $forwardResponses = array();

    public function __construct($target)
    {
        if (empty($target)) {
            throw new Exception("Forward response must have a target.");
        }

        $this->target = $target;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function processError(PGServerFoundationsAbstractRequest $request, Exception $exception)
    {
        if (count($this->forwardResponses) >= self::FORWARD_RESPONSE_LIMIT) {
            throw $exception;
        }

        $data = array_merge(array(
            'request' => $request,
            'exception' => $exception
        ), $this->data);

        $subRequest = new PGServerComponentsRequestsForwardRequest($this->target, $data);
        
        $this->forwardResponses[] = new PGServerComponentsResponsesForwardResponse($subRequest);

        return end($this->forwardResponses);
    }
}
