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

namespace Paygreen\Payment\Block;

use Magento\Framework\View\Element\Template as LocalTemplate;
use Magento\Framework\View\Element\Template\Context as LocalContext;
use PGI\Module\PGMagento\Services\Compilers\StaticResourceCompiler;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGModule\Services\Providers\OutputProvider;
use PGI\Module\PGSystem\Services\Container;

class DisplayBackofficeHeader extends LocalTemplate
{
    public function __construct(LocalContext $context)
    {
        parent::__construct($context);

        require_once PAYGREEN_BOOTSTRAP_SRC;

        /** @var Logger $logger */
        $logger = $this->getService('logger.view');

        $logger->debug('Successfully init backoffice header block.');
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

        $channels = array('BACK.PAYGREEN');

        $logger->debug("Back page detected. Loading associated ressources.");

        $resources = $outputProvider->getResources($channels);

        return $magentoResourceCompiler->compileResources($resources);
    }
}
