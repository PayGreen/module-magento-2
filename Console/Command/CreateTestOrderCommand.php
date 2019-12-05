<?php
/**
 * 2014 - 2019 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.5
 */

namespace Paygreen\Payment\Console\Command;

use Exception;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Customer;
use Magento\Framework\UrlInterface;
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;
use Magento\Store\Model\Store;
use PGClientEntitiesResponse;
use PGDomainInterfacesEntitiesButtonInterface;
use PGDomainServicesHandlersPaymentCreationHandler;
use PGFrameworkServicesLogger;
use PGModuleProvisionersPrePaymentProvisioner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Quote\Model\QuoteFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Quote\Model\QuoteManagement;
use Magento\Framework\App\State;

require_once PAYGREEN_BOOTSTRAP_SRC;

class CreateTestOrderCommand extends Command
{
    private $orderData = [
        'currency_id'  => 'EUR',
        'email'        => 'client@test.fr',
        'shipping_address' =>[
            'firstname'    => 'Laurent', //address Details
            'lastname'     => 'Barre',
            'street' => '123 Demo',
            'city' => 'Mageplaza',
            'country_id' => 'FR',
            'region' => 'xxx',
            'postcode' => '12345',
            'telephone' => '0123456789',
            'fax' => '32423',
            'save_in_address_book' => 1
        ],
        'items'=> [ //array of product which order you want to create
            ['product_id'=>'1','price'=>1,'qty'=>1],
            ['product_id'=>'2','price'=>2,'qty'=>2]
        ]
    ];

    protected function getService($name)
    {
        return \PGFrameworkContainer::getInstance()->get($name);
    }

    protected function configure()
    {
        $this
            ->setName('paygreen:test:create-order')
            ->setDescription('Create test order.')
            ->addArgument('mode', InputArgument::OPTIONAL, 'Payment mode.', 'CASH')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        $mode = $input->getArgument('mode');

        try {
            /** @var State $appState */
            $appState = $this->getService('magento')->get('Magento\Framework\App\State');

            $appState->setAreaCode('frontend');

            /** @var PGClientEntitiesResponse $response */
            $response = $this->buildOrder($mode);

            if (!$response->isSuccess()) {
                throw new Exception("Unable to create Order.");
            }

            $orderId = $response->data->metadata->order_id;

            $output->writeln("Order created with ID #$orderId.");
        } catch (Exception $exception) {
            $logger->critical("Error during execute 'paygreen:test:create-order' command.", $exception);

            throw $exception;
        }
    }

    /**
     * @param $mode
     * @return PGClientEntitiesResponse
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \PGClientExceptionsPaymentException
     * @throws \PGClientExceptionsPaymentRequestException
     * @throws \Exception
     */
    private function buildOrder($mode)
    {
        /** @var PGDomainInterfacesEntitiesButtonInterface $button */
        $button = $this->getButton($mode);

        /** @var Store $store */
        $store = $this->getStore();

        $quote = $this->createQuote($store);

        $customer = $this->getCustomer($store);

        $quote->assignCustomer($customer);

        /** @var Order $order */
        $order = $this->createOrder($quote);

        $response = $this->createPayment($order, $customer, $button);

        return $response;
    }

    /**
     * @param Quote $quote
     * @return \Magento\Sales\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function createOrder(Quote $quote)
    {
        $quote->setCurrency();

        /** @var ProductRepositoryInterface $productRepository */
        $productRepository = $this->getService('magento')->get('Magento\Catalog\Api\ProductRepositoryInterface');

        foreach($this->orderData['items'] as $item){
            $product = $productRepository->getById($item['product_id']);

            $quote->addProduct(
                $product,
                $item['qty']
            );
        }

        //Set Address to quote
        $quote->getBillingAddress()->addData($this->orderData['shipping_address']);
        $quote->getShippingAddress()->addData($this->orderData['shipping_address']);

        // Collect Rates and Set Shipping & Payment Method

        $shippingAddress = $quote->getShippingAddress();

