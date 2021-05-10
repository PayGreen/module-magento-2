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
 * Class PGServerFoundationsAbstractResponse
 * @package PGServer\Foundations
 */
abstract class PGServerFoundationsAbstractResponse extends PGSystemFoundationsObject
{
    private $tags = array();

    /** @var PGServerFoundationsAbstractRequest|null */
    private $request = null;

    /** @var PGServerFoundationsAbstractResponse|null */
    private $previousResponse = null;

    /**
     * PGServerFoundationsAbstractResponse constructor.
     * @param PGServerFoundationsAbstractRequest|PGServerFoundationsAbstractResponse $previous
     * @throws Exception
     */
    public function __construct($previous)
    {
        if ($previous instanceof PGServerFoundationsAbstractRequest) {
            $this->request = $previous;
        } elseif ($previous instanceof PGServerFoundationsAbstractResponse) {
            $this->previousResponse = $previous;
        } else {
            throw new Exception("A response must be attached to a request or a previous response.");
        }
    }

    /**
     * @return PGServerFoundationsAbstractRequest
     * @throws Exception
     */
    public function getRequest()
    {
        if ($this->request !== null) {
            return $this->request;
        } elseif ($this->previousResponse !== null) {
            return $this->previousResponse->getRequest();
        } else {
            throw new Exception("Unable to retrieve origin request..");
        }
    }

    /**
     * @return PGServerFoundationsAbstractResponse|null
     */
    public function getPreviousResponse()
    {
        return $this->previousResponse;
    }

    /**
     * @param string $tag
     * @return $this
     */
    public function tag($tag)
    {
        $tag = strtoupper($tag);

        $this->tags[] = $tag;

        return $this;
    }

    /**
     * @param string $tag
     * @return $this
     */
    public function untag($tag)
    {
        $tag = strtoupper($tag);

        if ($this->is($tag)) {
            $id = array_search($tag, $this->tags);
            unset($this->tags[$id]);
        }

        return $this;
    }

    public function is($tag)
    {
        $tag = strtoupper($tag);

        return in_array($tag, $this->tags);
    }
}
