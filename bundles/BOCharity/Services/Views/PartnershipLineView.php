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

namespace PGI\Module\BOCharity\Services\Views;

use PGI\Module\PGIntl\Services\Translator;
use PGI\Module\PGView\Services\View;
use Exception;

/**
 * Class PartnershipLineView
 * @package BOCharity\Services\Views
 */
class PartnershipLineView extends View
{
    /** @var Translator */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;

        $this->setTemplate('charity_partnerships/partnership-line');
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getData()
    {
        $data = parent::getData();
        $partnershipData = $data['partnership'];

        $viewData = array();

        $viewData['partnership']['idPartnership'] = $partnershipData->idPartnership;
        $viewData['partnership']['associationLogo'] = $partnershipData->association->picture1;
        $viewData['partnership']['associationName'] = $partnershipData->association->name;
        $viewData['partnership']['associationUrl'] = $partnershipData->association->url;
        $viewData['partnership']['associationFieldOfAction'] = $this->handleAssociationFieldOfAction(
            $partnershipData->association->fieldOfAction
        );
        $viewData['partnership']['associationStatus'] = $this->handleAssociationStatus(
            $partnershipData->associationStatus
        );

        return $viewData;
    }

    /**
     * @param string $status
     * @return string
     * @throws Exception
     */
    private function handleAssociationStatus($status)
    {
        return $this->translator->get("data.charity_partnerships.status.$status");
    }

    /**
     * @param string $fieldOfAction
     * @return string
     * @throws Exception
     */
    private function handleAssociationFieldOfAction($fieldOfAction)
    {
        return $this->translator->get("data.charity_partnerships.field_of_action.$fieldOfAction");
    }
}