        $shippingAddress
            ->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod('flatrate_flatrate')
        ;

        $quote->setPaymentMethod('paygreenpayment'); //payment method

        $quote->setInventoryProcessed(false); //not effetc inventory

        $quote->save(); //Now Save quote and your quote is ready

        // Set Sales Order Payment
        $quote->getPayment()->importData(['method' => 'paygreenpayment']);

        // Collect Totals & Save Quote
        $quote->collectTotals()->save();

        /** @var QuoteManagement $quoteManagement */
        $quoteManagement = $this->getService('magento')->get('Magento\Quote\Model\QuoteManagement');

        $order = $quoteManagement->submit($quote);

        $order->setEmailSent(0);

        return $order;
    }

    /**
     * @param Order $order
     * @param CustomerInterface $customer
     * @param PGDomainInterfacesEntitiesButtonInterface $button
     * @return PGClientEntitiesResponse
     * @throws \PGClientExceptionsPaymentException
     * @throws \PGClientExceptionsPaymentRequestException
     */
    private function createPayment(Order $order, CustomerInterface $customer, PGDomainInterfacesEntitiesButtonInterface $button)
    {
        /** @var PGModuleProvisionersPrePaymentProvisioner $prePaymentProvisioner */
        $prePaymentProvisioner = new PGModuleProvisionersPrePaymentProvisioner($order, $customer);

        /** @var PGDomainServicesHandlersPaymentCreationHandler $paymentCreationHandler */
        $paymentCreationHandler = $this->getService('handler.payment_creation');

        /** @var UrlInterface $urlBuilder */
        $urlBuilder = $this->getService('magento')->get('Magento\Framework\UrlInterface');

        /** @var PGClientEntitiesResponse $response */
        $response = $paymentCreationHandler->createPayment($prePaymentProvisioner, $button, array(
            'returned_url' => $urlBuilder->getUrl('pgfront/payment/validate'),
            'notified_url' => $urlBuilder->getUrl('pgfront/payment/notify')
        ));

        return $response;
    }

    private function getButton($mode)
    {
        /** @var PGDomainInterfacesEntitiesButtonInterface[] $buttons */
        $buttons = $this->getService('manager.button')->getAll();

        /** @var PGDomainInterfacesEntitiesButtonInterface $button */
        $button = null;

        /** @var PGDomainInterfacesEntitiesButtonInterface $btn */
        foreach ($buttons as $btn) {
            if ($btn->getPaymentMode() === $mode) {
                $button = $btn;
            }
        }

        if ($button === null) {
            throw new Exception("Button not found : '$mode'.");
        }

        return $button;
    }

    /**
     * @return \Magento\Store\Api\Data\StoreInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getStore()
    {
        /** @var StoreManagerInterface $storeManager */
        $storeManager = $this->getService('magento')->get('Magento\Store\Model\StoreManagerInterface');

        return $storeManager->getStore();
    }

    /**
     * @param StoreInterface $store
     * @return \Magento\Quote\Model\Quote
     */
    private function createQuote(Store $store)
    {
        /** @var QuoteFactory $quoteFactory */
        $quoteFactory = $this->getService('magento')->get('Magento\Quote\Model\QuoteFactory');

        $quote = $quoteFactory->create();

        $quote->setStore($store);

        return $quote;
    }

    /**
     * @param StoreInterface $store
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getCustomer(Store $store)
    {
        /** @var CustomerFactory $customerFactory */
        $customerFactory = $this->getService('magento')->get('Magento\Customer\Model\CustomerFactory');

        $customer = $customerFactory->create();

        $customer->setStore($store);

        $customer->loadByEmail('client@test.fr');

        if (!$customer->getId()) {
            throw new Exception("Customer not found : client@test.fr");
        }

        /** @var CustomerRepositoryInterface $customerRepository */
        $customerRepository = $this->getService('magento')->get('Magento\Customer\Api\CustomerRepositoryInterface');

        return $customerRepository->getById($customer->getId());
    }
}