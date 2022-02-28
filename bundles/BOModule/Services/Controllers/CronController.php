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

namespace PGI\Module\BOModule\Services\Controllers;

use PGI\Module\BOModule\Foundations\Controllers\AbstractBackofficeController;
use PGI\Module\PGCron\Services\Scheduler;
use PGI\Module\PGIntl\Components\Translation;
use PGI\Module\PGServer\Components\Responses\Template as TemplateResponseComponent;
use PGI\Module\PGServer\Components\Responses\Redirection;
use DateTime;
use Exception;

/**
 * Class CronController
 * @package BOModule\Services\Controllers
 */
class CronController extends AbstractBackofficeController
{
    /** @var Scheduler */
    private $scheduler;

    public function __construct(Scheduler $scheduler)
    {
        $this->scheduler = $scheduler;
    }

    /**
     * @return Redirection
     * @throws Exception
     */
    public function runSchedulerAction()
    {
        $this->scheduler->run();

        $this->success('actions.cron.run.result.success');

        $url = $this->getLinkHandler()->buildBackOfficeUrl('backoffice.cron.display');

        return $this->redirect($url);
    }

    /**
     * @return TemplateResponseComponent
     * @throws Exception
     */
    public function displayControlAction()
    {
        return $this->buildTemplateResponse('cron/block-control')
            ->addData('cron_activation', $this->getSettings()->get('cron_activation'))
            ->addData('cron_activation_mode', $this->getSettings()->get('cron_activation_mode'))
        ;
    }

    /**
     * @return TemplateResponseComponent
     * @throws Exception
     */
    public function displayTasksAction()
    {
        $checkUp = array();
        $now = time();

        foreach($this->scheduler->checkUp() as $name => $time) {
            if ($time === null) {
                $checkUp[$name] = 'blocks.cron.tasks.status.never';
            } elseif ($time <= $now) {
                $checkUp[$name] = 'blocks.cron.tasks.status.now';
            } else {
                $dt = new DateTime('@' . $time);
                $checkUp[$name] = new Translation(
                    'blocks.cron.tasks.status.later',
                    array(
                        'date' => $dt->format('j M Y'),
                        'time' => $dt->format('G')
                    )
                );
            }
        }

        return $this->buildTemplateResponse('cron/block-tasks')
            ->addData('tasks', $checkUp)
        ;
    }
}
