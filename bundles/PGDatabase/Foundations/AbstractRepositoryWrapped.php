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
 * @version   2.6.1
 *
 */

namespace PGI\Module\PGDatabase\Foundations;

use PGI\Module\PGDatabase\Foundations\AbstractRepository;
use PGI\Module\PGDatabase\Interfaces\RepositoryWrappedEntityInterface;

/**
 * Class AbstractRepositoryWrapped
 * @package PGDatabase\Foundations
 */
abstract class AbstractRepositoryWrapped extends AbstractRepository implements RepositoryWrappedEntityInterface
{
    /**
     * @inheritDoc
     */
    public function wrapEntities($localEntities)
    {
        $entities = array();

        foreach ($localEntities as $localEntity) {
            $entities[] = $this->wrapEntity($localEntity);
        }

        return $entities;
    }
}
