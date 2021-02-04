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
 * @version   1.2.2
 *
 */

namespace Paygreen\Payment\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use PGFrameworkContainer;
use PGFrameworkServicesLogger;
use PGFrameworkServicesHandlersOutputHandler;

class DisplayFrontofficeOutput extends Template
{
    public function __construct(Context $context)
    {
        parent::__construct($context);

        require_once PAYGREEN_BOOTSTRAP_SRC;
    }

    protected function getService($name)
    {
        return PGFrameworkContainer::getInstance()->get($name);
    }

    protected function _toHtml()
    {
        /** @var PGFrameworkServicesHandlersOutputHandler $outputHandler */
        $outputHandler = $this->getService('handler.output');

        /** @var PGFrameworkServicesLogger $outputHandler */
        $logger = $this->getService('logger.view');

        $content = $outputHandler->getContent();

        $size = strlen($content);

        $logger->debug("Displaying output content with size of {$size} octets.");

        return $content;
    }
}
