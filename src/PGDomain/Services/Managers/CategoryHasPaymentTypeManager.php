<?php
/**
 * 2014 - 2019 Watt Is It
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
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.2
 */

/**
 * Class PGDomainServicesManagersCategoryHasPaymentTypeManager
 *
 * @package PGDomain\Services\Managers
 * @method PGDomainInterfacesRepositoriesCategoryHasPaymentTypeRepositoryInterface getRepository()
 */
class PGDomainServicesManagersCategoryHasPaymentTypeManager extends PGFrameworkFoundationsAbstractManager
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
     * @param PGDomainInterfacesEntitiesCategoryInterface $category
     * @param string $mode
     * @return bool
     */
    public function isEligibleCategory(PGDomainInterfacesEntitiesCategoryInterface $category, $code)
    {
        if (!array_key_exists($code, $this->paymentTypes)) {
            $this->preloadPaymentType($code);
        }

        $isEmpty = empty($this->paymentTypes[$code]);
        $inArray = in_array($category->id(), $this->paymentTypes[$code]);

        return ($isEmpty || $inArray);
    }

    protected function preloadPaymentType($type)
    {
        $this->paymentTypes[$type] = $this->getRepository()->findCategoriesByPaymentType($type);
    }
}
