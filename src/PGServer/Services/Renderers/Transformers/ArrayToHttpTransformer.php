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
 * @version   1.2.5
 *
 */

class PGServerServicesRenderersTransformersArrayToHttpTransformer
{
    /**
     * @param PGServerComponentsResponsesArrayResponse $response
     * @return PGServerComponentsResponsesHTTPResponse
     * @throws Exception
     */
    public function process(PGServerComponentsResponsesArrayResponse $response)
    {
        $newResponse = new PGServerComponentsResponsesHTTPResponse($response);

        $newResponse
            ->setHeader('Content-Type', 'application/json')
            ->setHeader('Expires', '0')
            ->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT')
            ->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0')
            ->setHeader('Pragma', 'no-cache')
            ->setContent(json_encode($response->getData()))
        ;

        return $newResponse;
    }
}
