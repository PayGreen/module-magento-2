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
 * @version   2.3.0
 *
 */

namespace Paygreen\Payment\Controller\Frontoffice;

use Exception;
use Magento\Framework\App\Action\Context as LocalContext;
use Magento\Framework\App\Action\Action as LocalAction;
use Magento\Framework\View\Result\PageFactory as LocalPageFactory;

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

        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @return LocalPageFactory
     */
    protected function getResultPageFactory()
    {
        return $this->resultPageFactory;
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
