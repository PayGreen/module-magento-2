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

namespace PGI\Module\PGIntl\Interfaces\Repositories;

use PGI\Module\PGDatabase\Interfaces\RepositoryInterface;
use PGI\Module\PGIntl\Interfaces\Entities\TranslationEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\ShopEntityInterface;

/**
 * Interface TranslationRepositoryInterface
 * @package PGIntl\Interfaces\Repositories
 */
interface TranslationRepositoryInterface extends RepositoryInterface
{
    /**
     * @param string $code
     * @return TranslationEntityInterface[]
     */
    public function findByCode($code, ShopEntityInterface $shop = null);

    /**
     * @param string $pattern
     * @return TranslationEntityInterface[]
     */
    public function findByPattern($pattern, ShopEntityInterface $shop = null);

    /**
     * @param string $code
     * @param string $language
     * @return bool
     */
    public function create($code, $language, ShopEntityInterface $shop = null);

    /**
     * @param TranslationEntityInterface $translation
     * @return bool
     */
    public function insert(TranslationEntityInterface $translation);

    /**
     * @param TranslationEntityInterface $translation
     * @return bool
     */
    public function update(TranslationEntityInterface $translation);

    /**
     * @param TranslationEntityInterface $translation
     * @return bool
     */
    public function delete(TranslationEntityInterface $translation);
}
