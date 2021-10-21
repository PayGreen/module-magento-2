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

return array (
'kernel' =>
array (
'fixed' => true,
),
'container' =>
array (
'fixed' => true,
),
'bootstrap' =>
array (
'fixed' => true,
),
'autoloader' =>
array (
'fixed' => true,
),
'pathfinder' =>
array (
'fixed' => true,
),
'service.library' =>
array (
'fixed' => true,
),
'service.builder' =>
array (
'fixed' => true,
),
'parameters' =>
array (
'fixed' => true,
),
'parser' =>
array (
'fixed' => true,
),
'behavior.detailed_logs' =>
array (
'class' => 'PGI\\Module\\PGFramework\\Services\\Behaviors\\DetailedLogsBehavior',
'arguments' =>
array (
0 => '@settings',
),
),
'aggregator.requirement' =>
array (
'abstract' => false,
'class' => 'PGI\\Module\\PGFramework\\Components\\Aggregator',
'arguments' =>
array (
0 => '@container',
),
'extends' => 'aggregator.abstract',
'config' =>
array (
'type' => 'requirement',
'interface' => 'PGI\\Module\\PGFramework\\Interfaces\\RequirementInterface',
),
'catch' => 'requirement',
),
'aggregator.selector' =>
array (
'abstract' => false,
'class' => 'PGI\\Module\\PGFramework\\Components\\Aggregator',
'arguments' =>
array (
0 => '@container',
),
'extends' => 'aggregator.abstract',
'config' =>
array (
'type' => 'selector',
'interface' => 'PGI\\Module\\PGFramework\\Interfaces\\SelectorInterface',
),
'catch' => 'selector',
),
'aggregator.hook' =>
array (
'abstract' => false,
'class' => 'PGI\\Module\\PGFramework\\Components\\Aggregator',
'arguments' =>
array (
0 => '@container',
),
'extends' => 'aggregator.abstract',
'config' =>
array (
'type' => 'hook',
),
'catch' => 'hook',
),
'superglobal.get' =>
array (
'abstract' => false,
'class' => 'PGI\\Module\\PGFramework\\Services\\Superglobals\\GetSuperglobal',
'extends' => 'superglobal.abstract',
),
'superglobal.post' =>
array (
'abstract' => false,
'class' => 'PGI\\Module\\PGFramework\\Services\\Superglobals\\PostSuperglobal',
'extends' => 'superglobal.abstract',
),
'superglobal.cookie' =>
array (
'abstract' => false,
'class' => 'PGI\\Module\\PGFramework\\Services\\Superglobals\\CookieSuperglobal',
'extends' => 'superglobal.abstract',
),
'superglobal.session' =>
array (
'class' => 'PGI\\Module\\PGFramework\\Services\\Superglobals\\SessionSuperglobal',
'arguments' =>
array (
0 => '@logger',
),
),
'officer.setup' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Officers\\SetupOfficer',
),
'generator.csv' =>
array (
'class' => 'PGI\\Module\\PGFramework\\Services\\Generators\\CSVGenerator',
),
'handler.picture' =>
array (
'class' => 'PGI\\Module\\PGFramework\\Services\\Handlers\\PictureHandler',
'arguments' =>
array (
0 => '${PAYGREEN_MEDIA_DIR}',
1 => '%{media.baseurl}',
),
),
'handler.cache' =>
array (
'class' => 'PGI\\Module\\PGFramework\\Services\\Handlers\\CacheHandler',
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
'class' => 'PGI\\Module\\PGFramework\\Services\\Handlers\\SelectHandler',
'arguments' =>
array (
0 => '@aggregator.selector',
),
),
'handler.mime_type' =>
array (
'class' => 'PGI\\Module\\PGFramework\\Services\\Handlers\\MimeTypeHandler',
'arguments' =>
array (
0 => '@logger',
1 => '%mime_types',
),
),
'handler.session' =>
array (
'class' => 'PGI\\Module\\PGFramework\\Services\\Handlers\\SessionHandler',
'arguments' =>
array (
0 => '@superglobal.session',
),
),
'handler.upload' =>
array (
'class' => 'PGI\\Module\\PGFramework\\Services\\Handlers\\UploadHandler',
'arguments' =>
array (
0 => '@logger',
),
),
'handler.output' =>
array (
'class' => 'PGI\\Module\\PGFramework\\Services\\Handlers\\OutputHandler',
),
'handler.cookie' =>
array (
'class' => 'PGI\\Module\\PGFramework\\Services\\Handlers\\CookieHandler',
'arguments' =>
array (
0 => '@superglobal.cookie',
1 => '@logger',
),
),
'handler.requirement' =>
array (
'class' => 'PGI\\Module\\PGFramework\\Services\\Handlers\\RequirementHandler',
'arguments' =>
array (
0 => '@aggregator.requirement',
1 => '%requirements',
2 => '@logger',
),
),
'handler.hook' =>
array (
'class' => 'PGI\\Module\\PGFramework\\Services\\Handlers\\HookHandler',
'arguments' =>
array (
0 => '@aggregator.hook',
1 => '@logger',
),
),
'handler.http' =>
array (
'class' => 'PGI\\Module\\PGFramework\\Services\\Handlers\\HTTPHandler',
),
'listener.setup.static_files' =>
array (
'class' => 'PGI\\Module\\PGFramework\\Services\\Listeners\\InstallStaticFilesListener',
'arguments' =>
array (
0 => '@handler.static_file',
1 => '@logger',
),
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
'class' => 'PGI\\Module\\PGFramework\\Services\\Diagnostics\\MediaFilesChmodDiagnostic',
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
'class' => 'PGI\\Module\\PGFramework\\Services\\Diagnostics\\MediaFolderChmodDiagnostic',
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
'class' => 'PGI\\Module\\PGFramework\\Services\\Diagnostics\\VarFolderChmodDiagnostic',
'extends' => 'diagnostic.abstract',
'arguments' =>
array (
0 => '@pathfinder',
),
),
'dumper' =>
array (
'class' => 'PGI\\Module\\PGFramework\\Services\\Dumper',
),
'notifier' =>
array (
'class' => 'PGI\\Module\\PGFramework\\Services\\Notifier',
'arguments' =>
array (
0 => '@handler.session',
),
),
'upgrader' =>
array (
'class' => 'PGI\\Module\\PGModule\\Services\\Upgrader',
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
'aggregator.upgrade' =>
array (
'abstract' => false,
'class' => 'PGI\\Module\\PGFramework\\Components\\Aggregator',
'arguments' =>
array (
0 => '@container',
),
'extends' => 'aggregator.abstract',
'config' =>
array (
'type' => 'upgrade',
'interface' => 'PGI\\Module\\PGModule\\Interfaces\\UpgradeInterface',
),
'catch' => 'upgrade',
),
'aggregator.builder.output' =>
array (
'abstract' => false,
'class' => 'PGI\\Module\\PGFramework\\Components\\Aggregator',
'arguments' =>
array (
0 => '@container',
),
'extends' => 'aggregator.abstract',
'config' =>
array (
'type' => 'builder.output',
'interface' => 'PGI\\Module\\PGModule\\Interfaces\\Builders\\OutputBuilderInterface',
),
'catch' => 'builder.output',
),
'aggregator.diagnostic' =>
array (
'abstract' => false,
'class' => 'PGI\\Module\\PGFramework\\Components\\Aggregator',
'arguments' =>
array (
0 => '@container',
),
'extends' => 'aggregator.abstract',
'config' =>
array (
'type' => 'diagnostic',
),
'catch' => 'diagnostic',
),
'officer.settings.database.basic' =>
array (
'class' => 'PGI\\Module\\PGModule\\Services\\Officers\\SettingsDatabaseOfficer',
'arguments' =>
array (
0 => '@manager.setting',
1 => '@handler.shop',
),
),
'officer.settings.database.global' =>
array (
'class' => 'PGI\\Module\\PGModule\\Services\\Officers\\SettingsDatabaseOfficer',
'arguments' =>
array (
0 => '@manager.setting',
),
),
'officer.settings.storage.basic' =>
array (
'class' => 'PGI\\Module\\PGModule\\Services\\Officers\\SettingsStorageOfficer',
'arguments' =>
array (
0 => '@pathfinder',
1 => '@handler.shop',
),
),
'officer.settings.storage.global' =>
array (
'class' => 'PGI\\Module\\PGModule\\Services\\Officers\\SettingsStorageOfficer',
'arguments' =>
array (
0 => '@pathfinder',
),
),
'handler.setup' =>
array (
'class' => 'PGI\\Module\\PGModule\\Services\\Handlers\\SetupHandler',
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
'class' => 'PGI\\Module\\PGModule\\Services\\Handlers\\BehaviorHandler',
'arguments' =>
array (
0 => '@handler.requirement',
1 => '%behaviors',
),
),
'handler.diagnostic' =>
array (
'class' => 'PGI\\Module\\PGModule\\Services\\Handlers\\DiagnosticHandler',
'arguments' =>
array (
0 => '@aggregator.diagnostic',
1 => '@logger',
),
),
'handler.static_file' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Handlers\\StaticFileHandler',
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
'manager.setting' =>
array (
'class' => 'PGI\\Module\\PGModule\\Services\\Managers\\SettingManager',
'arguments' =>
array (
0 => '@repository.setting',
),
),
'listener.settings.install_default' =>
array (
'class' => 'PGI\\Module\\PGModule\\Services\\Listeners\\InstallDefaultSettingsListener',
'arguments' =>
array (
0 => '@settings',
1 => '@logger',
),
),
'listener.settings.uninstall' =>
array (
'class' => 'PGI\\Module\\PGModule\\Services\\Listeners\\UninstallSettingsListener',
'arguments' =>
array (
0 => '@settings',
1 => '@logger',
),
),
'listener.upgrade' =>
array (
'class' => 'PGI\\Module\\PGModule\\Services\\Listeners\\UpgradeListener',
'arguments' =>
array (
0 => '@upgrader',
1 => '@logger',
),
),
'listener.setup.create_setting_table' =>
array (
'abstract' => false,
'class' => 'PGI\\Module\\PGDatabase\\Services\\Listeners\\GenericDatabaseRunnerListener',
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
'class' => 'PGI\\Module\\PGModule\\Services\\Upgrades\\UpdateSettingsValuesUpgrade',
'extends' => 'upgrade.abstract',
'arguments' =>
array (
0 => '@settings',
),
),
'upgrade.rename_settings' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'upgrade',
),
),
'class' => 'PGI\\Module\\PGModule\\Services\\Upgrades\\RenameSettingsUpgrade',
'extends' => 'upgrade.abstract',
'arguments' =>
array (
0 => '@manager.setting',
1 => '@manager.shop',
),
),
'upgrade.retrieve_setting_global_value' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'upgrade',
),
),
'class' => 'PGI\\Module\\PGModule\\Services\\Upgrades\\RetrieveSettingGlobalValueUpgrade',
'extends' => 'upgrade.abstract',
'arguments' =>
array (
0 => '@manager.setting',
1 => '@manager.shop',
2 => '@logger',
),
),
'logger' =>
array (
'class' => 'PGI\\Module\\PGModule\\Services\\Logger',
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
'class' => 'PGI\\Module\\PGModule\\Services\\Settings',
'arguments' =>
array (
0 => '@container',
1 => '@parameters',
2 => '%settings',
),
),
'broadcaster' =>
array (
'class' => 'PGI\\Module\\PGModule\\Services\\Broadcaster',
'arguments' =>
array (
0 => '@container',
1 => '@handler.requirement',
2 => '@logger',
3 => '%listeners',
),
'catch' =>
array (
'tag' => 'listener',
'method' => 'addListener',
'built' => false,
),
),
'provider.output' =>
array (
'class' => 'PGI\\Module\\PGModule\\Services\\Providers\\OutputProvider',
'arguments' =>
array (
0 => '@aggregator.builder.output',
1 => '@handler.requirement',
2 => '%outputs',
3 => '@logger',
),
),
'facade.application' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Facades\\ApplicationFacade',
),
'facade.module' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Facades\\ModuleFacade',
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
'class' => 'PGI\\Module\\PGModule\\Services\\Repositories\\SettingRepository',
),
'handler.database' =>
array (
'class' => 'PGI\\Module\\PGDatabase\\Services\\Handlers\\DatabaseHandler',
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
'class' => 'PGI\\Module\\PGDatabase\\Services\\Upgrades\\DatabaseUpgrade',
'extends' => 'upgrade.abstract',
'arguments' =>
array (
0 => '@handler.database',
),
),
'aggregator.deflector' =>
array (
'abstract' => false,
'class' => 'PGI\\Module\\PGFramework\\Components\\Aggregator',
'arguments' =>
array (
0 => '@container',
),
'extends' => 'aggregator.abstract',
'config' =>
array (
'type' => 'deflector',
'interface' => 'PGI\\Module\\PGServer\\Interfaces\\DeflectorInterface',
),
'catch' => 'deflector',
),
'aggregator.linker' =>
array (
'abstract' => false,
'class' => 'PGI\\Module\\PGFramework\\Components\\Aggregator',
'arguments' =>
array (
0 => '@container',
),
'extends' => 'aggregator.abstract',
'config' =>
array (
'type' => 'linker',
'interface' => 'PGI\\Module\\PGServer\\Interfaces\\LinkerInterface',
),
'catch' => 'linker',
),
'aggregator.acceptor' =>
array (
'abstract' => false,
'class' => 'PGI\\Module\\PGFramework\\Components\\Aggregator',
'arguments' =>
array (
0 => '@container',
),
'extends' => 'aggregator.abstract',
'config' =>
array (
'type' => 'acceptor',
),
'catch' => 'acceptor',
),
'aggregator.controller' =>
array (
'abstract' => false,
'class' => 'PGI\\Module\\PGFramework\\Components\\Aggregator',
'arguments' =>
array (
0 => '@container',
),
'extends' => 'aggregator.abstract',
'config' =>
array (
'type' => 'controller',
),
'catch' => 'controller',
),
'aggregator.action' =>
array (
'abstract' => false,
'class' => 'PGI\\Module\\PGFramework\\Components\\Aggregator',
'arguments' =>
array (
0 => '@container',
),
'extends' => 'aggregator.abstract',
'config' =>
array (
'type' => 'action',
'interface' => 'PGI\\Module\\PGServer\\Interfaces\\ActionInterface',
),
'catch' => 'action',
),
'renderer.transformer.paygreen_module_2_array' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Renderers\\Transformers\\PaygreenModuleToArrayTransformerRenderer',
'arguments' =>
array (
0 => '@notifier',
),
),
'renderer.transformer.file_2_http' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Renderers\\Transformers\\FileToHttpTransformerRenderer',
'arguments' =>
array (
0 => '@handler.mime_type',
),
),
'renderer.transformer.array_2_http' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Renderers\\Transformers\\ArrayToHttpTransformerRenderer',
),
'renderer.transformer.string_2_http' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Renderers\\Transformers\\StringToHttpTransformerRenderer',
),
'renderer.transformer.redirection_2_http' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Renderers\\Transformers\\RedirectionToHttpTransformerRenderer',
),
'renderer.processor.write_http' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Renderers\\Processors\\WriteHTTPRendererProcessor',
'arguments' =>
array (
0 => '1.1',
1 => '%http_codes',
),
),
'renderer.processor.output_template' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Renderers\\Processors\\OutputTemplateRendererProcessor',
'arguments' =>
array (
0 => '@handler.view',
1 => '@handler.output',
),
),
'handler.route' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Handlers\\RouteHandler',
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
'class' => 'PGI\\Module\\PGServer\\Services\\Handlers\\AreaHandler',
'arguments' =>
array (
0 => '%routing.areas',
),
),
'handler.link' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Handlers\\LinkHandler',
'arguments' =>
array (
0 => '@aggregator.linker',
1 => '@logger',
2 => '@facade.module',
),
),
'acceptor.class' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Acceptors\\ModelAcceptor',
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
'class' => 'PGI\\Module\\PGServer\\Services\\Acceptors\\InstanceAcceptor',
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
'class' => 'PGI\\Module\\PGServer\\Services\\Acceptors\\TagAcceptor',
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
'class' => 'PGI\\Module\\PGServer\\Services\\Acceptors\\ActionAcceptor',
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
'factory.trigger' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Factories\\TriggerFactory',
'arguments' =>
array (
0 => '@aggregator.acceptor',
1 => '@logger',
),
),
'factory.stage' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Factories\\StageFactory',
'arguments' =>
array (
0 => '@factory.trigger',
1 => '@logger',
),
),
'cleaner.basic_http.not_found' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Cleaners\\BasicHTTPCleaner',
'arguments' =>
array (
0 => 404,
),
),
'cleaner.basic_http.unauthorized_access' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Cleaners\\BasicHTTPCleaner',
'arguments' =>
array (
0 => 401,
),
),
'cleaner.basic_http.server_error' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Cleaners\\BasicHTTPCleaner',
'arguments' =>
array (
0 => 500,
),
),
'cleaner.basic_http.bad_request' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Cleaners\\BasicHTTPCleaner',
'arguments' =>
array (
0 => 400,
),
),
'cleaner.basic_throw' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Cleaners\\BasicThrowCleaner',
),
'dispatcher' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Dispatcher',
'arguments' =>
array (
0 => '@logger',
1 => '@broadcaster',
2 => '@aggregator.controller',
3 => '@aggregator.action',
),
),
'builder.request.default' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Builders\\RequestBuilder',
'arguments' =>
array (
0 => '@superglobal.get',
1 => '@superglobal.post',
2 => '%request_builder.default',
),
),
'router' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Router',
'arguments' =>
array (
0 => '@handler.area',
1 => '@handler.route',
),
),
'derouter' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Derouter',
'arguments' =>
array (
0 => '@aggregator.deflector',
1 => '@logger',
),
),
'officer.locale' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Officers\\LocaleOfficer',
'arguments' =>
array (
0 => '@magento',
),
),
'handler.translation' =>
array (
'class' => 'PGI\\Module\\PGIntl\\Services\\Handlers\\TranslationHandler',
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
'class' => 'PGI\\Module\\PGIntl\\Services\\Handlers\\LocaleHandler',
'arguments' =>
array (
0 => '@officer.locale',
),
),
'handler.cache.translation' =>
array (
'class' => 'PGI\\Module\\PGIntl\\Services\\Handlers\\CacheTranslationHandler',
'arguments' =>
array (
0 => '@pathfinder',
1 => '@settings',
2 => '@logger',
),
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
'class' => 'PGI\\Module\\PGIntl\\Services\\Selectors\\LanguageSelector',
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
'class' => 'PGI\\Module\\PGIntl\\Services\\Selectors\\CountrySelector',
),
'manager.translation' =>
array (
'class' => 'PGI\\Module\\PGIntl\\Services\\Managers\\TranslationManager',
'arguments' =>
array (
0 => '@repository.translation',
),
),
'listener.setup.install_default_translations' =>
array (
'class' => 'PGI\\Module\\PGIntl\\Services\\Listeners\\InsertDefaultTranslationsListener',
'arguments' =>
array (
0 => '@handler.translation',
1 => '@manager.shop',
),
),
'listener.setup.create_translation_table' =>
array (
'abstract' => false,
'class' => 'PGI\\Module\\PGDatabase\\Services\\Listeners\\GenericDatabaseRunnerListener',
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
'class' => 'PGI\\Module\\PGIntl\\Services\\Listeners\\ResetTranslationCacheListener',
'arguments' =>
array (
0 => '@handler.cache.translation',
1 => '@logger',
),
),
'plugin.smarty.translator' =>
array (
'class' => 'PGI\\Module\\PGIntl\\Services\\Plugins\\SmartyTranslatorPlugin',
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
'class' => 'PGI\\Module\\PGIntl\\Services\\Upgrades\\InsertDefaultTranslationsHandler',
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
'class' => 'PGI\\Module\\PGIntl\\Services\\Upgrades\\RestoreTranslationsHandler',
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
'class' => 'PGI\\Module\\PGIntl\\Services\\Upgrades\\RestoreButtonLabelsHandler',
'extends' => 'upgrade.abstract',
'arguments' =>
array (
0 => '@manager.translation',
1 => '@manager.shop',
2 => '@handler.database',
),
),
'translator' =>
array (
'class' => 'PGI\\Module\\PGIntl\\Services\\Translator',
'arguments' =>
array (
0 => '@handler.cache.translation',
1 => '@pathfinder',
2 => '@handler.locale',
3 => '@logger',
4 => '%intl',
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
'class' => 'PGI\\Module\\PGIntl\\Services\\Repositories\\TranslationRepository',
),
'aggregator.view' =>
array (
'abstract' => false,
'class' => 'PGI\\Module\\PGFramework\\Components\\Aggregator',
'arguments' =>
array (
0 => '@container',
),
'extends' => 'aggregator.abstract',
'config' =>
array (
'type' => 'view',
'interface' => 'PGI\\Module\\PGView\\Interfaces\\ViewInterface',
),
'catch' => 'view',
),
'listener.upgrade.clear_smarty_cache' =>
array (
'class' => 'PGI\\Module\\PGView\\Services\\Listeners\\ClearSmartyCacheListener',
'arguments' =>
array (
0 => '@handler.smarty',
1 => '@logger',
),
),
'plugin.smarty.view_injecter' =>
array (
'class' => 'PGI\\Module\\PGView\\Services\\Plugins\\SmartyViewInjecterPlugin',
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
'class' => 'PGI\\Module\\PGView\\Services\\Plugins\\SmartyLinkerPlugin',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\PGView\\Services\\Plugins\\SmartyPicturePlugin',
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
'class' => 'PGI\\Module\\PGView\\Services\\Plugins\\SmartyClipPlugin',
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
'class' => 'PGI\\Module\\PGView\\Services\\Handlers\\ViewHandler',
'arguments' =>
array (
0 => '@aggregator.view',
1 => '@handler.smarty',
2 => '@pathfinder',
),
),
'view.basic' =>
array (
'class' => 'PGI\\Module\\PGView\\Services\\View',
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
'class' => 'PGI\\Module\\PGView\\Services\\Handlers\\SmartyHandler',
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
'class' => 'PGI\\Module\\PGView\\Services\\Handlers\\BlockHandler',
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
'class' => 'PGI\\Module\\PGView\\Services\\Builders\\SmartyBuilder',
'arguments' =>
array (
0 => '@pathfinder',
1 => '%smarty.builder',
),
),
'logger.view' =>
array (
'class' => 'PGI\\Module\\PGModule\\Services\\Logger',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Formatters\\StringFormatter',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Formatters\\IntegerFormatter',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Formatters\\FloatFormatter',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Formatters\\ArrayFormatter',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Formatters\\ObjectFormatter',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Formatters\\BooleanFormatter',
),
'aggregator.formatter' =>
array (
'abstract' => false,
'class' => 'PGI\\Module\\PGFramework\\Components\\Aggregator',
'arguments' =>
array (
0 => '@container',
),
'extends' => 'aggregator.abstract',
'config' =>
array (
'type' => 'formatter',
'interface' => 'PGI\\Module\\PGForm\\Interfaces\\FormatterInterface',
),
'catch' => 'formatter',
),
'aggregator.validator' =>
array (
'abstract' => false,
'class' => 'PGI\\Module\\PGFramework\\Components\\Aggregator',
'arguments' =>
array (
0 => '@container',
),
'extends' => 'aggregator.abstract',
'config' =>
array (
'type' => 'validator',
'interface' => 'PGI\\Module\\PGForm\\Interfaces\\ValidatorInterface',
),
'catch' => 'validator',
),
'builder.form' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Builders\\FormBuilder',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Builders\\FieldBuilder',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Builders\\ValidatorBuilder',
'arguments' =>
array (
0 => '@aggregator.validator',
),
),
'view.form' =>
array (
'class' => 'PGI\\Module\\PGForm\\Services\\Views\\FormView',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Views\\Fields\\BasicFieldView',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Views\\Fields\\BoolCheckboxFieldView',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Views\\Fields\\ChoiceExpandedFieldView',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Views\\Fields\\ChoiceContractedFieldView',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Views\\Fields\\PictureFieldView',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Views\\Fields\\CompositeFieldView',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Views\\Fields\\CollectionFieldView',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Views\\Fields\\DoubleChoiceBooleanFieldView',
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
'view.field.colorpicker' =>
array (
'class' => 'PGFormServicesViewsFieldColorPickerView',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Validators\\LengthMinValidator',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Validators\\LengthMaxValidator',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Validators\\RegexpValidator',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Validators\\ArrayInValidator',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Validators\\NotEmptyValidator',
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
'class' => 'PGI\\Module\\PGForm\\Services\\Validators\\RangeValidator',
),
'officer.post_payment' =>
array (
'class' => 'PGI\\Module\\PGMagentoPayment\\Services\\Officers\\PostPaymentOfficer',
),
'officer.cart' =>
array (
'abstract' => false,
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
'extends' => 'service.abstract',
'class' => 'PGI\\Module\\PGMagento\\Services\\Officers\\CartOfficer',
'arguments' =>
array (
0 => '@local.repository.quote',
),
),
'handler.shop' =>
array (
'class' => 'PGI\\Module\\PGShop\\Services\\Handlers\\ShopHandler',
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
'class' => 'PGI\\Module\\PGShop\\Services\\Selectors\\HierarchizedCategorySelector',
),
'manager.shop' =>
array (
'class' => 'PGI\\Module\\PGShop\\Services\\Managers\\ShopManager',
'arguments' =>
array (
0 => '@repository.shop',
),
),
'manager.cart' =>
array (
'class' => 'PGI\\Module\\PGShop\\Services\\Managers\\CartManager',
'arguments' =>
array (
0 => '@repository.cart',
),
'calls' =>
array (
0 =>
array (
'method' => 'setCartOfficer',
'arguments' =>
array (
0 => '@officer.cart',
),
),
),
),
'manager.order' =>
array (
'class' => 'PGI\\Module\\PGShop\\Services\\Managers\\OrderManager',
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
1 =>
array (
'method' => 'setBroadcaster',
'arguments' =>
array (
0 => '@broadcaster',
),
),
2 =>
array (
'method' => 'setLogger',
'arguments' =>
array (
0 => '@logger',
),
),
),
),
'manager.customer' =>
array (
'class' => 'PGI\\Module\\PGShop\\Services\\Managers\\CustomerManager',
'arguments' =>
array (
0 => '@repository.customer',
),
),
'manager.address' =>
array (
'class' => 'PGI\\Module\\PGShop\\Services\\Managers\\AddressManager',
'arguments' =>
array (
0 => '@repository.address',
),
),
'manager.product' =>
array (
'class' => 'PGI\\Module\\PGShop\\Services\\Managers\\ProductManager',
'arguments' =>
array (
0 => '@repository.product',
),
),
'manager.category' =>
array (
'class' => 'PGI\\Module\\PGShop\\Services\\Managers\\CategoryManager',
'arguments' =>
array (
0 => '@repository.category',
),
'calls' =>
array (
0 =>
array (
'method' => 'setShopHandler',
'arguments' =>
array (
0 => '@handler.shop',
),
),
),
),
'manager.order_state' =>
array (
'class' => 'PGI\\Module\\PGShop\\Services\\Managers\\OrderStateManager',
'arguments' =>
array (
0 => '@repository.order_state',
1 => '@factory.order_state_machine',
2 => '@mapper.order_state',
),
),
'factory.order_state_machine' =>
array (
'class' => 'PGI\\Module\\PGShop\\Services\\Factories\\OrderStateMachineFactory',
'arguments' =>
array (
0 => '%order.machines',
),
),
'mapper.order_state' =>
array (
'class' => 'PGI\\Module\\PGShop\\Services\\Mappers\\OrderStateMapper',
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
'class' => 'PGI\\Module\\PGShop\\Services\\OrderStateMappingStrategies\\SettingsOrderStateMappingStrategy',
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
'class' => 'PGI\\Module\\PGMagento\\Services\\Repositories\\ShopRepository',
),
'repository.cart' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Repositories\\CartRepository',
),
'repository.order' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Repositories\\OrderRepository',
),
'repository.customer' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Repositories\\CustomerRepository',
),
'repository.address' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Repositories\\AddressRepository',
),
'repository.product' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Repositories\\ProductRepository',
),
'repository.cart_item' =>
array (
),
'repository.category' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Repositories\\CategoryRepository',
),
'repository.order_state' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Repositories\\OrderStateRepository',
'arguments' =>
array (
0 => '%order.states',
),
),
'handler.menu' =>
array (
'class' => 'PGI\\Module\\BOModule\\Services\\Handlers\\MenuHandler',
'arguments' =>
array (
0 => '@handler.route',
1 => '@handler.link',
2 => '%menu',
),
),
'handler.server' =>
array (
'class' => 'PGI\\Module\\BOModule\\Services\\Handlers\\ServerHandler',
'arguments' =>
array (
0 => '@settings',
),
'calls' =>
array (
0 =>
array (
'method' => 'addServer',
'arguments' =>
array (
0 => 'blocks.tree.tree_products.title',
1 => 'tree_api_server',
),
),
1 =>
array (
'method' => 'addServer',
'arguments' =>
array (
0 => 'blocks.charity.charity_products.title',
1 => 'charity_api_server',
),
),
),
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_save_settings.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedSaveSettingsAction',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedDisplayPageAction',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedDisplayPageAction',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'support',
'static' =>
array (
'js' =>
array (
0 => '/js/page-support.js',
),
),
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedDisplayPageAction',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedDisplayPageAction',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'system',
),
),
'action.products.display' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedDisplayPageAction',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'products',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\BOModule\\Services\\Controllers\\ShopController',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\BOModule\\Services\\Controllers\\ErrorController',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\BOModule\\Services\\Controllers\\DiagnosticController',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\BOModule\\Services\\Controllers\\LogsController',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\BOModule\\Services\\Controllers\\SystemController',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\BOModule\\Services\\Controllers\\ReleaseNoteController',
'arguments' =>
array (
0 => '@pathfinder',
1 => '@logger',
),
),
'controller.backoffice.cache' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\BOModule\\Services\\Controllers\\CacheController',
'arguments' =>
array (
0 => '@handler.cache',
),
),
'listener.action.shop_context_backoffice' =>
array (
'class' => 'PGI\\Module\\BOModule\\Services\\Listeners\\ShopContextBackofficeListener',
'arguments' =>
array (
0 => '@notifier',
1 => '@handler.shop',
),
),
'listener.action.display_support_page' =>
array (
'class' => 'PGI\\Module\\BOModule\\Services\\Listeners\\DisplaySupportPageBackofficeListener',
'arguments' =>
array (
0 => '@notifier',
1 => '@handler.shop',
),
),
'view.menu' =>
array (
'class' => 'PGI\\Module\\BOModule\\Services\\Views\\MenuView',
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
3 => '@parameters',
),
),
'view.notifications' =>
array (
'class' => 'PGI\\Module\\BOModule\\Services\\Views\\NotificationsView',
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
'view.system.paths' =>
array (
'class' => 'PGI\\Module\\BOModule\\Services\\Views\\SystemPathsView',
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
'class' => 'PGI\\Module\\BOModule\\Services\\Views\\Blocks\\DiagnosticBlockView',
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
'class' => 'PGI\\Module\\BOModule\\Services\\Views\\Blocks\\LogBlockView',
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
'view.block.server' =>
array (
'class' => 'PGI\\Module\\BOModule\\Services\\Views\\Blocks\\ServerBlockView',
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
0 => '@handler.server',
),
),
'view.block.standardized.config_form' =>
array (
'class' => 'PGI\\Module\\BOModule\\Services\\Views\\Blocks\\StandardizedConfigurationFormBlockView',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'builder.output.back_office_paygreen' =>
array (
'abstract' => false,
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
'name' => 'builder.output',
),
),
'extends' => 'builder.output.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\OutputBuilders\\BackOfficeOutputBuilder',
'arguments' =>
array (
0 => '@server.backoffice',
1 => '@handler.output',
2 => '@handler.menu',
),
),
'plugin.smarty.bool' =>
array (
'class' => 'PGI\\Module\\BOModule\\Services\\Plugins\\SmartyBoolPlugin',
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
'builder.request.backoffice' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Builders\\RequestBuilder',
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
'class' => 'PGI\\Module\\PGServer\\Services\\Server',
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
'class' => 'PGI\\Module\\PGServer\\Services\\Cleaners\\ForwardCleaner',
'arguments' =>
array (
0 => 'displayException@backoffice.error',
),
),
'builder.translation_form' =>
array (
'class' => 'PGI\\Module\\PGIntl\\Services\\Builders\\TranslationFormBuilder',
'arguments' =>
array (
0 => '@builder.form',
1 => '@builder.field',
2 => '%translations',
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
'class' => 'PGI\\Module\\BOModule\\Services\\Requirements\\ShopContextRequirement',
'extends' => 'requirement.abstract',
'arguments' =>
array (
0 => '@handler.shop',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\BOModule\\Services\\Deflectors\\ShopContext',
'arguments' =>
array (
0 => '@handler.route',
),
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\FOModule\\Services\\Controllers\\NotificationController',
),
'builder.output.front_office_paygreen' =>
array (
'abstract' => false,
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
'name' => 'builder.output',
),
),
'extends' => 'builder.output.abstract',
'class' => 'PGI\\Module\\FOModule\\Services\\OutputBuilders\\FrontOfficeOutputBuilder',
'arguments' =>
array (
0 => '@server.front',
1 => '@handler.output',
),
),
'builder.output.global_front_office_paygreen' =>
array (
'abstract' => false,
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
'name' => 'builder.output',
),
),
'extends' => 'builder.output.abstract.static_files',
'class' => 'PGI\\Module\\PGModule\\Services\\OutputBuilders\\StaticFilesOutputBuilder',
'config' =>
array (
'css' =>
array (
0 => '/css/global-frontoffice.css',
),
),
),
'builder.request.frontoffice' =>
array (
'class' => 'PGI\\Module\\FOPayment\\Services\\Builders\\IncomingRequestBuilder',
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
'class' => 'PGI\\Module\\PGServer\\Services\\Server',
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
'class' => 'PGI\\Module\\PGPayment\\Services\\Processors\\PaymentValidationProcessor',
'extends' => 'processor.abstract',
'arguments' =>
array (
0 => '@handler.processing',
),
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
'class' => 'PGI\\Module\\PGPayment\\Services\\Processors\\ManageCashTransactionProcessor',
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
'class' => 'PGI\\Module\\PGPayment\\Services\\Processors\\ManageTokenizeTransactionProcessor',
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
'class' => 'PGI\\Module\\PGPayment\\Services\\Processors\\ManageRecurringTransactionProcessor',
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
'class' => 'PGI\\Module\\PGPayment\\Services\\Processors\\ManageXTimeTransactionProcessor',
),
'handler.payment_creation' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Services\\Handlers\\PaymentCreationHandler',
'arguments' =>
array (
0 => '%payment',
),
),
'handler.payment_testing' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Services\\Handlers\\TestingPaymentHandler',
'arguments' =>
array (
0 => '@logger',
1 => '@logger.api',
2 => '@pathfinder',
),
),
'handler.payment_button' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Services\\Handlers\\PaymentButtonHandler',
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
'class' => 'PGI\\Module\\PGPayment\\Services\\Handlers\\RefundHandler',
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
'class' => 'PGI\\Module\\PGPayment\\Services\\Handlers\\CheckoutHandler',
'arguments' =>
array (
0 => '@handler.requirement',
1 => '@logger',
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
'class' => 'PGI\\Module\\PGPayment\\Services\\Handlers\\TokenizeHandler',
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
'handler.processing' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Services\\Handlers\\ProcessingHandler',
'arguments' =>
array (
0 => '@manager.processing',
1 => '@handler.shop',
2 => '@logger',
),
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
'class' => 'PGI\\Module\\PGPayment\\Services\\Selectors\\PaymentModeSelector',
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
'class' => 'PGI\\Module\\PGPayment\\Services\\Selectors\\PaymentTypeSelector',
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
'class' => 'PGI\\Module\\PGFramework\\Services\\Selectors\\StaticArraySelector',
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
'class' => 'PGI\\Module\\PGFramework\\Services\\Selectors\\StaticArraySelector',
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
'class' => 'PGI\\Module\\PGFramework\\Services\\Selectors\\StaticArraySelector',
),
'manager.button' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Services\\Managers\\ButtonManager',
'arguments' =>
array (
0 => '@repository.button',
),
),
'manager.payment_type' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Services\\Managers\\PaymentTypeManager',
'arguments' =>
array (
0 => '@repository.payment_type',
),
),
'manager.lock' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Services\\Managers\\LockManager',
'arguments' =>
array (
0 => '@repository.lock',
),
),
'manager.category_has_payment_type' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Services\\Managers\\CategoryHasPaymentTypeManager',
'arguments' =>
array (
0 => '@repository.category_has_payment_type',
),
),
'manager.transaction' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Services\\Managers\\TransactionManager',
'arguments' =>
array (
0 => '@repository.transaction',
),
),
'manager.recurring_transaction' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Services\\Managers\\RecurringTransactionManager',
'arguments' =>
array (
0 => '@repository.recurring_transaction',
),
),
'manager.processing' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Services\\Managers\\ProcessingManager',
'arguments' =>
array (
0 => '@repository.processing',
),
),
'listener.setup.install_default_button' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Services\\Listeners\\InstallDefaultButtonListener',
'arguments' =>
array (
0 => '@manager.button',
1 => '@manager.translation',
2 => '@logger',
),
),
'listener.refund.update_transaction' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Services\\Listeners\\RefundListener',
'arguments' =>
array (
0 => '@handler.refund',
1 => '@handler.behavior',
2 => '@logger',
),
),
'listener.setup.processing_database' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Services\\Listeners\\SetupProcessingDatabaseListener',
'arguments' =>
array (
0 => '@handler.database',
1 => '@logger',
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
'class' => 'PGI\\Module\\PGPayment\\Services\\ChainLinks\\AddFrontofficeEntrypointChainLink',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\PGPayment\\Services\\ChainLinks\\AddFrontofficeEntrypointChainLink',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\PGPayment\\Services\\ChainLinks\\AddCommonDataChainLink',
'arguments' =>
array (
0 => '%payment.metadata',
),
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
'class' => 'PGI\\Module\\PGPayment\\Services\\ChainLinks\\AddCustomerDataChainLink',
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
'class' => 'PGI\\Module\\PGPayment\\Services\\ChainLinks\\AddCustomerAddressesDataChainLink',
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
'class' => 'PGI\\Module\\PGPayment\\Services\\ChainLinks\\AddEligibleAmountDataChainLink',
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
'class' => 'PGI\\Module\\PGPayment\\Services\\ChainLinks\\AddXTimeDataChainLink',
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
'class' => 'PGI\\Module\\PGPayment\\Services\\ChainLinks\\AddRecurringDataChainLink',
),
'plugin.smarty.designator' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Services\\Plugins\\SmartyDesignatorPlugin',
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
'class' => 'PGI\\Module\\PGFramework\\Services\\Upgrades\\MediaDeleteUpgrade',
'extends' => 'upgrade.abstract',
'arguments' =>
array (
0 => '@handler.picture',
),
),
'upgrade.insite_payment' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'upgrade',
),
),
'class' => 'PGI\\Module\\PGPayment\\Services\\Upgrades\\InsitePaymentUpgrade',
'extends' => 'upgrade.abstract',
'arguments' =>
array (
0 => '@handler.database',
1 => '@manager.shop',
2 => '@manager.setting',
),
),
'paygreen.facade' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Services\\Facades\\PaygreenFacade',
'arguments' =>
array (
0 => '@api.factory',
1 => '@handler.http',
2 => '@manager.payment_type',
),
),
'responsability_chain.payment_creation' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Services\\ResponsabilityChains\\PaymentCreationResponsabilityChain',
'catch' =>
array (
'tag' => 'payment_creation_chain_link',
'method' => 'addChainLink',
'built' => true,
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
'class' => 'PGI\\Module\\PGPayment\\Services\\Requirements\\PaygreenConnexionRequirement',
'extends' => 'requirement.abstract',
'arguments' =>
array (
0 => '@paygreen.facade',
),
),
'requirement.payment_kit_activation' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'requirement',
),
),
'class' => 'PGI\\Module\\PGPayment\\Services\\Requirements\\PaymentKitActivationRequirement',
'extends' => 'requirement.abstract',
'arguments' =>
array (
0 => '@settings',
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
'class' => 'PGI\\Module\\PGPayment\\Services\\Requirements\\PaymentActivationRequirement',
'extends' => 'requirement.abstract',
'arguments' =>
array (
0 => '@settings',
),
),
'requirement.footer_displayed' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'requirement',
),
),
'class' => 'PGI\\Module\\PGPayment\\Services\\Requirements\\FooterDisplayedRequirement',
'extends' => 'requirement.abstract',
'arguments' =>
array (
0 => '@settings',
1 => '@facade.module',
2 => '@paygreen.facade',
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
'class' => 'PGI\\Module\\PGPayment\\Services\\Repositories\\ButtonRepository',
),
'repository.payment_type' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Services\\Repositories\\PaymentTypeRepository',
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
'class' => 'PGI\\Module\\PGPayment\\Services\\Repositories\\LockRepository',
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
'class' => 'PGI\\Module\\PGPayment\\Services\\Repositories\\CategoryHasPaymentTypeRepository',
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
'class' => 'PGI\\Module\\PGPayment\\Services\\Repositories\\TransactionRepository',
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
'class' => 'PGI\\Module\\PGPayment\\Services\\Repositories\\RecurringTransactionRepository',
),
'repository.processing' =>
array (
'abstract' => false,
'arguments' =>
array (
0 => '@handler.database',
1 => '%database.entities.processing',
),
'extends' => 'repository.abstract',
'class' => 'PGI\\Module\\PGPayment\\Services\\Repositories\\ProcessingRepository',
),
'handler.oauth' =>
array (
'class' => 'PGI\\Module\\APIPayment\\Services\\Handlers\\OAuthHandler',
'arguments' =>
array (
0 => '@paygreen.facade',
1 => '@settings',
2 => '@pathfinder',
3 => '@handler.shop',
4 => '@handler.link',
),
),
'listener.setup.payment_client_compatibility_checker' =>
array (
'class' => 'PGI\\Module\\APIPayment\\Services\\Listeners\\InstallCompatibilityCheckListener',
'arguments' =>
array (
0 => '@paygreen.facade',
),
),
'api.factory' =>
array (
'class' => 'PGI\\Module\\APIPayment\\Services\\Factories\\ApiFacadeFactory',
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
'class' => 'PGI\\Module\\PGModule\\Services\\Logger',
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
'handler.payment_statistics' =>
array (
'class' => 'PGI\\Module\\BOPayment\\Services\\Handlers\\PaymentStatisticsHandler',
'arguments' =>
array (
0 => '@manager.transaction',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_save_settings.abstract',
'class' => 'PGI\\Module\\BOPayment\\Services\\Actions\\SaveAccountConfigurationAction',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_save_settings.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedSaveSettingsAction',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_save_settings.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedSaveSettingsAction',
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
'action.payment_kit_activation.save' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_save_settings.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedSaveSettingsAction',
'arguments' =>
array (
0 => '@builder.form',
1 => '@settings',
),
'config' =>
array (
'form_name' => 'payment_kit_activation',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedDisplayPageAction',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedDisplayPageAction',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'name' => 'action',
),
),
'extends' => 'action.standardized_form_settings_block.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedFormSettingsBlockAction',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'name' => 'action',
),
),
'extends' => 'action.standardized_form_settings_block.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedFormSettingsBlockAction',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'name' => 'action',
),
),
'extends' => 'action.standardized_form_settings_block.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedFormSettingsBlockAction',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'name' => 'action',
),
),
'extends' => 'action.standardized_form_settings_block.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedFormSettingsBlockAction',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedDisplayPageAction',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedDisplayPageAction',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'eligible_amounts',
'selected_page' => 'eligible_amounts',
'static' =>
array (
'js' =>
array (
0 => '/js/page-eligible-amounts.js',
),
),
),
),
'action.payment_statistics.display' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOPayment\\Services\\Actions\\DisplayPaymentStatisticsPageAction',
'arguments' =>
array (
0 => '@handler.block',
1 => '@handler.payment_statistics',
),
'config' =>
array (
'page_name' => 'statistics',
'static' =>
array (
'js' =>
array (
0 => '/js/page-statistics.js',
),
),
),
),
'action.payment_translations.display' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedDisplayPageAction',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'payment_translations',
),
),
'action.payment_translations_form.display' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_translations_form_block.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedFormTranslationsBlockAction',
'arguments' =>
array (
0 => '@builder.translation_form',
1 => '@handler.translation',
),
'config' =>
array (
'translation_tag' => 'payment',
'form_action' => 'backoffice.payment_translations.save',
),
),
'action.payment_translations_form.save' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_save_translations_form.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedSaveTranslationsFormAction',
'arguments' =>
array (
0 => '@builder.translation_form',
1 => '@handler.translation',
2 => '@manager.translation',
),
'config' =>
array (
'translation_tag' => 'payment',
'redirect_to' => 'payment_translations',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\BOPayment\\Services\\Controllers\\AccountController',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\BOPayment\\Services\\Controllers\\OAuthController',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\BOPayment\\Services\\Controllers\\EligibleAmountsController',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\BOPayment\\Services\\Controllers\\ButtonsController',
'arguments' =>
array (
0 => '@manager.button',
1 => '@handler.payment_button',
2 => '@handler.picture',
3 => '@manager.translation',
4 => '@handler.upload',
5 => '@handler.static_file',
6 => '@handler.link',
7 => '@manager.payment_type',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\BOPayment\\Services\\Controllers\\PluginController',
'arguments' =>
array (
0 => '@paygreen.facade',
1 => '@handler.cache',
2 => '@manager.transaction',
),
),
'listener.action.display_backoffice' =>
array (
'class' => 'PGI\\Module\\BOPayment\\Services\\Listeners\\DisplayBackofficeListener',
'arguments' =>
array (
0 => '@notifier',
1 => '@paygreen.facade',
),
),
'view.button.line' =>
array (
'class' => 'PGI\\Module\\BOPayment\\Services\\Views\\ButtonLineView',
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
0 => '@manager.button',
1 => '@handler.payment_button',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\BOPayment\\Services\\Deflectors\\PaygreenConnexionDeflector',
'arguments' =>
array (
0 => '@handler.route',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\FOPayment\\Services\\Controllers\\PaymentController',
'arguments' =>
array (
0 => '@paygreen.facade',
1 => '@handler.payment_creation',
2 => '@processor.payment_validation',
3 => '@manager.button',
4 => '@manager.payment_type',
5 => '@handler.behavior',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\FOPayment\\Services\\Controllers\\CustomerReturnController',
'arguments' =>
array (
0 => '%payment',
1 => '@processor.payment_validation',
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
'class' => 'PGI\\Module\\FOPayment\\Services\\Linkers\\RetryPaymentValidationLinker',
'extends' => 'linker.abstract',
'arguments' =>
array (
0 => '@handler.payment_creation',
),
),
'builder.output.success_payment_message' =>
array (
'abstract' => false,
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
'name' => 'builder.output',
),
),
'extends' => 'builder.output.abstract',
'class' => 'PGI\\Module\\FOPayment\\Services\\OutputBuilders\\SuccessPaymentMessageOutputBuilder',
'arguments' =>
array (
0 => '@handler.translation',
1 => '@handler.view',
2 => '@handler.link',
3 => '@logger',
),
),
'builder.output.payment_footer' =>
array (
'abstract' => false,
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
'name' => 'builder.output',
),
),
'extends' => 'builder.output.abstract',
'class' => 'PGI\\Module\\FOPayment\\Services\\OutputBuilders\\PaymentFooterOutputBuilder',
'arguments' =>
array (
0 => '@settings',
1 => '%paygreen.backlink',
),
),
'handler.tree_authentication' =>
array (
'class' => 'PGI\\Module\\PGTree\\Services\\Handlers\\TreeAuthenticationHandler',
'arguments' =>
array (
0 => '@facade.api.tree',
1 => '@factory.api.tree',
2 => '@settings',
3 => '@logger',
),
),
'handler.tree_carbon_offsetting' =>
array (
'class' => 'PGI\\Module\\PGTree\\Services\\Handlers\\TreeCarbonOffsettingHandler',
'arguments' =>
array (
0 => '@facade.api.tree',
1 => '@handler.tree_footprint_id',
2 => '@broadcaster',
3 => '@handler.requirement',
4 => '@manager.carbon_data',
5 => '@logger',
),
),
'handler.tree_footprint_id' =>
array (
'class' => 'PGI\\Module\\PGTree\\Services\\Handlers\\TreeFootprintIdHandler',
'arguments' =>
array (
0 => '@handler.cookie',
1 => '@handler.shop',
2 => '@settings',
3 => '@logger',
4 => '@facade.api.tree',
),
),
'handler.page_counter' =>
array (
'class' => 'PGI\\Module\\PGTree\\Services\\Handlers\\PageCounterHandler',
'arguments' =>
array (
0 => '@handler.cookie',
),
),
'handler.tree.catalog' =>
array (
'class' => 'PGI\\Module\\PGTree\\Services\\Handlers\\TreeCatalogHandler',
'arguments' =>
array (
0 => '@manager.product',
1 => '@handler.cache',
2 => '@translator',
3 => '@filter.product_reference',
4 => '@filter.product_name',
5 => '@parameters',
),
),
'handler.tree_account' =>
array (
'class' => 'PGI\\Module\\PGTree\\Services\\Handlers\\TreeAccountHandler',
'arguments' =>
array (
0 => '@handler.tree_authentication',
1 => '@facade.api.tree',
2 => '@handler.cache',
3 => '@settings',
4 => '@logger',
),
),
'manager.carbon_data' =>
array (
'class' => 'PGI\\Module\\PGTree\\Services\\Managers\\CarbonDataManager',
'arguments' =>
array (
0 => '@repository.carbon_data',
),
),
'filter.product_reference' =>
array (
'class' => 'PGI\\Module\\PGTree\\Services\\Filters\\ProductReferenceFilter',
),
'filter.product_name' =>
array (
'class' => 'PGI\\Module\\PGTree\\Services\\Filters\\ProductNameFilter',
),
'listener.carbon_offsetting_computing.adding_web_data' =>
array (
'class' => 'PGI\\Module\\PGTree\\Services\\Listeners\\CarbonOffsettingComputingWebListener',
'arguments' =>
array (
0 => '@facade.api.tree',
1 => '@handler.page_counter',
2 => '@logger',
),
),
'listener.carbon_offsetting_computing.setting_transport_data' =>
array (
'class' => 'PGI\\Module\\PGTree\\Services\\Listeners\\CarbonOffsettingComputingTransportationListener',
'arguments' =>
array (
0 => '@facade.api.tree',
1 => '@translator',
2 => '@settings',
3 => '@parameters',
4 => '@logger',
),
),
'listener.carbon_offsetting_computing.setting_product_data' =>
array (
'class' => 'PGI\\Module\\PGTree\\Services\\Listeners\\CarbonOffsettingComputingProductListener',
'arguments' =>
array (
0 => '@facade.api.tree',
1 => '@filter.product_reference',
2 => '@logger',
),
),
'listener.tree.page_counting' =>
array (
'class' => 'PGI\\Module\\PGTree\\Services\\Listeners\\PageCountingListener',
'arguments' =>
array (
0 => '@handler.page_counter',
1 => '@logger',
),
),
'listener.setup.tree_carbon_data_database' =>
array (
'class' => 'PGI\\Module\\PGTree\\Services\\Listeners\\SetupCarbonDataDatabaseListener',
'arguments' =>
array (
0 => '@handler.database',
1 => '@logger',
),
),
'upgrade.reset_tree_access_settings' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'upgrade',
),
),
'class' => 'PGI\\Module\\PGTree\\Services\\Upgrades\\ResetTreeAccessSettingsUpgrade',
'extends' => 'upgrade.abstract',
'arguments' =>
array (
0 => '@manager.setting',
1 => '@handler.shop',
2 => '@logger',
),
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
'class' => 'PGI\\Module\\PGTree\\Services\\Requirements\\TreeConnexionRequirement',
'extends' => 'requirement.abstract',
'arguments' =>
array (
0 => '@handler.tree_authentication',
),
),
'requirement.tree_kit_activation' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'requirement',
),
),
'class' => 'PGI\\Module\\PGTree\\Services\\Requirements\\TreeKitActivationRequirement',
'extends' => 'requirement.abstract',
'arguments' =>
array (
0 => '@settings',
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
'class' => 'PGI\\Module\\PGTree\\Services\\Requirements\\TreeActivationRequirement',
'extends' => 'requirement.abstract',
'arguments' =>
array (
0 => '@settings',
),
),
'requirement.tree_bot_activation' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'requirement',
),
),
'class' => 'PGI\\Module\\PGTree\\Services\\Requirements\\TreeBotActivationRequirement',
'extends' => 'requirement.abstract',
'arguments' =>
array (
0 => '@settings',
),
),
'requirement.tree_prod_available' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'requirement',
),
),
'class' => 'PGI\\Module\\PGTree\\Services\\Requirements\\TreeProdAvailableRequirement',
'extends' => 'requirement.abstract',
'arguments' =>
array (
0 => '@handler.tree_account',
),
),
'requirement.tree_access_available' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'requirement',
),
),
'class' => 'PGI\\Module\\PGTree\\Services\\Requirements\\TreeAccessAvailableRequirement',
'extends' => 'requirement.abstract',
'arguments' =>
array (
0 => '@handler.tree_account',
),
),
'repository.carbon_data' =>
array (
'abstract' => false,
'arguments' =>
array (
0 => '@handler.database',
1 => '%database.entities.carbon_data',
),
'extends' => 'repository.abstract',
'class' => 'PGI\\Module\\PGTree\\Services\\Repositories\\CarbonDataRepository',
),
'listener.setup.tree_client_compatibility_checker' =>
array (
'class' => 'PGI\\Module\\APITree\\Services\\Listeners\\InstallCompatibilityCheckListener',
'arguments' =>
array (
0 => '@facade.api.tree',
),
),
'facade.api.tree' =>
array (
'factory' => 'factory.api.tree',
),
'factory.api.tree' =>
array (
'class' => 'PGI\\Module\\APITree\\Services\\Factories\\ApiFacadeFactory',
'arguments' =>
array (
0 => '@logger.api_tree',
1 => '@settings',
2 => '@parameters',
3 => '@handler.requirement',
),
),
'logger.api_tree' =>
array (
'class' => 'PGI\\Module\\PGModule\\Services\\Logger',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_save_settings.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedSaveSettingsAction',
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
'action.tree_kit_activation.save' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_save_settings.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedSaveSettingsAction',
'arguments' =>
array (
0 => '@builder.form',
1 => '@settings',
),
'config' =>
array (
'form_name' => 'tree_kit_activation',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedDisplayPageAction',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedDisplayPageAction',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'tree_config',
),
),
'action.carbon_bot_config.display' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedDisplayPageAction',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'carbon_bot_config',
),
),
'action.carbon_bot_config_global_form.display' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'name' => 'action',
),
),
'extends' => 'action.standardized_form_settings_block.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedFormSettingsBlockAction',
'config' =>
array (
'form_name' => 'carbon_bot_config_global',
'form_action' => 'backoffice.carbon_bot_config_global_form.save',
),
),
'action.tree_bot_form.display' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'name' => 'action',
),
),
'extends' => 'action.abstract',
'class' => 'PGI\\Module\\BOTree\\Services\\Actions\\DisplayCarbonBotConfigurationFormAction',
),
'action.carbon_bot_config_global_form.save' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_save_settings.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedSaveSettingsAction',
'arguments' =>
array (
0 => '@builder.form',
1 => '@settings',
),
'config' =>
array (
'form_name' => 'carbon_bot_config_global',
'redirection' => 'backoffice.carbon_bot_config.display',
),
),
'action.tree_bot_form.save' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_save_settings.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedSaveSettingsAction',
'arguments' =>
array (
0 => '@builder.form',
1 => '@settings',
),
'config' =>
array (
'form_name' => 'tree_bot',
'redirection' => 'backoffice.carbon_bot_config.display',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'name' => 'action',
),
),
'extends' => 'action.standardized_form_settings_block.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedFormSettingsBlockAction',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'name' => 'action',
),
),
'extends' => 'action.standardized_form_settings_block.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedFormSettingsBlockAction',
'config' =>
array (
'form_name' => 'tree_shipping_address',
'form_action' => 'backoffice.tree_shipping_address.save',
),
),
'action.tree_translations.display' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedDisplayPageAction',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'tree_translations',
),
),
'action.tree_translations_form.display' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_translations_form_block.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedFormTranslationsBlockAction',
'arguments' =>
array (
0 => '@builder.translation_form',
1 => '@handler.translation',
),
'config' =>
array (
'translation_tag' => 'tree',
'form_action' => 'backoffice.tree_translations.save',
),
),
'action.tree_translations_form.save' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_save_translations_form.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedSaveTranslationsFormAction',
'arguments' =>
array (
0 => '@builder.translation_form',
1 => '@handler.translation',
2 => '@manager.translation',
),
'config' =>
array (
'translation_tag' => 'tree',
'redirect_to' => 'tree_translations',
),
),
'action.tree_bot_translations_form.display' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_translations_form_block.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedFormTranslationsBlockAction',
'arguments' =>
array (
0 => '@builder.translation_form',
1 => '@handler.translation',
),
'config' =>
array (
'translation_tag' => 'tree_bot',
'form_action' => 'backoffice.tree_bot_translations.save',
),
),
'action.tree_bot_translations_form.save' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_save_translations_form.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedSaveTranslationsFormAction',
'arguments' =>
array (
0 => '@builder.translation_form',
1 => '@handler.translation',
2 => '@manager.translation',
),
'config' =>
array (
'translation_tag' => 'tree_bot',
'redirect_to' => 'carbon_bot_config',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_save_settings.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedSaveSettingsAction',
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
'action.tree_products_synchronization.display' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedDisplayPageAction',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'tree_products_synchronization',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
7 =>
array (
'method' => 'setCarbonDataManager',
'arguments' =>
array (
0 => '@manager.carbon_data',
),
),
8 =>
array (
'method' => 'setTreeAccountHandler',
'arguments' =>
array (
0 => '@handler.tree_account',
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
'class' => 'PGI\\Module\\BOTree\\Services\\Controllers\\PluginController',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\BOTree\\Services\\Controllers\\AccountController',
),
'controller.backoffice.tree_export_product_catalog' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\BOTree\\Services\\Controllers\\ExportProductCatalogController',
'arguments' =>
array (
0 => '@generator.csv',
1 => '@handler.tree.catalog',
),
),
'listener.tree_action.display_backoffice' =>
array (
'class' => 'PGI\\Module\\BOTree\\Services\\Listeners\\DisplayBackofficeNotificationListener',
'arguments' =>
array (
0 => '@notifier',
1 => '@handler.tree_authentication',
),
),
'listener.tree_action.shipping_address' =>
array (
'class' => 'PGI\\Module\\BOTree\\Services\\Listeners\\ShippingAddressListener',
'arguments' =>
array (
0 => '@notifier',
1 => '@handler.tree_authentication',
2 => '@settings',
),
),
'listener.tree_action.display_tree_test_mode_expiration_notification' =>
array (
'class' => 'PGI\\Module\\BOTree\\Services\\Listeners\\DisplayTestModeExpirationNotificationListener',
'arguments' =>
array (
0 => '@notifier',
1 => '@handler.tree_account',
),
),
'handler.carbon_rounder' =>
array (
'class' => 'PGI\\Module\\FOTree\\Services\\Handlers\\CarbonRounderHandler',
'arguments' =>
array (
0 => '@handler.locale',
),
),
'controller.front.tree.climatebot' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\FOTree\\Services\\Controllers\\ClimateBotController',
'arguments' =>
array (
0 => '@manager.customer',
1 => '@manager.cart',
2 => '@handler.view',
3 => '@handler.tree_carbon_offsetting',
4 => '@handler.carbon_rounder',
),
),
'listener.carbon_footprint.finalization' =>
array (
'class' => 'PGI\\Module\\FOTree\\Services\\Listeners\\CarbonFootprintFinalizationListener',
'arguments' =>
array (
0 => '@handler.tree_carbon_offsetting',
1 => '@repository.carbon_data',
2 => '@logger',
),
),
'builder.output.carbon_footprint' =>
array (
'abstract' => false,
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
'name' => 'builder.output',
),
),
'extends' => 'builder.output.abstract',
'class' => 'PGI\\Module\\FOTree\\Services\\OutputBuilders\\CarbonFootprintOutputBuilder',
'arguments' =>
array (
0 => '@manager.carbon_data',
1 => '@handler.carbon_rounder',
2 => '@logger',
3 => '@settings',
),
),
'builder.output.carbon_bot' =>
array (
'abstract' => false,
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
'name' => 'builder.output',
),
),
'extends' => 'builder.output.abstract',
'class' => 'PGI\\Module\\FOTree\\Services\\OutputBuilders\\CarbonBotOutputBuilder',
'arguments' =>
array (
0 => '@handler.link',
1 => '@settings',
),
),
'officer.charity_gift' =>
array (
'abstract' => false,
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
'extends' => 'service.abstract',
'class' => 'PGI\\Module\\PGMagentoCharity\\Services\\Officers\\CharityGiftOfficer',
'arguments' =>
array (
0 => '@local.factory.product',
1 => '@local.repository.product',
2 => '@local.app.state',
3 => '@manager.product',
4 => '@settings',
5 => '@officer.charity_gift.picture',
6 => '@officer.charity_gift.translation',
7 => '@officer.charity_gift.stock',
),
'config' =>
array (
'gift_reference' => '%data.charity_gift.reference',
'gift_primary_setting' => 'charity_gift_id',
),
),
'handler.charity_authentication' =>
array (
'class' => 'PGI\\Module\\PGCharity\\Services\\Handlers\\CharityAuthenticationHandler',
'arguments' =>
array (
0 => '@facade.api.charity',
1 => '@factory.api.charity',
2 => '@settings',
3 => '@logger',
),
),
'handler.charity_association' =>
array (
'class' => 'PGI\\Module\\PGCharity\\Services\\Handlers\\CharityAssociationHandler',
'arguments' =>
array (
0 => '@facade.api.charity',
1 => '@handler.cache',
2 => '@logger',
),
),
'handler.charity_cart' =>
array (
'class' => 'PGI\\Module\\PGCharity\\Services\\Handlers\\CharityCartHandler',
'arguments' =>
array (
0 => '@manager.cart',
1 => '@handler.charity_gift',
2 => '@manager.product',
3 => '@logger',
),
),
'handler.charity' =>
array (
'class' => 'PGI\\Module\\PGCharity\\Services\\Handlers\\CharityHandler',
'arguments' =>
array (
0 => '@handler.charity_cart',
1 => '@handler.charity_partnership',
2 => '@handler.charity_gift',
3 => '@facade.api.charity',
4 => '@handler.session',
5 => '@manager.gift',
6 => '@handler.shop',
7 => '@settings',
8 => '@logger',
),
),
'handler.charity_partnership' =>
array (
'class' => 'PGI\\Module\\PGCharity\\Services\\Handlers\\CharityPartnershipHandler',
'arguments' =>
array (
0 => '@facade.api.charity',
1 => '@handler.cache',
2 => '@settings',
3 => '@logger',
),
),
'handler.charity_gift' =>
array (
'class' => 'PGI\\Module\\PGCharity\\Services\\Handlers\\CharityGiftHandler',
'arguments' =>
array (
0 => '@manager.product',
1 => '@officer.charity_gift',
2 => '@handler.shop',
),
),
'handler.charity_account' =>
array (
'class' => 'PGI\\Module\\PGCharity\\Services\\Handlers\\CharityAccountHandler',
'arguments' =>
array (
0 => '@handler.charity_authentication',
1 => '@facade.api.charity',
2 => '@handler.cache',
3 => '@settings',
4 => '@logger',
),
),
'manager.gift' =>
array (
'class' => 'PGI\\Module\\PGCharity\\Services\\Managers\\GiftManager',
'arguments' =>
array (
0 => '@repository.gift',
),
),
'listener.setup.gift_database' =>
array (
'class' => 'PGI\\Module\\PGCharity\\Services\\Listeners\\SetupGiftDatabaseListener',
'arguments' =>
array (
0 => '@handler.database',
1 => '@logger',
),
),
'listener.setup.charity_gift_product' =>
array (
'class' => 'PGI\\Module\\PGCharity\\Services\\Listeners\\SetupCharityGiftProductListener',
'arguments' =>
array (
0 => '@handler.charity_gift',
1 => '@logger',
),
),
'diagnostic.charity_gift' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'diagnostic',
),
),
'class' => 'PGI\\Module\\PGCharity\\Services\\Diagnostics\\CharityGiftDiagnostic',
'extends' => 'diagnostic.abstract',
'arguments' =>
array (
0 => '@handler.charity_gift',
1 => '@logger',
),
),
'upgrade.install_charity_gift_product' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'upgrade',
),
),
'class' => 'PGI\\Module\\PGCharity\\Services\\Upgrades\\InstallCharityGiftProductUpgrade',
'extends' => 'upgrade.abstract',
'arguments' =>
array (
0 => '@handler.charity_gift',
),
),
'upgrade.reset_charity_access_settings' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'upgrade',
),
),
'class' => 'PGI\\Module\\PGCharity\\Services\\Upgrades\\ResetCharityAccessSettingsUpgrade',
'extends' => 'upgrade.abstract',
'arguments' =>
array (
0 => '@manager.setting',
1 => '@handler.shop',
2 => '@logger',
),
),
'requirement.charity_connexion' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'requirement',
),
),
'class' => 'PGI\\Module\\PGCharity\\Services\\Requirements\\CharityConnexionRequirement',
'extends' => 'requirement.abstract',
'arguments' =>
array (
0 => '@handler.charity_authentication',
),
),
'requirement.charity_kit_activation' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'requirement',
),
),
'class' => 'PGI\\Module\\PGCharity\\Services\\Requirements\\CharityKitActivationRequirement',
'extends' => 'requirement.abstract',
'arguments' =>
array (
0 => '@settings',
),
),
'requirement.charity_activation' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'requirement',
),
),
'class' => 'PGI\\Module\\PGCharity\\Services\\Requirements\\CharityActivationRequirement',
'extends' => 'requirement.abstract',
'arguments' =>
array (
0 => '@settings',
),
),
'requirement.charity_prod_available' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'requirement',
),
),
'class' => 'PGI\\Module\\PGCharity\\Services\\Requirements\\CharityProdAvailableRequirement',
'extends' => 'requirement.abstract',
'arguments' =>
array (
0 => '@handler.charity_account',
),
),
'requirement.charity_access_available' =>
array (
'abstract' => false,
'tags' =>
array (
0 =>
array (
'name' => 'requirement',
),
),
'class' => 'PGI\\Module\\PGCharity\\Services\\Requirements\\CharityAccessAvailableRequirement',
'extends' => 'requirement.abstract',
'arguments' =>
array (
0 => '@handler.charity_account',
),
),
'repository.gift' =>
array (
'abstract' => false,
'arguments' =>
array (
0 => '@handler.database',
1 => '%database.entities.gift',
),
'extends' => 'repository.abstract',
'class' => 'PGI\\Module\\PGCharity\\Services\\Repositories\\GiftRepository',
),
'factory.api.charity' =>
array (
'class' => 'PGI\\Module\\APICharity\\Services\\Factories\\ApiFacadeFactory',
'arguments' =>
array (
0 => '@handler.requirement',
1 => '@logger.api_charity',
2 => '@settings',
3 => '@parameters',
),
),
'listener.setup.charity_client_compatibility_checker' =>
array (
'class' => 'PGI\\Module\\APICharity\\Services\\Listeners\\InstallCompatibilityCheckListener',
'arguments' =>
array (
0 => '@facade.api.charity',
),
),
'facade.api.charity' =>
array (
'factory' => 'factory.api.charity',
),
'logger.api_charity' =>
array (
'class' => 'PGI\\Module\\PGModule\\Services\\Logger',
'arguments' =>
array (
0 => '@dumper',
1 => '@pathfinder',
2 => '%{log.api_charity.file}',
3 => '%{log.api_charity.format}',
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
'action.charity_account.display' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedDisplayPageAction',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'charity_account',
),
),
'action.charity_config.display' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedDisplayPageAction',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'charity_config',
),
),
'action.charity_module_config.display' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'name' => 'action',
),
),
'extends' => 'action.standardized_form_settings_block.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedFormSettingsBlockAction',
'config' =>
array (
'form_name' => 'charity_config',
'form_action' => 'backoffice.charity_config.save',
),
),
'action.charity_config.save' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_save_settings.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedSaveSettingsAction',
'arguments' =>
array (
0 => '@builder.form',
1 => '@settings',
),
'config' =>
array (
'form_name' => 'charity_config',
'redirection' => 'backoffice.charity_config.display',
),
),
'action.charity_partnerships.display' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedDisplayPageAction',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'charity_partnerships',
),
),
'action.charity_translations.display' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedDisplayPageAction',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'charity_translations',
),
),
'action.charity_translations_form.display' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_translations_form_block.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedFormTranslationsBlockAction',
'arguments' =>
array (
0 => '@builder.translation_form',
1 => '@handler.translation',
),
'config' =>
array (
'translation_tag' => 'charity',
'form_action' => 'backoffice.charity_translations.save',
),
),
'action.charity_translations_form.save' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
),
),
'tags' =>
array (
0 =>
array (
'name' => 'action',
),
),
'extends' => 'action.standardized_save_translations_form.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\StandardizedSaveTranslationsFormAction',
'arguments' =>
array (
0 => '@builder.translation_form',
1 => '@handler.translation',
2 => '@manager.translation',
),
'config' =>
array (
'translation_tag' => 'charity',
'redirect_to' => 'charity_translations',
),
),
'controller.backoffice.charity' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'method' => 'setCharityAuthenticationHandler',
'arguments' =>
array (
0 => '@handler.charity_authentication',
),
),
7 =>
array (
'method' => 'setCharityAccountHandler',
'arguments' =>
array (
0 => '@handler.charity_account',
),
),
8 =>
array (
'method' => 'setGiftManager',
'arguments' =>
array (
0 => '@manager.gift',
),
),
9 =>
array (
'method' => 'setBroadcaster',
'arguments' =>
array (
0 => '@broadcaster',
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
'class' => 'PGI\\Module\\BOCharity\\Services\\Controllers\\PluginController',
),
'controller.backoffice.charity_account' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'method' => 'setCharityAuthenticationHandler',
'arguments' =>
array (
0 => '@handler.charity_authentication',
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
'class' => 'PGI\\Module\\BOCharity\\Services\\Controllers\\AccountController',
),
'controller.backoffice.charity_partnerships' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'method' => 'setCharityPartnershipHandler',
'arguments' =>
array (
0 => '@handler.charity_partnership',
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
'class' => 'PGI\\Module\\BOCharity\\Services\\Controllers\\PartnershipsController',
),
'listener.charity_action.display_backoffice' =>
array (
'class' => 'PGI\\Module\\BOCharity\\Services\\Listeners\\DisplayBackofficeNotificationListener',
'arguments' =>
array (
0 => '@notifier',
1 => '@handler.charity_authentication',
),
),
'listener.charity_action.display_charity_test_mode_expiration_notification' =>
array (
'class' => 'PGI\\Module\\BOCharity\\Services\\Listeners\\DisplayTestModeExpirationNotificationListener',
'arguments' =>
array (
0 => '@notifier',
1 => '@handler.charity_account',
),
),
'view.partnership.line' =>
array (
'class' => 'PGI\\Module\\BOCharity\\Services\\Views\\PartnershipLineView',
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
0 => '@translator',
),
),
'controller.front.charity.popin' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\FOCharity\\Services\\Controllers\\CharityPopinController',
'arguments' =>
array (
0 => '@handler.view',
1 => '@handler.charity_partnership',
2 => '@handler.charity',
),
),
'controller.front.charity.gift' =>
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\FOCharity\\Services\\Controllers\\CharityGiftController',
'arguments' =>
array (
0 => '@handler.charity',
1 => '@handler.session',
),
),
'listener.charity_gift.finalization' =>
array (
'class' => 'PGI\\Module\\FOCharity\\Services\\Listeners\\CharityGiftFinalizationListener',
'arguments' =>
array (
0 => '@handler.charity',
1 => '@repository.gift',
2 => '@logger',
),
),
'builder.output.charity_block' =>
array (
'abstract' => false,
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
'name' => 'builder.output',
),
),
'extends' => 'builder.output.abstract',
'class' => 'PGI\\Module\\FOCharity\\Services\\OutputBuilders\\CharityBlockOutputBuilder',
'arguments' =>
array (
0 => '@handler.charity',
1 => '@handler.link',
2 => '@settings',
),
),
'officer.settings.configuration.global' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Officers\\ConfigurationGlobalSettingsOfficer',
'arguments' =>
array (
0 => '@magento',
),
),
'officer.database' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Officers\\DatabaseOfficer',
'arguments' =>
array (
0 => '@magento',
),
),
'officer.shop' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Officers\\ShopOfficer',
),
'handler.cart' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Handlers\\CartHandler',
'arguments' =>
array (
0 => '@logger',
),
),
'provisioner.pre_payment' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Components\\Provisioners\\PrePayment',
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
'class' => 'PGI\\Module\\PGMagento\\Services\\Linkers\\BackofficeLinker',
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
'class' => 'PGI\\Module\\PGMagento\\Services\\Linkers\\FrontBasicLinker',
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
'class' => 'PGI\\Module\\PGMagento\\Services\\Linkers\\HomeLinker',
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
'class' => 'PGI\\Module\\PGMagento\\Services\\Linkers\\OrderLinker',
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
'class' => 'PGI\\Module\\PGMagento\\Services\\Linkers\\FrontBasicLinker',
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
'class' => 'PGI\\Module\\PGMagento\\Services\\Linkers\\FrontBasicLinker',
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
'class' => 'PGI\\Module\\PGMagento\\Services\\Linkers\\FrontBasicLinker',
),
'listener.setup.database' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Listeners\\SetupDatabaseListener',
'arguments' =>
array (
0 => '@handler.database',
1 => '@logger',
),
),
'builder.output.frontoffice_override_css' =>
array (
'abstract' => false,
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
'name' => 'builder.output',
),
),
'extends' => 'builder.output.abstract.static_files',
'class' => 'PGI\\Module\\PGModule\\Services\\OutputBuilders\\StaticFilesOutputBuilder',
'config' =>
array (
'css' =>
array (
0 => '/css/frontoffice-override.css',
),
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
'class' => 'PGI\\Module\\PGMagento\\Services\\Upgrades\\RestoreSettingsUpgrade',
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
'magento' =>
array (
),
'compiler.resource.magento' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Compilers\\StaticResourceCompiler',
'arguments' =>
array (
0 => '@handler.static_file',
),
),
'local.repository.quote' =>
array (
'factory' =>
array (
'service' => 'magento',
'method' => 'get',
),
'arguments' =>
array (
0 => 'Magento\\Quote\\Api\\CartRepositoryInterface',
),
),
'strategy.order_state_mapper.magento' =>
array (
'class' => 'PGI\\Module\\PGMagentoPayment\\Services\\Strategies\\OrderStateMagentoStrategy',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
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
'class' => 'PGI\\Module\\PGMagentoPayment\\Services\\Controllers\\InvalidPaymentsController',
'arguments' =>
array (
0 => '@magento',
1 => '@manager.order',
2 => '@handler.cart',
),
),
'listener.setup.order_states_creation' =>
array (
'class' => 'PGI\\Module\\PGMagentoPayment\\Services\\Listeners\\InstallOrderStateCreationListener',
'arguments' =>
array (
0 => '@manager.order_state',
1 => '@parameters',
2 => '@logger',
),
),
'listener.order_validation.invoice_creation' =>
array (
'class' => 'PGI\\Module\\PGMagentoPayment\\Services\\Listeners\\OrderValidationListener',
'arguments' =>
array (
0 => '@magento',
1 => '@logger',
),
),
'listener.setup.database_payment' =>
array (
'class' => 'PGI\\Module\\PGMagentoPayment\\Services\\Listeners\\SetupDatabaseListener',
'arguments' =>
array (
0 => '@handler.database',
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
'class' => 'PGI\\Module\\PGMagentoPayment\\Services\\Upgrades\\DatabaseMultiShopUpgrade',
'extends' => 'upgrade.abstract',
'arguments' =>
array (
0 => '@handler.database',
1 => '@handler.shop',
),
),
'builder.output.carbon_bot_css' =>
array (
'abstract' => false,
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
'name' => 'builder.output',
),
),
'extends' => 'builder.output.abstract.static_files',
'class' => 'PGI\\Module\\PGModule\\Services\\OutputBuilders\\StaticFilesOutputBuilder',
'config' =>
array (
'css' =>
array (
0 => '/css/tree-frontoffice.css',
),
),
),
'officer.charity_gift.picture' =>
array (
'abstract' => false,
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
'extends' => 'service.abstract',
'class' => 'PGI\\Module\\PGMagentoCharity\\Services\\Officers\\CharityGiftPictureOfficer',
'arguments' =>
array (
0 => '@pathfinder',
1 => '@local.product.media.config',
2 => '@local.filesystem',
),
'config' =>
array (
'gift_picture' => 'static:/pictures/FOCharity/logo-charity-kit.png',
),
),
'officer.charity_gift.translation' =>
array (
'abstract' => false,
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
'extends' => 'service.abstract',
'class' => 'PGI\\Module\\PGMagentoCharity\\Services\\Officers\\CharityGiftTranslationOfficer',
'arguments' =>
array (
0 => '@local.factory.product',
1 => '@local.resource_model.product',
2 => '@local.scope',
3 => '@translator',
),
'config' =>
array (
'gift_name_translation_key' => 'data.charity_gift.name',
),
),
'officer.charity_gift.stock' =>
array (
'abstract' => false,
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
'extends' => 'service.abstract',
'class' => 'PGI\\Module\\PGMagentoCharity\\Services\\Officers\\CharityGiftStockOfficer',
'arguments' =>
array (
0 => '@local.registry.stock',
),
),
'local.repository.product' =>
array (
'factory' =>
array (
'service' => 'magento',
'method' => 'get',
),
'arguments' =>
array (
0 => 'Magento\\Catalog\\Api\\ProductRepositoryInterface',
),
),
'local.factory.product' =>
array (
'factory' =>
array (
'service' => 'magento',
'method' => 'get',
),
'arguments' =>
array (
0 => 'Magento\\Catalog\\Api\\Data\\ProductInterfaceFactory',
),
),
'local.resource_model.product' =>
array (
'factory' =>
array (
'service' => 'magento',
'method' => 'get',
),
'arguments' =>
array (
0 => 'Magento\\Catalog\\Model\\ResourceModel\\Product',
),
),
'local.app.state' =>
array (
'factory' =>
array (
'service' => 'magento',
'method' => 'get',
),
'arguments' =>
array (
0 => 'Magento\\Framework\\App\\State',
),
),
'local.registry.stock' =>
array (
'factory' =>
array (
'service' => 'magento',
'method' => 'get',
),
'arguments' =>
array (
0 => 'Magento\\CatalogInventory\\Api\\StockRegistryInterface',
),
),
'local.product.media.config' =>
array (
'factory' =>
array (
'service' => 'magento',
'method' => 'get',
),
'arguments' =>
array (
0 => 'Magento\\Catalog\\Model\\Product\\Media\\Config',
),
),
'local.filesystem' =>
array (
'factory' =>
array (
'service' => 'magento',
'method' => 'get',
),
'arguments' =>
array (
0 => 'Magento\\Framework\\Filesystem',
),
),
'local.scope' =>
array (
'factory' =>
array (
'service' => 'magento',
'method' => 'get',
),
'arguments' =>
array (
0 => 'Magento\\Framework\\App\\Config\\ScopeConfigInterface',
),
),
);
