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
 * Class PGFormFoundationsAbstractFieldChoice
 * @package PGForm\Foundations
 */
abstract class PGFormFoundationsAbstractFieldChoice extends PGFormServicesViewsFieldView
{
    /** @var PGFrameworkServicesHandlersSelectHandler */
    private $selectHandler;

    /** @var PGIntlServicesTranslator */
    private $translator;

    public function __construct(
        PGFrameworkServicesHandlersSelectHandler $selectHandler,
        PGIntlServicesTranslator $translator
    ) {
        $this->selectHandler = $selectHandler;
        $this->translator = $translator;
    }

    /**
     * @return PGFrameworkServicesHandlersSelectHandler
     */
    public function getSelectHandler()
    {
        return $this->selectHandler;
    }

    /**
     * @return PGIntlServicesTranslator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    public function translate(&$val)
    {
        $val = $this->translator->get($val);
    }
}
