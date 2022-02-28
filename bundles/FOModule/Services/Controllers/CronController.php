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
 * @version   2.6.0
 *
 */

namespace PGI\Module\FOModule\Services\Controllers;

use PGI\Module\BOModule\Foundations\Controllers\AbstractBackofficeController;
use PGI\Module\PGCron\Services\Scheduler;
use Exception;
use PGI\Module\PGServer\Components\Responses\HTTP;
use PGI\Module\PGServer\Components\Responses\PaygreenModule;
use PGI\Module\PGServer\Exceptions\HTTPUnauthorized;
use PGI\Module\PGServer\Foundations\AbstractResponse;

/**
 * Class CronController
 * @package BOModule\Services\Controllers
 */
class CronController extends AbstractBackofficeController
{
    private static $ALLOWED_ACTIVATION_MODES = array('URL', 'AJAX');

    /** @var Scheduler */
    private $scheduler;

    public function __construct(Scheduler $scheduler)
    {
        $this->scheduler = $scheduler;
    }

    /**
     * @return AbstractResponse
     * @throws Exception
     */
    public function runSchedulerAction()
    {
        $mode = $this->getSettings()->get('cron_activation_mode');

        if (!in_array($mode, self::$ALLOWED_ACTIVATION_MODES)) {
            throw new HTTPUnauthorized("Public activation of the scheduler is not allowed.");
        }

        $this->scheduler->run();

        if ($mode === 'URL') {
            $response = new HTTP($this->getRequest());
        } elseif ($mode === 'AJAX') {
            $response = new PaygreenModule($this->getRequest());
            $response->validate();
        } else {
            throw new Exception("Unknown cron activation mode : '$mode'.");
        }

        return $response;
    }
}
