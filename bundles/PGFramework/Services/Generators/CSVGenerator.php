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
 * @version   2.5.1
 *
 */

namespace PGI\Module\PGFramework\Services\Generators;

/**
 * Class CSVGenerator
 * @package PGFramework\Services\Generators
 */
class CSVGenerator
{
    /**
     * @param array $data
     * @param array $headerColumns
     * @return false|resource
     */
    public function generateCSVFile($data, $headerColumns = array())
    {
        $workArray = array_values($data);

        ob_start();

        $file = tmpfile();
        fputcsv($file, $headerColumns);

        foreach ($workArray as $row) {
            fputcsv($file, $row);
        }

        return $file;
    }

    /**
     * @param array $data
     * @param array $headerColumns
     */
    public function generateCSV($data, $headerColumns = array())
    {
        $workArray = array_values($data);
        ob_start();
        $file = fopen("php://output", 'w');
        fputcsv($file, $headerColumns);
        foreach ($workArray as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
        return ob_get_clean();
    }
}
