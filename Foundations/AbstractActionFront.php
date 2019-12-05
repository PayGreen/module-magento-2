<?php
/**
 * 2014 - 2019 Watt Is It
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
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.4
 */

namespace Paygreen\Payment\Foundations;

use PGFrameworkContainer;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\ResponseFactory;
use Magento\Framework\UrlInterface;
use PGFrameworkServicesHandlersTranslatorHandler;

require_once PAYGREEN_BOOTSTRAP_SRC;

abstract class AbstractActionFront extends Action
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

    protected function getService(string $name)
    {
        return PGFrameworkContainer::getInstance()->get($name);
    }

    protected function buildUrl($path, array $parameters = [])
    {
        /** @var UrlInterface $urlHandler */
        $urlHandler = $this->getService('magento')->get('Magento\Framework\UrlInterface');

        return $urlHandler->getUrl($path, $parameters);
    }

    protected function redirectToRoute($path, array $parameters = [])
    {
        $url = $this->buildUrl($path, $parameters);

        $this->redirectToUrl($url);
    }

    protected function redirectToUrl($url)
    {
        /** @var ResponseFactory $responseFactory */
        $responseFactory = $this->getService('magento')->get('Magento\Framework\App\ResponseFactory');

        $responseFactory->create()->setRedirect($url)->sendResponse();

        exit;
    }

    protected function addSuccessMessage($code)
    {
        /** @var PGFrameworkServicesHandlersTranslatorHandler $translator */
        $translator = $this->getService('handler.translator');

        $text = $translator->get($code);

        $this->messageManager->addSuccessMessage($text);
    }

    protected function addErrorMessage($code)
    {
        /** @var PGFrameworkServicesHandlersTranslatorHandler $translator */
        $translator = $this->getService('handler.translator');

        $text = $translator->get($code);

        $this->messageManager->addErrorMessage($text);
    }
}
