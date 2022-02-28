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

namespace PGI\Module\BOPayment\Services\Views;

use PGI\Module\PGPayment\Services\Handlers\PaymentButtonHandler;
use PGI\Module\PGPayment\Services\Managers\ButtonManager;
use PGI\Module\PGView\Services\View;
use Exception;

/**
 * Class ButtonLineView
 * @package BOPayment\Services\Views
 */
class ButtonLineView extends View
{
    /** @var ButtonManager */
    private $buttonManager;

    /** @var PaymentButtonHandler */
    private $paymentButtonHandler;

    public function __construct(
        ButtonManager $buttonManager,
        PaymentButtonHandler $paymentButtonHandler
    ) {
        $this->buttonManager = $buttonManager;
        $this->paymentButtonHandler = $paymentButtonHandler;

        $this->setTemplate('button/list-line-button');
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getData()
    {
        $data = parent::getData();
        $buttonArray = $data['button'];

        $button= $this->buttonManager->getByPrimary($buttonArray['id']);

        $data['button']['errors'] = $this->buttonManager->check($button);
        $data['button']['imageUrl'] =  $this->paymentButtonHandler->getButtonFinalUrl($button);

        return $data;
    }
}
