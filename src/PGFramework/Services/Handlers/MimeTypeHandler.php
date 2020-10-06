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

class PGFrameworkServicesHandlersMimeTypeHandler
{
    private $mime_types = array();

    /** @var PGFrameworkServicesLogger */
    private $logger;

    public function __construct(PGFrameworkServicesLogger $logger, array $mime_types)
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
            $this->logger->warning("Unable to find the mime type associated with this extension : $ext.");
        }

        return $mime_type;
    }
}
