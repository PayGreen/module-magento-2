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
 * @version   1.2.5
 *
 */

/**
 * Class PGDomainServicesSelectorsHierarchizedCategorySelector
 * @package PGFramework\Services\Selectors
 */
class PGDomainServicesSelectorsHierarchizedCategorySelector extends PGFrameworkFoundationsAbstractSelector
{
    /** @var PGDomainServicesManagersCategoryManager */
    private $categoryManager;

    /**
     * @param PGDomainServicesManagersCategoryManager $categoryManager
     */
    public function setCategoryManager(PGDomainServicesManagersCategoryManager $categoryManager)
    {
        $this->categoryManager = $categoryManager;
    }

    /**
     * @return array
     */
    protected function buildChoices()
    {
        $rootCategories = $this->categoryManager->getRootCategories();
        $choices = array();

        $this->addCategoryChoices($rootCategories, $choices);

        return $choices;
    }

    protected function addCategoryChoices(array $categories, array &$choices)
    {
        /** @var PGDomainInterfacesEntitiesCategoryInterface $category */
        foreach ($categories as $category) {
            $depth = str_repeat('&nbsp;', $category->getDepth() * 8);

            $choices[$category->id()] = $depth . $category->getName();
            $this->addCategoryChoices($category->getChildren(), $choices);
        }
    }

    protected function getTranslationRoot()
    {
        return '';
    }
}
