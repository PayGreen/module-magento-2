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
 * @version   1.2.4
 *
 */

namespace Paygreen\Payment\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use PGFrameworkContainer;
use PGFrameworkServicesLogger;
use PGServerServicesLinker;
use PGServerComponentsResourceBag;
use PGServerComponentsResourcesScriptFileResource;
use PGModuleServicesMagentoResourceCompiler;
use PGServerComponentsResourcesDataResource;
use PGDomainServicesPaygreenFacade;

class DisplayHomeHeader extends Template
{
    /** @var \Magento\Framework\View\Asset\Repository */
    protected $assetRepository;

    public function __construct(Context $context)
    {
        parent::__construct($context);

        $this->assetRepository = $context->getAssetRepository();

        require_once PAYGREEN_BOOTSTRAP_SRC;

        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger.view');

        $logger->debug('Successfully init home page header block.');
    }

    protected function getService($name)
    {
        return PGFrameworkContainer::getInstance()->get($name);
    }

    protected function _toHtml()
    {
        /** @var PGModuleServicesMagentoResourceCompiler $magentoResourceCompiler */
        $magentoResourceCompiler = $this->getService('compiler.resource.magento');

        /** @var PGFrameworkServicesLogger $outputHandler */
        $logger = $this->getService('logger.view');

        /** @var PGServerServicesLinker $linker */
        $linker = $this->getService('linker');

        $logger->debug("Writing home page header output.");

        $resources = new PGServerComponentsResourceBag();

        if ($this->isTreeActivated()) {
            $resources->add(new PGServerComponentsResourcesScriptFileResource('/js/clientjs.js'));
            $resources->add(new PGServerComponentsResourcesScriptFileResource('/js/tree.js'));
            $resources->add(new PGServerComponentsResourcesDataResource(array(
                'paygreen_tree_computing_url' => $linker->buildFrontOfficeUrl('front.tree.save')
            )));
        }

        return $magentoResourceCompiler->compileResources($resources);
    }

    /**
     * @return boolean
     */
    protected function isTreeActivated()
    {
        /** @var PGDomainServicesPaygreenFacade $paygreenFacade */
        $paygreenFacade = $this->getService('paygreen.facade');

        /** @var PGFrameworkServicesLogger $outputHandler */
        $logger = $this->getService('logger.view');

        if (!$paygreenFacade->isConfigured()) {
            $logger->info("Paygreen Facade is not configured yet, we can't proceed the request now.");
            return false;
        }

        $shopInfos = $paygreenFacade->getAccountInfos();

        return (isset($shopInfos->solidarityType) && ($shopInfos->solidarityType == 'CCARBONE'));
    }

}
