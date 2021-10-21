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

namespace PGI\Module\PGModule\Components\Events;

use PGI\Module\PGModule\Foundations\AbstractEvent;

/**
 * Class ProductActivation
 * @package PGModule\Components\Events
 */
class ProductActivation extends AbstractEvent
{
    /** @var string */
    private $name;

    /** @var string */
    private $product;

    public function __construct($type, $product)
    {
        $this->name = 'PRODUCT_ACTIVATION.' . strtoupper($type) . '.'  . strtoupper($product);

        $this->product = $product;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getProduct()
    {
        return $this->product;
    }
}
