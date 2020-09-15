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
