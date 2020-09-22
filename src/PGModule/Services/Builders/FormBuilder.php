<?php
/**
 * 2014 - 2020 Watt Is It
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
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.1.1
 */

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\Form\FormKey;

class PGModuleServicesBuildersFormBuilder extends PGFormServicesFormBuilder
{
    /** @var FormKey */
    protected $formKey;

    /**
     * @param ObjectManager $objectManager
     */
    public function setFormKey(ObjectManager $objectManager)
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