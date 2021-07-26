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
 * @version   2.2.0
 *
 */

namespace PGI\Module\PGPayment\Services\ChainLinks;

use PGI\Module\PGPayment\Components\PaymentProject as PaymentProjectComponent;
use PGI\Module\PGPayment\Foundations\AbstractPaymentCreationChainLink;
use PGI\Module\PGServer\Services\Handlers\LinkHandler;
use Exception;

/**
 * Class AddFrontofficeEntrypointChainLink
 * @package PGPayment\Services\ChainLinks
 */
class AddFrontofficeEntrypointChainLink extends AbstractPaymentCreationChainLink
{
    private $link;

    private $key;

    /** @var LinkHandler */
    private $linkHandler;

    public function __construct(LinkHandler $linkHandler, $link, $key)
    {
        $this->linkHandler = $linkHandler;
        $this->link = $link;
        $this->key = $key;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function process(PaymentProjectComponent $paymentProject)
    {
        $paymentProject[$this->key] = $this->linkHandler->buildFrontOfficeUrl($this->link);
    }
}
