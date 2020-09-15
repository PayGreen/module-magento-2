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
 * @version   1.1.0
 */

/**
 * Class PGFrameworkServicesHandlersUploadHandler
 * @package PGFramework\Services
 */
class PGFrameworkServicesHandlersUploadHandler extends PGFrameworkFoundationsAbstractObject
{
    private $files = array();

    /** @var PGFrameworkServicesLogger */
    private $logger;

    public function __construct(PGFrameworkServicesLogger $logger)
    {
        $this->logger = $logger;

        $this->buildFileList();
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    private function buildFileList()
    {
        foreach ($_FILES as $key => $data) {
            $this->files[$key] = array();
            $this->exploreProperty('name', $this->files[$key], $data['name']);
            $this->exploreProperty('type', $this->files[$key], $data['type']);
            $this->exploreProperty('tmp_name', $this->files[$key], $data['tmp_name']);
            $this->exploreProperty('error', $this->files[$key], $data['error']);
            $this->exploreProperty('size', $this->files[$key], $data['size']);
        }

        $this->buildRecursive($this->files);
    }

    private function exploreProperty($property, array &$target, $data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $subdata) {
                if (!array_key_exists($key, $target)) {
                    $target[$key] = array();
                }

                $this->exploreProperty($property, $target[$key], $subdata);
            }
        } else {
            $target[$property] = $data;
        }
    }

    private function buildRecursive(array &$data)
    {
        if ($this->isValidUploadedDataFile($data)) {
            $data = new PGFrameworkComponentsUploadedFile($data);

            $this->logger->info("Uploaded file : {$data->getRealName()}");
        } else {
            foreach ($data as &$val) {
                $this->buildRecursive($val);
            }
        }
    }

    public function getFile($key)
    {
        return $this->searchData($key);
    }

    protected function isValidUploadedDataFile($data)
    {
        if (
            is_array($data) &&
            array_key_exists('name', $data) &&
            array_key_exists('tmp_name', $data) &&
            array_key_exists('type', $data) &&
            array_key_exists('error', $data) &&
            array_key_exists('size', $data)
        ) {
            return true;
        }

        return false;
    }

    private function searchData($key = false, &$data = false)
    {
        if (!$data) {
            $data =& $this->files;
        }

        if ($key === false) {
            return $data;
        }

        $all_keys = explode('.', $key);
        $first_key = array_shift($all_keys);

        if (is_array($data) and isset($data[$first_key])) {
            $data =& $data[$first_key];
        } else {
            return false;
        }

        if (!empty($all_keys)) {
            $key = implode('.', $all_keys);
            return $this->searchData($key, $data);
        } else {
            return $data;
        }
    }
}
