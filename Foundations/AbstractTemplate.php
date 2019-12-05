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
 * @version   0.3.4
 */

namespace Paygreen\Payment\Foundations;

use PGFrameworkContainer;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Data\Form\FormKey;

require_once PAYGREEN_BOOTSTRAP_SRC;

abstract class AbstractTemplate extends Template
{
    /** @var FormKey */
    protected $formKey;

    public function __construct(Context $context)
    {
        parent::__construct($context);

        $this->formKey = $this->getService('magento')->get('Magento\Framework\Data\Form\FormKey');
    }

    protected function getService($name)
    {
        return PGFrameworkContainer::getInstance()->get($name);
    }

    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }
}
