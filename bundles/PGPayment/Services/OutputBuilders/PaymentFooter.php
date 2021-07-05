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
 * @version   2.1.0
 *
 */

/**
 * Class PGPaymentServicesOutputBuildersPaymentFooter
 * @package PGWooPayment\Services\OutputBuilders
 */
class PGPaymentServicesOutputBuildersPaymentFooter extends PGModuleFoundationsOutputBuilder
{
    /** @var PGModuleServicesSettings */
    private $settings;

    /** @var string */
    private $backlink;

    public function __construct(
        PGModuleServicesSettings $settings,
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
        $output = new PGModuleComponentsOutput();

        $output->addResource(new PGServerComponentsResourcesStyleFileResource('/css/footer.css'));

        $content = $this->getViewHandler()->renderTemplate('footer', array(
            'color' => $this->settings->get('footer_color'),
            'backlink' => $this->backlink
        ));

        $output->setContent($content);

        return $output;
    }
}