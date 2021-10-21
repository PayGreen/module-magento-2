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

namespace PGI\Module\FOCharity\Services\Controllers;

use PGI\Module\BOModule\Foundations\Controllers\AbstractBackofficeController;
use PGI\Module\PGCharity\Services\Handlers\CharityHandler;
use PGI\Module\PGCharity\Services\Handlers\CharityPartnershipHandler;
use PGI\Module\PGClient\Exceptions\Response as ResponseException;
use PGI\Module\PGServer\Components\Responses\HTTP as HTTPResponseComponent;
use Exception;
use PGI\Module\PGView\Services\Handlers\ViewHandler;

/**
 * Class CharityPopinController
 * @package FOCharity\Services\Controllers
 */
class CharityPopinController extends AbstractBackofficeController
{
    /** @var ViewHandler */
    private $viewHandler;

    /** @var CharityPartnershipHandler */
    private $charityPartnershipHandler;

    /** @var CharityHandler */
    private $charityHandler;

    public function __construct(
        ViewHandler $viewHandler,
        CharityPartnershipHandler $charityPartnershipHandler,
        CharityHandler $charityHandler
    ) {
        $this->viewHandler = $viewHandler;
        $this->charityPartnershipHandler = $charityPartnershipHandler;
        $this->charityHandler = $charityHandler;
    }

    /**
     * @return HTTPResponseComponent
     * @throws Exception
     */
    public function displayAction()
    {
        $isCharityTestModeActivated = $this->getSettings()->get('charity_test_mode');

        $templateContent = $this->viewHandler->renderTemplate('charity-popin', array(
            'partnerships' => $this->getPartnerships(),
            'giftAmount' => $this->charityHandler->getCurrentAmount(),
            'associationSelectedId' => $this->charityHandler->getCurrentPartnershipPrimary(),
            'hasGift' => $this->charityHandler->hasGift(),
            'isCharityTestModeActivated' => $isCharityTestModeActivated
        ));

        $response = new HTTPResponseComponent($this->getRequest());
        $response->setContent($templateContent);

        return $response;
    }

    /**
     * @throws ResponseException
     */
    private function getPartnerships()
    {
        $partnerships = $this->charityPartnershipHandler->getPartnerships();

        return array_filter($partnerships, function ($partnership) {
            return ($partnership->associationStatus === 'ACCEPT');
        });
    }
}
