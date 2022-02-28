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

namespace PGI\Module\PGForm\Services\Builders;

use PGI\Module\PGForm\Interfaces\ValidatorInterface;
use PGI\Module\PGFramework\Components\Aggregator as AggregatorComponent;
use PGI\Module\PGSystem\Tools\Collection as CollectionTool;

/**
 * Class Validator
 * @package PGForm\Services\Builders
 */
class ValidatorBuilder
{
    /** @var AggregatorComponent */
    private $validatorAggregator;

    public function __construct(AggregatorComponent $validatorAggregator)
    {
        $this->validatorAggregator = $validatorAggregator;
    }

    public function build($name, $config)
    {
        /** @var ValidatorInterface $validator */
        $validator = $this->validatorAggregator->getService($name);

        $isSequentialArray = (is_array($config) && CollectionTool::isSequential($config));
        if (!is_array($config) || ($isSequentialArray)) {
            $config = array(
                'default' => $config
            );
        }

        $validator->setConfig($config);

        return $validator;
    }
}
