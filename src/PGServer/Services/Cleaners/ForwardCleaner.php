<?php
/**
 * 2014 - 2020 Watt Is It
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
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.0.0
 */

class PGServerServicesCleanersForwardCleaner implements PGServerInterfacesCleanerInterface
{
    private $target;

    private $data = array();

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
        $data = array_merge(array(
            'request' => $request,
            'exception' => $exception
        ), $this->data);

        $subRequest = new PGServerComponentsRequestsForwardRequest($this->target, $data);

        return new PGServerComponentsResponsesForwardResponse($subRequest);
    }
}