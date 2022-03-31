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
 * @version   2.6.1
 *
 */

return array (
'behaviors' =>
array (
'detailed_logs' =>
array (
'type' => 'service',
'service' => 'behavior.detailed_logs',
'method' => 'isDetailedLogActivated',
),
'cancel_order_on_refused_payment' =>
array (
'type' => 'user',
'key' => 'cancel_order_on_refused_payment',
),
'cancel_order_on_canceled_payment' =>
array (
'type' => 'fixed',
'value' => false,
),
'transmission_on_delivery_confirmation' =>
array (
'type' => 'user',
'key' => 'behavior_delivery_confirmation',
),
'behavior_payment_insite' =>
array (
'type' => 'user',
'key' => 'behavior_payment_insite',
),
'use_transaction_lock' =>
array (
'type' => 'fixed',
'value' => true,
),
'transmission_on_refund' =>
array (
'type' => 'user',
'key' => 'behavior_payment_refund',
),
),
'listeners' =>
array (
'run_diagnostics' =>
array (
'event' =>
array (
0 => 'module.install',
1 => 'module.upgrade',
),
'service' => 'handler.diagnostic',
'priority' => 750,
),
'upgrade_static_files' =>
array (
'event' =>
array (
0 => 'module.install',
1 => 'module.upgrade',
),
'service' => 'listener.setup.static_files',
'method' => 'installStaticFiles',
),
'upgrade_module' =>
array (
'service' => 'listener.upgrade',
'event' => 'module.upgrade',
'priority' => 25,
),
'install_module_database' =>
array (
'event' =>
array (
0 => 'module.install',
),
'service' => 'listener.database.runner',
'priority' => 50,
'config' =>
array (
'script' =>
array (
0 => 'PGModule:setting/clean.sql',
1 => 'PGModule:setting/install.sql',
),
),
),
'install_default_settings' =>
array (
'event' => 'module.install',
'service' => 'listener.settings.install_default',
'priority' => 150,
),
'uninstall_settings' =>
array (
'event' => 'module.uninstall',
'service' => 'listener.settings.uninstall',
'priority' => 900,
),
'uninstall_module_database' =>
array (
'event' =>
array (
0 => 'module.uninstall',
),
'service' => 'listener.database.runner',
'priority' => 950,
'config' =>
array (
'script' => 'PGModule:setting/clean.sql',
),
),
'install_intl_database' =>
array (
'event' =>
array (
0 => 'module.install',
),
'service' => 'listener.database.runner',
'priority' => 50,
'config' =>
array (
'script' =>
array (
0 => 'PGIntl:translation/clean.sql',
1 => 'PGIntl:translation/install.sql',
),
),
),
'install_default_translations' =>
array (
'event' =>
array (
0 => 'module.install',
),
'service' => 'listener.setup.install_default_translations',
),
'reset_translation_cache' =>
array (
'event' =>
array (
0 => 'module.upgrade',
),
'service' => 'listener.setup.reset_translation_cache',
),
'uninstall_intl_database' =>
array (
'event' =>
array (
0 => 'module.uninstall',
),
'service' => 'listener.database.runner',
'priority' => 950,
'config' =>
array (
'script' => 'PGIntl:translation/clean.sql',
),
),
'clear_smarty_cache' =>
array (
'event' => 'module.upgrade',
'service' => 'listener.upgrade.clear_smarty_cache',
),
'pre_filling_cron_tabs' =>
array (
'event' =>
array (
0 => 'module.install',
1 => 'module.upgrade',
),
'service' => 'listener.cron.tabs.pre_filling',
),
'cleaning_cron_tabs' =>
array (
'event' =>
array (
0 => 'module.upgrade',
),
'service' => 'listener.cron.tabs.cleaning',
),
'display_shop_context_requirement' =>
array (
'event' => 'action.backoffice.system.display',
'service' => 'listener.action.shop_context_backoffice',
),
'display_support_page' =>
array (
'event' => 'action.backoffice.support.display',
'service' => 'listener.action.display_support_page',
),
'install_payment_database' =>
array (
'event' =>
array (
0 => 'module.install',
),
'service' => 'listener.database.runner',
'priority' => 50,
'config' =>
array (
'script' =>
array (
0 => 'PGPayment:button/clean.sql',
1 => 'PGPayment:button/install.sql',
2 => 'PGPayment:category_has_payment/clean.sql',
3 => 'PGPayment:category_has_payment/install.sql',
4 => 'PGPayment:lock/clean.sql',
5 => 'PGPayment:lock/install.sql',
6 => 'PGPayment:processing/clean.sql',
7 => 'PGPayment:processing/install.sql',
8 => 'PGPayment:transaction/clean.sql',
9 => 'PGPayment:transaction/install.sql',
10 => 'PGPayment:recurring_transaction/clean.sql',
11 => 'PGPayment:recurring_transaction/install.sql',
),
),
),
'install_default_button' =>
array (
'event' => 'module.install',
'service' => 'listener.setup.install_default_button',
),
'refund_order' =>
array (
'event' => 'order.refund',
'service' => 'listener.refund.update_transaction',
),
'uninstall_payment_database' =>
array (
'event' =>
array (
0 => 'module.uninstall',
),
'service' => 'listener.database.runner',
'priority' => 950,
'config' =>
array (
'script' =>
array (
0 => 'PGPayment:button/clean.sql',
1 => 'PGPayment:category_has_payment/clean.sql',
2 => 'PGPayment:lock/clean.sql',
3 => 'PGPayment:processing/clean.sql',
4 => 'PGPayment:transaction/clean.sql',
5 => 'PGPayment:recurring_transaction/clean.sql',
),
),
),
'payment_check_client_compatibility' =>
array (
'event' => 'module.install',
'service' => 'listener.setup.payment_client_compatibility_checker',
'method' => 'checkCompatibility',
'priority' => 100,
),
'display_connexion_requirement' =>
array (
'event' =>
array (
0 => 'action.backoffice.account.display',
1 => 'action.backoffice.system.display',
),
'service' => 'listener.action.display_backoffice',
),
'create_order_states' =>
array (
'event' => 'listener.setup.order_states_creation',
'service' => 'handler.diagnostic',
'method' => 'createOrderStates',
),
'create_invoice' =>
array (
'event' => 'order.validation',
'service' => 'listener.order_validation.invoice_creation',
'method' => 'createInvoice',
),
'update_order_history' =>
array (
'event' => 'order.validation',
'service' => 'listener.order_validation.invoice_creation',
'method' => 'saveOrderHistory',
'priority' => 250,
),
),
'media' =>
array (
'baseurl' => '/pub/media/paygreen',
),
'cache' =>
array (
'entries' =>
array (
'translations-fr' =>
array (
'ttl' => 604800,
'format' => 'array',
),
'translations-en' =>
array (
'ttl' => 604800,
'format' => 'array',
),
'account-infos' =>
array (
'ttl' => 3600,
'format' => 'object',
),
'status-shop' =>
array (
'ttl' => 3600,
'format' => 'object',
),
'payment-types' =>
array (
'ttl' => 86400,
'format' => 'object',
),
),
),
'settings' =>
array (
'entries' =>
array (
'behavior_detailed_logs' =>
array (
'type' => 'bool',
'system' => true,
'default' => false,
),
'use_cache' =>
array (
'type' => 'bool',
'system' => true,
'default' => true,
),
'last_update' =>
array (
'type' => 'string',
'system' => true,
),
'crontab_global' =>
array (
'type' => 'array',
'global' => true,
),
'crontab_shop' =>
array (
'type' => 'array',
),
'ssl_security_skip' =>
array (
'type' => 'bool',
'global' => true,
'default' => false,
),
'shop_identifier' =>
array (
'type' => 'string',
),
'cron_activation' =>
array (
'type' => 'bool',
'default' => true,
'global' => true,
),
'cron_activation_mode' =>
array (
'type' => 'string',
'default' => 'AJAX',
'global' => true,
),
'oauth_access' =>
array (
'type' => 'array',
'default' =>
array (
),
),
'payment_activation' =>
array (
'type' => 'bool',
'default' => true,
),
'behavior_payment_refund' =>
array (
'type' => 'bool',
'default' => false,
),
'behavior_delivery_confirmation' =>
array (
'type' => 'bool',
'default' => false,
),
'behavior_payment_insite' =>
array (
'type' => 'bool',
'default' => false,
),
'shipping_deactivated_payment_modes' =>
array (
'type' => 'array',
'default' =>
array (
),
),
'ipn_reception_method' =>
array (
'global' => true,
'default' => 'POST',
),
'cancel_order_on_refused_payment' =>
array (
'type' => 'bool',
'default' => false,
),
'admin_only_visibility' =>
array (
'type' => 'bool',
'default' => false,
),
'footer_display' =>
array (
'type' => 'bool',
'default' => true,
),
'footer_color' =>
array (
'type' => 'string',
'default' => 'green',
),
'private_key' =>
array (
'type' => 'string',
'private' => true,
),
'public_key' =>
array (
'type' => 'string',
'private' => true,
),
'api_server' =>
array (
'type' => 'string',
'global' => true,
'default' => 'paygreen.fr',
),
'use_https' =>
array (
'type' => 'bool',
'global' => true,
'default' => true,
),
'oauth_ip_source' =>
array (
'type' => 'string',
'global' => true,
'default' => null,
),
'regenerate_cart_on_cancelled_payment' =>
array (
'type' => 'bool',
'default' => true,
),
'regenerate_cart_on_refused_payment' =>
array (
'type' => 'bool',
'default' => true,
),
),
'officers' =>
array (
'basic' => 'officer.settings.database.basic',
'global' => 'officer.settings.database.global',
'system' => 'officer.settings.configuration.global',
),
),
'outputs' =>
array (
'back_office_paygreen' =>
array (
'target' => 'BACK.PAYGREEN',
'builder' => 'back_office_paygreen',
'clean' => false,
),
'front_office_paygreen' =>
array (
'target' => 'FRONT.PAYGREEN',
'builder' => 'front_office_paygreen',
'clean' => false,
),
'global_front_office_paygreen' =>
array (
'target' => 'FRONT.HEAD',
'builder' => 'global_front_office_paygreen',
'clean' => false,
),
'global_cron_launcher' =>
array (
'target' => 'FRONT.HEAD',
'builder' => 'cron_launcher',
'clean' => true,
'requirements' =>
array (
0 => 'cron_activation',
),
),
'success_payment_message' =>
array (
'target' => 'FRONT.FUNNEL.CONFIRMATION',
'builder' => 'success_payment_message',
'clean' => true,
'requirements' =>
array (
0 => 'payment_activation',
),
),
'payment_footer' =>
array (
'target' => 'FRONT.HOME.FOOTER',
'builder' => 'payment_footer',
'clean' => true,
'requirements' =>
array (
0 => 'footer_displayed',
),
),
'frontoffice_override_css' =>
array (
'target' => 'FRONT.HEAD',
'builder' => 'frontoffice_override_css',
),
),
'static' =>
array (
'public' => null,
'path' => 'static',
'install' =>
array (
'target' => null,
'envs' =>
array (
),
),
'swap' =>
array (
),
'module' => 'Paygreen_Payment',
'folder' => 'static',
),
'upgrades' =>
array (
'1.2.0' =>
array (
'type' => 'media_delete',
'config' =>
array (
'media' => 'default-payment-button.png',
),
),
'2.1.0' =>
array (
0 =>
array (
'type' => 'database',
'config' =>
array (
'script' =>
array (
0 => 'PGPayment:processing/upgrades/001-creation-table.sql',
),
),
),
),
'2.6.0' =>
array (
0 =>
array (
'type' => 'database',
'config' =>
array (
'script' =>
array (
0 => 'PGModule:green/upgrades/001-clean-green-table.sql',
),
),
),
1 =>
array (
'type' => 'remove_settings',
'config' =>
array (
'settings' =>
array (
0 => 'payment_kit_activation',
1 => 'tree_kit_activation',
2 => 'charity_kit_activation',
3 => 'charity_access_token',
4 => 'charity_access_token_validity',
5 => 'charity_access_activation',
6 => 'charity_api_server',
7 => 'charity_client_id',
8 => 'charity_client_username',
9 => 'charity_gift_id',
10 => 'charity_partnerships_positions',
11 => 'charity_refresh_token',
12 => 'charity_refresh_token_validity',
13 => 'charity_test_mode',
14 => 'charity_use_https',
15 => 'tree_access_token',
16 => 'tree_access_token_validity',
17 => 'tree_activation',
18 => 'tree_api_server',
19 => 'tree_bot_activated',
20 => 'tree_bot_color',
21 => 'tree_bot_mobile_activated',
22 => 'tree_bot_side',
23 => 'tree_bot_corner',
24 => 'tree_bot_details_activated',
25 => 'tree_client_id',
26 => 'tree_client_username',
27 => 'tree_contribution_id',
28 => 'tree_details_url',
29 => 'tree_refresh_token',
30 => 'tree_refresh_token_validity',
31 => 'tree_test_mode',
32 => 'tree_user_contribution',
33 => 'tree_use_https',
34 => 'carbon_offsetting_payer',
35 => 'active',
36 => 'tree_active',
),
),
),
),
'2.5.0' =>
array (
0 =>
array (
'type' => 'database',
'config' =>
array (
'script' =>
array (
0 => 'PGPayment:lock/upgrades/003-fix-column-id.sql',
1 => 'PGPayment:category_has_payment/upgrades/002-fix-integer-columns.sql',
2 => 'PGPayment:category_has_payment/upgrades/005-fix-column-id-shop.sql',
3 => 'PGPayment:transaction/upgrades/003-fix-integer-columns.sql',
4 => 'PGPayment:recurring_transaction/upgrades/003-fix-integer-columns.sql',
5 => 'PGPayment:button/upgrades/021-fix-columns.sql',
),
),
),
),
'2.2.0' =>
array (
0 =>
array (
'type' => 'insite_payment',
),
1 =>
array (
'type' => 'database',
'config' =>
array (
'script' => 'PGPayment:button/upgrades/020-remove-column-integration.sql',
),
),
),
'1.1.0' =>
array (
0 =>
array (
'type' => 'database',
'config' =>
array (
'script' => 'PGIntl:translation/upgrades/001-creation-table.sql',
),
),
1 =>
array (
'type' => 'button_labels.restore',
),
2 =>
array (
'type' => 'translations.install_default_values',
'config' =>
array (
'codes' =>
array (
0 => 'payment_bloc',
1 => 'payment_link',
2 => 'message_payment_success',
3 => 'message_payment_refused',
),
),
),
3 =>
array (
'type' => 'translations.restore',
'config' =>
array (
'keys' =>
array (
'payment_bloc' => 'title',
'payment_link' => 'payment_confirmation_button',
'message_payment_success' => 'notice_payment_accepted',
'message_payment_refused' => 'notice_payment_refused',
),
),
),
4 =>
array (
'type' => 'database',
'config' =>
array (
'script' => 'PGPayment:button/upgrades/017-remove-column-label.sql',
),
),
),
'1.0.0' =>
array (
0 =>
array (
'type' => 'database.shopified',
'config' =>
array (
'scripts' =>
array (
0 => 'PGPayment:button/upgrades/014-add-column-id-shop-with-default.sql',
1 => 'PGPayment:category_has_payment/upgrades/003-add-column-id-shop-with-default.sql',
),
),
),
1 =>
array (
'type' => 'database',
'config' =>
array (
'script' =>
array (
0 => 'PGPayment:button/upgrades/015-remove-default-id-shop.sql',
1 => 'PGPayment:category_has_payment/upgrades/004-remove-default-id-shop.sql',
2 => 'PGModule:setting/upgrades/001-creation-table.sql',
),
),
),
2 =>
array (
'type' => 'settings.restore',
'config' =>
array (
'keys' =>
array (
'active' => 'active',
'title' => 'title',
'visibility' => 'admin_only_visibility',
'payment_confirmation_button' => 'payment_confirmation_button',
'payment_success_text' => 'notice_payment_accepted',
'payment_error_text' => 'notice_payment_refused',
'behavior_transmit_refund' => 'behavior_payment_refund',
'behavior_transmit_delivering' => 'behavior_delivery_confirmation',
),
),
),
),
'2.0.0' =>
array (
'type' => 'database',
'config' =>
array (
'script' =>
array (
0 => 'PGPayment:button/upgrades/018-add-columns-filters.sql',
1 => 'PGPayment:button/upgrades/019-update-filtered-category-primaries-remove-default-value.sql',
),
),
),
),
'setup' =>
array (
'older' => null,
),
'translations' =>
array (
'payment_bloc' =>
array (
'label' => 'payment_translations.payment_bloc.field.label',
'help' => 'payment_translations.payment_bloc.field.help',
'tags' =>
array (
0 => 'payment',
),
'default' =>
array (
'fr' => 'PayGreen : paiement solidaire et responsable',
'en' => 'PayGreen: green & sustainable payment',
),
),
'payment_link' =>
array (
'label' => 'payment_translations.payment_link.field.label',
'help' => 'payment_translations.payment_link.field.help',
'tags' =>
array (
0 => 'payment',
),
'default' =>
array (
'fr' => 'Payer avec PayGreen',
'en' => 'Pay with PayGreen',
),
),
'message_payment_success' =>
array (
'label' => 'payment_translations.message_payment_success.field.label',
'help' => 'payment_translations.message_payment_success.field.help',
'tags' =>
array (
0 => 'payment',
),
'default' =>
array (
'fr' => 'Votre paiement a été enregistré avec succès.',
'en' => 'Your payment has been successfully registered.',
),
),
'message_payment_refused' =>
array (
'label' => 'payment_translations.message_payment_refused.field.label',
'help' => 'payment_translations.message_payment_refused.field.help',
'tags' =>
array (
0 => 'payment',
),
'default' =>
array (
'fr' => 'Votre paiement n\'a pas abouti.',
'en' => 'Your payment is unsuccessful.',
),
),
'message_order_canceled' =>
array (
'label' => 'payment_translations.message_order_canceled.field.label',
'help' => 'payment_translations.message_order_canceled.field.help',
'tags' =>
array (
0 => 'payment',
),
'default' =>
array (
'fr' => 'Votre commande a été annulée.',
'en' => 'Your order has been canceled.',
),
'enabled' => false,
),
),
'database' =>
array (
'entities' =>
array (
'setting' =>
array (
'class' => 'PGI\\Module\\PGModule\\Entities\\Setting',
'table' => 'paygreen_settings',
'primary' => 'id',
'fields' =>
array (
'id' =>
array (
'type' => 'int',
),
'id_shop' =>
array (
'type' => 'string',
'default' => null,
),
'name' =>
array (
'type' => 'string',
),
'value' =>
array (
'type' => 'string',
),
),
),
'fingerprint' =>
array (
'table' => 'paygreen_fingerprint',
),
'carbon_data' =>
array (
'table' => 'paygreen_carbon_data',
),
'gift' =>
array (
'table' => 'paygreen_gifts',
),
'translation' =>
array (
'class' => 'PGI\\Module\\PGIntl\\Entities\\Translation',
'table' => 'paygreen_translations',
'primary' => 'id',
'fields' =>
array (
'id' =>
array (
'type' => 'int',
),
'id_shop' =>
array (
'type' => 'string',
'default' => null,
),
'code' =>
array (
'type' => 'string',
),
'language' =>
array (
'type' => 'string',
),
'text' =>
array (
'type' => 'string',
),
),
),
'button' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Entities\\Button',
'table' => 'paygreen_buttons',
'primary' => 'id',
'fields' =>
array (
'id' =>
array (
'type' => 'int',
),
'image' =>
array (
'type' => 'string',
),
'height' =>
array (
'type' => 'int',
),
'position' =>
array (
'type' => 'int',
),
'displayType' =>
array (
'type' => 'string',
),
'integration' =>
array (
'type' => 'string',
),
'maxAmount' =>
array (
'type' => 'int',
),
'minAmount' =>
array (
'type' => 'int',
),
'filtered_category_mode' =>
array (
'type' => 'string',
'default' => 'NONE',
),
'filtered_category_primaries' =>
array (
'type' => 'array',
'default' =>
array (
),
),
'paymentMode' =>
array (
'type' => 'string',
),
'paymentType' =>
array (
'type' => 'string',
),
'firstPaymentPart' =>
array (
'type' => 'string',
),
'paymentNumber' =>
array (
'type' => 'int',
),
'paymentReport' =>
array (
'type' => 'string',
),
'discount' =>
array (
'type' => 'string',
),
'orderRepeated' =>
array (
'type' => 'bool',
),
'id_shop' =>
array (
'type' => 'int',
),
),
),
'lock' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Entities\\Lock',
'table' => 'paygreen_transaction_locks',
'primary' => 'id',
'fields' =>
array (
'id' =>
array (
'type' => 'int',
),
'pid' =>
array (
'type' => 'string',
),
'locked_at' =>
array (
'type' => 'datetime',
),
),
),
'category_has_payment' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Entities\\CategoryHasPaymentType',
'table' => 'paygreen_categories_has_payments',
'primary' => 'id',
'fields' =>
array (
'id' =>
array (
'type' => 'int',
),
'id_category' =>
array (
'type' => 'string',
),
'payment' =>
array (
'type' => 'string',
),
'id_shop' =>
array (
'type' => 'int',
),
),
),
'transaction' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Entities\\Transaction',
'table' => 'paygreen_transactions',
'primary' => 'id',
'fields' =>
array (
'id' =>
array (
'type' => 'int',
),
'pid' =>
array (
'type' => 'string',
),
'id_order' =>
array (
'type' => 'string',
),
'state' =>
array (
'type' => 'string',
),
'mode' =>
array (
'type' => 'string',
),
'amount' =>
array (
'type' => 'int',
),
'created_at' =>
array (
'type' => 'datetime',
),
),
),
'recurring_transaction' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Entities\\RecurringTransaction',
'table' => 'paygreen_transactions_recurring',
'primary' => 'id',
'fields' =>
array (
'id' =>
array (
'type' => 'int',
),
'pid' =>
array (
'type' => 'string',
),
'id_order' =>
array (
'type' => 'string',
),
'state' =>
array (
'type' => 'string',
),
'state_order_before' =>
array (
'type' => 'string',
),
'state_order_after' =>
array (
'type' => 'string',
),
'mode' =>
array (
'type' => 'string',
),
'amount' =>
array (
'type' => 'int',
),
'rank' =>
array (
'type' => 'int',
),
'created_at' =>
array (
'type' => 'datetime',
),
),
),
'processing' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Entities\\Processing',
'table' => 'paygreen_processing',
'primary' => 'id',
'fields' =>
array (
'id' =>
array (
'type' => 'int',
),
'id_shop' =>
array (
'type' => 'int',
),
'reference' =>
array (
'type' => 'string',
),
'success' =>
array (
'type' => 'string',
),
'status' =>
array (
'type' => 'string',
),
'pid' =>
array (
'type' => 'string',
),
'pid_status' =>
array (
'type' => 'string',
),
'created_at' =>
array (
'type' => 'datetime',
),
'echoes' =>
array (
'type' => 'array',
'default' =>
array (
),
),
'amount' =>
array (
'type' => 'int',
),
'id_order' =>
array (
'type' => 'string',
),
'state_from' =>
array (
'type' => 'string',
),
'state_to' =>
array (
'type' => 'string',
),
),
),
),
),
'logo' =>
array (
'path' => 'PGModule:paygreen-logo.svg',
'template' => 'block-menu-logo.tpl',
),
'db' =>
array (
'var' =>
array (
'prefix' => '',
'engine' => 'innodb',
),
'split' => true,
),
'mime_types' =>
array (
'aac' => 'audio/aac',
'abw' => 'application/x-abiword',
'arc' => 'application/octet-stream',
'avi' => 'video/x-msvideo',
'azw' => 'application/vnd.amazon.ebook',
'bin' => 'application/octet-stream',
'bz' => 'application/x-bzip',
'bz2' => 'application/x-bzip2',
'csh' => 'application/x-csh',
'css' => 'text/css',
'csv' => 'text/csv',
'doc' => 'application/msword',
'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
'eot' => 'application/vnd.ms-fontobject',
'epub' => 'application/epub+zip',
'gif' => 'image/gif',
'htm' => 'text/html',
'html' => 'text/html',
'ico' => 'image/x-icon',
'ics' => 'text/calendar',
'jar' => 'application/java-archive',
'jpeg' => 'image/jpeg',
'jpg' => 'image/jpeg',
'js' => 'application/javascript',
'json' => 'application/json',
'log' => 'text/plain',
'mid' => 'audio/midi',
'midi' => 'audio/midi',
'mpeg' => 'video/mpeg',
'mpkg' => 'application/vnd.apple.installer+xml',
'odp' => 'application/vnd.oasis.opendocument.presentation',
'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
'odt' => 'application/vnd.oasis.opendocument.text',
'oga' => 'audio/ogg',
'ogv' => 'video/ogg',
'ogx' => 'application/ogg',
'otf' => 'font/otf',
'png' => 'image/png',
'pdf' => 'application/pdf',
'ppt' => 'application/vnd.ms-powerpoint',
'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
'rar' => 'application/x-rar-compressed',
'rtf' => 'application/rtf',
'sh' => 'application/x-sh',
'svg' => 'image/svg+xml',
'swf' => 'application/x-shockwave-flash',
'tar' => 'application/x-tar',
'tif' => 'image/tiff',
'tiff' => 'image/tiff',
'ts' => 'application/typescript',
'ttf' => 'font/ttf',
'vsd' => 'application/vnd.visio',
'wav' => 'audio/x-wav',
'weba' => 'audio/webm',
'webm' => 'video/webm',
'webp' => 'image/webp',
'woff' => 'font/woff',
'woff2' => 'font/woff2',
'xhtml' => 'application/xhtml+xml',
'xls' => 'application/vnd.ms-excel',
'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
'xml' => 'application/xml',
'xul' => 'application/vnd.mozilla.xul+xml',
'zip' => 'application/zip',
'3gp' => 'video/3gpp',
'3g2' => 'video/3gpp2',
'7z' => 'application/x-7z-compressed',
),
'http_codes' =>
array (
100 => 'Continue',
101 => 'Switching Protocols',
102 => 'Processing',
200 => 'OK',
201 => 'Created',
202 => 'Accepted',
203 => 'Non-Authoritative Information',
204 => 'No Content',
205 => 'Reset Content',
206 => 'Partial Content',
207 => 'Multi-Status',
208 => 'Already Reported',
226 => 'IM Used',
300 => 'Multiple Choices',
301 => 'Moved Permanently',
302 => 'Found',
303 => 'See Other',
304 => 'Not Modified',
305 => 'Use Proxy',
306 => 'Switch Proxy',
307 => 'Temporary Redirect',
308 => 'Permanent Redirect',
400 => 'Bad Request',
401 => 'Unauthorized',
402 => 'Payment Required',
403 => 'Forbidden',
404 => 'Not Found',
405 => 'Method Not Allowed',
406 => 'Not Acceptable',
407 => 'Proxy Authentication Required',
408 => 'Request Timeout',
409 => 'Conflict',
410 => 'Gone',
411 => 'Length Required',
412 => 'Precondition Failed',
413 => 'Request Entity Too Large',
414 => 'Request-URI Too Long',
415 => 'Unsupported Media Type',
416 => 'Requested Range Not Satisfiable',
417 => 'Expectation Failed',
418 => 'I"m a teapot',
419 => 'Authentication Timeout',
420 => 'Enhance Your Calm (Twitter) / Method Failure (Spring Framework)',
422 => 'Unprocessable Entity',
423 => 'Locked',
424 => 'Failed Dependency (WebDAV; RFC 4918) / Method Failure (WebDAV)',
425 => 'Unordered Collection',
426 => 'Upgrade Required',
428 => 'Precondition Required',
429 => 'Too Many Requests',
431 => 'Request Header Fields Too Large',
444 => 'No Response',
449 => 'Retry With',
450 => 'Blocked by Windows Parental Controls',
451 => 'Redirect (Microsoft) / Unavailable For Legal Reasons (Internet draft)',
494 => 'Request Header Too Large',
495 => 'Cert Error',
496 => 'No Cert',
497 => 'HTTP to HTTPS',
499 => 'Client Closed Request',
500 => 'Internal Server Error',
501 => 'Not Implemented',
502 => 'Bad Gateway',
503 => 'Service Unavailable',
504 => 'Gateway Timeout',
505 => 'HTTP Version Not Supported',
506 => 'Variant Also Negotiates',
507 => 'Insufficient Storage',
508 => 'Loop Detected',
509 => 'Bandwidth Limit Exceeded',
510 => 'Not Extended',
511 => 'Network Authentication Required',
598 => 'Network read timeout error',
599 => 'Network connect timeout error',
),
'request_builder' =>
array (
'default' =>
array (
),
'backoffice' =>
array (
'strict' => false,
),
'frontoffice' =>
array (
'strict' => false,
),
),
'routing' =>
array (
'areas' =>
array (
'front' =>
array (
'patterns' =>
array (
0 => 'front.*',
),
),
'backoffice' =>
array (
'patterns' =>
array (
0 => 'backoffice.*',
),
),
),
'routes' =>
array (
'backoffice.cron.display' =>
array (
'target' => 'cron.display',
'requirements' =>
array (
0 => 'shop_context',
),
),
'backoffice.cron.run' =>
array (
'target' => 'runScheduler@backoffice.cron',
'requirements' =>
array (
0 => 'shop_context',
),
),
'backoffice.cron.save_config' =>
array (
'target' => 'cron_configuration.save',
),
'backoffice.system.display' =>
array (
'target' => 'system.display',
),
'backoffice.support.save_support_config' =>
array (
'target' => 'support_configuration.save',
),
'backoffice.logs.download' =>
array (
'target' => 'downloadLogFile@backoffice.logs',
),
'backoffice.logs.delete' =>
array (
'target' => 'deleteLogFile@backoffice.logs',
),
'backoffice.shop.select' =>
array (
'target' => 'setCurrentShop@backoffice.shop',
),
'backoffice.support.display' =>
array (
'target' => 'support.display',
),
'backoffice.release_note.display' =>
array (
'target' => 'release_note.display',
),
'backoffice.diagnostic.run' =>
array (
'target' => 'run@backoffice.diagnostic',
),
'backoffice.home.display' =>
array (
'target' => 'home.display',
'requirements' =>
array (
0 => 'shop_context',
),
),
'backoffice.cache.reset' =>
array (
'target' => 'resetCache@backoffice.cache',
),
'front.cron.run' =>
array (
'target' => 'runScheduler@front.cron',
'requirements' =>
array (
0 => 'cron_activation',
),
),
'backoffice.payment.activation' =>
array (
'target' => 'paymentActivation@backoffice.payment',
'requirements' =>
array (
0 => 'shop_context',
),
),
'backoffice.account.display' =>
array (
'target' => 'account.display',
'requirements' =>
array (
0 => 'shop_context',
),
),
'backoffice.account.save' =>
array (
'target' => 'account_configuration.save',
'requirements' =>
array (
0 => 'shop_context',
),
),
'backoffice.account.disconnect' =>
array (
'target' => 'disconnect@backoffice.account',
'requirements' =>
array (
0 => 'shop_context',
1 => 'paygreen_connexion',
),
),
'backoffice.account.oauth.request' =>
array (
'target' => 'sendOAuthRequest@backoffice.oauth',
'requirements' =>
array (
0 => 'shop_context',
1 => '!paygreen_connexion',
),
),
'backoffice.account.oauth.response' =>
array (
'target' => 'processOAuthResponse@backoffice.oauth',
'requirements' =>
array (
0 => 'shop_context',
1 => '!paygreen_connexion',
),
),
'backoffice.config.display' =>
array (
'target' => 'config.display',
'requirements' =>
array (
0 => 'shop_context',
1 => 'paygreen_connexion',
),
),
'backoffice.config.save' =>
array (
'target' => 'module_configuration.save',
'requirements' =>
array (
0 => 'shop_context',
1 => 'paygreen_connexion',
),
),
'backoffice.config.save_customization' =>
array (
'target' => 'module_customization.save',
'requirements' =>
array (
0 => 'shop_context',
1 => 'paygreen_connexion',
),
),
'backoffice.eligible_amounts.display' =>
array (
'target' => 'eligible_amounts.display',
'requirements' =>
array (
0 => 'shop_context',
1 => 'paygreen_connexion',
),
),
'backoffice.eligible_amounts.categories.save' =>
array (
'target' => 'saveCategoryPayments@backoffice.eligible_amounts',
'requirements' =>
array (
0 => 'shop_context',
1 => 'paygreen_connexion',
),
),
'backoffice.eligible_amounts.shipping.save' =>
array (
'target' => 'saveShippingPayments@backoffice.eligible_amounts',
'requirements' =>
array (
0 => 'shop_context',
1 => 'paygreen_connexion',
),
),
'backoffice.buttons.display' =>
array (
'target' => 'buttons_list.display',
'requirements' =>
array (
0 => 'shop_context',
1 => 'paygreen_connexion',
),
),
'backoffice.buttons.display_update' =>
array (
'target' => 'displayUpdateForm@backoffice.buttons',
'requirements' =>
array (
0 => 'shop_context',
1 => 'paygreen_connexion',
),
),
'backoffice.buttons.update' =>
array (
'target' => 'updateButton@backoffice.buttons',
'requirements' =>
array (
0 => 'shop_context',
1 => 'paygreen_connexion',
),
),
'backoffice.buttons.display_insert' =>
array (
'target' => 'displayInsertForm@backoffice.buttons',
'requirements' =>
array (
0 => 'shop_context',
1 => 'paygreen_connexion',
),
),
'backoffice.buttons.insert' =>
array (
'target' => 'insertButton@backoffice.buttons',
'requirements' =>
array (
0 => 'shop_context',
1 => 'paygreen_connexion',
),
),
'backoffice.buttons.display_filters' =>
array (
'target' => 'displayFiltersForm@backoffice.buttons',
'requirements' =>
array (
0 => 'shop_context',
1 => 'paygreen_connexion',
),
),
'backoffice.buttons.update_filters' =>
array (
'target' => 'updateButtonFilters@backoffice.buttons',
'requirements' =>
array (
0 => 'shop_context',
1 => 'paygreen_connexion',
),
),
'backoffice.buttons.delete' =>
array (
'target' => 'deleteButton@backoffice.buttons',
'requirements' =>
array (
0 => 'shop_context',
1 => 'paygreen_connexion',
),
),
'backoffice.payment_translations.display' =>
array (
'target' => 'payment_translations.display',
'requirements' =>
array (
0 => 'shop_context',
),
),
'backoffice.payment_translations.save' =>
array (
'target' => 'payment_translations_form.save',
'requirements' =>
array (
0 => 'shop_context',
),
),
'backoffice.buttons.update_position' =>
array (
'target' => 'updateButtonsPosition@backoffice.buttons',
'requirements' =>
array (
0 => 'shop_context',
1 => 'paygreen_connexion',
),
),
'front.payment.validation' =>
array (
'target' => 'validatePayment@front.payment',
'requirements' =>
array (
0 => 'payment_activation',
),
),
'front.payment.process_customer_return' =>
array (
'target' => 'process@front.customer_return',
'requirements' =>
array (
0 => 'payment_activation',
),
),
'front.payment.receive' =>
array (
'target' => 'receive@front.payment',
'requirements' =>
array (
0 => 'payment_activation',
),
),
'front.payment.abort' =>
array (
'target' => 'abortPayment@front.invalid_payments',
),
),
),
'fields' =>
array (
'models' =>
array (
'collection.translations' =>
array (
'model' => 'collection',
'default' =>
array (
0 =>
array (
'text' => '',
'language' => 'fr',
),
),
'validators' =>
array (
'not_empty' => null,
),
'child' =>
array (
'model' => 'object',
'children' =>
array (
'text' =>
array (
'type' => 'basic',
'format' => 'string',
'required' => true,
'validators' =>
array (
'not_empty' => null,
),
'view' =>
array (
'name' => 'field',
'data' =>
array (
'attr' =>
array (
'type' => 'text',
),
'placeholder' => 'forms.translations.placeholder.text',
),
'template' => 'fields/partials/input',
),
),
'language' =>
array (
'type' => 'basic',
'format' => 'string',
'required' => true,
'validators' =>
array (
'not_empty' => null,
),
'view' =>
array (
'name' => 'field.choice.contracted',
'data' =>
array (
'choices' => 'language',
'translate' => true,
'multiple' => false,
'placeholder' => 'forms.translations.placeholder.lang',
'attr' =>
array (
'class' => 'pg_translated_field_language_selector',
),
),
'template' => 'fields/partials/select',
),
),
),
'view' =>
array (
'name' => 'field.object',
'data' =>
array (
'class' => null,
'label' => 'forms.button.fields.image.label',
),
'template' => 'fields/partials/object',
),
),
),
'string' =>
array (
'format' => 'string',
'view' =>
array (
'name' => 'field',
'data' =>
array (
'class' => null,
'attr' =>
array (
'type' => 'text',
),
),
'template' => 'fields/input-bloc',
),
),
'collection' =>
array (
'type' => 'collection',
'format' => 'array',
'view' =>
array (
'name' => 'field.collection',
'data' =>
array (
'class' => null,
'allowCreation' => true,
'allowDeletion' => true,
),
'template' => 'fields/bloc-collection',
),
),
'int' =>
array (
'format' => 'int',
'view' =>
array (
'name' => 'field',
'data' =>
array (
'class' => null,
'attr' =>
array (
'type' => 'number',
),
),
'template' => 'fields/input-bloc',
),
),
'float' =>
array (
'format' => 'float',
'view' =>
array (
'name' => 'field',
'data' =>
array (
'class' => null,
'attr' =>
array (
'type' => 'text',
),
),
'template' => 'fields/input-bloc',
),
),
'object' =>
array (
'type' => 'object',
'format' => 'object',
),
'bool' =>
array (
'format' => 'bool',
'view' =>
array (
'name' => 'field.bool.checkbox',
'data' =>
array (
'class' => null,
),
'template' => 'fields/input-bloc',
),
),
'hidden' =>
array (
'format' => 'string',
'view' =>
array (
'name' => 'field',
'data' =>
array (
'attr' =>
array (
'type' => 'hidden',
),
),
'template' => 'fields/partials/input',
),
),
'choice.expanded.single' =>
array (
'format' => 'string',
'view' =>
array (
'name' => 'field.choice.expanded',
'data' =>
array (
'class' => null,
'translate' => false,
'multiple' => false,
),
'template' => 'fields/bloc-choice-expanded',
),
),
'choice.expanded.multiple' =>
array (
'format' => 'array',
'view' =>
array (
'name' => 'field.choice.expanded',
'data' =>
array (
'class' => null,
'translate' => false,
'multiple' => true,
),
'template' => 'fields/bloc-choice-expanded',
),
),
'choice.contracted.single' =>
array (
'format' => 'string',
'view' =>
array (
'name' => 'field.choice.contracted',
'data' =>
array (
'class' => null,
'translate' => false,
'multiple' => false,
),
'template' => 'fields/bloc-choice-contracted',
),
),
'choice.contracted.multiple' =>
array (
'format' => 'array',
'view' =>
array (
'name' => 'field.choice.contracted',
'data' =>
array (
'class' => null,
'translate' => false,
'multiple' => true,
),
'template' => 'fields/bloc-choice-contracted',
),
),
'choice.double.bool' =>
array (
'format' => 'array',
'view' =>
array (
'name' => 'field.choice.double.bool',
'data' =>
array (
'class' => null,
'translate' =>
array (
'horizontal_choices' => false,
'vertical_choices' => false,
),
'axis' => 'both',
'multiple' => true,
'filter' => true,
'filterPlaceholder' => 'misc.forms.default.input.search.placeholder',
),
'template' => 'fields/bloc-double-choice-boolean',
),
),
'bool.switch' =>
array (
'format' => 'bool',
'view' =>
array (
'name' => 'field',
'data' =>
array (
'class' => null,
),
'template' => 'fields/bloc-switch',
),
),
'colorpicker' =>
array (
'format' => 'string',
'view' =>
array (
'name' => 'field',
'data' =>
array (
'class' => null,
'attr' =>
array (
'data-js' => 'colorpicker',
'class' => 'pgform__field__colorpicker',
),
),
'template' => 'fields/bloc-colorpicker',
),
),
),
'default' =>
array (
'type' => 'basic',
'enabled' => true,
),
'types' =>
array (
'basic' => 'PGI\\Module\\PGForm\\Components\\Fields\\Basic',
'object' => 'PGI\\Module\\PGForm\\Components\\Fields\\Composite',
'collection' => 'PGI\\Module\\PGForm\\Components\\Fields\\Collection',
),
),
'languages' =>
array (
0 => 'ab',
1 => 'aa',
2 => 'af',
3 => 'ak',
4 => 'sq',
5 => 'de',
6 => 'am',
7 => 'en',
8 => 'ar',
9 => 'an',
10 => 'hy',
11 => 'as',
12 => 'av',
13 => 'ae',
14 => 'ay',
15 => 'az',
16 => 'ba',
17 => 'bm',
18 => 'eu',
19 => 'bn',
20 => 'bi',
21 => 'be',
22 => 'my',
23 => 'bs',
24 => 'br',
25 => 'bg',
26 => 'ca',
27 => 'ch',
28 => 'ny',
29 => 'zh',
30 => 'ko',
31 => 'kw',
32 => 'co',
33 => 'cr',
34 => 'hr',
35 => 'da',
36 => 'dz',
37 => 'es',
38 => 'eo',
39 => 'et',
40 => 'ee',
41 => 'fo',
42 => 'fj',
43 => 'fi',
44 => 'nl',
45 => 'fr',
46 => 'fy',
47 => 'gd',
48 => 'gl',
49 => 'om',
50 => 'cy',
51 => 'lg',
52 => 'ka',
53 => 'gu',
54 => 'el',
55 => 'kl',
56 => 'gn',
57 => 'ht',
58 => 'ha',
59 => 'he',
60 => 'hz',
61 => 'hi',
62 => 'ho',
63 => 'hu',
64 => 'io',
65 => 'ig',
66 => 'id',
67 => 'iu',
68 => 'ik',
69 => 'ga',
70 => 'is',
71 => 'it',
72 => 'ja',
73 => 'jv',
74 => 'kn',
75 => 'kr',
76 => 'ks',
77 => 'kk',
78 => 'km',
79 => 'ki',
80 => 'ky',
81 => 'kv',
82 => 'kg',
83 => 'ku',
84 => 'kj',
85 => 'bh',
86 => 'lo',
87 => 'la',
88 => 'lv',
89 => 'li',
90 => 'ln',
91 => 'lt',
92 => 'lu',
93 => 'lb',
94 => 'mk',
95 => 'ms',
96 => 'ml',
97 => 'dv',
98 => 'mg',
99 => 'mt',
100 => 'gv',
101 => 'mi',
102 => 'mr',
103 => 'mh',
104 => 'ro',
105 => 'mn',
106 => 'na',
107 => 'nv',
108 => 'nd',
109 => 'nr',
110 => 'ng',
111 => 'ne',
112 => 'no',
113 => 'nb',
114 => 'nn',
115 => 'oc',
116 => 'oj',
117 => 'or',
118 => 'os',
119 => 'ug',
120 => 'ur',
121 => 'uz',
122 => 'ps',
123 => 'pi',
124 => 'pa',
125 => 'fa',
126 => 'ff',
127 => 'pl',
128 => 'pt',
129 => 'qu',
130 => 'rm',
131 => 'rn',
132 => 'ru',
133 => 'rw',
134 => 'se',
135 => 'sm',
136 => 'sg',
137 => 'sa',
138 => 'sc',
139 => 'sr',
140 => 'sn',
141 => 'sd',
142 => 'si',
143 => 'sk',
144 => 'sl',
145 => 'so',
146 => 'st',
147 => 'su',
148 => 'sv',
149 => 'sw',
150 => 'ss',
151 => 'tg',
152 => 'tl',
153 => 'ty',
154 => 'ta',
155 => 'tt',
156 => 'cs',
157 => 'ce',
158 => 'cv',
159 => 'te',
160 => 'th',
161 => 'bo',
162 => 'ti',
163 => 'to',
164 => 'ts',
165 => 'tn',
166 => 'tr',
167 => 'tk',
168 => 'tw',
169 => 'uk',
170 => 've',
171 => 'vi',
172 => 'cu',
173 => 'vo',
174 => 'wa',
175 => 'wo',
176 => 'xh',
177 => 'ii',
178 => 'yi',
179 => 'yo',
180 => 'za',
181 => 'zu',
),
'countries' =>
array (
0 => 'af',
1 => 'al',
2 => 'ag',
3 => 'an',
4 => 'ao',
5 => 'av',
6 => 'ac',
7 => 'ar',
8 => 'am',
9 => 'aa',
10 => 'at',
11 => 'as',
12 => 'au',
13 => 'aj',
14 => 'bf',
15 => 'ba',
16 => 'bg',
17 => 'bb',
18 => 'bs',
19 => 'bo',
20 => 'be',
21 => 'bh',
22 => 'bn',
23 => 'bd',
24 => 'bt',
25 => 'bl',
26 => 'bk',
27 => 'bc',
28 => 'bv',
29 => 'br',
30 => 'io',
31 => 'vi',
32 => 'bx',
33 => 'bu',
34 => 'uv',
35 => 'bm',
36 => 'by',
37 => 'cb',
38 => 'cm',
39 => 'ca',
40 => 'cv',
41 => 'cj',
42 => 'ct',
43 => 'cd',
44 => 'ci',
45 => 'ch',
46 => 'kt',
47 => 'ip',
48 => 'ck',
49 => 'co',
50 => 'cn',
51 => 'cf',
52 => 'cg',
53 => 'cw',
54 => 'cr',
55 => 'cs',
56 => 'iv',
57 => 'hr',
58 => 'cu',
59 => 'cy',
60 => 'ez',
61 => 'da',
62 => 'dj',
63 => 'do',
64 => 'dr',
65 => 'tt',
66 => 'ec',
67 => 'eg',
68 => 'es',
69 => 'ek',
70 => 'er',
71 => 'en',
72 => 'et',
73 => 'eu',
74 => 'fk',
75 => 'fo',
76 => 'fj',
77 => 'fi',
78 => 'fr',
79 => 'fg',
80 => 'fp',
81 => 'fs',
82 => 'gb',
83 => 'ga',
84 => 'gz',
85 => 'gg',
86 => 'gm',
87 => 'gh',
88 => 'gi',
89 => 'go',
90 => 'gr',
91 => 'gl',
92 => 'gj',
93 => 'gp',
94 => 'gt',
95 => 'gk',
96 => 'gv',
97 => 'pu',
98 => 'gy',
99 => 'ha',
100 => 'hm',
101 => 'ho',
102 => 'hk',
103 => 'hu',
104 => 'ic',
105 => 'in',
106 => 'id',
107 => 'ir',
108 => 'iz',
109 => 'ei',
110 => 'im',
111 => 'is',
112 => 'it',
113 => 'jm',
114 => 'jn',
115 => 'ja',
116 => 'je',
117 => 'jo',
118 => 'ju',
119 => 'kz',
120 => 'ke',
121 => 'kr',
122 => 'ku',
123 => 'kg',
124 => 'la',
125 => 'lg',
126 => 'le',
127 => 'lt',
128 => 'li',
129 => 'ly',
130 => 'ls',
131 => 'lh',
132 => 'lu',
133 => 'mc',
134 => 'mk',
135 => 'ma',
136 => 'mi',
137 => 'my',
138 => 'mv',
139 => 'ml',
140 => 'mt',
141 => 'rm',
142 => 'mb',
143 => 'mr',
144 => 'mp',
145 => 'mf',
146 => 'mx',
147 => 'fm',
148 => 'md',
149 => 'mn',
150 => 'mg',
151 => 'mh',
152 => 'mo',
153 => 'mz',
154 => 'wa',
155 => 'nr',
156 => 'np',
157 => 'nl',
158 => 'nt',
159 => 'nc',
160 => 'nz',
161 => 'nu',
162 => 'ng',
163 => 'ni',
164 => 'ne',
165 => 'nm',
166 => 'nf',
167 => 'kn',
168 => 'no',
169 => 'mu',
170 => 'pk',
171 => 'ps',
172 => 'pm',
173 => 'pp',
174 => 'pa',
175 => 'pe',
176 => 'rp',
177 => 'pc',
178 => 'pl',
179 => 'po',
180 => 'qa',
181 => 're',
182 => 'ro',
183 => 'rs',
184 => 'rw',
185 => 'sh',
186 => 'sc',
187 => 'st',
188 => 'sb',
189 => 'vc',
190 => 'ws',
191 => 'sm',
192 => 'tp',
193 => 'sa',
194 => 'sg',
195 => 'yi',
196 => 'se',
197 => 'sl',
198 => 'sn',
199 => 'lo',
200 => 'si',
201 => 'bp',
202 => 'so',
203 => 'sf',
204 => 'sx',
205 => 'ks',
206 => 'sp',
207 => 'pg',
208 => 'ce',
209 => 'su',
210 => 'ns',
211 => 'sv',
212 => 'wz',
213 => 'sw',
214 => 'sz',
215 => 'sy',
216 => 'tw',
217 => 'ti',
218 => 'tz',
219 => 'th',
220 => 'to',
221 => 'tl',
222 => 'tn',
223 => 'td',
224 => 'te',
225 => 'ts',
226 => 'tu',
227 => 'tx',
228 => 'tk',
229 => 'tv',
230 => 'ug',
231 => 'up',
232 => 'ae',
233 => 'uk',
234 => 'uy',
235 => 'uz',
236 => 'nh',
237 => 'vt',
238 => 've',
239 => 'vm',
240 => 'wf',
241 => 'we',
242 => 'wi',
243 => 'ym',
244 => 'za',
245 => 'zi',
),
'intl' =>
array (
'native_languages' =>
array (
0 => 'en',
1 => 'fr',
),
),
'form' =>
array (
'definitions' =>
array (
'translations' =>
array (
'model' => 'basic',
'fields' =>
array (
),
'view' =>
array (
'data' =>
array (
'validate' => 'misc.forms.default.buttons.save',
),
),
),
'cron' =>
array (
'model' => 'basic',
'fields' =>
array (
'cron_activation' =>
array (
'model' => 'bool.switch',
'view' =>
array (
'data' =>
array (
'label' => 'forms.cron.fields.cron_activation.label',
'help' => 'forms.cron.fields.cron_activation.help',
),
),
),
'cron_activation_mode' =>
array (
'model' => 'choice.contracted.single',
'default' => 'URL',
'requirements' =>
array (
0 => 'cron_activation',
),
'validators' =>
array (
'array.in' => 'cron_activation_mode',
),
'view' =>
array (
'data' =>
array (
'choices' => 'cron_activation_mode',
'label' => 'forms.cron.fields.cron_activation_mode.label',
'help' => 'forms.cron.fields.cron_activation_mode.help',
'translate' => true,
),
),
),
),
'view' =>
array (
'data' =>
array (
'validate' => 'misc.forms.default.buttons.save',
),
),
),
'settings_support' =>
array (
'model' => 'basic',
'fields' =>
array (
'behavior_detailed_logs' =>
array (
'model' => 'bool.switch',
'view' =>
array (
'data' =>
array (
'label' => 'forms.settings_support.fields.detailed_logs.label',
'help' => 'forms.settings_support.fields.detailed_logs.help',
),
),
),
),
'view' =>
array (
'data' =>
array (
'validate' => 'misc.forms.default.buttons.save',
),
),
),
'config' =>
array (
'model' => 'basic',
'fields' =>
array (
'admin_only_visibility' =>
array (
'model' => 'choice.expanded.single',
'format' => 'int',
'view' =>
array (
'data' =>
array (
'choices' =>
array (
0 => 'forms.config.fields.visibility.values.no',
1 => 'forms.config.fields.visibility.values.yes',
),
'translate' => true,
'label' => 'forms.config.fields.visibility.label',
'help' => 'forms.config.fields.visibility.help',
),
),
'enabled' => false,
),
'cancel_order_on_refused_payment' =>
array (
'model' => 'choice.expanded.single',
'format' => 'int',
'view' =>
array (
'data' =>
array (
'choices' =>
array (
0 => 'forms.config.fields.behavior_payment_refused.values.no',
1 => 'forms.config.fields.behavior_payment_refused.values.yes',
),
'translate' => true,
'label' => 'forms.config.fields.behavior_payment_refused.label',
'help' => 'forms.config.fields.behavior_payment_refused.help',
),
),
'enabled' => false,
),
'behavior_payment_refund' =>
array (
'model' => 'bool.switch',
'view' =>
array (
'data' =>
array (
'label' => 'forms.config.fields.behavior_transmit_refund.label',
'help' => 'forms.config.fields.behavior_transmit_refund.help',
),
),
),
'behavior_delivery_confirmation' =>
array (
'model' => 'bool.switch',
'view' =>
array (
'data' =>
array (
'label' => 'forms.config.fields.behavior_transmit_delivering.label',
'help' => 'forms.config.fields.behavior_transmit_delivering.help',
),
),
'enabled' => false,
),
'behavior_payment_insite' =>
array (
'model' => 'choice.expanded.single',
'format' => 'int',
'view' =>
array (
'data' =>
array (
'choices' =>
array (
0 => 'forms.config.fields.behavior_payment_insite.values.external',
1 => 'forms.config.fields.behavior_payment_insite.values.insite',
),
'translate' => true,
'label' => 'forms.config.fields.behavior_payment_insite.label',
'help' => 'forms.config.fields.behavior_payment_insite.help',
),
),
),
'regenerate_cart_on_cancelled_payment' =>
array (
'model' => 'bool.switch',
'view' =>
array (
'data' =>
array (
'label' => 'forms.config.fields.regenerate_cart_on_cancelled_payment.label',
'help' => 'forms.config.fields.regenerate_cart_on_cancelled_payment.help',
),
),
),
'regenerate_cart_on_refused_payment' =>
array (
'model' => 'bool.switch',
'view' =>
array (
'data' =>
array (
'label' => 'forms.config.fields.regenerate_cart_on_refused_payment.label',
'help' => 'forms.config.fields.regenerate_cart_on_refused_payment.help',
),
),
),
),
'view' =>
array (
'data' =>
array (
'validate' => 'misc.forms.default.buttons.save',
),
),
),
'eligible_amounts' =>
array (
'model' => 'basic',
'fields' =>
array (
'eligible_amounts' =>
array (
'model' => 'choice.double.bool',
'default' =>
array (
),
'view' =>
array (
'data' =>
array (
'horizontal_choices' => 'payment_type',
'vertical_choices' => 'category.hierarchized',
'axis' => 'vertical',
'filterPlaceholder' => 'forms.eligible_amounts.fields.search.placeholder',
),
),
),
),
'view' =>
array (
'data' =>
array (
'validate' => 'misc.forms.default.buttons.save',
),
),
),
'exclusion_shipping_cost' =>
array (
'model' => 'basic',
'fields' =>
array (
'payment_types' =>
array (
'model' => 'choice.expanded.multiple',
'default' =>
array (
),
'view' =>
array (
'data' =>
array (
'choices' => 'payment_type',
'label' => 'forms.exclusion_shipping_cost.fields.label.label',
),
),
),
),
'view' =>
array (
'data' =>
array (
'validate' => 'misc.forms.default.buttons.save',
),
),
),
'authentication' =>
array (
'model' => 'basic',
'fields' =>
array (
'public_key' =>
array (
'model' => 'string',
'required' => true,
'validators' =>
array (
'regexp' =>
array (
'format' => '/^(PP|SB)?[a-f0-9]{32}$/',
'error' => 'forms.authentication.errors.identifier_bad_format',
),
),
'view' =>
array (
'data' =>
array (
'label' => 'forms.authentication.fields.shop_token.label',
'attr' =>
array (
'maxlength' => 34,
'size' => 34,
),
),
),
),
'private_key' =>
array (
'model' => 'string',
'required' => true,
'validators' =>
array (
'regexp' =>
array (
'format' => '/^[a-f0-9]{4}\\-[a-f0-9]{4}\\-[a-f0-9]{4}\\-[a-f0-9]{12}$/',
'error' => 'forms.authentication.errors.private_key_bad_format',
),
),
'view' =>
array (
'data' =>
array (
'label' => 'forms.authentication.fields.private_key.label',
'attr' =>
array (
'maxlength' => 27,
'size' => 34,
),
),
),
),
),
'view' =>
array (
'data' =>
array (
'validate' => 'misc.forms.default.buttons.save',
),
),
),
'settings_customization' =>
array (
'model' => 'basic',
'fields' =>
array (
'footer_display' =>
array (
'model' => 'bool.switch',
'view' =>
array (
'data' =>
array (
'label' => 'forms.config.fields.behavior_display_footer.label',
'help' => 'forms.config.fields.behavior_display_footer.help',
),
),
'enabled' => false,
),
'footer_color' =>
array (
'model' => 'choice.contracted.single',
'validators' =>
array (
'array.in' =>
array (
0 => 'white',
1 => 'green',
2 => 'black',
),
),
'view' =>
array (
'data' =>
array (
'choices' =>
array (
'white' => 'forms.config.fields.display_footer_color.values.white',
'green' => 'forms.config.fields.display_footer_color.values.green',
'black' => 'forms.config.fields.display_footer_color.values.black',
),
'translate' => true,
'label' => 'forms.config.fields.display_footer_color.label',
),
),
'enabled' => false,
),
),
'view' =>
array (
'data' =>
array (
'validate' => 'misc.forms.default.buttons.save',
),
),
),
'button' =>
array (
'model' => 'multipart',
'fields' =>
array (
'label' =>
array (
'model' => 'collection.translations',
'view' =>
array (
'data' =>
array (
'label' => 'forms.button.fields.label.label',
),
),
),
'payment_type' =>
array (
'model' => 'choice.contracted.single',
'default' => 'CB',
'validators' =>
array (
'array.in' => 'payment_type',
),
'view' =>
array (
'data' =>
array (
'choices' => 'payment_type',
'label' => 'forms.button.fields.payment_type.label',
),
),
),
'display_type' =>
array (
'model' => 'choice.expanded.single',
'default' => 'DEFAULT',
'validators' =>
array (
'array.in' => 'display_type',
),
'view' =>
array (
'data' =>
array (
'choices' => 'display_type',
'translate' => true,
'label' => 'forms.button.fields.display_type.label',
),
),
),
'picture' =>
array (
'model' => 'object',
'children' =>
array (
'image' =>
array (
'type' => 'basic',
'format' => 'string',
'view' =>
array (
'name' => 'field',
'data' =>
array (
'attr' =>
array (
'type' => 'file',
),
),
'template' => 'fields/partials/input',
),
),
'reset' =>
array (
'type' => 'basic',
'format' => 'bool',
'view' =>
array (
'name' => 'field.bool.checkbox',
'data' =>
array (
'label' => 'forms.button.fields.image.default',
'translate' => true,
'attr' =>
array (
'type' => 'checkbox',
),
),
'template' => 'fields/partials/radio-check',
),
),
),
'view' =>
array (
'name' => 'field.picture',
'data' =>
array (
'class' => null,
'label' => 'forms.button.fields.image.label',
),
'template' => 'fields/bloc-picture',
),
),
'height' =>
array (
'model' => 'int',
'default' => 60,
'view' =>
array (
'data' =>
array (
'label' => 'forms.button.fields.height.label',
'help' => 'forms.button.fields.height.helper',
'append' => 'forms.button.fields.height.append',
'attr' =>
array (
'min' => 0,
),
),
),
),
'payment_mode' =>
array (
'model' => 'choice.expanded.single',
'default' => 'CASH',
'validators' =>
array (
'array.in' => 'payment_mode',
),
'view' =>
array (
'data' =>
array (
'choices' => 'payment_mode',
'label' => 'forms.button.fields.payment_mode.label',
),
),
),
'payment_number' =>
array (
'model' => 'int',
'default' => 1,
'view' =>
array (
'data' =>
array (
'label' => 'forms.button.fields.payment_number.label',
'warning' => 'forms.button.fields.payment_number.warning',
'attr' =>
array (
'min' => 1,
),
'class' => 'js-hidden-field-togglable',
),
),
),
'first_payment_part' =>
array (
'model' => 'int',
'default' => 0,
'view' =>
array (
'data' =>
array (
'label' => 'forms.button.fields.first_payment_part.label',
'help' => 'forms.button.fields.first_payment_part.helper',
'append' => 'forms.button.fields.first_payment_part.append',
'class' => 'js-hidden-field-togglable',
'attr' =>
array (
'min' => 0,
'max' => 100,
),
),
),
),
'payment_report' =>
array (
'model' => 'choice.contracted.single',
'default' => 0,
'validators' =>
array (
'array.in' => 'payment_report',
),
'view' =>
array (
'data' =>
array (
'choices' => 'payment_report',
'translate' => true,
'label' => 'forms.button.fields.payment_report.label',
'help' => 'forms.button.fields.payment_report.helper',
'class' => 'js-hidden-field-togglable',
),
),
),
'order_repeated' =>
array (
'model' => 'bool',
'default' => false,
'enabled' => false,
'view' =>
array (
'data' =>
array (
'label' => 'forms.button.fields.order_repeated.label',
'help' => 'forms.button.fields.order_repeated.helper',
'class' => 'js-hidden-field-togglable',
),
),
),
),
'view' =>
array (
'data' =>
array (
'validate' => 'misc.forms.default.buttons.save',
'columns' =>
array (
'appearance' =>
array (
0 => 'display_type',
1 => 'label',
2 => 'picture',
3 => 'height',
),
'payment' =>
array (
0 => 'payment_type',
),
'other' =>
array (
0 => 'payment_mode',
1 => 'payment_number',
2 => 'first_payment_part',
3 => 'order_repeated',
4 => 'payment_report',
5 => 'form_key',
),
),
),
'template' => 'forms/button',
),
),
'button_update' =>
array (
'extends' => 'button',
'fields' =>
array (
'id' =>
array (
'model' => 'hidden',
'format' => 'int',
'required' => true,
'validators' =>
array (
'not_empty' =>
array (
'error' => 'forms.button.errors.id_not_found',
),
),
),
),
),
'button_filters' =>
array (
'model' => 'multipart',
'fields' =>
array (
'id' =>
array (
'model' => 'hidden',
'format' => 'int',
'required' => true,
'validators' =>
array (
'not_empty' =>
array (
'error' => 'forms.button.errors.id_not_found',
),
),
),
'categories_filtering_mode' =>
array (
'model' => 'choice.contracted.single',
'validators' =>
array (
'array.in' =>
array (
0 => 'NONE',
1 => 'STRICT',
2 => 'FLEXIBLE',
),
),
'view' =>
array (
'data' =>
array (
'choices' =>
array (
'NONE' => 'forms.button_filters.fields.categories_filtering_mode.values.NONE',
'STRICT' => 'forms.button_filters.fields.categories_filtering_mode.values.STRICT',
'FLEXIBLE' => 'forms.button_filters.fields.categories_filtering_mode.values.FLEXIBLE',
),
'translate' => true,
'label' => 'forms.button_filters.fields.categories_filtering_mode.label',
'help' => 'forms.button_filters.fields.categories_filtering_mode.help',
),
),
),
'filtered_categories' =>
array (
'model' => 'choice.double.bool',
'default' =>
array (
),
'view' =>
array (
'data' =>
array (
'horizontal_choices' =>
array (
0 => 'forms.button_filters.fields.filtered_categories.column',
),
'vertical_choices' => 'category.hierarchized',
'axis' => 'vertical',
'filterPlaceholder' => 'forms.button_filters.fields.search.placeholder',
'translate' =>
array (
'horizontal_choices' => true,
),
'label' => 'forms.button_filters.fields.filtered_categories.label',
'help' => 'forms.button_filters.fields.filtered_categories.help',
),
),
),
'cart_amount_limits' =>
array (
'model' => 'object',
'children' =>
array (
'min' =>
array (
'model' => 'int',
'default' => 0,
'view' =>
array (
'data' =>
array (
'attr' =>
array (
'min' => 0,
),
),
'template' => 'fields/partials/input',
),
),
'max' =>
array (
'model' => 'int',
'default' => 0,
'view' =>
array (
'data' =>
array (
'attr' =>
array (
'min' => 0,
),
),
'template' => 'fields/partials/input',
),
),
),
'view' =>
array (
'name' => 'field.object',
'data' =>
array (
'label' => 'forms.button_filters.fields.cart_amount.label',
'class' => null,
'help' => 'forms.button_filters.fields.cart_amount.helper',
'warning' => 'errors.button.min_amount_greater_than_max_amount',
),
'template' => 'fields/bloc-range',
),
),
),
'view' =>
array (
'data' =>
array (
'validate' => 'misc.forms.default.buttons.save',
'columns' =>
array (
'categories_filtering' =>
array (
0 => 'categories_filtering_mode',
1 => 'filtered_categories',
),
'other_filtering' =>
array (
0 => 'cart_amount_limits',
1 => 'form_key',
),
),
),
'template' => 'forms/button_filters',
),
),
'account_activation' =>
array (
'model' => 'basic',
'fields' =>
array (
'activation' =>
array (
'model' => 'bool.switch',
'view' =>
array (
'data' =>
array (
'label' => 'forms.account_activation.fields.activation.label',
'help' => 'forms.account_activation.fields.activation.help',
),
),
),
),
'view' =>
array (
'data' =>
array (
'validate' => 'misc.forms.default.buttons.save',
),
),
),
),
'default' =>
array (
),
'models' =>
array (
'basic' =>
array (
'view' =>
array (
'name' => 'form',
'data' =>
array (
'attr' =>
array (
'method' => 'post',
),
),
'template' => 'form',
),
),
'multipart' =>
array (
'view' =>
array (
'name' => 'form',
'data' =>
array (
'attr' =>
array (
'method' => 'post',
'enctype' => 'multipart/form-data',
),
),
'template' => 'form',
),
),
),
),
'blocks' =>
array (
'diagnostics' =>
array (
'target' => 'support',
'view' => 'block.diagnostics',
),
'logs' =>
array (
'target' => 'support',
'view' => 'block.logs',
),
'config_form_support' =>
array (
'target' => 'support',
'view' => 'block.standardized.config_form',
'data' =>
array (
'title' => 'blocks.config_form_support.title',
'class' => 'pgblock__max__md',
'name' => 'settings_support',
'action' => 'backoffice.support.save_support_config',
),
),
'cache_reset' =>
array (
'target' => 'support',
'template' => 'cache/block-reset',
'data' =>
array (
'class' => 'pgblock__max__md',
'title' => 'blocks.cache.reset.title',
'description' => 'blocks.cache.reset.description',
),
),
'servers' =>
array (
'target' => 'support',
'view' => 'block.server',
),
'system_module_informations' =>
array (
'target' => 'system',
'action' => 'displayModuleSystemInformations@backoffice.system',
'data' =>
array (
'class' => 'pgblock pgblock__max__xl',
'title' => 'blocks.system.title',
'subtitle' => 'blocks.system.platform.title',
),
),
'system_paths_informations' =>
array (
'target' => 'system',
'view' => 'system.paths',
),
'releases_notes_list' =>
array (
'target' => 'release_note',
'action' => 'displayList@backoffice.release_note',
'data' =>
array (
'class' => 'pgblock__full__xl',
),
),
'cron_tasks' =>
array (
'target' => 'cron',
'action' => 'displayTasks@backoffice.cron',
'data' =>
array (
'class' => 'pgblock__xl',
),
),
'config_form_cron' =>
array (
'target' => 'cron',
'view' => 'block.standardized.config_form',
'data' =>
array (
'title' => 'blocks.config_form_cron.title',
'class' => 'pgblock__max__md',
'name' => 'cron',
'action' => 'backoffice.cron.save_config',
),
),
'cron_control' =>
array (
'target' => 'cron',
'action' => 'displayControl@backoffice.cron',
'data' =>
array (
'class' => 'pgblock__xl',
),
),
'payment_kit_header' =>
array (
'position' => 1,
'target' => 'home',
'action' => 'display@backoffice.payment',
'data' =>
array (
'class' => 'pgblock__max__md pgblock__shadow',
),
),
'account_ids' =>
array (
'target' => 'account',
'action' => 'account_ids.display',
'data' =>
array (
'class' => 'pgblock__max__md',
),
'requirements' =>
array (
0 => 'paygreen_connexion',
),
),
'account_logout' =>
array (
'target' => 'account',
'template' => 'account/block-logout',
'data' =>
array (
'class' => 'pgblock__max__md pg__danger',
),
'requirements' =>
array (
0 => 'paygreen_connexion',
),
),
'account_infos' =>
array (
'target' => 'account',
'action' => 'displayAccountInfos@backoffice.account',
'data' =>
array (
'class' => 'pgblock__max__md',
),
'requirements' =>
array (
0 => 'paygreen_connexion',
),
),
'account_login' =>
array (
'target' => 'account',
'action' => 'account_login.display',
'data' =>
array (
'class' => 'pgblock__max__md',
),
'requirements' =>
array (
0 => '!paygreen_connexion',
),
),
'account_subscription' =>
array (
'target' => 'account',
'template' => 'account/block-subscription',
'data' =>
array (
'class' => 'pgblock__max__md',
),
'requirements' =>
array (
0 => '!paygreen_connexion',
),
),
'config_form_common' =>
array (
'target' => 'config',
'action' => 'payment_module_config.display',
'data' =>
array (
'title' => 'blocks.config_form_common.title',
'class' => 'pgblock__max__md',
),
),
'config_form_integration' =>
array (
'target' => 'config',
'action' => 'payment_customization.display',
'data' =>
array (
'title' => 'blocks.config_form_integration.title',
'class' => 'pgblock__max__md',
),
'enabled' => false,
),
'config_disclaimer' =>
array (
'target' => 'config',
'template' => 'blocks/config-disclaimer',
'data' =>
array (
'subtitle' => 'blocks.config_disclaimer.title',
'class' => 'pgblock__max__md pg__default',
),
),
'buttons_list' =>
array (
'target' => 'button_list',
'action' => 'displayList@backoffice.buttons',
'data' =>
array (
'class' => 'pgblock',
),
),
'form_eligible_amounts' =>
array (
'target' => 'eligible_amounts',
'action' => 'displayFormEligibleAmounts@backoffice.eligible_amounts',
'data' =>
array (
'class' => 'pgblock',
'title' => 'forms.eligible_amounts.title',
'description' => 'forms.eligible_amounts.explain',
),
),
'form_exclusion_shipping_costs' =>
array (
'target' => 'eligible_amounts',
'action' => 'displayFormExclusionShippingCosts@backoffice.eligible_amounts',
'data' =>
array (
'class' => 'pgblock pgblock__max__xxl',
'title' => 'forms.exclusion_shipping_cost.title',
'description' => 'forms.exclusion_shipping_cost.explain',
),
),
'form_payment_translations_management' =>
array (
'target' => 'payment_translations',
'action' => 'payment_translations_form.display',
'data' =>
array (
'class' => 'pgblock pgblock__max__lg',
'title' => 'pages.translations.frontoffice.title',
'description' => 'pages.translations.frontoffice.description',
),
),
),
'smarty' =>
array (
'builder' =>
array (
'service' => 'builder.smarty',
'path' => null,
'template_folders' =>
array (
0 => 'templates:/',
),
),
'null_stream' => 'PGI\\Module\\PGView\\Components\\NullStream',
),
'log' =>
array (
'outputs' =>
array (
'view' =>
array (
'config' =>
array (
'file' => 'log:/view.log',
'format' => '<datetime> | *<type>* | <text>',
),
),
'api' =>
array (
'config' =>
array (
'file' => 'log:/api.log',
'format' => '<datetime> | *<type>* | <text>',
),
),
'default' =>
array (
'config' =>
array (
'file' => 'log:/module.log',
'format' => '<datetime> | *<type>* | <text>',
),
),
),
'archive' =>
array (
'file' =>
array (
'folder' => 'var:/chronicles',
'file' => '<name>_<date>_<time>.zip',
'max_size' => 10485760,
),
),
),
'data' =>
array (
'cron_activation_mode' =>
array (
'URL' => 'data.cron_activation_mode.url',
'AJAX' => 'data.cron_activation_mode.ajax',
),
'backoffice' =>
array (
'template' => 'backoffice-require',
),
'payment_report' =>
array (
0 => 'forms.button.fields.payment_report.values.none',
'1 week' => 'forms.button.fields.payment_report.values.1week',
'2 weeks' => 'forms.button.fields.payment_report.values.2week',
'1 month' => 'forms.button.fields.payment_report.values.1month',
'2 months' => 'forms.button.fields.payment_report.values.2month',
'3 months' => 'forms.button.fields.payment_report.values.3month',
),
'button_integration' =>
array (
'EXTERNAL' => 'forms.button.fields.integration.values.external',
'INSITE' => 'forms.button.fields.integration.values.insite',
),
'display_type' =>
array (
'DEFAULT' => 'forms.button.fields.display_type.values.all',
'IMAGE' => 'forms.button.fields.display_type.values.picture',
'TEXT' => 'forms.button.fields.display_type.values.text',
),
),
'tasks' =>
array (
'log_zipping_module' =>
array (
'task' => 'log.zipping',
'frequency' => 'P1D',
'tab' => 'global',
),
'log_cleaning' =>
array (
'task' => 'log.cleaning',
'frequency' => 'P7D',
'tab' => 'global',
),
),
'requirements' =>
array (
'cron_activation' =>
array (
'name' => 'generic.setting',
'config' =>
array (
'setting' => 'cron_activation',
),
),
'shop_context' =>
array (
'name' => 'generic.bridge',
'config' =>
array (
'service' => 'handler.shop',
'method' => 'isShopContext',
),
),
'paygreen_connexion' =>
array (
'name' => 'generic.bridge',
'config' =>
array (
'service' => 'paygreen.facade',
'method' => 'isConnected',
),
),
'payment_activation' =>
array (
'name' => 'generic.setting',
'config' =>
array (
'setting' => 'payment_activation',
),
),
'footer_displayed' =>
array (
'name' => 'generic.setting',
'config' =>
array (
'setting' => 'footer_display',
),
'requirements' =>
array (
0 => 'payment_activation',
1 => 'paygreen_connexion',
),
),
),
'servers' =>
array (
'backoffice' =>
array (
'areas' =>
array (
0 => 'backoffice',
),
'request_builder' => 'builder.request.backoffice',
'deflectors' =>
array (
0 => 'filter.shop_context',
1 => 'filter.paygreen_connexion',
),
'cleaners' =>
array (
'not_found' => 'cleaner.forward.message_page',
'unauthorized_access' => 'cleaner.forward.message_page',
'server_error' => 'cleaner.forward.message_page',
'bad_request' => 'cleaner.forward.message_page',
'rendering_error' => 'cleaner.forward.message_page',
),
'rendering' =>
array (
0 =>
array (
'if' =>
array (
'class' => 'PGI\\Module\\PGServer\\Components\\Responses\\Template',
),
'do' => 'return',
'with' => 'renderer.processor.output_template',
),
1 =>
array (
'if' =>
array (
'instance' => 'PGI\\Module\\PGServer\\Components\\Responses\\PaygreenModule',
),
'do' => 'continue',
'with' => 'renderer.transformer.paygreen_module_2_array',
),
2 =>
array (
'if' =>
array (
'instance' => 'PGI\\Module\\PGServer\\Components\\Responses\\Collection',
),
'do' => 'continue',
'with' => 'renderer.transformer.array_2_http',
),
3 =>
array (
'if' =>
array (
'class' => 'PGI\\Module\\PGServer\\Components\\Responses\\File',
),
'do' => 'continue',
'with' => 'renderer.transformer.file_2_http',
),
4 =>
array (
'if' =>
array (
'instance' => 'PGI\\Module\\PGServer\\Components\\Responses\\Redirection',
),
'do' => 'continue',
'with' => 'renderer.transformer.redirection_2_http',
),
5 =>
array (
'if' =>
array (
'class' => 'PGI\\Module\\PGServer\\Components\\Responses\\HTTP',
),
'do' => 'stop',
'with' => 'renderer.processor.write_http',
),
),
),
'front' =>
array (
'areas' =>
array (
0 => 'front',
),
'request_builder' => 'builder.request.frontoffice',
'cleaners' =>
array (
'not_found' => 'cleaner.basic_http.not_found',
'unauthorized_access' => 'cleaner.basic_http.unauthorized_access',
'server_error' => 'cleaner.basic_http.server_error',
'bad_request' => 'cleaner.basic_http.bad_request',
'rendering_error' => 'cleaner.basic_http.server_error',
),
'rendering' =>
array (
0 =>
array (
'if' =>
array (
'class' => 'PGI\\Module\\PGServer\\Components\\Responses\\Template',
),
'do' => 'return',
'with' => 'renderer.processor.output_template',
),
1 =>
array (
'if' =>
array (
'instance' => 'PGI\\Module\\PGServer\\Components\\Responses\\PaygreenModule',
),
'do' => 'continue',
'with' => 'renderer.transformer.paygreen_module_2_array',
),
2 =>
array (
'if' =>
array (
'instance' => 'PGI\\Module\\PGServer\\Components\\Responses\\Collection',
),
'do' => 'continue',
'with' => 'renderer.transformer.array_2_http',
),
3 =>
array (
'if' =>
array (
'instance' => 'PGI\\Module\\PGServer\\Components\\Responses\\File',
),
'do' => 'continue',
'with' => 'renderer.transformer.file_2_http',
),
4 =>
array (
'if' =>
array (
'instance' => 'PGI\\Module\\PGServer\\Components\\Responses\\Redirection',
),
'do' => 'continue',
'with' => 'renderer.transformer.redirection_2_http',
),
5 =>
array (
'if' =>
array (
'instance' => 'PGI\\Module\\PGServer\\Components\\Responses\\HTTP',
),
'do' => 'stop',
'with' => 'renderer.processor.write_http',
),
),
),
),
'menu' =>
array (
'shop_selector' => true,
'entries' =>
array (
'home' =>
array (
'action' => 'backoffice.home.display',
'name' => 'pages.home.name',
'title' => 'pages.home.title',
),
'payment' =>
array (
'name' => 'menu.payment.name',
'title' => 'menu.payment.title',
'children' =>
array (
'account' =>
array (
'action' => 'backoffice.account.display',
'name' => 'pages.account.name',
'title' => 'pages.account.title',
),
'module' =>
array (
'action' => 'backoffice.config.display',
'name' => 'pages.config.name',
'title' => 'pages.config.title',
),
'buttons' =>
array (
'action' => 'backoffice.buttons.display',
'name' => 'pages.buttons.name',
'title' => 'pages.buttons.title',
),
'eligible_amounts' =>
array (
'action' => 'backoffice.eligible_amounts.display',
'name' => 'pages.eligible_amounts.name',
'title' => 'pages.eligible_amounts.title',
),
'payment_translations' =>
array (
'action' => 'backoffice.payment_translations.display',
'name' => 'pages.translations.name',
'title' => 'pages.translations.title',
),
),
),
'tree' =>
array (
'name' => 'menu.tree.name',
'title' => 'menu.tree.title',
'children' =>
array (
),
),
'charity' =>
array (
'children' =>
array (
),
),
'config' =>
array (
'name' => 'menu.config.name',
'title' => 'menu.config.title',
'children' =>
array (
'cron' =>
array (
'action' => 'backoffice.cron.display',
'name' => 'pages.cron.name',
'title' => 'pages.cron.title',
),
),
),
'help' =>
array (
'name' => 'menu.help.name',
'title' => 'menu.help.title',
'children' =>
array (
'system' =>
array (
'action' => 'backoffice.system.display',
'name' => 'pages.system.name',
'title' => 'pages.system.title',
),
'support' =>
array (
'action' => 'backoffice.support.display',
'name' => 'pages.support.name',
'title' => 'pages.support.title',
),
'release_note' =>
array (
'action' => 'backoffice.release_note.display',
'name' => 'pages.release_note.name',
'title' => 'pages.release_note.title',
),
),
),
'error' =>
array (
'title' => 'pages.error.title',
),
),
),
'payment' =>
array (
'pictures' =>
array (
'default' => 'logo-cb-visa-mastercard.png',
'amex' => 'logo-amex.png',
'ancv' => 'logo-ancv.png',
'cb' => 'logo-cb-visa-mastercard.png',
'trd' => 'logo-conecs.png',
'lunchr' => 'logo-swile.png',
'restoflash' => 'logo-restoflash.png',
'sepa' => 'logo-sepa.png',
),
'metadata' =>
array (
0 => 'order_id',
1 => 'cart_id',
),
'entrypoints' =>
array (
'customer' => 'front.payment.process_customer_return',
'ipn' => 'front.payment.receive',
),
'targets' =>
array (
'external' => 'redirect@front.payment',
),
'insite' =>
array (
'return' => 'front:front.payment.abort',
),
'forwarding' =>
array (
'task' =>
array (
'success' =>
array (
'type' => 'forward',
'target' => 'dispatchByOrderState@front.customer_return',
),
'payment_aborted' =>
array (
'type' => 'forward',
'target' => 'abortPayment@front.invalid_payments',
),
'payment_refused' =>
array (
'type' => 'forward',
'target' => 'refusePayment@front.invalid_payments',
),
'pid_locked' =>
array (
'type' => 'redirect',
'link' => 'order.history',
),
'fatal_error' =>
array (
'type' => 'error',
'error' => 'frontoffice.payment.results.payment.fatal_error.error',
),
'inconsistent_context' =>
array (
'type' => 'error',
'error' => 'frontoffice.payment.results.payment.inconsistent_context.error',
),
'pid_not_found' =>
array (
'type' => 'error',
'error' => 'frontoffice.payment.results.payment.pid_not_found.error',
),
'workflow_error' =>
array (
'type' => 'error',
'error' => 'frontoffice.payment.results.payment.workflow_error.error',
),
'provider_error' =>
array (
'type' => 'error',
'error' => 'frontoffice.payment.results.payment.inconsistent_context.error',
),
'paygreen_unavailable' =>
array (
'type' => 'message',
'title' => 'frontoffice.payment.results.payment.paygreen_unavailable.title',
'message' => 'frontoffice.payment.results.payment.paygreen_unavailable.message',
'link' =>
array (
'name' => 'retry_payment_validation',
'text' => 'frontoffice.payment.results.payment.paygreen_unavailable.link',
'reload' => false,
),
),
),
'order' =>
array (
'validate' =>
array (
'type' => 'redirect',
'link' => 'checkout.success',
),
'test' =>
array (
'extends' => 'validate',
),
'verify' =>
array (
'extends' => 'validate',
),
'auth' =>
array (
'extends' => 'validate',
),
'wait' =>
array (
'extends' => 'validate',
),
'unknown' =>
array (
'type' => 'redirect',
'link' => 'order',
),
'cancel' =>
array (
'type' => 'message',
'title' => 'frontoffice.payment.results.order.cancel.title',
'message' => '~message_order_canceled',
'link' =>
array (
'name' => 'order',
'text' => 'frontoffice.payment.results.order.cancel.link',
),
),
'error' =>
array (
'type' => 'message',
'title' => 'frontoffice.payment.results.order.error.title',
'message' => 'frontoffice.payment.results.order.error.message',
'link' =>
array (
'name' => 'order',
'text' => 'frontoffice.payment.results.order.error.link',
),
),
'new' =>
array (
'type' => 'message',
'title' => 'frontoffice.payment.results.order.new.title',
'message' => 'frontoffice.payment.results.order.new.message',
'link' =>
array (
'name' => 'order',
'text' => 'frontoffice.payment.results.order.new.link',
),
),
),
),
),
'order' =>
array (
'states' =>
array (
'VALIDATE' =>
array (
'name' => 'Paiement confirmé',
'source' =>
array (
'type' => 'magento',
'state' => 'processing',
'status' => 'processing',
),
),
'ERROR' =>
array (
'name' => 'Paiement en erreur',
'create' => true,
'source' =>
array (
'type' => 'magento',
'state' => 'payment_review',
'status' => 'paygreen_payment_error',
),
'metadata' =>
array (
'visibility' => true,
),
),
'CANCEL' =>
array (
'name' => 'Paiement annulé',
'source' =>
array (
'type' => 'magento',
'state' => 'canceled',
'status' => 'canceled',
),
),
'TEST' =>
array (
'name' => 'Test validé',
'create' => true,
'source' =>
array (
'type' => 'magento',
'state' => 'processing',
'status' => 'paygreen_test',
),
'metadata' =>
array (
'visibility' => true,
),
),
'VERIFY' =>
array (
'name' => 'Paiement suspect',
'source' =>
array (
'type' => 'magento',
'state' => 'payment_review',
'status' => 'fraud',
),
),
'AUTH' =>
array (
'name' => 'Prélèvement en attente',
'create' => true,
'source' =>
array (
'type' => 'magento',
'state' => 'processing',
'status' => 'paygreen_payment_authorized',
),
'metadata' =>
array (
'visibility' => true,
),
),
'WAIT' =>
array (
'name' => 'Dossier de paiement validé',
'create' => true,
'source' =>
array (
'type' => 'magento',
'state' => 'payment_review',
'status' => 'paygreen_payment_waiting',
),
'metadata' =>
array (
'visibility' => true,
),
),
'NEW' =>
array (
'name' => 'Paiement en attente',
'source' =>
array (
'type' => 'magento',
'state' => 'payment_review',
'status' => 'payment_review',
),
),
),
'machines' =>
array (
'CASH' =>
array (
'start' =>
array (
),
'transitions' =>
array (
'NEW' =>
array (
0 => 'ERROR',
1 => 'VALIDATE',
2 => 'TEST',
3 => 'VERIFY',
4 => 'CANCEL',
),
),
),
'RECURRING' =>
array (
'start' =>
array (
),
'transitions' =>
array (
'WAIT' =>
array (
0 => 'ERROR',
1 => 'VALIDATE',
2 => 'TEST',
),
'ERROR' =>
array (
0 => 'VALIDATE',
1 => 'TEST',
),
'VALIDATE' =>
array (
0 => 'ERROR',
),
'TEST' =>
array (
0 => 'ERROR',
),
'NEW' =>
array (
0 => 'ERROR',
1 => 'WAIT',
2 => 'CANCEL',
),
),
),
'XTIME' =>
array (
'start' =>
array (
),
'transitions' =>
array (
'WAIT' =>
array (
0 => 'ERROR',
1 => 'VALIDATE',
2 => 'TEST',
),
'ERROR' =>
array (
0 => 'VALIDATE',
1 => 'TEST',
),
'NEW' =>
array (
0 => 'ERROR',
1 => 'WAIT',
2 => 'CANCEL',
),
),
),
'TOKENIZE' =>
array (
'start' =>
array (
),
'transitions' =>
array (
'AUTH' =>
array (
0 => 'ERROR',
1 => 'VALIDATE',
2 => 'TEST',
3 => 'VERIFY',
),
'NEW' =>
array (
0 => 'ERROR',
1 => 'CANCEL',
2 => 'AUTH',
),
),
),
),
),
'paygreen' =>
array (
'backlink' => 'http://paygreen.io/paiement-securise/',
),
'api' =>
array (
'payment' =>
array (
'requests' =>
array (
'oauth_access' =>
array (
'method' => 'POST',
'url' => '{host}/api/auth',
'private' => false,
'validity' => '200,500',
'strict' => false,
),
'validate_shop' =>
array (
'method' => 'PATCH',
'url' => '{host}/api/{ui}/shop',
'private' => true,
),
'refund' =>
array (
'method' => 'DELETE',
'url' => '{host}/api/{ui}/payins/transaction/{pid}',
'private' => true,
),
'are_valid_ids' =>
array (
'method' => 'GET',
'url' => '{host}/api/{ui}',
'private' => true,
),
'get_data' =>
array (
'method' => 'GET',
'url' => '{host}/api/{ui}/{type}',
'private' => true,
),
'delivery' =>
array (
'method' => 'PUT',
'url' => '{host}/api/{ui}/payins/transaction/{pid}',
'private' => true,
),
'create_cash' =>
array (
'method' => 'POST',
'url' => '{host}/api/{ui}/payins/transaction/cash',
'private' => true,
),
'create_subscription' =>
array (
'method' => 'POST',
'url' => '{host}/api/{ui}/payins/transaction/subscription',
'private' => true,
),
'create_tokenize' =>
array (
'method' => 'POST',
'url' => '{host}/api/{ui}/payins/transaction/tokenize',
'private' => true,
),
'create_xtime' =>
array (
'method' => 'POST',
'url' => '{host}/api/{ui}/payins/transaction/xTime',
'private' => true,
),
'get_datas' =>
array (
'method' => 'GET',
'url' => '{host}/api/{ui}/payins/transaction/{pid}',
'private' => true,
),
'get_rounding' =>
array (
'method' => 'GET',
'url' => '{host}/api/{ui}/solidarity/{paymentToken}',
'private' => true,
),
'validate_rounding' =>
array (
'method' => 'PATCH',
'url' => '{host}/api/{ui}/solidarity/{paymentToken}',
'private' => true,
),
'refund_rounding' =>
array (
'method' => 'DELETE',
'url' => '{host}/api/{ui}/solidarity/{paymentToken}',
'private' => true,
),
'send_ccarbone' =>
array (
'method' => 'POST',
'url' => '{host}/api/{ui}/payins/ccarbone',
'private' => true,
),
'payment_types' =>
array (
'method' => 'GET',
'url' => '{host}/api/{ui}/availablepaymenttype',
'private' => true,
),
),
'clients' =>
array (
'curl' =>
array (
'allow_redirection' => true,
'verify_peer' => true,
'verify_host' => 2,
'timeout' => 30,
'http_version' => '1.1',
),
),
'responses' =>
array (
'class' => 'PGI\\Module\\APIPayment\\Components\\Response',
'strict' => true,
),
),
),
'oauth_exceptions_messages' =>
array (
1 => 'actions.authentication.save.errors.oauth_server_address_missmatch',
2 => 'actions.authentication.save.errors.oauth_data_validation',
),
'client' =>
array (
'curl' =>
array (
'verify_peer' => false,
),
),
);
