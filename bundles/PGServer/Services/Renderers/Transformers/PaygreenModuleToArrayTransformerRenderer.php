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
 * @version   2.5.2
 *
 */

namespace PGI\Module\PGServer\Services\Renderers\Transformers;

use PGI\Module\PGFramework\Services\Notifier;
use PGI\Module\PGServer\Components\Responses\Collection as CollectionResponseComponent;
use PGI\Module\PGServer\Components\Responses\PaygreenModule as PaygreenModuleResponseComponent;
use Exception;

/**
 * Class PaygreenModuleToArrayTransformerRenderer
 * @package PGServer\Services\Renderers\Transformers
 */
class PaygreenModuleToArrayTransformerRenderer
{
    /** @var Notifier */
    private $notifier;

    public function __construct(Notifier $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * @param PaygreenModuleResponseComponent $response
     * @return CollectionResponseComponent
     * @throws Exception
     */
    public function process(PaygreenModuleResponseComponent $response)
    {
        $newResponse = new CollectionResponseComponent($response);

        $newResponse->setData(array(
            'success' => $response->isSuccess(),
            'data' => $response->getData(),
            'notices' => $this->notifier->collect()
        ));

        return $newResponse;
    }
}
