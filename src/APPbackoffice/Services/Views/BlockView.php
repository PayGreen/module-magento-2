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
 * @version   1.2.4
 *
 */

class APPbackofficeServicesViewsBlockView extends PGViewServicesView
{
    /** @var PGViewServicesHandlersBlockHandler */
    private $blockHandler;

    public function __construct()
    {
        $this->setTemplate('block-blocks');
    }

    /**
     * @param PGViewServicesHandlersBlockHandler $blockHandler
     */
    public function setBlockHandler(PGViewServicesHandlersBlockHandler $blockHandler)
    {
        $this->blockHandler = $blockHandler;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getData()
    {
        $page = $this->get('page');

        if (!$page) {
            throw new Exception("BlocksView require 'page' attribut.");
        }

        return array(
            'blocks' => $this->blockHandler->buildBlocks($page)
        );
    }
}
