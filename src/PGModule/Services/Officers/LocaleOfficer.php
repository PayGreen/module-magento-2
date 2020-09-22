<?php
/**
 * 2014 - 2020 Watt Is It
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
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.1.1
 */

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Locale\Resolver;
use Magento\User\Model\User;

/**
 * Class PGModuleServicesOfficersLocaleOfficer
 * @package PGModule\Services\Officers
 */
class PGModuleServicesOfficersLocaleOfficer implements PGIntlInterfacesLocaleOfficerInterface
{
    const LOCAL_SEPARATOR = '_';

    /** @var Resolver */
    private $localeResolver;

    /** @var User */
    private $adminUser;

    public function __construct(ObjectManager $magento)
    {
        $this->localeResolver = $magento->get('Magento\Framework\Locale\Resolver');
        $this->adminUser = $magento->get('Magento\Backend\Model\Auth\Session')->getUser();
    }

    public function getCustomerLocale()
    {
        if ($this->adminUser !== null) {
            $locale = $this->adminUser->getInterfaceLocale();
        } else {
            $locale = $this->getShopLocale();
        }

        return $locale;
    }

    public function getCustomerCountry()
    {
        list(, $country) = explode(self::LOCAL_SEPARATOR, $this->getCustomerLocale());

        return $country;
    }

    public function getCustomerLanguage()
    {
        list($language, ) = explode(self::LOCAL_SEPARATOR, $this->getCustomerLocale());

        return $language;
    }

    public function getShopLocale()
    {
        return $this->localeResolver->getDefaultLocale();
    }

    public function getShopCountry()
    {
        list(, $country) = explode(self::LOCAL_SEPARATOR, $this->getShopLocale());

        return $country;
    }

    public function getShopLanguage()
    {
        list($language, ) = explode(self::LOCAL_SEPARATOR, $this->getShopLocale());

        return $language;
    }
}
