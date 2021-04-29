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
 * Class PGPaymentComponentsPaymentProject
 * @package PGPayment\Components
 */
class PGPaymentComponentsPaymentProject implements arrayaccess
{
    private $data = array();

    /** @var PGPaymentInterfacesEntitiesButtonInterface */
    private $button;

    /** @var PGShopInterfacesProvisionersPrePayment */
    private $prePaymentProvisionner;

    public function __construct(
        PGPaymentInterfacesEntitiesButtonInterface $button,
        PGShopInterfacesProvisionersPrePayment $prePaymentProvisionner
    ) {
        $this->button = $button;
        $this->prePaymentProvisionner = $prePaymentProvisionner;
    }

    /**
     * @return PGPaymentInterfacesEntitiesButtonInterface
     */
    public function getButton()
    {
        return $this->button;
    }

    /**
     * @return PGShopInterfacesProvisionersPrePayment
     */
    public function getPrePaymentProvisionner()
    {
        return $this->prePaymentProvisionner;
    }

    public function toArray()
    {
        return $this->data;
    }

    // ###################################################################
    // ###       sous-fonctions d'accès par tableau
    // ###################################################################

    public function offsetSet($var, $value)
    {
        $this->data[$var] = $value;
    }

    public function offsetExists($var)
    {
        return array_key_exists($var, $this->data);
    }

    public function offsetUnset($var)
    {
        if (isset($this[$var])) {
            unset($this->data[$var]);
        }
    }

    public function offsetGet($var)
    {
        return isset($this[$var]) ? $this->data[$var] : null;
    }
}
