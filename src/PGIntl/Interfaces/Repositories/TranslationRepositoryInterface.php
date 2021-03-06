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
 * Interface PGIntlInterfacesRepositoriesTranslationRepositoryInterface
 * @package PGFramework\Interfaces\Repositories
 */
interface PGIntlInterfacesRepositoriesTranslationRepositoryInterface extends PGFrameworkInterfacesRepositoryInterface
{
    /**
     * @param string $code
     * @return PGIntlInterfacesEntitiesTranslationInterface[]
     */
    public function findByCode($code, PGDomainInterfacesEntitiesShopInterface $shop = null);

    /**
     * @param string $pattern
     * @return PGIntlInterfacesEntitiesTranslationInterface[]
     */
    public function findByPattern($pattern, PGDomainInterfacesEntitiesShopInterface $shop = null);

    /**
     * @param string $code
     * @param string $language
     * @return bool
     */
    public function create($code, $language, PGDomainInterfacesEntitiesShopInterface $shop = null);

    /**
     * @param PGIntlInterfacesEntitiesTranslationInterface $translation
     * @return bool
     */
    public function insert(PGIntlInterfacesEntitiesTranslationInterface $translation);

    /**
     * @param PGIntlInterfacesEntitiesTranslationInterface $translation
     * @return bool
     */
    public function update(PGIntlInterfacesEntitiesTranslationInterface $translation);

    /**
     * @param PGIntlInterfacesEntitiesTranslationInterface $translation
     * @return bool
     */
    public function delete(PGIntlInterfacesEntitiesTranslationInterface $translation);
}
