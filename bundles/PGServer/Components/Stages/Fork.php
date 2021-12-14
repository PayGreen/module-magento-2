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

namespace PGI\Module\PGServer\Components\Stages;

use PGI\Module\PGServer\Foundations\AbstractResponse;
use PGI\Module\PGServer\Foundations\AbstractStage;
use PGI\Module\PGServer\Services\Factories\StageFactory;
use Exception;

/**
 * Class Fork
 * @package PGServer\Components\Stages
 */
class Fork extends AbstractStage
{
    /** @var AbstractStage[] */
    private $stages = array();

    /** @var StageFactory */
    private $stageFactory;

    private $bin;

    /**
     * @param StageFactory $stageFactory
     */
    public function setStageFactory(StageFactory $stageFactory)
    {
        $this->stageFactory = $stageFactory;
    }

    /**
     * @param AbstractResponse $response
     * @return AbstractStage[]
     * @throws Exception
     */
    public function execute(AbstractResponse $response)
    {
        // Thrashing unused arguments
        $this->bin = $response;

        if (empty($this->stages)) {
            $this->buildStages();
        }

        reset($this->stages);

        return $this->stages;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function follow()
    {
        $follow = strtoupper($this->getConfig('with'));

        if (empty($follow)) {
            $follow = 'RETURN';
        }

        if (!in_array($follow, array('RETURN', 'RESTART', 'CONTINUE'))) {
            throw new Exception("Unknown follow type: '$follow'.");
        }

        return $follow;
    }

    /**
     * @throws Exception
     */
    protected function buildStages()
    {
        $stageDefinitions = $this->getConfig('with');

        if (empty($stageDefinitions)) {
            throw new Exception("Fork stage require sub-stages configuration in 'with' key.");
        }

        foreach ($stageDefinitions as $stageDefinition) {
            $this->stages[] = $this->stageFactory->buildStage($stageDefinition);
        }

        $this->getLogger()->debug("Fork of rendering stages successfully built.");
    }
}
