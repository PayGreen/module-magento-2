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

namespace PGI\Module\APICharity\Services\Facades;

use Exception;
use PGI\Module\APICharity\Components\Replies\CharityGift as CharityGiftReplyComponent;
use PGI\Module\PGCharity\Services\Handlers\CharityAccountHandler;
use PGI\Module\PGClient\Components\Request as RequestComponent;
use PGI\Module\PGClient\Components\Response as ResponseComponent;
use PGI\Module\PGClient\Exceptions\Response as ResponseException;
use PGI\Module\PGClient\Services\Factories\RequestFactory;
use PGI\Module\PGClient\Services\Sender;
use PGI\Module\PGFramework\Services\Handlers\RequirementHandler;
use PGI\Module\PGModule\Services\Settings;
use stdClass;

/**
 * Class ApiFacade
 * @package APICharity\Services\Facades
 */
class ApiFacade
{
    const VERSION = '1.0.0';

    /** @var Sender */
    private $requestSender;

    /** @var RequestFactory */
    private $requestFactory;

    /** @var Settings */
    private $settings;

    /** @var RequirementHandler */
    private $requirementHandler;

    /**
     * ApiFacade constructor.
     * @param Sender $requestSender
     * @param RequestFactory $requestFactory
     * @param Settings $settings
     * @param RequirementHandler $requirementHandler
     */
    public function __construct(
        Sender $requestSender,
        RequestFactory $requestFactory,
        Settings $settings,
        RequirementHandler $requirementHandler
    ) {
        $this->requestSender = $requestSender;
        $this->requestFactory = $requestFactory;
        $this->settings = $settings;
        $this->requirementHandler = $requirementHandler;
    }

    /**
     * @return RequestFactory
     */
    public function getRequestFactory()
    {
        return $this->requestFactory;
    }

    /**
     * @param RequestFactory $requestFactory
     * @return void
     */
    public function setRequestFactory(RequestFactory $requestFactory)
    {
        $this->requestFactory = $requestFactory;
    }

    /**
     * @return Sender
     */
    public function getRequestSender()
    {
        return $this->requestSender;
    }

