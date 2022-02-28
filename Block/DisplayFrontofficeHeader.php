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
 * @version   2.6.0
 *
 */

namespace Paygreen\Payment\Block;

use Magento\Framework\View\Asset\Repository as LocalRepository;
use Magento\Framework\View\Element\Template as LocalTemplate;
use Magento\Framework\View\Element\Template\Context as LocalContext;
use Magento\Theme\Block\Html\Header\Logo as Logo;
use PGI\Module\PGMagento\Services\Compilers\StaticResourceCompiler;
use PGI\Module\PGLog\Interfaces\LoggerInterface;
use PGI\Module\PGModule\Services\Providers\OutputProvider;
use PGI\Module\PGSystem\Services\Container;

class DisplayFrontofficeHeader extends LocalTemplate
{
    /** @var LocalRepository */
    protected $assetRepository;

    /** @var Logo */
    protected $headerLogo;

    public function __construct(LocalContext $context, Logo $logo)
    {
        parent::__construct($context);

        $this->assetRepository = $context->getAssetRepository();

        $this->headerLogo = $logo;

        require_once PAYGREEN_BOOTSTRAP_SRC;

        /** @var LoggerInterface $logger */
        $logger = $this->getService('logger.view');

        $logger->debug('Successfully init frontoffice header block.');
    }

    protected function getService($name)
    {
        return Container::getInstance()->get($name);
    }

    protected function _toHtml()
    {
        /** @var StaticResourceCompiler $magentoResourceCompiler */
        $magentoResourceCompiler = $this->getService('compiler.resource.magento');

        /** @var OutputProvider $outputProvider */
        $outputProvider = $this->getService('provider.output');

        $logger = $this->getService('logger.view');

        $channels = array('FRONT.HEAD');

        $objectManager = $this->getService('magento');
        $request = $objectManager->get('\Magento\Framework\App\Request\Http');
        $checkModule = $request->getFullActionName();

        if ($checkModule === "checkout_index_index"){
            $logger->debug("Checkout page detected. Loading associated ressources.");
            $channels[] = 'FRONT.FUNNEL.CHECKOUT';
        } elseif ($checkModule === "paygreen_frontoffice_index"){
            $logger->debug("PGFrontPage page detected. Loading associated ressources.");
            $channels[] = 'FRONT.PAYGREEN';
        } elseif ($this->headerLogo->isHomePage()) {
            $logger->debug("Frontoffice home page detected. Loading associated ressources.");
            $channels[] = 'FRONT.HOME.FOOTER';
        }

        $resources = $outputProvider->getResources($channels);

        return $magentoResourceCompiler->compileResources($resources);
    }
}
