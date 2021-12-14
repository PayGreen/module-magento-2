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
 * @version   2.5.0
 *
 */

namespace PGI\Module\PGPayment\Services\Managers;

use PGI\Module\PGDatabase\Foundations\AbstractManager;
use PGI\Module\PGPayment\Interfaces\Repositories\CategoryHasPaymentTypeRepositoryInterface;
use PGI\Module\PGShop\Interfaces\Entities\CategoryEntityInterface;

/**
 * Class CategoryHasPaymentTypeManager
 *
 * @package PGPayment\Services\Managers
 * @method CategoryHasPaymentTypeRepositoryInterface getRepository()
 */
class CategoryHasPaymentTypeManager extends AbstractManager
{
    private $paymentTypes = array();

    /**
     * @param array $categoryPayments
     */
    public function saveCategoryPayments(array $categoryPayments)
    {
        $categoryPaymentRows = array();
        foreach ($categoryPayments as $id_category => $categoryPayment) {
            foreach ($categoryPayment as $mode) {
                $categoryPaymentRows[] = array(
                    'id_category' => $id_category,
                    'payment' => $mode
                );
            }
        }

        $this->getRepository()->truncate();
        $this->getRepository()->saveAll($categoryPaymentRows);

        $this->paymentTypes = array();
    }

    /**
     * @param CategoryEntityInterface $category
     * @param string $code
     * @return bool
     */
    public function isEligibleCategory(CategoryEntityInterface $category, $code)
    {
        if (!array_key_exists($code, $this->paymentTypes)) {
            $this->preloadPaymentType($code);
        }

        $isUnlimited = $this->isUnlimitedPaymentType($code);

        $inArray = in_array($category->id(), $this->paymentTypes[$code]);

        return ($isUnlimited || $inArray);
    }


    /**
     * @param string $code
     * @return bool
     */
    public function isUnlimitedPaymentType($code)
    {
        if (!array_key_exists($code, $this->paymentTypes)) {
            $this->preloadPaymentType($code);
        }

        return empty($this->paymentTypes[$code]);
    }




    protected function preloadPaymentType($type)
    {
        $this->paymentTypes[$type] = $this->getRepository()->findCategoriesByPaymentType($type);
    }
}