    /**
     * @return string
     */
    public function getApiEntryPoint()
    {
        return $this->getRequestFactory()->getAPIHost();
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $client_id
     * @param string $grant_type
     * @return ResponseComponent
     * @throws ResponseException
     */
    public function getOAuthAccess($username, $password, $client_id, $grant_type = 'password')
    {
        $request = $this->getRequestFactory()->buildRequest('oauth_access', array(), false)
            ->setContent(array(
                'username' => $username,
                'password' => $password,
                'client_id' => $client_id,
                'grant_type' => $grant_type
            ));

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param string $refresh_token
     * @param string $client_id
     * @param string $grant_type
     * @return ResponseComponent
     * @throws ResponseException
     */
    public function refreshOAuthAccess($refresh_token, $client_id, $grant_type = 'refresh_token')
    {
        $request = $this->getRequestFactory()->buildRequest('oauth_refresh_access', array(), false)
            ->setContent(array(
                'refresh_token' => $refresh_token,
                'client_id' => $client_id,
                'grant_type' => $grant_type
            ));

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param string $account_id
     * @return ResponseComponent
     * @throws ResponseException
     */
    public function getAccountInfos($account_id)
    {
        $request = $this->getRequestFactory()->buildRequest('get_account_infos', array(
            'account_id' => $account_id
        ));

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param string $account_id
     * @param string $username
     * @return ResponseComponent
     * @throws ResponseException
     */
    public function getUserData($account_id, $username)
    {
        $request = $this->getRequestFactory()->buildRequest('get_user_data', array(
            'account_id' => $account_id,
            'username' => $username
        ));

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param int $pageNumber Page number is 1 by default
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getAvailableAssociations($pageNumber = 1)
    {
        $request = $this->getRequestFactory()->buildRequest('list_available_associations', array(
            'page' => $pageNumber
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param string $association_id
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getAssociation($association_id)
    {
        $request = $this->getRequestFactory()->buildRequest('get_association', array(
            'association_id' => $association_id
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param int $page_number Page number is 1 by default
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getPartnerships($page_number = 1)
    {
        $request = $this->getRequestFactory()->buildRequest('list_partnerships', array(
            'page' => $page_number
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param string $partnership_id
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getPartnership($partnership_id)
    {
        $request = $this->getRequestFactory()->buildRequest('get_partnership', array(
            'partnership_id' => $partnership_id
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param string $association_id
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function requestPartnership($association_id)
    {
        $request = $this->getRequestFactory()->buildRequest('request_partnership')->setContent(array(
            'idAssociation' => $association_id
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param string $partnership_id
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function cancelPartnership($partnership_id)
    {
        $request = $this->getRequestFactory()->buildRequest('cancel_partnership', array(
            'partnership_id' => $partnership_id
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param string $partnership_id
     * @param string $partnership_account_status The Status of the Partnership on the Account side. ['ACCEPT', 'DELETE']
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function updatePartnership($partnership_id, $partnership_account_status)
    {
        $request = $this->getRequestFactory()->buildRequest('cancel_partnership', array(
            'partnership_id' => $partnership_id
        ))->setContent(array(
            'accountStatus' => $partnership_account_status
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getPartnershipGroups()
    {
        $request = $this->getRequestFactory()->buildRequest('list_partnership_groups');

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param string $external_id
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getPartnershipGroup($external_id)
    {
        $request = $this->getRequestFactory()->buildRequest('get_partnership_group', array(
            'external_id' => $external_id
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param array $partnership_ids
     * @param string $external_id Used to name and retrieve your PartnershipGroup. It must be unique to your User.
     * @param bool $is_default If is sent, it will make the created PartnershipGroup the new default one.
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function createPartnershipGroup($partnership_ids, $external_id, $is_default)
    {
        $request = $this->getRequestFactory()->buildRequest('create_partnership_group')->setContent(array(
            'partnershipIds' => $partnership_ids,
            'externalId' => $external_id,
            'isDefault' => $is_default
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param array $partnership_ids
     * @param string $external_id Used to name and retrieve your PartnershipGroup. It must be unique to your User.
     * @param bool $is_default If is sent, it will make the created PartnershipGroup the new default one.
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function updatePartnershipGroup($partnership_ids, $external_id, $is_default)
    {
        $request = $this->getRequestFactory()->buildRequest('update_partnership_group', array(
            'externalId' => $external_id
        ))->setContent(array(
            'partnershipIds' => $partnership_ids,
            'externalId' => $external_id,
            'isDefault' => $is_default
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param string $external_id
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function cancelPartnershipGroup($external_id)
    {
        $request = $this->getRequestFactory()->buildRequest('cancel_partnership_group', array(
            'externalId' => $external_id
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param int $partnership_group Allows to filter all rules that are not related to a particular PartnershipGroup
     * @param int $page_number Page limit is 50.
     * @param bool $onlypast Allows to display only past rules
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getPartnershipGroupRules($partnership_group, $page_number = 1, $onlypast = false)
    {
        $request = $this->getRequestFactory()->buildRequest('list_partnership_group_rules', array(
            'page' => $page_number,
            'partnershipGroup' => $partnership_group,
            'onlypast' => $onlypast
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * Return both the PartnershipGroupRule and the associated PartnershipGroup
     *
     * @param int $id_partnership_group_rule
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getPartnershipGroupRule($id_partnership_group_rule)
    {
        $request = $this->getRequestFactory()->buildRequest('get_partnership_group_rule', array(
            'idPartnershipGroupRule' => $id_partnership_group_rule
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param array $rule_type Type of PartnershipGroupRule. ['timeslot']
     * @param string $activated_partnership_group_id PartnershipGroup identification that will be activated by this rule
     * @param string $activation_start_time
     * @param string $activation_end_time
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function createPartnershipGroupRule(
        $rule_type,
        $activated_partnership_group_id,
        $activation_start_time,
        $activation_end_time
    ) {
        $request = $this->getRequestFactory()->buildRequest('create_partnership_group')->setContent(array(
            'ruleType' => $rule_type,
            'activatedPartnershipGroupId' => $activated_partnership_group_id,
            'activationStartTime' => $activation_start_time,
            'activationEndTime' => $activation_end_time
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param int $id_partnership_group_rule
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function deactivatePartnershipGroupRule($id_partnership_group_rule)
    {
        $request = $this->getRequestFactory()->buildRequest('deactivate_partnership_group_rule', array(
            'idPartnershipGroupRule' => $id_partnership_group_rule
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getCurrentPartnershipGroupRule()
    {
        $request = $this->getRequestFactory()->buildRequest('get_current_partnership_group_rule');

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param string $external_id
     * @param string $association_id
     * @param int $only_not_refundable
     * @param string $start_date ('Y-m-d' format)
     * @param string $end_date ('Y-m-d' format)
     * @param int $page_number Page number is 1 by default
     * @param int $page_limit Page limit is 50
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getDonations(
        $external_id,
        $association_id,
        $only_not_refundable,
        $start_date,
        $end_date,
        $page_number,
        $page_limit
    ) {
        $request = $this->getRequestFactory()->buildRequest('list_donations', array(
            'externalId' => $external_id,
            'idAssociation' => $association_id,
            'onlyNotRefundable' => $only_not_refundable,
            'start' => $start_date,
            'end' => $end_date,
            'page' => $page_number,
            'limit' => $page_limit
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param int $donation_id
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getDonation($donation_id)
    {
        $request = $this->getRequestFactory()->buildRequest('get_donation', array(
            'donation_id' => $donation_id
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param int $association_id ,
     * @param string $donation_type ['ROUNDING', 'CCARBON', 'MECENAT']
     * @param int $donation_amount
     * @param int $total_amount
     * @param string $currency
     * @param stdClass $buyer
     * @param bool $is_a_pledge Whether the Donation will be registered as a successful Donation or a pledged Donation
     * @param string $donation_reference
     * @param string $tracking_token Single use unique identifier defined by you. Used to link the DonationDisplay and the Donation
     * @return CharityGiftReplyComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function createDonation(
        $association_id,
        $donation_type,
        $donation_amount,
        $total_amount,
        $currency,
        $buyer,
        $is_a_pledge,
        $donation_reference = '',
        $tracking_token = ''
    ) {
        $request = $this->getRequestFactory()->buildRequest('create_donation')->setContent(array(
            'idAssociation' => $association_id,
            'type' => $donation_type,
            'donationAmount' => $donation_amount,
            'totalAmount' => $total_amount,
            'currency' => $currency,
            'buyer' => $buyer,
            'isAPledge' => $is_a_pledge,
            'donationReference' => $donation_reference,
            'trackingToken' => $tracking_token
        ));

        $request = $this->checkTestMode($request);

        $response = $this->getRequestSender()->sendRequest($request);

        return new CharityGiftReplyComponent($response->data);
    }

    /**
     * @param int $donation_id
     * @param string $status Only the SUCCESS value is authorized.
     * @param string $tracking_token Single use unique identifier defined by you. Used to link the DonationDisplay and the Donation
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function fulfillPledgedDonation($donation_id, $status, $tracking_token)
    {
        $request = $this->getRequestFactory()->buildRequest('fulfill_pledged_donation', array(
            'donation_id' => $donation_id
        ))->setContent(array(
            'status' => $status,
            'trackingToken' => $tracking_token
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * Only a Donation in a SUCCESS status can be refunded. The operation will change the Donation status to REFUND.
     *
     * @param int $donation_id
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function refundDonation($donation_id)
    {
        $request = $this->getRequestFactory()->buildRequest('refund_donation', array(
            'donation_id' => $donation_id
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param string $page_number
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getDonationDisplays($page_number)
    {
        $request = $this->getRequestFactory()->buildRequest('list_donation_display', array(
            'page' => $page_number
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param int $donation_display_id
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getDonationDisplay($donation_display_id)
    {
        $request = $this->getRequestFactory()->buildRequest('get_donation_display', array(
            'donation_display_id' => $donation_display_id
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param string $user_agent Complete UserAgent header
     * @param string $tracking_token Single use unique identifier defined by you. Used to link the DonationDisplay and the Donation
     * @param string $buyer_external_id Permanent use unique identifier defined by you. Used to link the DonationDisplay and the Donation
     * @param string $ipv4
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function recordDonationDisplay(
        $user_agent,
        $tracking_token,
        $buyer_external_id,
        $ipv4
    ) {
        $request = $this->getRequestFactory()->buildRequest('record_donation_display')->setContent(array(
            'userAgent' => $user_agent,
            'trackingToken' => $tracking_token,
            'buyerExternalId' => $buyer_external_id,
            'ipV4' => $ipv4
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param string $begin_date Report begin date (YYYY-MM-DD)
     * @param string $end_date Report end date (YYYY-MM-DD)
     * @param int $association_id
     * @param int $user_id
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getDonationReport($begin_date, $end_date, $association_id, $user_id)
    {
        $request = $this->getRequestFactory()->buildRequest('get_donation_report', array(
            'begin' => $begin_date,
            'end' => $end_date,
            'association' => $association_id,
            'user' => $user_id
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param int $page_number Page limit is 50
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getDonationsHistory($page_number)
    {
        $request = $this->getRequestFactory()->buildRequest('list_donations_history', array(
            'page' => $page_number
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param int $amount
     * @param string $currency
     * @return ResponseComponent
     * @throws ResponseException
     */
    public function roundUpNumber($amount, $currency)
    {
        $request = $this->getRequestFactory()->buildRequest('round_up_number')->setContent(array(
            'amount' => $amount,
            'currency' => $currency
        ));

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param RequestComponent $request
     * @return RequestComponent
     * @throws Exception
     */
    private function checkTestMode(RequestComponent $request)
    {
        if ($this->isTestModeActivated()) {
            if (!$this->isAccessAvailable()) {
                throw new Exception('You must have a signed SEPA mandate to be able to call the Charity API.');
            }

            $request->addHeaders(array(
                "X-TEST-MODE: 1"
            ));
        }

        return $request;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function isTestModeActivated()
    {
        return $this->settings->get('charity_test_mode');
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function isAccessAvailable()
    {
        return $this->requirementHandler->isFulfilled('charity_access_available');
    }
}
