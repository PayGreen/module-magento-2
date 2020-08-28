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
 * @version   1.0.1
 */

namespace Paygreen\Payment\Controller\Adminhtml\Backoffice;

use APPbackofficeServicesHandlersMenuHandler;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use PGFrameworkContainer;
use PGFrameworkServicesHandlersCacheHandler;
use PGFrameworkServicesHandlersSetupHandler;
use PGFrameworkServicesHandlersTranslatorHandler;
use PGFrameworkServicesLogger;
use PGServerServicesServer;

class Index extends Action
{
    /** @var PageFactory */
    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @throws Exception
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        require_once PAYGREEN_BOOTSTRAP_SRC;

        parent::__construct($context);

        $this->resultPageFactory = $resultPageFactory;

        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        $logger->debug("Request incoming in back office endpoint.");

        $this->cleanModule();
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
     * Is the user allowed to view the blog post grid.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }

    /**
     * @throws Exception
     */
    private function cleanModule()
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        /** @var PGFrameworkServicesHandlersCacheHandler $cacheHandler */
        $cacheHandler = $this->getService('handler.cache');

        /** @var PGFrameworkServicesHandlersSetupHandler $setupHandler */
        $setupHandler = $this->getService('handler.setup');

        $logger->debug("Cleaning module.");

        $setupHandler->run($setupHandler::UPGRADE);

        $cacheHandler->clearCache();
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
        $server = $this->getService('server.backoffice');

        /** @var APPbackofficeServicesHandlersMenuHandler $menuHandler */
        $menuHandler = $this->getService('handler.menu');

        try {
            $logger->debug("Building backoffice output.");

            $server->getRequestBuilder()->setConfig('default_action', $menuHandler->getDefaultAction());

            $server->run();
        } catch (Exception $exception) {
            $logger->error("Error during backoffice building : " . $exception->getMessage(), $exception);

            $this->buildErrorOutput();

            throw $exception;
        }

        $page = $this->getResultPageFactory()->create();

        $page->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0', true);

        return $page;
    }

    /**
     * @throws Exception
     */
    private function buildErrorOutput()
    {
        /** @var PGFrameworkServicesHandlersTranslatorHandler $translator */
        $translator = $this->getService('handler.translator');

        $error = $translator->get('backoffice.errors.interface_construction');

        $this->messageManager->addErrorMessage($error);
    }
}
