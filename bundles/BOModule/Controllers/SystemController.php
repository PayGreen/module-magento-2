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

/**
 * Class BOModuleControllersSystemController
 * @package BOModule\Controllers
 */
class BOModuleControllersSystemController extends BOModuleFoundationsAbstractBackofficeController
{
    /** @var PGModuleInterfacesApplicationFacade */
    private $applicationFacade;

    /** @var PGSystemComponentsKernel */
    private $kernel;

    public function __construct(
        PGModuleInterfacesApplicationFacade $applicationFacade,
        PGSystemComponentsKernel $kernel
    ) {
        $this->applicationFacade = $applicationFacade;
        $this->kernel = $kernel;
    }

    /**
     * @return PGServerComponentsResponsesTemplateResponse
     * @throws Exception
     */
    public function displayModuleSystemInformationsAction()
    {
        if (function_exists('curl_version')) {
            $curl_data = curl_version();
        } else {
            $curl_data = array(
                'version' => 'NA',
                'ssl_version' => 'NA'
            );
        }

        return $this->buildTemplateResponse('system/block-informations-module')
            ->addData('platforme', $this->applicationFacade->getName())
            ->addData('version_platforme', $this->applicationFacade->getVersion())
            ->addData('version_php', PHP_VERSION)
            ->addData('version_module', PAYGREEN_MODULE_VERSION)
            ->addData('version_framework', $this->kernel->getVersion())
            ->addData('version_curl', $curl_data['version'])
            ->addData('version_ssl', $curl_data['ssl_version'])
        ;
    }
}
