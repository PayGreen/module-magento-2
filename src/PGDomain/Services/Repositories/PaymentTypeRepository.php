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
 */

/**
 * Class PGFrameworkServicesRepositoriesPaymentTypeRepository
 *
 * @package PGDomain\Services\Repositories
 * @method PGDomainEntitiesPaymentType[] wrapList(array $rawEntities)
 */
class PGDomainServicesRepositoriesPaymentTypeRepository extends PGFrameworkFoundationsAbstractRepositoryPaygreen
{
    /**
     * @inheritdoc
     */
    public function getModelClassName()
    {
        return 'PGDomainEntitiesPaymentType';
    }

    /**
     * @return PGDomainEntitiesPaymentType[]
     */
    public function findAll()
    {
        /** @var PGFrameworkServicesHandlersCacheHandler $cacheHandler */
        $cacheHandler = $this->getService('handler.cache');

        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        $rawPaymentTypes = $cacheHandler->loadEntry('payment-types');

        if ($rawPaymentTypes === null) {
            try {
                /** @var PGClientEntitiesResponse $response */
                $response = $this->getApiFacade()->paymentTypes();

                $rawPaymentTypes = (array) $response->data;

                if (!empty($rawPaymentTypes)) {
                    $cacheHandler->saveEntry('payment-types', $rawPaymentTypes);
                }
            } catch (Exception $exception) {
                $logger->alert("Error when importing payment types : " . $exception->getMessage(), $exception);

                $rawPaymentTypes = array();
            }
        }

        return $this->wrapEntities($rawPaymentTypes);
    }
}
