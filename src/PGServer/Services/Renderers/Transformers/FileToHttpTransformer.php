<?php
/**
 * 2014 - 2020 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.2.0
 */

class PGServerServicesRenderersTransformersFileToHttpTransformer
{
    /** @var PGFrameworkServicesHandlersMimeTypeHandler */
    private $mimeTypeHandler;

    /**
     * PGServerServicesResponseTransformersFileToHttpTransformer constructor.
     * @param PGFrameworkServicesHandlersMimeTypeHandler $mimeTypeHandler
     */
    public function __construct(PGFrameworkServicesHandlersMimeTypeHandler $mimeTypeHandler)
    {
        $this->mimeTypeHandler = $mimeTypeHandler;
    }

    /**
     * @param PGServerComponentsResponsesFileResponse $response
     * @return PGServerComponentsResponsesHTTPResponse
     * @throws Exception
     */
    public function process(PGServerComponentsResponsesFileResponse $response)
    {
        $newResponse = new PGServerComponentsResponsesHTTPResponse($response);

        $filename = $response->getPath();

        $newResponse
            ->setHeader('Content-Description', 'File Transfer')
            ->setHeader('Content-Type', 'application/json')
            ->setHeader('Content-Disposition', 'attachment; filename="' . pathinfo($filename, PATHINFO_BASENAME) . '"')
            ->setHeader('Expires', '0')
            ->setHeader('Cache-Control', 'must-revalidate')
            ->setHeader('Pragma', 'public')
            ->setHeader('Content-Length', filesize($filename))
            ->setContent(file_get_contents($filename))
        ;

        return $newResponse;
    }
}
