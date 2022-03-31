<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.6.1
 *
 */

namespace PGI\Module\PGMagento\Services\Linkers;

use Exception;
use PGI\Module\PGServer\Foundations\AbstractLinker;
use Magento\Framework\UrlInterface;

/**
 * Class MagentoLinker
 * @package PGMagento\Services\Linkers
 */
class MagentoLinker extends AbstractLinker
{
    /** @var UrlInterface */
    private $localUrlBuilder;

    public function __construct(UrlInterface $localUrlBuilder)
    {
        $this->localUrlBuilder = $localUrlBuilder;
    }

    public function buildUrl(array $data = array())
    {
        /** @var string $route */
        $route = $this->getConfig('route');

        if (!is_string($route)) {
            throw new Exception("Magento linker require 'route' config key.");
        }

        return $this->buildLocalUrl($route);
    }

    /**
     * @return UrlInterface
     */
    protected function getLocalUrlBuilder()
    {
        return $this->localUrlBuilder;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function buildLocalUrl($name)
    {
        return $this->getLocalUrlBuilder()->getUrl($name);
    }
}
