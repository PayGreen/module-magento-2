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
 * @version   2.5.0
 *
 */

namespace PGI\Module\PGModule\Services\OutputBuilders;

use PGI\Module\PGModule\Components\Output as OutputComponent;
use PGI\Module\PGModule\Foundations\AbstractOutputBuilder;
use PGI\Module\PGServer\Components\Resources\ScriptFile as ScriptFileResourceComponent;
use PGI\Module\PGServer\Components\Resources\StyleFile as StyleFileResourceComponent;
use Exception;

/**
 * Class StaticFilesOutputBuilder
 * @package PGModule\Services\OutputBuilders
 */
class StaticFilesOutputBuilder extends AbstractOutputBuilder
{
    /**
     * @inheritDoc
     */
    public function build(array $data = array())
    {
        if (!$this->hasConfig('js') && !$this->hasConfig('css')) {
            throw new Exception("StaticFiles output builder require at least 'js' or 'css' config keys.");
        }

        $output = new OutputComponent();

        if ($this->hasConfig('js')) {
            foreach ($this->getConfig('js') as $path) {
                $output->addResource(new ScriptFileResourceComponent($path));
            }
        }

        if ($this->hasConfig('css')) {
            foreach ($this->getConfig('css') as $path) {
                $output->addResource(new StyleFileResourceComponent($path));
            }
        }

        return $output;
    }
}
