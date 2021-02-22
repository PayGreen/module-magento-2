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
 * @version   1.2.4
 *
 */

use Magento\Sales\Model\Order;

class PGModuleServicesRepositoriesOrderStateRepository extends PGModuleFoundationsAbstractMagentoRepository implements PGDomainInterfacesRepositoriesOrderStateRepositoryInterface
{
    const ENTITY = 'Magento\Sales\Model\Order\Status';
    const RESOURCE = 'Magento\Sales\Model\ResourceModel\Order\Status';

    private $definitions = array();

    public function __construct(array $definitions)
    {
        $this->definitions = $definitions;
    }

    public function wrapEntity($localEntity)
    {
        return new PGModuleEntitiesOrderState($localEntity);
    }

    /**
     * @inheritdoc
     * @throws PGFrameworkExceptionsConfigurationException
     * @throws Exception
     */
    public function create($code, $name, array $metadata = array())
    {
        if (!array_key_exists($code, $this->definitions)) {
            $message = "Code definition not found : '$code'.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        } elseif (!is_array($this->definitions[$code])) {
            $message = "Uncorrectly defined order state : '$code'.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        } elseif (!array_key_exists('create', $this->definitions[$code]) || !$this->definitions[$code]['create']) {
            $message = "This state can not be created : '$code'.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        } elseif (!array_key_exists('source', $this->definitions[$code])) {
            $message = "Target state has no 'source' field : '$code'.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        } elseif (!is_array($this->definitions[$code]['source'])) {
            $message = "Target state 'source' must be an array : '$code'.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        } elseif (!array_key_exists('state', $this->definitions[$code]['source'])) {
            $message = "Target state has no 'state' field : '$code'.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        } elseif (!is_string($this->definitions[$code]['source']['state'])) {
            $message = "Target state 'state' field must be a string : '$code'.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        } elseif (!array_key_exists('status', $this->definitions[$code]['source'])) {
            $message = "Target state has no 'status' field : '$code'.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        } elseif (!is_string($this->definitions[$code]['source']['status'])) {
            $message = "Target state 'status' field must be a string : '$code'.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        }

        $metadata = array();

        if (array_key_exists('metadata', $this->definitions[$code]) and is_array($this->definitions[$code]['metadata'])) {
            $metadata = $this->definitions[$code]['metadata'];
        }

        $visibility = array_key_exists('visibility', $metadata) ? $metadata['visibility'] : false;
        $default = array_key_exists('default', $metadata) ? $metadata['default'] : false;

        /** @var Magento\Sales\Model\Order\Status $localEntity */
        $localEntity = $this->createLocalEntity([
            'status' => $this->definitions[$code]['source']['status'],
            'label' => $name
        ]);

        $this->insertLocalEntity($localEntity);

        $localEntity->assignState($this->definitions[$code]['source']['state'], $default, $visibility);

        $this->updateLocalEntity($localEntity);

        return $this->wrapEntity($localEntity);
    }
}
