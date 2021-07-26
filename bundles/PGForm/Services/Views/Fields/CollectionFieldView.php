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
 * @version   2.2.0
 *
 */

namespace PGI\Module\PGForm\Services\Views\Fields;

use PGI\Module\PGForm\Interfaces\Fields\BasicFieldInterface;
use PGI\Module\PGForm\Interfaces\Fields\CollectionFieldInterface;
use PGI\Module\PGForm\Services\Views\Fields\BasicFieldView;

/**
 * Class CollectionFieldView
 *
 * @method CollectionFieldInterface getField()
 * @package PGForm\Services\Views\Fields
 */
class CollectionFieldView extends BasicFieldView
{
    private $bin;

    public function getData()
    {
        $data = parent::getData();

        $data['children'] = $this->buildChildrenViews();

        return $data;
    }

    protected function completeFieldAttributes(array $data)
    {
        // Thrashing unused arguments
        $this->bin = $data;
        
        return array();
    }

    protected function buildChildrenViews()
    {
        $childrenViews = array();

        /**
         * @var string $name
         * @var BasicFieldInterface $field
         */
        foreach ($this->getField()->getChildren() as $field) {
            $childrenViews[] = $field->buildView()->render();
        }

        return $childrenViews;
    }
}