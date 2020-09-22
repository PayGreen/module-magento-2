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

class APPbackofficeActionsSaveSettingsAction extends PGServerFoundationsAbstractAction
{
    /** @var PGFormServicesFormBuilder */
    private $formBuilder;

    /** @var PGFrameworkServicesSettings */
    private $settings;

    protected $default = array(
        'success_message' => 'config.result.success'
    );

    public function __construct(
        PGFrameworkServicesNotifier $notifier,
        PGFrameworkServicesLogger $logger,
        PGServerServicesLinker $linker,
        PGFormServicesFormBuilder $formBuilder,
        PGFrameworkServicesSettings $settings
    ) {
        parent::__construct($notifier, $logger, $linker);

        $this->formBuilder = $formBuilder;
        $this->settings = $settings;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function process()
    {
        /** @var PGFormComponentsForm $form */
        $form = $this->formBuilder->build(
            $this->getConfig('form_name'),
            $this->getRequest()->getAll()
        );

        if ($form->isValid()) {
            $this->saveSettings($form);
            $this->setSuccess();
        } else {
            $this->notifyFailure($form);
        }

        return $this->redirect($this->getConfig('redirection'));
    }

    /**
     * @param PGFormComponentsForm $form
     * @throws Exception
     */
    protected function saveSettings(PGFormComponentsForm $form)
    {
        foreach ($form->getValues() as $key => $value) {
            if ($this->settings->isDefined($key)) {
                $value = trim($value);

                if ($value === null) {
                    $this->getLogger()->debug("Remove setting '$key'.", $value);
                    $this->settings->remove($key);
                } else {
                    $this->getLogger()->debug("Define setting '$key'.", $value);
                    $this->settings->set($key, $value);
                }
            }
        }
    }

    protected function notifyFailure(PGFormComponentsForm $form)
    {
        foreach ($form->getErrors() as $error) {
            $this->getNotifier()->add(PGFrameworkServicesNotifier::STATE_FAILURE, $error);
        }
    }
}
