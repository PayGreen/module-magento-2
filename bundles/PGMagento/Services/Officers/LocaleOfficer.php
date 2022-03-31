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

namespace PGI\Module\PGMagento\Services\Officers;

use Magento\Framework\App\ObjectManager as LocalObjectManager;
use Magento\Framework\Locale\Resolver as LocalResolver;
use Magento\User\Model\User as LocalUser;
use PGI\Module\PGIntl\Interfaces\Officers\LocaleOfficerInterface;

/**
 * Class LocaleOfficer
 * @package PGMagento\Services\Officers
 */
class LocaleOfficer implements LocaleOfficerInterface
{
    const LOCAL_SEPARATOR = '_';

    /** @var LocalResolver */
    private $localeResolver;

    /** @var LocalUser */
    private $adminUser;

    public function __construct(LocalObjectManager $magento)
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
