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

namespace PGI\Module\PGMagento\Services\Builders;

use Magento\Framework\App\ObjectManager as LocalObjectManager;
use Magento\Framework\Data\Form\FormKey as LocalFormKey;
use PGI\Module\PGForm\Services\Builders\FormBuilder as ParentFormBuilder;
use Exception;

class FormBuilder extends ParentFormBuilder
{
    /** @var LocalFormKey */
    protected $formKey;

    /**
     * @param LocalObjectManager $objectManager
     */
    public function setFormKey(LocalObjectManager $objectManager)
    {
        $this->formKey = $objectManager->get('Magento\Framework\Data\Form\FormKey');
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function buildFields(array $formDefinition)
    {
        $extendedFields = array(
            "fields" => array(
                "form_key" => array(
                    "model" => "hidden",
                    "default" => $this->formKey->getFormKey()
                )
            )
        );

        return array_merge(
            parent::buildFields($extendedFields),
            parent::buildFields($formDefinition)
        );
    }
}