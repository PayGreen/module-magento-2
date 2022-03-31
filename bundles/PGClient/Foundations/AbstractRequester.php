<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.6.1
 *
 */

namespace PGI\Module\PGClient\Foundations;

use PGI\Module\PGClient\Components\Request as RequestComponent;
use PGI\Module\PGClient\Interfaces\RequesterInterface;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGSystem\Components\Bag as BagComponent;
use Exception;

/**
 * Class AbstractRequester
 * @package PGClient\Foundations
 */
abstract class AbstractRequester implements RequesterInterface
{
    const DEFAULT_RESPONSE_COMPONENT = 'PGI\Module\PGClient\Components\Response';

    /** @var callable|null Service to log requests, responses and errors. */
    private $logger = null;

    /** @var BagComponent */
    private $config;

    /** @var Settings */
    private $settings;

    /**
     * AbstractRequester constructor.
     * @param Settings $settings
     * @param null $logger
     * @param array $config
     */
    public function __construct(Settings $settings, $logger = null, $config = array())
    {
        $this->settings = $settings;
        $this->logger = $logger;
        $this->config = new BagComponent($config ? $config : array());
    }

    protected function getConfig($key)
    {
        return $this->config[$key];
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function getSetting($name)
    {
        return $this->settings->get($name);
    }

    /**
     * @inheritdoc
     */
    public function send(RequestComponent $request, $jsonEncodePostFields = true)
    {
        $request->markAsSent();

        return $this->sendRequest($request, $jsonEncodePostFields);
    }

    /**
     * @param RequestComponent $request
     * @return mixed
     * @throw Exception
     */
    abstract public function sendRequest(RequestComponent $request);

    /**
     * @param string $level
     * @param string $message
     * @param mixed $result
     * @param array $details
     */
    protected function log($level, $message, $result, array $details = array())
    {
        if ($this->logger !== null) {
            if (empty($details)) {
                $data = $result;
            } else {
                $data = array(
                    'result' => $result,
                    'details' => $details
                );
            }

            call_user_func(array($this->logger, $level), $message, $data);
        }
    }
}
