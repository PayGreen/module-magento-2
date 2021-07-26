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
 * @version   2.2.0
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
'generator.csv' =>
array (
'class' => 'PGI\\Module\\PGFramework\\Services\\Generators\\CSVGenerator',
),
'officer.setup' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Officers\\SetupOfficer',
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
),
),
'handler.hook' =>
array (
'class' => 'PGI\\Module\\PGFramework\\Services\\Handlers\\HookHandler',
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
'manager.setting' =>
array (
'class' => 'PGI\\Module\\PGModule\\Services\\Managers\\SettingManager',
'arguments' =>
array (
0 => '@repository.setting',
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
1 => '%settings',
),
),
'broadcaster' =>
array (
'class' => 'PGI\\Module\\PGModule\\Services\\Broadcaster',
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
'dispatcher' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Dispatcher',
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
'factory.trigger' =>
array (
'class' => 'PGI\\Module\\PGServer\\Services\\Factories\\TriggerFactory',
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
'class' => 'PGI\\Module\\PGServer\\Services\\Factories\\StageFactory',
'arguments' =>
array (
0 => '@factory.trigger',
1 => '@logger',
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
'manager.translation' =>
array (
'class' => 'PGI\\Module\\PGIntl\\Services\\Managers\\TranslationManager',
'arguments' =>
array (
0 => '@repository.translation',
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
4 => '%translator',
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
'officer.locale' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Officers\\LocaleOfficer',
'arguments' =>
array (
0 => '@magento',
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
'listener.upgrade.clear_smarty_cache' =>
array (
'class' => 'PGI\\Module\\PGView\\Services\\Listeners\\ClearSmartyCacheListener',
'arguments' =>
array (
0 => '@handler.smarty',
1 => '@logger',
),
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
'class' => 'PGI\\Module\\PGForm\\Services\\Builders\\Validator',
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
1 => '@handler.shop',
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
'officer.post_payment' =>
array (
'class' => 'PGI\\Module\\PGMagentoPayment\\Services\\Officers\\PostPaymentOfficer',
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
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\DisplayHomePageAction',
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
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\DisplaySupportPageAction',
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
'method' => 'setLinkHandler',
'arguments' =>
array (
0 => '@handler.link',
),
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
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOModule\\Services\\Actions\\DisplayProductsPageAction',
'arguments' =>
array (
0 => '@handler.block',
),
'config' =>
array (
'page_name' => 'products',
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
'paygreen.facade' =>
array (
'class' => 'PGI\\Module\\PGPayment\\Services\\Facades\\PaygreenFacade',
'arguments' =>
array (
0 => '@api.factory',
1 => '@handler.http',
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
1 => '@handler.requirement',
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
'controller.backoffice.payment_translations' =>
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
'class' => 'PGI\\Module\\BOPayment\\Services\\Controllers\\Translations',
'arguments' =>
array (
0 => '@builder.translation_form',
1 => '@handler.translation',
2 => '@manager.translation',
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
'extends' => 'action.standardized_display_page.abstract',
'class' => 'PGI\\Module\\BOPayment\\Services\\Actions\\DisplayEligibleAmountsPageAction',
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
'handler.payment_statistics' =>
array (
'class' => 'PGI\\Module\\BOPayment\\Services\\Handlers\\PaymentStatisticsHandler',
'arguments' =>
array (
0 => '@manager.transaction',
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
'manager.carbon_data' =>
array (
'class' => 'PGI\\Module\\PGTree\\Services\\Managers\\CarbonDataManager',
'arguments' =>
array (
0 => '@repository.carbon_data',
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
0 => '@handler.requirement',
1 => '@handler.tree_authentication',
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
1 => '@handler.requirement',
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
'behavior.tree_activation' =>
array (
'class' => 'PGI\\Module\\PGTree\\Services\\Behaviors\\TreeActivationBehavior',
'arguments' =>
array (
0 => '@handler.requirement',
),
),
'handler.tree_authentication' =>
array (
'class' => 'PGI\\Module\\PGTree\\Services\\Handlers\\TreeAuthenticationHandler',
'arguments' =>
array (
0 => '@facade.api.tree',
1 => '@settings',
2 => '@logger',
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
4 => '@handler.view',
5 => '@manager.carbon_data',
6 => '@logger',
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
1 => '@logger',
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
'listener.setup.tree_client_compatibility_checker' =>
array (
'class' => 'PGI\\Module\\APITree\\Services\\Listeners\\InstallCompatibilityCheckListener',
'arguments' =>
array (
0 => '@facade.api.tree',
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
'controller.backoffice.tree_translations' =>
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
'class' => 'PGI\\Module\\BOTree\\Services\\Controllers\\TranslationsController',
'arguments' =>
array (
0 => '@builder.translation_form',
1 => '@handler.translation',
2 => '@manager.translation',
),
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
1 => '@repository.product',
2 => '@handler.tree_authentication',
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
'listener.tree_action.display_backoffice' =>
array (
'class' => 'PGI\\Module\\BOTree\\Services\\Listeners\\DisplayBackofficeListener',
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
1 => '@handler.locale',
2 => '@logger',
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
'controller.tree.climatebot' =>
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
4 => '@handler.locale',
),
),
'listener.carbon_footprint.finalization' =>
array (
'class' => 'PGI\\Module\\FOTree\\Services\\Listeners\\CarbonFootprintFinalizationListener',
'arguments' =>
array (
0 => '@handler.tree_carbon_offsetting',
1 => '@handler.requirement',
2 => '@repository.carbon_data',
3 => '@logger',
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
'provisioner.pre_payment' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Components\\Provisioners\\PrePayment',
'arguments' =>
array (
0 => '@magento',
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
'listener.setup.database' =>
array (
'class' => 'PGI\\Module\\PGMagento\\Services\\Listeners\\SetupDatabaseListener',
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
);
