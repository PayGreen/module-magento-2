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
 * @version   2.0.0
 *
 */

/**
 * Class PGFrameworkServicesHandlersSelectHandler
 * @package PGFramework\Services\Handlers
 */
class PGFrameworkServicesHandlersSelectHandler extends PGSystemFoundationsObject
{
    private $selectorNames = array();

    /** @var PGSystemServicesContainer */
    private $container;

    public function __construct(PGSystemServicesContainer $container)
    {
        $this->container = $container;
    }

    public function addSelectorServiceName($serviceName, $selectorName = null)
    {
        if ($selectorName === null) {
            if (preg_match("/^selector\.(?P<name>.+)/", $serviceName, $result)) {
                $selectorName = $result['name'];
            } else {
                throw new Exception(
                    "Unable to automatically determine the selector name with the service name : '$serviceName'."
                );
            }
        }

        $this->selectorNames[$selectorName] = $serviceName;
    }

    /**
     * @param string $name
     * @return PGFrameworkInterfacesSelectorInterface
     * @throws LogicException
     * @throws Exception
     */
    public function getSelector($name)
    {
        if (!array_key_exists($name, $this->selectorNames)) {
            throw new LogicException("Unknown selector name : '$name'.");
        }

        /** @var PGFrameworkInterfacesSelectorInterface $validator */
        $selector = $this->container->get($this->selectorNames[$name]);

        if (! $selector instanceof PGFrameworkInterfacesSelectorInterface) {
            throw new Exception("Selector '$name' must implements PGFrameworkInterfacesSelectorInterface interface.");
        }

        return $selector;
    }

    /**
     * @param string $name
     * @param string $code
     * @return string
     * @throws Exception
     */
    public function getName($name, $code)
    {
        return $this->getSelector($name)->getName($code);
    }

    /**
     * @param string $name
     * @return array
     * @throws Exception
     */
    public function getChoices($name)
    {
        return $this->getSelector($name)->getChoices();
    }

    /**
     * @param string $name
     * @return array
     * @throws Exception
     */
    public function getKeys($name)
    {
        return $this->getSelector($name)->getKeys();
    }
}
