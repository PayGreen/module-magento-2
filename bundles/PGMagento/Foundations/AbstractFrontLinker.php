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

use Magento\Framework\UrlInterface;
use Magento\Framework\App\ObjectManager;

abstract class PGMagentoFoundationsAbstractFrontLinker implements PGServerInterfacesLinkerInterface
{
    /** @var UrlInterface */
    private $urlBuilder;

    public function __construct(ObjectManager $objectManager)
    {
        $this->urlBuilder = $objectManager->get('Magento\Framework\Url');
    }

    protected function buildFrontUrl($code, array $arguments = array())
    {
        $params = empty($arguments) ? null : $arguments;

        return $this->urlBuilder->getUrl($code, $params);
    }
}
