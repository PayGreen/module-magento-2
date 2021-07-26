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

namespace Paygreen\Payment\Block;

use Magento\Framework\View\Element\Template as LocalTemplate;
use Magento\Framework\View\Element\Template\Context as LocalContext;
use PGI\Module\PGModule\Components\Output;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGModule\Services\Providers\OutputProvider;
use PGI\Module\PGSystem\Services\Container;

class DisplayFrontofficeOutput extends LocalTemplate
{
    public function __construct(LocalContext $context)
    {
        parent::__construct($context);

        require_once PAYGREEN_BOOTSTRAP_SRC;
    }

    protected function getService($name)
    {
        return Container::getInstance()->get($name);
    }

    protected function _toHtml()
    {
        /** @var OutputProvider $outputProvider */
        $outputProvider = $this->getService('provider.output');

        /** @var Output $output */
        $output = $outputProvider->getZoneOutput('FRONT.PAYGREEN');

        /** @var Logger $logger */
        $logger = $this->getService('logger.view');

        $content = $output->getContent();

        $size = strlen($content);

        $logger->debug("Displaying output content with size of {$size} octets.");

        return $content;
    }
}
