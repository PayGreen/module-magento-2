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
 * @version   2.1.1
 *
 */

use Magento\Framework\App\ObjectManager;

class PGMagentoServicesLinkersFrontBasicLinker extends PGMagentoFoundationsAbstractFrontLinker
{
    private $endpoint;

    public function __construct(ObjectManager $objectManager, $endpoint)
    {
        parent::__construct($objectManager);

        $this->endpoint = $endpoint;
    }

    public function buildUrl(array $data = array())
    {
        return $this->buildFrontUrl($this->endpoint);
    }
}
