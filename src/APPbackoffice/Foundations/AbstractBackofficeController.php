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
 * @version   1.2.1
 *
 */

/**
 * Class APPbackofficeFoundationsAbstractBackofficeController
 * @package APPbackoffice\Foundations
 */
abstract class APPbackofficeFoundationsAbstractBackofficeController extends PGServerFoundationsAbstractController
{
    /**
     * @param string $name
     * @param string $action
     * @return PGViewComponentsBox
     * @throws Exception
     */
    protected function buildSettingsFormView($name, $action)
    {
        /** @var PGFrameworkServicesSettings $settings */
        $settings = $this->getService('settings');

        /** @var PGFrameworkComponentsParameters $parameters */
        $parameters = $this->getService('parameters');

        $keys = array_keys($parameters["form.definitions.$name.fields"]);

        $values = $settings->getArray($keys);

        /** @var PGFormServicesViewsFormView $view */
        $view = $this->buildForm($name, $values)->buildView();

        $url = $this->getLinker()->buildBackOfficeUrl($action);

        $view->setAction($url);

        return new PGViewComponentsBox($view);
    }
}
