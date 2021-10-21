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

namespace PGI\Module\PGShop\Foundations\Entities;

use PGI\Module\PGDatabase\Foundations\AbstractEntityWrapped;
use PGI\Module\PGShop\Interfaces\Entities\CategoryEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\ProductEntityInterface;

/**
 * Class AbstractProductEntity
 * @package PGShop\Foundations\Entities
 */
abstract class AbstractProductEntity extends AbstractEntityWrapped implements ProductEntityInterface
{
    /** @var CategoryEntityInterface[] */
    private $categories = array();

    /**
     * @return CategoryEntityInterface[]
     */
    public function getCategories()
    {
        if (empty($this->categories)) {
            $this->categories = $this->preloadCategories();
        }

        return $this->categories;
    }

    /**
     * @return CategoryEntityInterface[]
     */
    abstract protected function preloadCategories();
}
