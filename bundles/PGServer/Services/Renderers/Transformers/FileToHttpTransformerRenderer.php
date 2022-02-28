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

namespace PGI\Module\PGServer\Services\Renderers\Transformers;

use PGI\Module\PGFramework\Services\Handlers\MimeTypeHandler;
use PGI\Module\PGServer\Components\Responses\File as FileResponseComponent;
use PGI\Module\PGServer\Components\Responses\HTTP as HTTPResponseComponent;
use Exception;

/**
 * Class FileToHttpTransformerRenderer
 * @package PGServer\Services\Renderers\Transformers
 */
class FileToHttpTransformerRenderer
{
    /** @var MimeTypeHandler */
    private $mimeTypeHandler;

    /**
     * PGServerServicesResponseTransformersFileToHttpTransformer constructor.
     * @param MimeTypeHandler $mimeTypeHandler
     */
    public function __construct(MimeTypeHandler $mimeTypeHandler)
    {
        $this->mimeTypeHandler = $mimeTypeHandler;
    }

    /**
     * @param FileResponseComponent $response
     * @return HTTPResponseComponent
     * @throws Exception
     */
    public function process(FileResponseComponent $response)
    {
        $newResponse = new HTTPResponseComponent($response);

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
