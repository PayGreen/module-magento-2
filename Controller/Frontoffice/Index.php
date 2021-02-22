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

namespace Paygreen\Payment\Controller\Frontoffice;

use Exception;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\PageFactory;
use Paygreen;
use PGFrameworkContainer;
use PGServerServicesServer;
use PGFrameworkServicesLogger;
use PGFrameworkServicesHandlersOutputHandler;
use PGServerComponentsResourcesScriptFileResource;
use PGServerComponentsResourcesStyleFileResource;

class Index extends Action
{
    /** @var PageFactory */
    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        require_once PAYGREEN_BOOTSTRAP_SRC;

        parent::__construct($context);

        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @return PageFactory
     */
    protected function getResultPageFactory()
    {
        return $this->resultPageFactory;
    }

    /**
     * @param string $name
     * @return object
     * @throws Exception
     */
    protected function getService(string $name)
    {
        return PGFrameworkContainer::getInstance()->get($name);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function execute()
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        /** @var PGServerServicesServer $server */
        $server = $this->getService('server.front');

        /** @var PGFrameworkServicesHandlersOutputHandler $outputHandler */
        $outputHandler = $this->getService('handler.output');

        try {
            $logger->debug("Building frontoffice output.");

            $server->run();

            $outputHandler->addResource(new PGServerComponentsResourcesStyleFileResource('/css/frontoffice.css'));

            $logger->debug("Frontoffice output successfully built.");
        } catch (Exception $exception) {
            $logger->error("Error during frontoffice building : " . $exception->getMessage(), $exception);

            throw $exception;
        }

        $page = $this->getResultPageFactory()->create();

        $page->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0', true);
        $page->setHeader('X-Frame-Options', 'ALLOW', true);

        return $page;
    }
}
