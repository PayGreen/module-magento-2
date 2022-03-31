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

namespace PGI\Module\BOModule\Services\Views;

use PGI\Module\PGSystem\Services\Pathfinder;
use PGI\Module\PGView\Services\View;
use Exception;

/**
 * Class SystemPathsView
 * @package BOModule\Services\Views
 */
class SystemPathsView extends View
{
    /** @var Pathfinder */
    private $pathfinder;

    public function __construct(Pathfinder $pathfinder)
    {
        $this->pathfinder = $pathfinder;

        $this->setTemplate('system/block-informations-paths');
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getData()
    {
        $data = parent::getData();

        $entries = array();

        foreach ($this->pathfinder->getBases() as $base) {
            $path = $this->pathfinder->getBasePath($base);

            $entries[] = array(
                'name' => $base,
                'path' => $path,
                'exists' => is_dir($path),
                'writable' => is_dir($path) ? is_writable($path) : null
            );
        }


        $data['entries'] = $entries;

        return $data;
    }
}
