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
 * @version   2.5.0
 *
 */

namespace PGI\Module\BOPayment\Services\Actions;

use PGI\Module\BOModule\Services\Actions\StandardizedDisplayPageAction;
use PGI\Module\BOPayment\Services\Handlers\PaymentStatisticsHandler;
use PGI\Module\PGServer\Components\Resources\Data as DataResourceComponent;
use PGI\Module\PGServer\Components\Responses\Template as TemplateResponseComponent;
use PGI\Module\PGView\Services\Handlers\BlockHandler;
use Exception;

/**
 * Class DisplayPaymentStatisticsPageAction
 * @package BOPayment\Services\Actions
 */
class DisplayPaymentStatisticsPageAction extends StandardizedDisplayPageAction
{
    /** @var PaymentStatisticsHandler */
    private $paymentStatisticsHandler;

    public function __construct(
        BlockHandler $blockHandler,
        PaymentStatisticsHandler $paymentStatisticsHandler
    ) {
        parent::__construct($blockHandler);

        $this->paymentStatisticsHandler = $paymentStatisticsHandler;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function process()
    {
        /** @var TemplateResponseComponent $response */
        $response = parent::process();

        $dataResource = new DataResourceComponent(
            $this->paymentStatisticsHandler->getStatisticsData()
        );

        $response->addResource($dataResource);

        return $response;
    }
}
