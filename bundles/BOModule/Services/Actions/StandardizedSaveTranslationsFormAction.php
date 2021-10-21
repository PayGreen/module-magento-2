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
 * @version   2.4.0
 *
 */

namespace PGI\Module\BOModule\Services\Actions;

use Exception;
use PGI\Module\PGForm\Interfaces\FormInterface;
use PGI\Module\PGIntl\Services\Builders\TranslationFormBuilder;
use PGI\Module\PGIntl\Services\Handlers\TranslationHandler;
use PGI\Module\PGIntl\Services\Managers\TranslationManager;
use PGI\Module\PGServer\Foundations\AbstractAction;
use ReflectionException;

/**
 * Class StandardizedSaveTranslationsFormAction
 * @package BOModule\Services\Actions
 */
class StandardizedSaveTranslationsFormAction extends AbstractAction
{
    /** @var TranslationFormBuilder */
    private $translationFormBuilder;

    /** @var TranslationHandler */
    private $translationHandler;

    /** @var TranslationManager */
    private $translationManager;

    public function __construct(
        TranslationFormBuilder $translationFormBuilder,
        TranslationHandler $translationHandler,
        TranslationManager $translationManager
    ) {
        $this->translationManager = $translationManager;
        $this->translationFormBuilder = $translationFormBuilder;
        $this->translationHandler = $translationHandler;

        parent::__construct();
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function process()
    {
        $translation_tag = $this->getConfig('translation_tag');
        $redirect_to = $this->getConfig('redirect_to');

        $values = $this->getRequest()->getAll();

        /** @var FormInterface $form */
        $form = $this->translationFormBuilder->build($translation_tag, $values);

        if ($form->isValid()) {
            foreach ($this->translationHandler->getCodes() as $code) {
                if ($form->hasField($code)) {
                    $this->translationManager->saveByCode($code, $form->getValue($code), null, true);
                }
            }

            $this->success('actions.translations.save.result.success');

            return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl(
                'backoffice.' . $redirect_to . '.display'
            ));
        } else {
            $this->failure('actions.translations.save.result.failure');

            return $this->forward(
                'display@backoffice.' . $redirect_to,
                array('form' => $form)
            );
        }
    }
}
