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
 * @version   1.2.0
 */

class PGFormServicesFormatterBuilder
{
    private $formatterNames = array();

    /** @var PGFrameworkContainer */
    private $container;

    public function __construct(PGFrameworkContainer $container)
    {
        $this->container = $container;
    }

    public function addFormatterServiceName($serviceName, $formatterName = null)
    {
        if ($formatterName === null) {
            if (preg_match("/^formatter\.(?P<name>.+)/", $serviceName, $result)) {
                $formatterName = $result['name'];
            } else {
                throw new Exception("Unable to automatically determine the formatter name with the service name : '$serviceName'.");
            }
        }

        $this->formatterNames[$formatterName] = $serviceName;
    }

    /**
     * @param string $name
     * @return PGFormInterfacesFormatterInterface
     * @throws LogicException
     * @throws Exception
     */
    public function getFormatter($name)
    {
        if (!array_key_exists($name, $this->formatterNames)) {
            throw new LogicException("Unknown formatter name : '$name'.");
        }

        /** @var PGFormInterfacesFormatterInterface $validator */
        $formatter = $this->container->get($this->formatterNames[$name]);

        if (! $formatter instanceof PGFormInterfacesFormatterInterface) {
            throw new Exception("Formatter '$name' must implements PGFormInterfacesFormatterInterface interface.");
        }

        return $formatter;
    }
}
