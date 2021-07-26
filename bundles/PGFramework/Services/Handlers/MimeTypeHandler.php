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
 * @version   2.2.0
 *
 */

namespace PGI\Module\PGFramework\Services\Handlers;

use PGI\Module\PGModule\Services\Logger;

/**
 * Class MimeTypeHandler
 * @package PGFramework\Services\Handlers
 */
class MimeTypeHandler
{
    private $mime_types = array();

    /** @var Logger */
    private $logger;

    public function __construct(Logger $logger, array $mime_types)
    {
        $this->logger = $logger;
        $this->mime_types = $mime_types;
    }

    public function getMimeType($filename)
    {
        $mime_type = 'application/octet-stream';

        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if (array_key_exists($ext, $this->mime_types)) {
            $mime_type = $this->mime_types[$ext];
        } else {
            $this->logger->warning("Unable to find the mime type associated with this product : $ext.");
        }

        return $mime_type;
    }
}
