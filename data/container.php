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
 * @version   2.0.1
 *
 */

return array (
'kernel' =>
array (
'static' => true,
),
'container' =>
array (
'static' => true,
),
'bootstrap' =>
array (
'static' => true,
),
'autoloader' =>
array (
'static' => true,
),
'pathfinder' =>
array (
'static' => true,
),
'service.library' =>
array (
'static' => true,
),
'service.builder' =>
array (
'static' => true,
),
'parameters' =>
array (
'static' => true,
),
'parser' =>
array (
'static' => true,
),
'aggregator.requirement' =>
array (
'abstract' => false,
'class' => 'PGFrameworkComponentsAggregator',
'arguments' =>
array (
0 => '@container',
),
'extends' => 'aggregator.abstract',
'config' =>
array (
'type' => 'requirement',
'interface' => 'PGFrameworkInterfacesRequirementInterface',
),
'catch' => 'requirement',
),
'diagnostic.media_files_chmod' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'diagnostic',
),
),
'class' => 'PGFrameworkServicesDiagnosticsMediaFilesChmod',
'extends' => 'diagnostic.abstract',
'arguments' =>
array (
0 => '@pathfinder',
),
),
'diagnostic.media_folder_chmod' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'diagnostic',
),
),
'class' => 'PGFrameworkServicesDiagnosticsMediaFolderChmod',
'extends' => 'diagnostic.abstract',
'arguments' =>
array (
0 => '@pathfinder',
),
),
'diagnostic.var_folder_chmod' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'diagnostic',
),
),
'class' => 'PGFrameworkServicesDiagnosticsVarFolderChmod',
'extends' => 'diagnostic.abstract',
'arguments' =>
array (
0 => '@pathfinder',
),
),
'officer.setup' =>
array (
'class' => 'PGMagentoServicesOfficersSetupOfficer',
),
'handler.picture' =>
array (
'class' => 'PGFrameworkServicesHandlersPictureHandler',
'arguments' =>
array (
0 => '${PAYGREEN_MEDIA_DIR}',
1 => '%{media.baseurl}',
),
),
'handler.cache' =>
array (
'class' => 'PGFrameworkServicesHandlersCacheHandler',
'arguments' =>
array (
0 => '%cache',
1 => '@pathfinder',
2 => '@settings',
3 => '@logger',
),
),
'handler.select' =>
array (
'class' => 'PGFrameworkServicesHandlersSelectHandler',
'arguments' =>
array (
0 => '@container',
),
'catch' =>
array (
'tag' => 'selector',
'method' => 'addSelectorServiceName',
'built' => false,
),
),
'handler.mime_type' =>
array (
'class' => 'PGFrameworkServicesHandlersMimeTypeHandler',
'arguments' =>
array (
0 => '@logger',
1 => '%mime_types',
),
),
'handler.session' =>
array (
'class' => 'PGFrameworkServicesHandlersSessionHandler',
'arguments' =>
array (
0 => '@superglobal.session',
),
),
'handler.upload' =>
array (
'class' => 'PGFrameworkServicesHandlersUploadHandler',
'arguments' =>
array (
0 => '@logger',
),
),
'handler.output' =>
array (
'class' => 'PGFrameworkServicesHandlersOutputHandler',
),
'handler.cookie' =>
array (
'class' => 'PGFrameworkServicesHandlersCookieHandler',
'arguments' =>
array (
0 => '@superglobal.cookie',
1 => '@logger',
),
),
'handler.requirement' =>
array (
'class' => 'PGFrameworkServicesHandlersRequirementHandler',
'arguments' =>
array (
0 => '@aggregator.requirement',
),
),
'handler.hook' =>
array (
'class' => 'PGFrameworkServicesHandlersHookHandler',
'arguments' =>
array (
0 => '@container',
1 => '@logger',
),
'catch' =>
array (
'tag' => 'hook',
'method' => 'addHookName',
'built' => false,
),
),
'handler.http' =>
array (
'class' => 'PGFrameworkServicesHandlersHTTPHandler',
),
'superglobal.get' =>
array (
'abstract' => false,
'class' => 'PGFrameworkServicesSuperglobalsGet',
'extends' => 'superglobal.abstract',
),
'superglobal.post' =>
array (
'abstract' => false,
'class' => 'PGFrameworkServicesSuperglobalsPost',
'extends' => 'superglobal.abstract',
),
'superglobal.cookie' =>
array (
'abstract' => false,
'class' => 'PGFrameworkServicesSuperglobalsCookie',
'extends' => 'superglobal.abstract',
),
'superglobal.session' =>
array (
'class' => 'PGFrameworkServicesSuperglobalsSession',
'arguments' =>
array (
0 => '@logger',
),
),
'dumper' =>
array (
'class' => 'PGFrameworkServicesDumper',
),
'notifier' =>
array (
'class' => 'PGFrameworkServicesNotifier',
'arguments' =>
array (
0 => '@handler.session',
),
),
'upgrader' =>
array (
'class' => 'PGModuleServicesUpgrader',
'arguments' =>
array (
0 => '@aggregator.upgrade',
1 => '@logger',
2 => '%upgrades',
3 => '@aggregator.upgrade',
4 => '@logger',
5 => '%upgrades',
),
),
'listener.setup.static_files' =>
array (
'class' => 'PGFrameworkServicesListenersInstallStaticFilesListener',
'arguments' =>
array (
0 => '@handler.static_file',
1 => '@logger',
),
),
'behavior.detailed_logs' =>
array (
'class' => 'PGFrameworkServicesBehaviorsDetailedLogsBehavior',
'arguments' =>
array (
0 => '@settings',
),
),
'aggregator.upgrade' =>
array (
'abstract' => false,
'class' => 'PGFrameworkComponentsAggregator',
'arguments' =>
array (
0 => '@container',
),
'extends' => 'aggregator.abstract',
'config' =>
array (
'type' => 'upgrade',
'interface' => 'PGModuleInterfacesUpgrade',
),
'catch' => 'upgrade',
),
'manager.setting' =>
array (
'class' => 'PGModuleServicesManagersSetting',
'arguments' =>
array (
0 => '@repository.setting',
),
),
'officer.settings.database.basic' =>
array (
'class' => 'PGModuleServicesOfficersSettingsDatabase',
'arguments' =>
array (
0 => '@manager.setting',
1 => '@handler.shop',
),
),
'officer.settings.database.global' =>
array (
'class' => 'PGModuleServicesOfficersSettingsDatabase',
'arguments' =>
array (
0 => '@manager.setting',
),
),
'officer.settings.storage.basic' =>
array (
'class' => 'PGModuleServicesOfficersSettingsStorage',
'arguments' =>
array (
0 => '@pathfinder',
1 => '@handler.shop',
),
),
'officer.settings.storage.global' =>
array (
'class' => 'PGModuleServicesOfficersSettingsStorage',
'arguments' =>
array (
0 => '@pathfinder',
),
),
'handler.setup' =>
array (
'class' => 'PGModuleServicesHandlersSetup',
'arguments' =>
array (
0 => '@broadcaster',
1 => '@officer.setup',
2 => '@settings',
3 => '@logger',
),
),
'handler.behavior' =>
array (
'class' => 'PGModuleServicesHandlersBehavior',
'arguments' =>
array (
0 => '%behaviors',
),
),
'handler.diagnostic' =>
array (
'class' => 'PGModuleServicesHandlersDiagnostic',
'arguments' =>
array (
0 => '@container',
1 => '@logger',
),
'catch' =>
array (
'tag' => 'diagnostic',
'method' => 'addDiagnosticName',
'built' => false,
),
),
'handler.static_file' =>
array (
'class' => 'PGMagentoServicesHandlersStaticFileHandler',
'arguments' =>
array (
0 => '@logger',
1 => '@pathfinder',
2 => '%static',
),
'calls' =>
array (
0 =>
array (
'method' => 'setAssetRepository',
'arguments' =>
array (
0 => '@magento',
),
),
),
),
'logger' =>
array (
'class' => 'PGModuleServicesLogger',
'arguments' =>
array (
0 => '@dumper',
1 => '@pathfinder',
2 => '%{log.file}',
3 => '%{log.format}',
),
'calls' =>
array (
0 =>
array (
'method' => 'setBehaviorHandler',
'arguments' =>
array (
0 => '@handler.behavior',
),
),
),
),
'settings' =>
array (
'class' => 'PGModuleServicesSettings',
'arguments' =>
array (
0 => '@container',
1 => '%settings',
),
),
'broadcaster' =>
array (
'class' => 'PGModuleServicesBroadcaster',
'arguments' =>
array (
0 => '@container',
1 => '@logger',
2 => '%listeners',
),
'catch' =>
array (
'tag' => 'listener',
'method' => 'addListener',
'built' => false,
),
),
'facade.application' =>
array (
'class' => 'PGMagentoServicesFacadesApplication',
),
'facade.module' =>
array (
'class' => 'PGMagentoServicesFacadesModule',
),
'listener.settings.install_default' =>
array (
'class' => 'PGModuleServicesListenersInstallDefaultSettings',
'arguments' =>
array (
0 => '@settings',
1 => '@logger',
),
),
'listener.settings.uninstall' =>
array (
'class' => 'PGModuleServicesListenersUninstallSettings',
'arguments' =>
array (
0 => '@settings',
1 => '@logger',
),
),
'listener.upgrade' =>
array (
'class' => 'PGModuleServicesListenersUpgrade',
'arguments' =>
array (
0 => '@upgrader',
1 => '@logger',
),
),
'listener.setup.create_setting_table' =>
array (
'abstract' => false,
'class' => 'PGDatabaseServicesListenersGenericDatabaseRunner',
'arguments' =>
array (
0 => '@handler.database',
1 =>
array (
0 => 'PGModule:setting/clean.sql',
1 => 'PGModule:setting/install.sql',
),
),
'extends' => 'listener.database.runner',
),
'upgrade.update_settings_values' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'upgrade',
),
),
'class' => 'PGModuleServicesUpgradesUpdateSettingsValues',
'extends' => 'upgrade.abstract',
'arguments' =>
array (
0 => '@settings',
),
),
'repository.setting' =>
array (
'abstract' => false,
'arguments' =>
array (
0 => '@handler.database',
1 => '%database.entities.setting',
),
'extends' => 'repository.abstract',
'class' => 'PGModuleServicesRepositoriesSetting',
),
'handler.database' =>
array (
'class' => 'PGDatabaseServicesDatabaseHandler',
'arguments' =>
array (
0 => '@officer.database',
1 => '@parser',
2 => '@pathfinder',
3 => '@logger',
),
),
'upgrade.database' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'upgrade',
),
),
'class' => 'PGDatabaseServicesUpgradesDatabase',
'extends' => 'upgrade.abstract',
'arguments' =>
array (
0 => '@handler.database',
),
),
'aggregator.deflector' =>
array (
'abstract' => false,
'class' => 'PGFrameworkComponentsAggregator',
'arguments' =>
array (
0 => '@container',
),
'extends' => 'aggregator.abstract',
'config' =>
array (
'type' => 'deflector',
'interface' => 'PGServerInterfacesDeflectorInterface',
),
'catch' => 'deflector',
),
'aggregator.linker' =>
array (
'abstract' => false,
'class' => 'PGFrameworkComponentsAggregator',
'arguments' =>
array (
0 => '@container',
),
'extends' => 'aggregator.abstract',
'config' =>
array (
'type' => 'linker',
'interface' => 'PGServerInterfacesLinkerInterface',
),
'catch' => 'linker',
),
'cleaner.basic_http.not_found' =>
array (
'class' => 'PGServerServicesCleanersBasicHTTPCleaner',
'arguments' =>
array (
0 => 404,
),
),
'cleaner.basic_http.unauthorized_access' =>
array (
'class' => 'PGServerServicesCleanersBasicHTTPCleaner',
'arguments' =>
array (
0 => 401,
),
),
'cleaner.basic_http.server_error' =>
array (
'class' => 'PGServerServicesCleanersBasicHTTPCleaner',
'arguments' =>
array (
0 => 500,
),
),
'cleaner.basic_http.bad_request' =>
array (
'class' => 'PGServerServicesCleanersBasicHTTPCleaner',
'arguments' =>
array (
0 => 400,
),
),
'cleaner.basic_throw' =>
array (
'class' => 'PGServerServicesCleanersBasicThrowCleaner',
),
'handler.route' =>
array (
'class' => 'PGServerServicesHandlersRouteHandler',
'arguments' =>
array (
0 => '%routing.routes',
),
'calls' =>
array (
0 =>
array (
'method' => 'setRequirementHandler',
'arguments' =>
array (
0 => '@handler.requirement',
),
),
),
),
'handler.area' =>
array (
'class' => 'PGServerServicesHandlersAreaHandler',
'arguments' =>
array (
0 => '%routing.areas',
),
),
'renderer.transformer.paygreen_module_2_array' =>
array (
'class' => 'PGServerServicesRenderersTransformersPaygreenModuleToArrayTransformer',
'arguments' =>
array (
0 => '@notifier',
),
),
'renderer.transformer.file_2_http' =>
array (
'class' => 'PGServerServicesRenderersTransformersFileToHttpTransformer',
'arguments' =>
array (
0 => '@handler.mime_type',
),
),
'renderer.transformer.array_2_http' =>
array (
'class' => 'PGServerServicesRenderersTransformersArrayToHttpTransformer',
),
'renderer.transformer.string_2_http' =>
array (
'class' => 'PGServerServicesRenderersTransformersStringToHttpTransformer',
),
'renderer.transformer.redirection_2_http' =>
array (
'class' => 'PGServerServicesRenderersTransformersRedirectionToHttpTransformer',
),
'renderer.processor.write_http' =>
array (
'class' => 'PGServerServicesRenderersProcessorsWriteHTTPProcessor',
'arguments' =>
array (
0 => '1.1',
1 => '%http_codes',
),
),
'renderer.processor.output_template' =>
array (
'class' => 'PGServerServicesRenderersProcessorsOutputTemplateProcessor',
'arguments' =>
array (
0 => '@handler.view',
1 => '@handler.output',
),
),
'dispatcher' =>
array (
'class' => 'PGServerServicesDispatcher',
'arguments' =>
array (
0 => '@logger',
1 => '@broadcaster',
),
'catch' =>
array (
'tag' => 'controller',
'method' => 'addControllerName',
'built' => false,
),
),
'builder.request.default' =>
array (
'class' => 'PGServerServicesRequestBuilder',
'arguments' =>
array (
0 => '@superglobal.get',
1 => '@superglobal.post',
2 => '%request_builder.default',
),
),
'router' =>
array (
'class' => 'PGServerServicesRouter',
'arguments' =>
array (
0 => '@handler.area',
1 => '@handler.route',
),
),
'derouter' =>
array (
'class' => 'PGServerServicesDerouter',
'arguments' =>
array (
0 => '@aggregator.deflector',
1 => '@logger',
),
),
'linker' =>
array (
'class' => 'PGServerServicesLinker',
'arguments' =>
array (
0 => '@aggregator.linker',
1 => '@logger',
2 => '@facade.module',
),
),
'factory.trigger' =>
array (
'class' => 'PGServerServicesFactoriesTriggerFactory',
'arguments' =>
array (
0 => '@container',
1 => '@logger',
),
'catch' =>
array (
'tag' => 'acceptor',
'method' => 'addAcceptorServiceName',
'built' => false,
),
),
'factory.stage' =>
array (
'class' => 'PGServerServicesFactoriesStageFactory',
'arguments' =>
array (
0 => '@factory.trigger',
1 => '@logger',
),
),
'acceptor.class' =>
array (
'class' => 'PGServerServicesAcceptorsClassAcceptor',
'tags' =>
array (
0 =>
array (
'name' => 'acceptor',
'options' =>
array (
0 => 'class',
),
),
),
),
'acceptor.instance' =>
array (
'class' => 'PGServerServicesAcceptorsInstanceAcceptor',
'tags' =>
array (
0 =>
array (
'name' => 'acceptor',
'options' =>
array (
0 => 'instance',
),
),
),
),
'acceptor.tag' =>
array (
'class' => 'PGServerServicesAcceptorsTagAcceptor',
'tags' =>
array (
0 =>
array (
'name' => 'acceptor',
'options' =>
array (
0 => 'tag',
),
),
),
),
'acceptor.action' =>
array (
'class' => 'PGServerServicesAcceptorsActionAcceptor',
'tags' =>
array (
0 =>
array (
'name' => 'acceptor',
'options' =>
array (
0 => 'action',
),
),
),
),
'manager.translation' =>
array (
'class' => 'PGIntlServicesManagersTranslationManager',
'arguments' =>
array (
0 => '@repository.translation',
),
),
'officer.locale' =>
array (
'class' => 'PGMagentoServicesOfficersLocaleOfficer',
'arguments' =>
array (
0 => '@magento',
),
),
'handler.translation' =>
array (
'class' => 'PGIntlServicesHandlersTranslationHandler',
'arguments' =>
array (
0 => '@manager.translation',
1 => '@handler.locale',
2 => '@logger',
3 => '%translations',
),
),
'handler.locale' =>
array (
'class' => 'PGIntlServicesHandlersLocaleHandler',
'arguments' =>
array (
0 => '@officer.locale',
),
),
'handler.cache.translation' =>
array (
'class' => 'PGIntlServicesHandlersCacheTranslationHandler',
'arguments' =>
array (
0 => '@pathfinder',
1 => '@settings',
2 => '@logger',
),
),
'plugin.smarty.translator' =>
array (
'class' => 'PGIntlServicesPluginsSmartyTranslator',
'arguments' =>
array (
0 => '@translator',
),
'tags' =>
array (
0 =>
array (
'name' => 'plugin.smarty',
'options' =>
array (
0 => 'pgtrans',
1 => 'translateExpression',
),
),
1 =>
array (
'name' => 'plugin.smarty',
'options' =>
array (
0 => 'pgtranslines',
1 => 'translateParagraph',
),
),
),
),
'translator' =>
array (
'class' => 'PGIntlServicesTranslator',
'arguments' =>
array (
0 => '@handler.cache.translation',
1 => '@pathfinder',
2 => '@handler.locale',
3 => '@logger',
4 => '%translator',
),
),
'listener.setup.install_default_translations' =>
array (
'class' => 'PGIntlServicesListenersInsertDefaultTranslationsListener',
'arguments' =>
array (
0 => '@handler.translation',
1 => '@manager.shop',
),
),
'listener.setup.create_translation_table' =>
array (
'abstract' => false,
'class' => 'PGDatabaseServicesListenersGenericDatabaseRunner',
'arguments' =>
array (
0 => '@handler.database',
1 =>
array (
0 => 'PGIntl:translation/clean.sql',
1 => 'PGIntl:translation/install.sql',
),
),
'extends' => 'listener.database.runner',
),
'listener.setup.reset_translation_cache' =>
array (
'class' => 'PGIntlServicesListenersResetTranslationCacheListener',
'arguments' =>
array (
0 => '@handler.cache.translation',
1 => '@logger',
),
),
'upgrade.translations.install_default_values' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'upgrade',
),
),
'class' => 'PGIntlServicesUpgradesInsertDefaultTranslationsUpgrade',
'extends' => 'upgrade.abstract',
'arguments' =>
array (
0 => '@handler.translation',
1 => '@manager.shop',
),
),
'upgrade.translations.restore' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'upgrade',
),
),
'class' => 'PGIntlServicesUpgradesRestoreTranslationsUpgrade',
'extends' => 'upgrade.abstract',
'arguments' =>
array (
0 => '@manager.translation',
1 => '@manager.shop',
2 => '@repository.setting',
3 => '@settings',
),
),
'upgrade.button_labels.restore' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'upgrade',
),
),
'class' => 'PGIntlServicesUpgradesRestoreButtonLabelsUpgrade',
'extends' => 'upgrade.abstract',
'arguments' =>
array (
0 => '@manager.translation',
1 => '@manager.shop',
2 => '@handler.database',
),
),
'repository.translation' =>
array (
'abstract' => false,
'arguments' =>
array (
0 => '@handler.database',
1 => '%database.entities.translation',
2 => '@handler.shop',
),
'extends' => 'repository.abstract',
'class' => 'PGIntlServicesRepositoriesTranslationRepository',
),
'selector.language' =>
array (
'arguments' =>
array (
0 => '@logger',
1 => '%languages',
),
'abstract' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setTranslator',
'arguments' =>
array (
0 => '@translator',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'selector',
),
),
'extends' => 'selector.abstract',
'class' => 'PGIntlServicesSelectorsLanguageSelector',
),
'selector.countries' =>
array (
'arguments' =>
array (
0 => '@logger',
1 => '%countries',
),
'abstract' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setTranslator',
'arguments' =>
array (
0 => '@translator',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'selector',
),
),
'extends' => 'selector.abstract',
'class' => 'PGIntlServicesSelectorsCountrySelector',
),
'aggregator.view' =>
array (
'abstract' => false,
'class' => 'PGFrameworkComponentsAggregator',
'arguments' =>
array (
0 => '@container',
),
'extends' => 'aggregator.abstract',
'config' =>
array (
'type' => 'view',
'interface' => 'PGViewInterfacesViewInterface',
),
'catch' => 'view',
),
'plugin.smarty.view_injecter' =>
array (
'class' => 'PGViewServicesPluginsSmartyViewInjecter',
'arguments' =>
array (
0 => '@handler.view',
),
'tags' =>
array (
0 =>
array (
'name' => 'plugin.smarty',
'options' =>
array (
0 => 'view',
1 => 'insertView',
2 => 'function',
),
),
1 =>
array (
'name' => 'plugin.smarty',
'options' =>
array (
0 => 'template',
1 => 'insertTemplate',
2 => 'function',
),
),
),
),
'plugin.smarty.linker' =>
array (
'class' => 'PGViewServicesPluginsSmartyLinker',
'arguments' =>
array (
0 => '@linker',
),
'tags' =>
array (
0 =>
array (
'name' => 'plugin.smarty',
'options' =>
array (
0 => 'toback',
1 => 'buildBackOfficeUrl',
),
),
1 =>
array (
'name' => 'plugin.smarty',
'options' =>
array (
0 => 'tofront',
1 => 'buildFrontOfficeUrl',
),
),
),
),
'plugin.smarty.picture' =>
array (
'class' => 'PGViewServicesPluginsSmartyPicture',
'arguments' =>
array (
0 => '@handler.static_file',
),
'tags' =>
array (
0 =>
array (
'name' => 'plugin.smarty',
'options' =>
array (
0 => 'picture',
1 => 'buildPictureUrl',
),
),
),
),
'plugin.smarty.clip' =>
array (
'class' => 'PGViewServicesPluginsSmartyClip',
'tags' =>
array (
0 =>
array (
'name' => 'plugin.smarty',
'options' =>
array (
0 => 'clip',
1 => 'clip',
),
),
),
),
'handler.view' =>
array (
'class' => 'PGViewServicesHandlersViewHandler',
'arguments' =>
array (
0 => '@aggregator.view',
1 => '@handler.smarty',
2 => '@pathfinder',
),
),
'view.basic' =>
array (
'class' => 'PGViewServicesView',
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setViewHandler',
'arguments' =>
array (
0 => '@handler.view',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'view',
),
),
),
'handler.smarty' =>
array (
'class' => 'PGViewServicesHandlersSmartyHandler',
'arguments' =>
array (
0 => '@%{smarty.builder.service}',
1 => '@pathfinder',
2 => '@logger.view',
3 => '%smarty',
),
'catch' =>
array (
'tag' => 'plugin.smarty',
'method' => 'installPlugin',
'built' => true,
),
),
'handler.block' =>
array (
'class' => 'PGViewServicesHandlersBlockHandler',
'arguments' =>
array (
0 => '@handler.view',
1 => '@handler.requirement',
2 => '@dispatcher',
3 => '%blocks',
),
),
'builder.smarty' =>
array (
'class' => 'PGViewServicesBuildersSmartyBuilder',
'arguments' =>
array (
0 => '@pathfinder',
1 => '%smarty.builder',
),
),
'logger.view' =>
array (
'class' => 'PGModuleServicesLogger',
'arguments' =>
array (
0 => '@dumper',
1 => '@pathfinder',
2 => '%{log.view.file}',
3 => '%{log.view.format}',
),
'calls' =>
array (
0 =>
array (
'method' => 'setBehaviorHandler',
'arguments' =>
array (
0 => '@handler.behavior',
),
),
),
),
'listener.upgrade.clear_smarty_cache' =>
array (
'class' => 'PGViewServicesListenersClearSmartyCacheListener',
'arguments' =>
array (
0 => '@handler.smarty',
1 => '@logger',
),
),
'aggregator.formatter' =>
array (
'abstract' => false,
'class' => 'PGFrameworkComponentsAggregator',
'arguments' =>
array (
0 => '@container',
),
'extends' => 'aggregator.abstract',
'config' =>
array (
'type' => 'formatter',
'interface' => 'PGFormInterfacesFormatterInterface',
),
'catch' => 'formatter',
),
'builder.form' =>
array (
'class' => 'PGMagentoServicesBuildersFormBuilder',
'arguments' =>
array (
0 => '@builder.field',
1 => '@logger',
2 => '@aggregator.view',
3 => '%form',
),
'calls' =>
array (
0 =>
array (
'method' => 'setFormKey',
'arguments' =>
array (
0 => '@magento',
),
),
),
),
'builder.field' =>
array (
'class' => 'PGFormServicesFieldBuilder',
'arguments' =>
array (
0 => '@container',
1 => '@builder.validator',
2 => '@aggregator.formatter',
3 => '@handler.behavior',
4 => '@aggregator.view',
5 => '@logger',
6 => '%fields',
),
),
'builder.validator' =>
array (
'class' => 'PGFormServicesValidatorBuilder',
'arguments' =>
array (
0 => '@container',
),
'catch' =>
array (
'tag' => 'validator',
'method' => 'addValidatorServiceName',
'built' => false,
),
),
'view.form' =>
array (
'class' => 'PGFormServicesViewsFormView',
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setViewHandler',
'arguments' =>
array (
0 => '@handler.view',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'view',
),
),
'abstract' => false,
'extends' => 'view.basic',
),
'view.field' =>
array (
'class' => 'PGFormServicesViewsFieldView',
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setViewHandler',
'arguments' =>
array (
0 => '@handler.view',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'view',
),
),
'abstract' => false,
'extends' => 'view.basic',
),
'view.field.bool.checkbox' =>
array (
'class' => 'PGFormServicesViewsFieldBoolCheckboxView',
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setViewHandler',
'arguments' =>
array (
0 => '@handler.view',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'view',
),
),
'abstract' => false,
'extends' => 'view.basic',
),
'view.field.choice.expanded' =>
array (
'class' => 'PGFormServicesViewsFieldChoiceExpandedView',
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setViewHandler',
'arguments' =>
array (
0 => '@handler.view',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'view',
),
),
'abstract' => false,
'extends' => 'view.field.choice.abstract',
'arguments' =>
array (
0 => '@handler.select',
1 => '@translator',
),
),
'view.field.choice.contracted' =>
array (
'class' => 'PGFormServicesViewsFieldChoiceContractedView',
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setViewHandler',
'arguments' =>
array (
0 => '@handler.view',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'view',
),
),
'abstract' => false,
'extends' => 'view.field.choice.abstract',
'arguments' =>
array (
0 => '@handler.select',
1 => '@translator',
),
),
'view.field.picture' =>
array (
'class' => 'PGFormServicesViewsFieldPictureView',
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setViewHandler',
'arguments' =>
array (
0 => '@handler.view',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'view',
),
),
'abstract' => false,
'extends' => 'view.basic',
),
'view.field.object' =>
array (
'class' => 'PGFormServicesViewsFieldObjectView',
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setViewHandler',
'arguments' =>
array (
0 => '@handler.view',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'view',
),
),
'abstract' => false,
'extends' => 'view.basic',
),
'view.field.collection' =>
array (
'class' => 'PGFormServicesViewsFieldCollectionView',
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setViewHandler',
'arguments' =>
array (
0 => '@handler.view',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'view',
),
),
'abstract' => false,
'extends' => 'view.basic',
),
'view.field.choice.double.bool' =>
array (
'class' => 'PGFormServicesViewsFieldDoubleChoiceBooleanView',
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setViewHandler',
'arguments' =>
array (
0 => '@handler.view',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'view',
),
),
'abstract' => false,
'extends' => 'view.field.choice.abstract',
'arguments' =>
array (
0 => '@handler.select',
1 => '@translator',
),
),
'view.field.choice.filtered' =>
array (
'class' => 'PGFormServicesViewsFieldChoiceFilteredView',
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setViewHandler',
'arguments' =>
array (
0 => '@handler.view',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'view',
),
),
'abstract' => false,
'extends' => 'view.field.choice.abstract',
'arguments' =>
array (
0 => '@handler.select',
1 => '@translator',
),
),
'formatter.abstract' =>
array (
'shared' => false,
'tags' =>
array (
0 =>
array (
'name' => 'formatter',
),
),
),
'formatter.string' =>
array (
'shared' => false,
'tags' =>
array (
0 =>
array (
'name' => 'formatter',
),
),
'abstract' => false,
'extends' => 'formatter.abstract',
'class' => 'PGFormServicesFormattersStringFormatter',
),
'formatter.int' =>
array (
'shared' => false,
'tags' =>
array (
0 =>
array (
'name' => 'formatter',
),
),
'abstract' => false,
'extends' => 'formatter.abstract',
'class' => 'PGFormServicesFormattersIntegerFormatter',
),
'formatter.float' =>
array (
'shared' => false,
'tags' =>
array (
0 =>
array (
'name' => 'formatter',
),
),
'abstract' => false,
'extends' => 'formatter.abstract',
'class' => 'PGFormServicesFormattersFloatFormatter',
),
'formatter.array' =>
array (
'shared' => false,
'tags' =>
array (
0 =>
array (
'name' => 'formatter',
),
),
'abstract' => false,
'extends' => 'formatter.abstract',
'class' => 'PGFormServicesFormattersArrayFormatter',
),
'formatter.object' =>
array (
'shared' => false,
'tags' =>
array (
0 =>
array (
'name' => 'formatter',
),
),
'abstract' => false,
'extends' => 'formatter.abstract',
'class' => 'PGFormServicesFormattersObjectFormatter',
),
'formatter.bool' =>
array (
'shared' => false,
'tags' =>
array (
0 =>
array (
'name' => 'formatter',
),
),
'abstract' => false,
'extends' => 'formatter.abstract',
'class' => 'PGFormServicesFormattersBooleanFormatter',
),
'validator.length.min' =>
array (
'abstract' => false,
'shared' => false,
'tags' =>
array (
0 =>
array (
'name' => 'validator',
),
),
'extends' => 'validator.abstract',
'class' => 'PGFormServicesValidatorsLengthMinValidator',
),
'validator.length.max' =>
array (
'abstract' => false,
'shared' => false,
'tags' =>
array (
0 =>
array (
'name' => 'validator',
),
),
'extends' => 'validator.abstract',
'class' => 'PGFormServicesValidatorsLengthMaxValidator',
),
'validator.regexp' =>
array (
'abstract' => false,
'shared' => false,
'tags' =>
array (
0 =>
array (
'name' => 'validator',
),
),
'extends' => 'validator.abstract',
'class' => 'PGFormServicesValidatorsRegexpValidator',
),
'validator.array.in' =>
array (
'abstract' => false,
'shared' => false,
'tags' =>
array (
0 =>
array (
'name' => 'validator',
),
),
'extends' => 'validator.abstract',
'class' => 'PGFormServicesValidatorsArrayInValidator',
'arguments' =>
array (
0 => '@handler.select',
),
),
'validator.not_empty' =>
array (
'abstract' => false,
'shared' => false,
'tags' =>
array (
0 =>
array (
'name' => 'validator',
),
),
'extends' => 'validator.abstract',
'class' => 'PGFormServicesValidatorsNotEmptyValidator',
),
'validator.range' =>
array (
'abstract' => false,
'shared' => false,
'tags' =>
array (
0 =>
array (
'name' => 'validator',
),
),
'extends' => 'validator.abstract',
'class' => 'PGFormServicesValidatorsRangeValidator',
),
'manager.shop' =>
array (
'class' => 'PGShopServicesManagersShop',
'arguments' =>
array (
0 => '@repository.shop',
),
),
'manager.cart' =>
array (
'class' => 'PGShopServicesManagersCart',
'arguments' =>
array (
0 => '@repository.cart',
),
),
'manager.order' =>
array (
'class' => 'PGShopServicesManagersOrder',
'arguments' =>
array (
0 => '@repository.order',
),
'calls' =>
array (
0 =>
array (
'method' => 'setOrderStateMapper',
'arguments' =>
array (
0 => '@mapper.order_state',
),
),
),
),
'manager.customer' =>
array (
'class' => 'PGShopServicesManagersCustomer',
'arguments' =>
array (
0 => '@repository.customer',
),
),
'manager.address' =>
array (
'class' => 'PGShopServicesManagersAddress',
'arguments' =>
array (
0 => '@repository.address',
),
),
'manager.product' =>
array (
'class' => 'PGShopServicesManagersProduct',
'arguments' =>
array (
0 => '@repository.product',
),
),
'manager.category' =>
array (
'class' => 'PGShopServicesManagersCategory',
'arguments' =>
array (
0 => '@repository.category',
1 => '@handler.shop',
),
),
'manager.order_state' =>
array (
'class' => 'PGShopServicesManagersOrderState',
'arguments' =>
array (
0 => '@repository.order_state',
1 => '@factory.order_state_machine',
2 => '@mapper.order_state',
),
),
'officer.post_payment' =>
array (
'class' => 'PGMagentoPaymentServicesOfficersPostPaymentOfficer',
),
'handler.shop' =>
array (
'class' => 'PGShopServicesHandlersShopHandler',
'arguments' =>
array (
0 => '@logger',
),
'calls' =>
array (
0 =>
array (
'method' => 'setShopManager',
'arguments' =>
array (
0 => '@manager.shop',
),
),
1 =>
array (
'method' => 'setSessionHandler',
'arguments' =>
array (
0 => '@handler.session',
),
),
2 =>
array (
'method' => 'setShopOfficer',
'arguments' =>
array (
0 => '@officer.shop',
),
),
),
),
'factory.order_state_machine' =>
array (
'class' => 'PGShopServicesFactoriesOrderStateMachine',
'arguments' =>
array (
0 => '%order.machines',
),
),
'mapper.order_state' =>
array (
'class' => 'PGShopServicesOrderStateMapper',
'arguments' =>
array (
0 => '%order.states',
),
'catch' =>
array (
'tag' => 'mapper.strategy.order_state',
'method' => 'addMapperStrategy',
'built' => true,
),
),
'strategy.order_state_mapper.settings' =>
array (
'class' => 'PGShopServicesOrderStateMappingStrategiesSettings',
'arguments' =>
array (
0 => '@settings',
),
'calls' =>
array (
0 =>
array (
'method' => 'setOrderStateManager',
'arguments' =>
array (
0 => '@manager.order_state',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'mapper.strategy.order_state',
'options' =>
array (
0 => 'settings',
),
),
),
),
'repository.shop' =>
array (
'class' => 'PGMagentoServicesRepositoriesShopRepository',
),
'repository.cart' =>
array (
),
'repository.order' =>
array (
'class' => 'PGMagentoServicesRepositoriesOrderRepository',
),
'repository.customer' =>
array (
),
'repository.address' =>
array (
'class' => 'PGMagentoServicesRepositoriesAddressRepository',
),
'repository.product' =>
array (
'class' => 'PGMagentoServicesRepositoriesProductRepository',
),
'repository.cart_item' =>
array (
),
'repository.category' =>
array (
'class' => 'PGMagentoServicesRepositoriesCategoryRepository',
),
'repository.order_state' =>
array (
'class' => 'PGMagentoServicesRepositoriesOrderStateRepository',
'arguments' =>
array (
0 => '%order.states',
),
),
'selector.category.hierarchized' =>
array (
'arguments' =>
array (
0 => '@logger',
),
'abstract' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setTranslator',
'arguments' =>
array (
0 => '@translator',
),
),
1 =>
array (
'method' => 'setCategoryManager',
'arguments' =>
array (
0 => '@manager.category',
),
),
2 =>
array (
'method' => 'setCategoryManager',
'arguments' =>
array (
0 => '@manager.category',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'selector',
),
),
'extends' => 'selector.abstract',
'class' => 'PGShopServicesSelectorsHierarchizedCategory',
),
'action.support_configuration.save' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
),
'extends' => 'action.standardized_save_settings.abstract',
'class' => 'BOModuleActionsStandardizedSaveSettings',
'arguments' =>
array (
0 => '@builder.form',
1 => '@settings',
),
'config' =>
array (
'form_name' => 'settings_support',
'redirection' => 'backoffice.support.display',
),
),
'action.home.display' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'BOModuleActionsStandardizedDisplayPage',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'home',
),
),
'action.support.display' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'BOModuleActionsDisplaySupportPage',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'support',
),
),
'action.release_note.display' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'BOModuleActionsDisplayReleasesNotesPage',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'release_note',
),
),
'action.system.display' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'BOModuleActionsStandardizedDisplayPage',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'system',
),
),
'action.translations.display' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'BOModuleActionsStandardizedDisplayPage',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'translations',
),
),
'controller.backoffice.translations' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'controller',
),
),
'extends' => 'controller.abstract',
'class' => 'BOModuleControllersTranslationsController',
'arguments' =>
array (
0 => '@builder.translation_form',
1 => '@handler.translation',
2 => '@manager.translation',
),
),
'controller.backoffice.shop' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'controller',
),
),
'extends' => 'controller.abstract',
'class' => 'BOModuleControllersShopController',
'arguments' =>
array (
0 => '@handler.shop',
1 => '@manager.shop',
2 => '@handler.menu',
),
),
'controller.backoffice.error' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'controller',
),
),
'extends' => 'controller.abstract',
'class' => 'BOModuleControllersErrorController',
),
'controller.backoffice.diagnostic' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
6 =>
array (
'method' => 'setDiagnosticHandler',
'arguments' =>
array (
0 => '@handler.diagnostic',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'controller',
),
),
'extends' => 'controller.abstract',
'class' => 'BOModuleControllersDiagnosticController',
),
'controller.backoffice.logs' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'controller',
),
),
'extends' => 'controller.abstract',
'class' => 'BOModuleControllersLogsController',
'arguments' =>
array (
0 => '@pathfinder',
),
),
'controller.backoffice.system' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'controller',
),
),
'extends' => 'controller.abstract',
'class' => 'BOModuleControllersSystemController',
'arguments' =>
array (
0 => '@facade.application',
1 => '@kernel',
),
),
'controller.backoffice.release_note' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'controller',
),
),
'extends' => 'controller.abstract',
'class' => 'BOModuleControllersReleaseNoteController',
'arguments' =>
array (
0 => '@pathfinder',
1 => '@logger',
),
),
'handler.menu' =>
array (
'class' => 'BOModuleServicesHandlersMenuHandler',
'arguments' =>
array (
0 => '@handler.route',
1 => '@linker',
2 => '%menu',
),
),
'plugin.smarty.bool' =>
array (
'class' => 'BOModuleServicesPluginsSmartyBool',
'arguments' =>
array (
0 => '@translator',
),
'tags' =>
array (
0 =>
array (
'name' => 'plugin.smarty',
'options' =>
array (
0 => 'pgbool',
1 => 'writeBoolean',
),
),
),
),
'deflector.filter.shop_context' =>
array (
'abstract' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
1 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'deflector',
),
),
'extends' => 'deflector.abstract',
'class' => 'BOModuleServicesDeflectorsShopContextDeflector',
'arguments' =>
array (
0 => '@handler.route',
),
),
'requirement.shop_context' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'requirement',
),
),
'class' => 'BOModuleServicesRequirementsShopContextRequirement',
'extends' => 'requirement.abstract',
'arguments' =>
array (
0 => '@handler.shop',
),
),
'builder.request.backoffice' =>
array (
'class' => 'PGServerServicesRequestBuilder',
'arguments' =>
array (
0 => '@superglobal.get',
1 => '@superglobal.post',
2 => '%request_builder.backoffice',
),
),
'server.backoffice' =>
array (
'abstract' => false,
'class' => 'PGServerServicesServer',
'arguments' =>
array (
0 => '@router',
1 => '@derouter',
2 => '@dispatcher',
3 => '@logger',
4 => '@factory.stage',
5 => '%servers.backoffice',
),
'extends' => 'server.abstract',
),
'cleaner.forward.message_page' =>
array (
'class' => 'PGServerServicesCleanersForwardCleaner',
'arguments' =>
array (
0 => 'displayException@backoffice.error',
),
),
'builder.translation_form' =>
array (
'class' => 'PGIntlServicesBuildersTranslationFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
1 => '@builder.field',
2 => '%translations',
),
),
'listener.action.shop_context_backoffice' =>
array (
'class' => 'BOModuleServicesListenersShopContextBackofficeListener',
'arguments' =>
array (
0 => '@notifier',
1 => '@handler.shop',
),
),
'listener.page.backoffice_static_files' =>
array (
'class' => 'BOModuleServicesListenersDisplayBackofficeListener',
),
'listener.action.display_support_page' =>
array (
'class' => 'BOModuleServicesListenersDisplaySupportPageBackofficeListener',
'arguments' =>
array (
0 => '@notifier',
1 => '@handler.shop',
),
),
'view.menu' =>
array (
'class' => 'BOModuleServicesViewsMenuView',
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setViewHandler',
'arguments' =>
array (
0 => '@handler.view',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'view',
),
),
'abstract' => false,
'extends' => 'view.basic',
'arguments' =>
array (
0 => '@handler.menu',
1 => '@manager.shop',
2 => '@handler.shop',
),
),
'view.notifications' =>
array (
'class' => 'BOModuleServicesViewsNotificationsView',
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setViewHandler',
'arguments' =>
array (
0 => '@handler.view',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'view',
),
),
'abstract' => false,
'extends' => 'view.basic',
'arguments' =>
array (
0 => '@notifier',
),
),
'view.blocks' =>
array (
'class' => 'BOModuleServicesViewsBlockView',
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setViewHandler',
'arguments' =>
array (
0 => '@handler.view',
),
),
1 =>
array (
'method' => 'setBlockHandler',
'arguments' =>
array (
0 => '@handler.block',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'view',
),
),
'abstract' => false,
'extends' => 'view.basic',
),
'view.system.paths' =>
array (
'class' => 'BOModuleServicesViewsSystemPathsView',
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setViewHandler',
'arguments' =>
array (
0 => '@handler.view',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'view',
),
),
'abstract' => false,
'extends' => 'view.basic',
'arguments' =>
array (
0 => '@pathfinder',
),
),
'view.block.diagnostics' =>
array (
'class' => 'BOModuleServicesViewsBlocksDiagnosticBlock',
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setViewHandler',
'arguments' =>
array (
0 => '@handler.view',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'view',
),
),
'abstract' => false,
'extends' => 'view.basic',
'arguments' =>
array (
0 => '@handler.diagnostic',
),
),
'view.block.logs' =>
array (
'class' => 'BOModuleServicesViewsBlocksLogBlock',
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setViewHandler',
'arguments' =>
array (
0 => '@handler.view',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'view',
),
),
'abstract' => false,
'extends' => 'view.basic',
'arguments' =>
array (
0 => '@pathfinder',
),
),
'view.block.standardized.config_form' =>
array (
'class' => 'BOModuleServicesViewsBlocksStandardizedConfigurationFormBlock',
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setViewHandler',
'arguments' =>
array (
0 => '@handler.view',
),
),
1 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
2 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
3 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
4 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'view',
),
),
'abstract' => false,
'extends' => 'view.basic',
),
'controller.front.notification' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'controller',
),
),
'extends' => 'controller.abstract',
'class' => 'FOModuleControllersNotificationController',
),
'builder.request.frontoffice' =>
array (
'class' => 'FOPaymentServicesRequestBuilder',
'arguments' =>
array (
0 => '@superglobal.get',
1 => '@superglobal.post',
2 => '%request_builder.frontoffice',
),
),
'server.front' =>
array (
'abstract' => false,
'class' => 'PGServerServicesServer',
'arguments' =>
array (
0 => '@router',
1 => '@derouter',
2 => '@dispatcher',
3 => '@logger',
4 => '@factory.stage',
5 => '%servers.front',
),
'extends' => 'server.abstract',
),
'manager.button' =>
array (
'class' => 'PGPaymentServicesManagersButtonManager',
'arguments' =>
array (
0 => '@repository.button',
),
),
'manager.payment_type' =>
array (
'class' => 'PGPaymentServicesManagersPaymentTypeManager',
'arguments' =>
array (
0 => '@repository.payment_type',
),
),
'manager.lock' =>
array (
'class' => 'PGPaymentServicesManagersLockManager',
'arguments' =>
array (
0 => '@repository.lock',
),
),
'manager.category_has_payment_type' =>
array (
'class' => 'PGPaymentServicesManagersCategoryHasPaymentTypeManager',
'arguments' =>
array (
0 => '@repository.category_has_payment_type',
),
),
'manager.transaction' =>
array (
'class' => 'PGPaymentServicesManagersTransactionManager',
'arguments' =>
array (
0 => '@repository.transaction',
),
),
'manager.recurring_transaction' =>
array (
'class' => 'PGPaymentServicesManagersRecurringTransactionManager',
'arguments' =>
array (
0 => '@repository.recurring_transaction',
),
),
'handler.payment_creation' =>
array (
'class' => 'PGPaymentServicesHandlersPaymentCreationHandler',
'arguments' =>
array (
0 => '%payment',
),
),
'handler.payment_testing' =>
array (
'class' => 'PGPaymentServicesHandlersTestingPaymentHandler',
'arguments' =>
array (
0 => '@logger',
1 => '@logger.api',
2 => '@pathfinder',
),
),
'handler.payment_button' =>
array (
'class' => 'PGPaymentServicesHandlersPaymentButtonHandler',
'arguments' =>
array (
0 => '@logger',
1 => '@handler.picture',
2 => '@handler.static_file',
3 => '%payment.pictures',
),
),
'handler.refund' =>
array (
'class' => 'PGPaymentServicesHandlersRefundHandler',
'arguments' =>
array (
0 => '@paygreen.facade',
1 => '@logger',
),
'calls' =>
array (
0 =>
array (
'method' => 'setOrderManager',
'arguments' =>
array (
0 => '@manager.order',
),
),
1 =>
array (
'method' => 'setTransactionManager',
'arguments' =>
array (
0 => '@manager.transaction',
),
),
),
),
'handler.checkout' =>
array (
'class' => 'PGPaymentServicesHandlersCheckoutHandler',
'arguments' =>
array (
0 => '@logger',
1 => '@settings',
),
'calls' =>
array (
0 =>
array (
'method' => 'setPaygreenFacade',
'arguments' =>
array (
0 => '@paygreen.facade',
),
),
1 =>
array (
'method' => 'setModuleFacade',
'arguments' =>
array (
0 => '@facade.module',
),
),
2 =>
array (
'method' => 'setButtonManager',
'arguments' =>
array (
0 => '@manager.button',
),
),
),
),
'handler.tokenize' =>
array (
'class' => 'PGPaymentServicesHandlersTokenizeHandler',
'arguments' =>
array (
0 => '@broadcaster',
1 => '@logger',
),
'calls' =>
array (
0 =>
array (
'method' => 'setBehaviorHandler',
'arguments' =>
array (
0 => '@handler.behavior',
),
),
1 =>
array (
'method' => 'setPaygreenFacade',
'arguments' =>
array (
0 => '@paygreen.facade',
),
),
2 =>
array (
'method' => 'setTransactionManager',
'arguments' =>
array (
0 => '@manager.transaction',
),
),
),
),
'plugin.smarty.designator' =>
array (
'class' => 'PGPaymentServicesPluginsSmartyDesignator',
'arguments' =>
array (
0 => '@selector.payment_mode',
1 => '@selector.payment_type',
),
'tags' =>
array (
0 =>
array (
'name' => 'plugin.smarty',
'options' =>
array (
0 => 'modename',
1 => 'resolvePaymentModeName',
),
),
1 =>
array (
'name' => 'plugin.smarty',
'options' =>
array (
0 => 'typename',
1 => 'resolvePaymentTypeName',
),
),
),
),
'paygreen.facade' =>
array (
'class' => 'PGPaymentServicesPaygreenFacade',
'arguments' =>
array (
0 => '@api.factory',
1 => '@handler.http',
),
),
'responsability_chain.payment_creation' =>
array (
'class' => 'PGPaymentServicesResponsabilityChainsPaymentCreation',
'catch' =>
array (
'tag' => 'payment_creation_chain_link',
'method' => 'addChainLink',
'built' => true,
),
),
'listener.setup.install_default_button' =>
array (
'class' => 'PGPaymentServicesListenersInstallDefaultButtonListener',
'arguments' =>
array (
0 => '@manager.button',
1 => '@manager.translation',
2 => '@logger',
),
),
'listener.refund.update_transaction' =>
array (
'class' => 'PGPaymentServicesListenersRefundListener',
'arguments' =>
array (
0 => '@handler.refund',
1 => '@handler.behavior',
2 => '@logger',
),
),
'chain_links.payment_creation.add_customer_entrypoint' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'payment_creation_chain_link',
),
),
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
),
'extends' => 'chain_links.payment_creation.abstract',
'class' => 'PGPaymentServicesChainLinksAddFrontofficeEntrypoint',
'arguments' =>
array (
0 => '@linker',
1 => '%payment.entrypoints.customer',
2 => 'returned_url',
),
),
'chain_links.payment_creation.add_ipn_entrypoint' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'payment_creation_chain_link',
),
),
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
),
'extends' => 'chain_links.payment_creation.abstract',
'class' => 'PGPaymentServicesChainLinksAddFrontofficeEntrypoint',
'arguments' =>
array (
0 => '@linker',
1 => '%payment.entrypoints.ipn',
2 => 'notified_url',
),
),
'chain_links.payment_creation.add_common_data' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'payment_creation_chain_link',
),
),
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
),
'extends' => 'chain_links.payment_creation.abstract',
'class' => 'PGPaymentServicesChainLinksAddCommonData',
),
'chain_links.payment_creation.add_customer_data' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'payment_creation_chain_link',
),
),
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
),
'extends' => 'chain_links.payment_creation.abstract',
'class' => 'PGPaymentServicesChainLinksAddCustomerData',
),
'chain_links.payment_creation.add_customer_addresses_data' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'payment_creation_chain_link',
),
),
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
),
'extends' => 'chain_links.payment_creation.abstract',
'class' => 'PGPaymentServicesChainLinksAddCustomerAddressesData',
),
'chain_links.payment_creation.add_eligible_amount_data' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'payment_creation_chain_link',
),
),
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
),
'extends' => 'chain_links.payment_creation.abstract',
'class' => 'PGPaymentServicesChainLinksAddEligibleAmountData',
'arguments' =>
array (
0 => '@manager.product',
1 => '@settings',
),
),
'chain_links.payment_creation.add_xtime_data' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'payment_creation_chain_link',
),
),
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
),
'extends' => 'chain_links.payment_creation.abstract',
'class' => 'PGPaymentServicesChainLinksAddXTimeData',
),
'chain_links.payment_creation.add_recurring_data' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'payment_creation_chain_link',
),
),
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
),
'extends' => 'chain_links.payment_creation.abstract',
'class' => 'PGPaymentServicesChainLinksAddRecurringData',
),
'processor.payment_validation' =>
array (
'abstract' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setBroadcaster',
'arguments' =>
array (
0 => '@broadcaster',
),
),
1 =>
array (
'method' => 'setPostPaymentOfficer',
'arguments' =>
array (
0 => '@officer.post_payment',
),
),
),
'class' => 'PGPaymentServicesProcessorsPaymentValidationProcessor',
'extends' => 'processor.abstract',
),
'processor.transaction_management.cash' =>
array (
'abstract' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setBroadcaster',
'arguments' =>
array (
0 => '@broadcaster',
),
),
1 =>
array (
'method' => 'setPostPaymentOfficer',
'arguments' =>
array (
0 => '@officer.post_payment',
),
),
),
'extends' => 'processor.transaction_management.abstract',
'class' => 'PGPaymentServicesProcessorsManageCashTransactionProcessor',
),
'processor.transaction_management.tokenize' =>
array (
'abstract' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setBroadcaster',
'arguments' =>
array (
0 => '@broadcaster',
),
),
1 =>
array (
'method' => 'setPostPaymentOfficer',
'arguments' =>
array (
0 => '@officer.post_payment',
),
),
),
'extends' => 'processor.transaction_management.abstract',
'class' => 'PGPaymentServicesProcessorsManageTokenizeTransactionProcessor',
),
'processor.transaction_management.recurring' =>
array (
'abstract' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setBroadcaster',
'arguments' =>
array (
0 => '@broadcaster',
),
),
1 =>
array (
'method' => 'setPostPaymentOfficer',
'arguments' =>
array (
0 => '@officer.post_payment',
),
),
),
'extends' => 'processor.transaction_management.abstract',
'class' => 'PGPaymentServicesProcessorsManageRecurringTransactionProcessor',
),
'processor.transaction_management.xtime' =>
array (
'abstract' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setBroadcaster',
'arguments' =>
array (
0 => '@broadcaster',
),
),
1 =>
array (
'method' => 'setPostPaymentOfficer',
'arguments' =>
array (
0 => '@officer.post_payment',
),
),
),
'extends' => 'processor.transaction_management.abstract',
'class' => 'PGPaymentServicesProcessorsManageXTimeTransactionProcessor',
),
'upgrade.media_delete' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'upgrade',
),
),
'class' => 'PGPaymentServicesUpgradesMediaDeleteUpgrade',
'extends' => 'upgrade.abstract',
'arguments' =>
array (
0 => '@handler.picture',
),
),
'repository.button' =>
array (
'abstract' => false,
'arguments' =>
array (
0 => '@handler.database',
1 => '@handler.shop',
2 => '%database.entities.button',
),
'extends' => 'repository.abstract',
'class' => 'PGPaymentServicesRepositoriesButtonRepository',
),
'repository.payment_type' =>
array (
'class' => 'PGPaymentServicesRepositoriesPaymentTypeRepository',
),
'repository.lock' =>
array (
'abstract' => false,
'arguments' =>
array (
0 => '@handler.database',
1 => '%database.entities.lock',
),
'extends' => 'repository.abstract',
'class' => 'PGPaymentServicesRepositoriesLockRepository',
),
'repository.category_has_payment_type' =>
array (
'abstract' => false,
'arguments' =>
array (
0 => '@handler.database',
1 => '@handler.shop',
2 => '%database.entities.category_has_payment',
),
'extends' => 'repository.abstract',
'class' => 'PGPaymentServicesRepositoriesCategoryHasPaymentTypeRepository',
),
'repository.transaction' =>
array (
'abstract' => false,
'arguments' =>
array (
0 => '@handler.database',
1 => '%database.entities.transaction',
),
'extends' => 'repository.abstract',
'class' => 'PGPaymentServicesRepositoriesTransactionRepository',
),
'repository.recurring_transaction' =>
array (
'abstract' => false,
'arguments' =>
array (
0 => '@handler.database',
1 => '%database.entities.recurring_transaction',
),
'extends' => 'repository.abstract',
'class' => 'PGPaymentServicesRepositoriesRecurringTransactionRepository',
),
'selector.payment_mode' =>
array (
'arguments' =>
array (
0 => '@logger',
),
'abstract' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setTranslator',
'arguments' =>
array (
0 => '@translator',
),
),
1 =>
array (
'method' => 'setPaygreenFacade',
'arguments' =>
array (
0 => '@paygreen.facade',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'selector',
),
),
'extends' => 'selector.abstract',
'class' => 'PGPaymentServicesSelectorsPaymentModeSelector',
),
'selector.payment_type' =>
array (
'arguments' =>
array (
0 => '@logger',
),
'abstract' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setTranslator',
'arguments' =>
array (
0 => '@translator',
),
),
1 =>
array (
'method' => 'setPaymentTypeManager',
'arguments' =>
array (
0 => '@manager.payment_type',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'selector',
),
),
'extends' => 'selector.abstract',
'class' => 'PGPaymentServicesSelectorsPaymentTypeSelector',
),
'selector.payment_report' =>
array (
'arguments' =>
array (
0 => '@logger',
1 => '%data.payment_report',
),
'abstract' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setTranslator',
'arguments' =>
array (
0 => '@translator',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'selector',
),
),
'extends' => 'selector.abstract',
'class' => 'PGFrameworkServicesSelectorsStaticSelector',
),
'selector.button_integration' =>
array (
'arguments' =>
array (
0 => '@logger',
1 => '%data.button_integration',
),
'abstract' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setTranslator',
'arguments' =>
array (
0 => '@translator',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'selector',
),
),
'extends' => 'selector.abstract',
'class' => 'PGFrameworkServicesSelectorsStaticSelector',
),
'selector.display_type' =>
array (
'arguments' =>
array (
0 => '@logger',
1 => '%data.display_type',
),
'abstract' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setTranslator',
'arguments' =>
array (
0 => '@translator',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'selector',
),
),
'extends' => 'selector.abstract',
'class' => 'PGFrameworkServicesSelectorsStaticSelector',
),
'handler.oauth' =>
array (
'class' => 'APIPaymentServicesHandlersOAuth',
'arguments' =>
array (
0 => '@paygreen.facade',
1 => '@settings',
2 => '@pathfinder',
3 => '@handler.shop',
4 => '@linker',
),
),
'api.factory' =>
array (
'class' => 'APIPaymentServicesApiFactory',
'arguments' =>
array (
0 => '@logger.api',
1 => '@settings',
2 => '@facade.application',
3 => '@parameters',
),
),
'logger.api' =>
array (
'class' => 'PGModuleServicesLogger',
'arguments' =>
array (
0 => '@dumper',
1 => '@pathfinder',
2 => '%{log.api.file}',
3 => '%{log.api.format}',
),
'calls' =>
array (
0 =>
array (
'method' => 'setBehaviorHandler',
'arguments' =>
array (
0 => '@handler.behavior',
),
),
),
),
'listener.setup.payment_client_compatibility_checker' =>
array (
'class' => 'APIPaymentServicesListenersInstallCompatibilityCheck',
'arguments' =>
array (
0 => '@paygreen.facade',
),
),
'action.account_configuration.save' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setPaygreenFacade',
'arguments' =>
array (
0 => '@paygreen.facade',
),
),
4 =>
array (
'method' => 'setCacheHandler',
'arguments' =>
array (
0 => '@handler.cache',
),
),
),
'extends' => 'action.standardized_save_settings.abstract',
'class' => 'BOPaymentActionsSaveAccountConfiguration',
'arguments' =>
array (
0 => '@builder.form',
1 => '@settings',
),
'config' =>
array (
'form_name' => 'authentication',
'redirection' => 'backoffice.account.display',
),
),
'action.module_configuration.save' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
),
'extends' => 'action.standardized_save_settings.abstract',
'class' => 'BOModuleActionsStandardizedSaveSettings',
'arguments' =>
array (
0 => '@builder.form',
1 => '@settings',
),
'config' =>
array (
'form_name' => 'config',
'redirection' => 'backoffice.config.display',
),
),
'action.module_customization.save' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
),
'extends' => 'action.standardized_save_settings.abstract',
'class' => 'BOModuleActionsStandardizedSaveSettings',
'arguments' =>
array (
0 => '@builder.form',
1 => '@settings',
),
'config' =>
array (
'form_name' => 'settings_customization',
'redirection' => 'backoffice.config.display',
),
),
'action.payment_activation.save' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
),
'extends' => 'action.standardized_save_settings.abstract',
'class' => 'BOModuleActionsStandardizedSaveSettings',
'arguments' =>
array (
0 => '@builder.form',
1 => '@settings',
),
'config' =>
array (
'form_name' => 'payment_activation',
'redirection' => 'backoffice.home.display',
),
),
'action.account.display' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'BOModuleActionsStandardizedDisplayPage',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'account',
),
),
'action.config.display' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'BOModuleActionsStandardizedDisplayPage',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'config',
'selected_page' => 'module',
),
),
'action.account_ids.display' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'extends' => 'action.standardized_form_settings_block.abstract',
'class' => 'BOModuleActionsStandardizedFormSettingsBlock',
'config' =>
array (
'template' => 'account/block-ids',
'form_name' => 'authentication',
'form_action' => 'backoffice.account.save',
),
),
'action.account_login.display' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'extends' => 'action.standardized_form_settings_block.abstract',
'class' => 'BOModuleActionsStandardizedFormSettingsBlock',
'config' =>
array (
'template' => 'account/block-login',
'form_name' => 'authentication',
'form_action' => 'backoffice.account.save',
),
),
'action.payment_module_config.display' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'extends' => 'action.standardized_form_settings_block.abstract',
'class' => 'BOModuleActionsStandardizedFormSettingsBlock',
'config' =>
array (
'form_name' => 'config',
'form_action' => 'backoffice.config.save',
),
),
'action.payment_customization.display' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'extends' => 'action.standardized_form_settings_block.abstract',
'class' => 'BOModuleActionsStandardizedFormSettingsBlock',
'config' =>
array (
'form_name' => 'settings_customization',
'form_action' => 'backoffice.config.save_customization',
),
),
'action.buttons_list.display' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'BOModuleActionsStandardizedDisplayPage',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'button_list',
'selected_page' => 'buttons',
),
),
'action.eligible_amounts.display' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'BOPaymentActionsDisplayEligibleAmountsPage',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'eligible_amounts',
'selected_page' => 'eligible_amounts',
),
),
'controller.backoffice.account' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'controller',
),
),
'extends' => 'controller.abstract',
'class' => 'BOPaymentControllersAccount',
'arguments' =>
array (
0 => '@paygreen.facade',
1 => '@handler.cache',
),
),
'controller.backoffice.oauth' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'controller',
),
),
'extends' => 'controller.abstract',
'class' => 'BOPaymentControllersOAuth',
'arguments' =>
array (
0 => '@handler.oauth',
1 => '@superglobal.get',
),
),
'controller.backoffice.eligible_amounts' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'controller',
),
),
'extends' => 'controller.abstract',
'class' => 'BOPaymentControllersEligibleAmounts',
'arguments' =>
array (
0 => '@manager.category_has_payment_type',
1 => '@manager.category',
),
),
'controller.backoffice.buttons' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'controller',
),
),
'extends' => 'controller.abstract',
'class' => 'BOPaymentControllersButtons',
'arguments' =>
array (
0 => '@manager.button',
1 => '@handler.payment_button',
2 => '@handler.picture',
3 => '@manager.translation',
4 => '@handler.upload',
5 => '@handler.static_file',
),
),
'controller.backoffice.payment' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'controller',
),
),
'extends' => 'controller.abstract',
'class' => 'BOPaymentControllersPlugin',
'arguments' =>
array (
0 => '@paygreen.facade',
1 => '@handler.cache',
),
),
'deflector.filter.paygreen_connexion' =>
array (
'abstract' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
1 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'deflector',
),
),
'extends' => 'deflector.abstract',
'class' => 'BOPaymentServicesDeflectorsPaygreenConnexion',
'arguments' =>
array (
0 => '@handler.route',
),
),
'requirement.paygreen_connexion' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'requirement',
),
),
'class' => 'BOPaymentServicesRequirementsPaygreenConnexion',
'extends' => 'requirement.abstract',
'arguments' =>
array (
0 => '@paygreen.facade',
),
),
'requirement.payment_activation' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'requirement',
),
),
'class' => 'BOPaymentServicesRequirementsPaymentActivation',
'extends' => 'requirement.abstract',
'arguments' =>
array (
0 => '@settings',
),
),
'listener.action.display_backoffice' =>
array (
'class' => 'BOPaymentServicesListenersDisplayBackoffice',
'arguments' =>
array (
0 => '@notifier',
1 => '@paygreen.facade',
),
),
'linker.retry_payment_validation' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'linker',
),
),
'class' => 'FOPaymentServicesLinkersRetryPaymentValidation',
'extends' => 'linker.abstract',
'arguments' =>
array (
0 => '@handler.payment_creation',
),
),
'controller.front.payment' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'controller',
),
),
'extends' => 'controller.abstract',
'class' => 'FOPaymentControllersPayment',
'arguments' =>
array (
0 => '@paygreen.facade',
1 => '@handler.payment_creation',
2 => '@processor.payment_validation',
3 => '@manager.button',
4 => '@manager.payment_type',
),
),
'controller.front.customer_return' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'controller',
),
),
'extends' => 'controller.abstract',
'class' => 'FOPaymentControllersCustomerReturn',
'arguments' =>
array (
0 => '%payment',
1 => '@processor.payment_validation',
),
),
'behavior.tree_activation' =>
array (
'class' => 'PGTreeServicesBehaviorsTreeActivation',
'arguments' =>
array (
0 => '@settings',
1 => '@handler.requirement',
),
),
'handler.tree_authentication' =>
array (
'class' => 'PGTreeServicesHandlersTreeAuthentication',
'arguments' =>
array (
0 => '@facade.tree',
1 => '@settings',
2 => '@logger',
),
),
'handler.tree_carbon_offsetting' =>
array (
'class' => 'PGTreeServicesHandlersTreeCarbonOffsetting',
'arguments' =>
array (
0 => '@facade.tree',
1 => '@handler.tree_authentication',
2 => '@handler.fingerprint',
3 => '@handler.view',
4 => '@handler.behavior',
5 => '@settings',
6 => '@translator',
7 => '@logger',
),
),
'facade.tree' =>
array (
'class' => 'PGTreeServicesTreeFacade',
'arguments' =>
array (
0 => '@api_tree.factory',
),
),
'manager.fingerprint' =>
array (
'class' => 'PGTreeCommonServicesManagersFingerPrint',
'arguments' =>
array (
0 => '@repository.fingerprint',
),
),
'controller.front.tree.customer_navigation' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'controller',
),
),
'extends' => 'controller.abstract',
'class' => 'PGTreeCommonServicesControllersCustomerNavigation',
'arguments' =>
array (
0 => '@handler.fingerprint',
),
),
'handler.fingerprint' =>
array (
'class' => 'PGTreeCommonServicesHandlersFingerPrint',
'arguments' =>
array (
0 => '@manager.fingerprint',
1 => '@handler.cookie',
2 => '@logger',
),
),
'repository.fingerprint' =>
array (
'abstract' => false,
'arguments' =>
array (
0 => '@handler.database',
1 => '%database.entities.fingerprint',
),
'extends' => 'repository.abstract',
'class' => 'PGTreeCommonServicesRepositoriesFingerPrint',
),
'api_tree.factory' =>
array (
'class' => 'APITreeServicesApiFactory',
'arguments' =>
array (
0 => '@logger.api_tree',
1 => '@settings',
2 => '@parameters',
),
),
'logger.api_tree' =>
array (
'class' => 'PGModuleServicesLogger',
'arguments' =>
array (
0 => '@dumper',
1 => '@pathfinder',
2 => '%{log.api_tree.file}',
3 => '%{log.api_tree.format}',
),
'calls' =>
array (
0 =>
array (
'method' => 'setBehaviorHandler',
'arguments' =>
array (
0 => '@handler.behavior',
),
),
),
),
'listener.setup.tree_client_compatibility_checker' =>
array (
'class' => 'APITreeServicesListenersInstallCompatibilityCheck',
'arguments' =>
array (
0 => '@facade.tree',
),
),
'action.tree_configuration.save' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
),
'extends' => 'action.standardized_save_settings.abstract',
'class' => 'BOModuleActionsStandardizedSaveSettings',
'arguments' =>
array (
0 => '@builder.form',
1 => '@settings',
),
'config' =>
array (
'form_name' => 'tree_config',
'redirection' => 'backoffice.tree_config.display',
),
),
'action.tree_shipping_address.save' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
),
'extends' => 'action.standardized_save_settings.abstract',
'class' => 'BOModuleActionsStandardizedSaveSettings',
'arguments' =>
array (
0 => '@builder.form',
1 => '@settings',
),
'config' =>
array (
'form_name' => 'tree_shipping_address',
'redirection' => 'backoffice.tree_config.display',
),
),
'action.tree_activation.save' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
),
'extends' => 'action.standardized_save_settings.abstract',
'class' => 'BOModuleActionsStandardizedSaveSettings',
'arguments' =>
array (
0 => '@builder.form',
1 => '@settings',
),
'config' =>
array (
'form_name' => 'tree_activation',
'redirection' => 'backoffice.home.display',
),
),
'action.tree_account.display' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'BOModuleActionsStandardizedDisplayPage',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'tree_account',
),
),
'action.tree_config.display' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'BOModuleActionsStandardizedDisplayPage',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'tree_config',
),
),
'action.tree_module_config.display' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'extends' => 'action.standardized_form_settings_block.abstract',
'class' => 'BOModuleActionsStandardizedFormSettingsBlock',
'config' =>
array (
'form_name' => 'tree_config',
'form_action' => 'backoffice.tree_config.save',
),
),
'action.tree_shipping_address.display' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'extends' => 'action.standardized_form_settings_block.abstract',
'class' => 'BOModuleActionsStandardizedFormSettingsBlock',
'config' =>
array (
'form_name' => 'tree_shipping_address',
'form_action' => 'backoffice.tree_shipping_address.save',
),
),
'controller.backoffice.tree' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
6 =>
array (
'method' => 'setTreeAuthenticationHandler',
'arguments' =>
array (
0 => '@handler.tree_authentication',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'controller',
),
),
'extends' => 'controller.abstract',
'class' => 'BOTreeControllersPlugin',
),
'controller.backoffice.tree_account' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
6 =>
array (
'method' => 'setTreeAuthenticationHandler',
'arguments' =>
array (
0 => '@handler.tree_authentication',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'controller',
),
),
'extends' => 'controller.abstract',
'class' => 'BOTreeControllersAccount',
),
'requirement.tree_connexion' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'requirement',
),
),
'class' => 'BOTreeServicesRequirementsTreeConnexion',
'extends' => 'requirement.abstract',
'arguments' =>
array (
0 => '@handler.requirement',
1 => '@handler.tree_authentication',
),
),
'requirement.tree_activation' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'requirement',
),
),
'class' => 'BOTreeServicesRequirementsTreeActivation',
'extends' => 'requirement.abstract',
'arguments' =>
array (
0 => '@settings',
),
),
'listener.tree_action.display_backoffice' =>
array (
'class' => 'BOTreeServicesListenersDisplayBackoffice',
'arguments' =>
array (
0 => '@notifier',
1 => '@handler.tree_authentication',
),
),
'listener.tree_action.shipping_address' =>
array (
'class' => 'BOTreeServicesListenersShippingAddress',
'arguments' =>
array (
0 => '@notifier',
1 => '@handler.tree_authentication',
2 => '@settings',
),
),
'provisioner.pre_payment' =>
array (
'class' => 'PGMagentoProvisionersPrePaymentProvisioner',
'arguments' =>
array (
0 => '@magento',
),
),
'linker.backoffice' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'linker',
),
),
'class' => 'PGMagentoServicesLinkersBackofficeLinker',
'extends' => 'linker.abstract',
),
'linker.frontoffice' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'linker',
),
),
'extends' => 'linker.abstract.front',
'arguments' =>
array (
0 => '@magento',
1 => 'pgfront/frontoffice/index',
),
'class' => 'PGMagentoServicesLinkersFrontBasicLinker',
),
'linker.home' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'linker',
),
),
'class' => 'PGMagentoServicesLinkersHomeLinker',
'extends' => 'linker.abstract',
'arguments' =>
array (
0 => '@magento',
),
),
'linker.order' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'linker',
),
),
'extends' => 'linker.abstract.order',
'arguments' =>
array (
0 => '@magento',
1 => '@manager.order',
),
'class' => 'PGMagentoServicesLinkersOrderLinker',
),
'linker.order.history' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'linker',
),
),
'extends' => 'linker.abstract.front',
'arguments' =>
array (
0 => '@magento',
1 => 'sales/order/history',
),
'class' => 'PGMagentoServicesLinkersFrontBasicLinker',
),
'linker.checkout' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'linker',
),
),
'extends' => 'linker.abstract.front',
'arguments' =>
array (
0 => '@magento',
1 => 'checkout',
),
'class' => 'PGMagentoServicesLinkersFrontBasicLinker',
),
'linker.checkout.success' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'linker',
),
),
'extends' => 'linker.abstract.front',
'arguments' =>
array (
0 => '@magento',
1 => 'checkout/onepage/success',
),
'class' => 'PGMagentoServicesLinkersFrontBasicLinker',
),
'officer.settings.configuration.global' =>
array (
'class' => 'PGMagentoServicesOfficersConfigurationGlobalSettingsOfficer',
'arguments' =>
array (
0 => '@magento',
),
),
'officer.database' =>
array (
'class' => 'PGMagentoServicesOfficersDatabaseOfficer',
'arguments' =>
array (
0 => '@magento',
),
),
'officer.shop' =>
array (
'class' => 'PGMagentoServicesOfficersShopOfficer',
),
'handler.cart' =>
array (
'class' => 'PGMagentoServicesHandlersCartHandler',
'arguments' =>
array (
0 => '@logger',
),
),
'magento' =>
array (
),
'compiler.resource.magento' =>
array (
'class' => 'PGMagentoServicesMagentoResourceCompiler',
'arguments' =>
array (
0 => '@handler.static_file',
),
),
'listener.setup.database' =>
array (
'class' => 'PGMagentoServicesListenersSetupDatabaseListener',
'arguments' =>
array (
0 => '@handler.database',
1 => '@logger',
),
),
'upgrade.settings.restore' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'upgrade',
),
),
'class' => 'PGMagentoServicesUpgradesRestoreSettingsUpgrade',
'extends' => 'upgrade.abstract',
'arguments' =>
array (
0 => '@magento',
1 => '@repository.setting',
2 => '@handler.shop',
3 => '@officer.settings.database.basic',
4 => '@officer.settings.database.global',
),
),
'strategy.order_state_mapper.magento' =>
array (
'class' => 'PGMagentoPaymentServicesStrategiesOrderStateMagentoStrategy',
'tags' =>
array (
0 =>
array (
'name' => 'mapper.strategy.order_state',
'options' =>
array (
0 => 'magento',
),
),
),
'calls' =>
array (
0 =>
array (
'method' => 'setOrderStateManager',
'arguments' =>
array (
0 => '@manager.order_state',
),
),
),
),
'controller.front.invalid_payments' =>
array (
'abstract' => false,
'shared' => false,
'calls' =>
array (
0 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
1 =>
array (
'method' => 'setNotifier',
'arguments' =>
array (
0 => '@notifier',
),
),
2 =>
array (
'method' => 'setLinker',
'arguments' =>
array (
0 => '@linker',
),
),
3 =>
array (
'method' => 'setSettings',
'arguments' =>
array (
0 => '@settings',
),
),
4 =>
array (
'method' => 'setParameters',
'arguments' =>
array (
0 => '@parameters',
),
),
5 =>
array (
'method' => 'setFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'controller',
),
),
'extends' => 'controller.abstract',
'class' => 'PGMagentoPaymentServicesControllersInvalidPaymentsController',
'arguments' =>
array (
0 => '@magento',
1 => '@manager.order',
2 => '@handler.cart',
),
),
'listener.setup.order_states_creation' =>
array (
'class' => 'PGMagentoPaymentServicesListenersInstallOrderStateCreationListener',
'arguments' =>
array (
0 => '@manager.order_state',
1 => '@parameters',
2 => '@logger',
),
),
'listener.order_validation.invoice_creation' =>
array (
'class' => 'PGMagentoPaymentServicesListenersOrderValidationListener',
'arguments' =>
array (
0 => '@magento',
1 => '@logger',
),
),
'listener.setup.database_payment' =>
array (
'class' => 'PGMagentoPaymentServicesListenersSetupDatabaseListener',
'arguments' =>
array (
0 => '@handler.database',
1 => '@logger',
),
),
'listener.payment.display_success_message' =>
array (
'class' => 'PGMagentoPaymentServicesListenersDisplayPaymentSuccessMessage',
'arguments' =>
array (
0 => '@handler.translation',
1 => '@logger',
),
),
'upgrade.database.multishop' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'upgrade',
),
),
'class' => 'PGMagentoPaymentServicesUpgradesDatabaseMultiShopUpgrade',
'extends' => 'upgrade.abstract',
'arguments' =>
array (
0 => '@handler.database',
1 => '@handler.shop',
),
),
'handler.local_carbon_offset' =>
array (
'class' => 'PGMagentoTreeServicesHandlersLocalCarbonOffset',
'arguments' =>
array (
0 => '@handler.tree_carbon_offsetting',
1 => '@handler.session',
2 => '@logger',
),
),
'listener.tree.display_carbon_offset' =>
array (
'class' => 'PGMagentoTreeServicesListenersDisplayCarbonOffset',
'arguments' =>
array (
0 => '@handler.local_carbon_offset',
),
),
);
