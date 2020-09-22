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
 * @version   1.1.1
 */

class PGFrameworkComponentsUploadedFile
{
    private $realName;

    private $temporaryName;

    private $type;

    private $error;

    private $size;

    public function __construct(array $data)
    {
        if (
            !array_key_exists('name', $data) ||
            !array_key_exists('tmp_name', $data) ||
            !array_key_exists('type', $data) ||
            !array_key_exists('error', $data) ||
            !array_key_exists('size', $data)
        ) {
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
