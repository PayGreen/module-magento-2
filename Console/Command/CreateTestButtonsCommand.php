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

namespace Paygreen\Payment\Console\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PGFrameworkServicesLogger;

require_once PAYGREEN_BOOTSTRAP_SRC;

class CreateTestButtonsCommand extends Command
{
    protected $customerHelper;

    protected function getService($name)
    {
        return \PGFrameworkContainer::getInstance()->get($name);
    }

    protected function configure()
    {
        $this
            ->setName('paygreen:create:buttons')
            ->setDescription('Create test buttons.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        try {
            /** @var \PGDomainServicesManagersButtonManager $buttonManager */
            $buttonManager = $this->getService('manager.button');

            $button = $buttonManager->getNew()
                ->setLabel('Payer en 3 fois')
                ->setPosition(2)
                ->setImageHeight(60)
                ->setDisplayType('DEFAULT')
                ->setPaymentNumber(3)
                ->setPaymentMode('XTIME')
                ->setFirstPaymentPart(50)
                ->setDiscount(0);

            $buttonManager->save($button);

            $button = $buttonManager->getNew()
                ->setLabel('Payer à la livraison')
                ->setPosition(3)
                ->setImageHeight(60)
                ->setDisplayType('DEFAULT')
                ->setPaymentNumber(1)
                ->setPaymentMode('TOKENIZE')
                ->setDiscount(0);

            $buttonManager->save($button);

            $button = $buttonManager->getNew()
                ->setLabel("Abonnement à l'année")
                ->setPosition(4)
                ->setImageHeight(60)
                ->setDisplayType('DEFAULT')
                ->setPaymentNumber(12)
                ->setPaymentMode('RECURRING')
                ->setDiscount(0);

            $buttonManager->save($button);
        } catch (Exception $exception) {
            $logger->critical("Error during execute 'paygreen:create:buttons' command.", $exception);

            throw $exception;
        }
    }
}