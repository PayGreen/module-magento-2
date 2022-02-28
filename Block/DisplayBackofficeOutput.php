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

use Magento\Framework\View\Element\Template as LocalTemplate;
use Magento\Framework\View\Element\Template\Context as LocalContext;
use PGI\Module\PGFramework\Services\Handlers\OutputHandler;
use PGI\Module\PGLog\Interfaces\LoggerInterface;
use PGI\Module\PGModule\Services\Providers\OutputProvider;
use PGI\Module\PGSystem\Services\Container;

class DisplayBackofficeOutput extends LocalTemplate
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

        /** @var LoggerInterface $outputHandler */
        $logger = $this->getService('logger.view');

        /** @var OutputHandler $output */
        $output = $outputProvider->getZoneOutput('BACK.PAYGREEN');

        $content = $output->getContent();

        $size = strlen($content);

        $logger->debug("Displaying output content with size of {$size} octets.");

        return $content;
    }
}
