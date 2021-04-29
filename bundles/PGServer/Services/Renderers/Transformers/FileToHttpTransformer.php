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
 * @version   2.0.1
 *
 */

/**
 * Class PGServerServicesRenderersTransformersFileToHttpTransformer
 * @package PGServer\Services\Renderers\Transformers
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

        $path = $response->getPath();

        $filename = pathinfo($path, PATHINFO_BASENAME);

        $newResponse
            ->setHeader('Content-Description', 'File Transfer')
            ->setHeader('Content-Type', $this->mimeTypeHandler->getMimeType($filename))
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Expires', '0')
            ->setHeader('Cache-Control', 'must-revalidate')
            ->setHeader('Pragma', 'public')
            ->setHeader('Content-Length', filesize($path))
            ->setContent(file_get_contents($path))
        ;

        return $newResponse;
    }
}
