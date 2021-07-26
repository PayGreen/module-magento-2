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

namespace PGI\Module\PGFramework\Components;

use Exception;

/**
 * Class UploadedFile
 * @package PGFramework\Components
 */
class UploadedFile
{
    private $realName;

    private $temporaryName;

    private $type;

    private $error;

    private $size;

    public function __construct(array $data)
    {
        $nameExist = array_key_exists('name', $data);
        $tmpNameExist = array_key_exists('tmp_name', $data);
        $typeExist = array_key_exists('type', $data);
        $errorExist = array_key_exists('error', $data);
        $sizeExist = array_key_exists('size', $data);

        if (!$nameExist || !$tmpNameExist || !$typeExist || !$errorExist || !$sizeExist) {
            throw new Exception("Provided array is not a valid uploaded file descriptor.");
        }

        $this->realName = $data['name'];
        $this->temporaryName = $data['tmp_name'];
        $this->type = $data['type'];
        $this->error = $data['error'];
        $this->size = $data['size'];
    }

    /**
     * @return mixed
     */
    public function getRealName()
    {
        return $this->realName;
    }

    /**
     * @return mixed
     */
    public function getTemporaryName()
    {
        return $this->temporaryName;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function hasError()
    {
        return ($this->error > 0);
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }
}
