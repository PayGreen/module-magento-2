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
 * @version   2.4.0
 *
 */

namespace Paygreen\Payment\Controller\Frontoffice;

use Exception;
use Magento\Framework\App\Action\Context as LocalContext;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Action\Action as LocalAction;
use Magento\Framework\View\Result\PageFactory as LocalPageFactory;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGSystem\Services\Container;

class Index extends LocalAction
{
    /** @var LocalPageFactory */
    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param LocalContext $context
     * @param LocalPageFactory $resultPageFactory
     */
    public function __construct(
        LocalContext $context,
        LocalPageFactory $resultPageFactory
    ) {
        require_once PAYGREEN_BOOTSTRAP_SRC;

        parent::__construct($context);

        // CsrfAwareAction Magento2.3 compatibility
        if (interface_exists("Magento\Framework\App\CsrfAwareActionInterface")) {
            $request = $this->getRequest();
            if ($request instanceof RequestInterface && $request->isPost() && empty($request->getParam('form_key'))) {
                $formKey = $this->_objectManager->get(\Magento\Framework\Data\Form\FormKey::class);
                $request->setParam('form_key', $formKey->getFormKey());
            }
        }

        $this->resultPageFactory = $resultPageFactory;

        /** @var Logger $logger */
        $logger = $this->getService('logger');

        $logger->debug("Request incoming in front office endpoint.");
    }

    /**
     * @return LocalPageFactory
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
        return Container::getInstance()->get($name);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function execute()
    {
        $page = $this->getResultPageFactory()->create();

        $page->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0', true);
        $page->setHeader('X-Frame-Options', 'ALLOW', true);

        return $page;
    }
}
