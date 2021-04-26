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
 * Class PGServerComponentsStagesProcessStage
 * @package PGServer\Components\Stages
 */
class PGServerComponentsStagesProcessStage extends PGServerFoundationsAbstractStage
{
    const STAGE_TYPE = 'Processor';
    const STAGE_METHOD = 'process';

    /**
     * @param PGServerFoundationsAbstractResponse $response
     * @return void
     * @throws Exception
     */
    public function execute(PGServerFoundationsAbstractResponse $response)
    {
        $this->callService($response);

        $this->getLogger()->debug("Processor executed successfully.");
    }
}
