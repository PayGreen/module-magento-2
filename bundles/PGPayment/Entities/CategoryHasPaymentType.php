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
 * @version   2.6.0
 *
 */

namespace PGI\Module\PGPayment\Entities;

use PGI\Module\PGDatabase\Foundations\AbstractEntityPersisted;
use PGI\Module\PGPayment\Interfaces\Entities\CategoryHasPaymentTypeEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\CategoryEntityInterface;
use PGI\Module\PGShop\Services\Managers\CategoryManager;
use Exception;

/**
 * Class CategoryHasPaymentType
 * @package PGPayment\Entities
 */
class CategoryHasPaymentType extends AbstractEntityPersisted implements CategoryHasPaymentTypeEntityInterface
{
    /** @var null|CategoryEntityInterface */
    private $category = null;

    /**
     * @return CategoryEntityInterface
     * @throws Exception
     */
    public function getCategory()
    {
        if ($this->category === null) {
            $this->loadCategory();
        }

        return $this->category;
    }

    /**
     * @throws Exception
     */
    protected function loadCategory()
    {
        /** @var CategoryManager $categoryManager */
        $categoryManager = $this->getService('manager.category');

        $id_category = $this->getCategoryPrimary();

        $this->category = $categoryManager->getByPrimary($id_category);
    }

    /**
     * @inheritdoc
     */
    public function getCategoryPrimary()
    {
        return $this->get('id_category');
    }

    /**
     * @inheritdoc
     */
    public function getPaymentType()
    {
        return $this->get('payment');
    }
}
