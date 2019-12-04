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
 * @version   0.3.2
 */

namespace Paygreen\Payment\Controller\Adminhtml\Systeminfo;

use Paygreen\Payment\Foundations\AbstractActionAdmin;
use Magento\Framework\Controller\Result\Redirect;
use PGFrameworkServicesPathfinder;

class Reset extends AbstractActionAdmin
{
    public function execute()
    {
        /** @var PGFrameworkServicesPathfinder $pathfinder */
        $pathfinder = $this->getService('pathfinder');

        /** @var Magento\Framework\Message\ManagerInterface $notifier */
        $notifier = $this->getService('magento')->get('Magento\Framework\Message\ManagerInterface');

        $files = array('module.log', 'api.log');

        $file = $this->getRequest()->getParam('file', null);
        $filename = $pathfinder->toAbsolutePath('var', "/$file");

        if (in_array($file, $files) && file_exists($filename)) {
            unlink($filename);
            $notifier->addNoticeMessage("Le fichier ciblé a bien été effacé.");
        } elseif(!in_array($file, $files)) {
            $notifier->addErrorMessage("Le fichier ciblé n'est pas un fichier de log valide.");
        } elseif(!file_exists($filename)) {
            $notifier->addErrorMessage("Le fichier ciblé n'existe pas.");
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/*/index');

        return $resultRedirect;
    }
}

