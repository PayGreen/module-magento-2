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
 * @version   2.5.1
 *
 */

namespace PGI\Module\FOPayment\Services\OutputBuilders;

use PGI\Module\PGModule\Components\Output as OutputComponent;
use PGI\Module\PGModule\Foundations\AbstractOutputBuilder;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGServer\Components\Resources\StyleFile as StyleFileResourceComponent;

/**
 * Class PaymentFooterOutputBuilder
 * @package FOPayment\Services\OutputBuilders
 */
class PaymentFooterOutputBuilder extends AbstractOutputBuilder
{
    /** @var Settings */
    private $settings;

    /** @var string */
    private $backlink;

    public function __construct(
        Settings $settings,
        $backlink
    ) {
        parent::__construct();

        $this->settings = $settings;
        $this->backlink = $backlink;
    }

    /**
     * @inheritDoc
     */
    public function build(array $data = array())
    {
        $output = new OutputComponent();

        $output->addResource(new StyleFileResourceComponent('/css/footer.css'));

        $content = $this->getViewHandler()->renderTemplate('footer', array(
            'color' => $this->settings->get('footer_color'),
            'backlink' => $this->backlink
        ));

        $output->setContent($content);

        return $output;
    }
}
