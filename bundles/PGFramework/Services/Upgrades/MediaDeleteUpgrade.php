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

namespace PGI\Module\PGFramework\Services\Upgrades;

use PGI\Module\PGFramework\Services\Handlers\PictureHandler;
use PGI\Module\PGModule\Components\UpgradeStage as UpgradeComponent;
use PGI\Module\PGModule\Interfaces\UpgradeInterface;

/**
 * Class MediaDeleteUpgrade
 * @package PGFramework\Services\Upgrades
 */
class MediaDeleteUpgrade implements UpgradeInterface
{
    /** @var PictureHandler */
    private $pictureHandler;

    public function __construct(PictureHandler $pictureHandler)
    {
        $this->pictureHandler = $pictureHandler;
    }

    /**
     * @inheritDoc
     */
    public function apply(UpgradeComponent $upgradeStage)
    {
        $filename = $upgradeStage->getConfig('media');

        if ($this->pictureHandler->isStored($filename)) {
            $this->pictureHandler->removeFromStore($filename);
        }
    }
}
