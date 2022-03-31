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

namespace PGI\Module\PGIntl\Services\Handlers;

use PGI\Module\PGIntl\Interfaces\Officers\LocaleOfficerInterface;

/**
 * Class LocaleHandler
 * @package PGIntl\Services\Handlers
 */
class LocaleHandler
{
    const DEFAULT_LANGUAGE = 'en';

    /** @var LocaleOfficerInterface */
    private $localeOfficer;

    public function __construct(LocaleOfficerInterface $localeOfficer)
    {
        $this->localeOfficer = $localeOfficer;
    }

    public function getLanguage()
    {
        return $this->localeOfficer->getCustomerLanguage();
    }

    public function getDefaultLanguage()
    {
        return $this->localeOfficer->getShopLanguage();
    }
}
