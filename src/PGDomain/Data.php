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

/**
 * Class PGDomainData
 * @package PGDomain
 */
abstract class PGDomainData
{
    const MODE_CASH = 'CASH';
    const MODE_RECURRING = 'RECURRING';
    const MODE_XTIME = 'XTIME';
    const MODE_TOKENIZE = 'TOKENIZE';
    const MODE_CARDPRINT = 'CARDPRINT';

    const RECURRING_DAILY = 10;
    const RECURRING_WEEKLY = 20;
    const RECURRING_SEMI_MONTHLY = 30;
    const RECURRING_MONTHLY = 40;
    const RECURRING_BIMONTHLY = 50;
    const RECURRING_QUARTERLY = 60;
    const RECURRING_SEMI_ANNUAL = 70;
    const RECURRING_ANNUAL = 80;
    const RECURRING_BIANNUAL = 90;
}
