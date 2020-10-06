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
 * @version   1.2.0
 */

class PGServerComponentsResponsesRedirectionResponse extends PGServerFoundationsAbstractResponse
{
    /** @var string */
    private $url;

    /** @var int */
    private $redirectionCode = 303;

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRedirectionCode()
    {
        return $this->redirectionCode;
    }

    /**
     * @param int $redirectionCode
     * @return self
     * @throws Exception
     */
    public function setRedirectionCode($redirectionCode)
    {
        if (!is_int($redirectionCode)) {
            throw new Exception("RedirectionCode must be an integer.");
        } elseif (($redirectionCode < 300) || ($redirectionCode >= 400)) {
            throw new Exception("RedirectionCode must be a number between 300 and 399.");
        }

        $this->redirectionCode = $redirectionCode;

        return $this;
    }
}
