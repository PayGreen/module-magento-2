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
 * @version   0.3.4
 */

namespace Paygreen\Payment\Controller\Adminhtml\Config;

use PGFrameworkServicesSettings;
use Paygreen\Payment\Foundations\AbstractActionAdmin;
use Magento\Framework\Controller\ResultFactory;

class Save extends AbstractActionAdmin
{
    private $parameters = [
        'public_key' => [
            'target' => 'public_key',
            'default' => ''
        ],
        'private_key' => [
            'target' => 'private_key',
            'default' => ''
        ],
        'active' => [
            'target' => 'active',
            'default' => 1
        ],
//        'visibility' => [
//            'target' => 'visibility',
//            'default' => 0
//        ],
        'title' => [
            'target' => 'title',
            'default' => ''
        ],
        'payment_confirmation_button' => [
            'target' => 'payment_confirmation_button',
            'default' => 'Payer votre commande'
        ],
        'payment_success_text' => [
            'target' => 'payment_success_text',
            'default' => 'Votre paiement a été accepté.'
        ],
        'payment_error_text' => [
            'target' => 'payment_error_text',
            'default' => 'Votre paiement a été refusé.'
        ],
        'behavior_payment_refused' => [
            'target' => 'behavior_payment_refused',
            'default' => 'none'
        ],
        'behavior_transmit_refund' => [
            'target' => 'behavior_transmit_refund',
            'default' => 'none'
        ]
    ];

    public function execute()
    {
        $post = (array) $this->getRequest()->getPost();

        /** @var \PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        /** @var \PGFrameworkServicesHandlersCacheHandler $cacheHandler */
        $cacheHandler = $this->getService('handler.cache');

        if (!empty($post)) {
            /** @var PGFrameworkServicesSettings $settings */
            $settings = $this->getService('settings');

            foreach ($this->parameters as $name => $data) {
                $default = isset($data['default']) ? $data['default'] : null;
                $value = isset($post[$name]) ? $post[$name] : $default;

                $logger->debug("Setting '{$data['target']}' parameter with value :", $value);

                $settings->set($data['target'], $value);
            }

            $this->addSuccessMessage('config.result.success');
        } else {
            $logger->error("Request is empty.");
        }

        $cacheHandler->clearCache();

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*/index');

        return $resultRedirect;
    }
}
