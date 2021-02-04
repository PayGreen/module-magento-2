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
 * @version   1.2.2
 *
 */

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'IGrantType.php');

/**
 * Authorization code  Grant Type Validator
 */
class AuthorizationCode implements IGrantType
{
    /**
     * Defines the Grant Type
     *
     * @var string  Defaults to 'authorization_code'.
     */
    const GRANT_TYPE = 'authorization_code';

    /**
     * Adds a specific Handling of the parameters
     *
     * @return array of Specific parameters to be sent.
     * @param  mixed  $parameters the parameters array (passed by reference)
     */
    public function validateParameters(&$parameters)
    {
        if (!isset($parameters['code'])) {
            throw new InvalidArgumentException(
                'The \'code\' parameter must be defined for the Authorization Code grant type',
                InvalidArgumentException::MISSING_PARAMETER
            );
        } elseif (!isset($parameters['redirect_uri'])) {
            throw new InvalidArgumentException(
                'The \'redirect_uri\' parameter must be defined for the Authorization Code grant type',
                InvalidArgumentException::MISSING_PARAMETER
            );
        }
    }
}
