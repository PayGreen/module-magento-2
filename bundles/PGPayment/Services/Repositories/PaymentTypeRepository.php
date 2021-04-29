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
 * @version   2.0.1
 *
 */

/**
 * Class PGPaymentServicesRepositoriesPaymentTypeRepository
 *
 * @package PGPayment\Services\Repositories
 * @method PGPaymentEntitiesPaymentType[] wrapList(array $rawEntities)
 */
class PGPaymentServicesRepositoriesPaymentTypeRepository extends PGDatabaseFoundationsRepositoryPaygreen
{
    /**
     * @inheritdoc
     */
    public function getModelClassName()
    {
        return 'PGPaymentEntitiesPaymentType';
    }

    /**
     * @return PGPaymentEntitiesPaymentType[]
     */
    public function findAll()
    {
        /** @var PGFrameworkServicesHandlersCacheHandler $cacheHandler */
        $cacheHandler = $this->getService('handler.cache');

        /** @var PGModuleServicesLogger $logger */
        $logger = $this->getService('logger');

        $rawPaymentTypes = $cacheHandler->loadEntry('payment-types');

        if ($rawPaymentTypes === null) {
            try {
                /** @var APIPaymentComponentsResponse $response */
                $response = $this->getApiFacade()->paymentTypes();

                $rawPaymentTypes = (array) $response->data;

                if (!empty($rawPaymentTypes)) {
                    $cacheHandler->saveEntry('payment-types', $rawPaymentTypes);
                }
            } catch (Exception $exception) {
                $logger->alert("Error when importing payment methods: " . $exception->getMessage(), $exception);

                $rawPaymentTypes = array();
            }
        }

        return $this->wrapEntities($rawPaymentTypes);
    }
}