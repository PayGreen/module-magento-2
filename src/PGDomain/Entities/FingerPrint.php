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
 * @version   1.2.1
 *
 */

class PGDomainEntitiesFingerPrint extends PGFrameworkFoundationsAbstractEntityPersisted implements PGDomainInterfacesEntitiesFingerPrintInterface
{
    /**
     * @inheritDoc
     */
    public function getSession()
    {
        return $this->get('session');
    }

    public function getBrowser()
    {
        return $this->get('browser');
    }

    public function getDevice()
    {
        return $this->get('device');
    }

    /**
     * @inheritDoc
     */
    public function getPages()
    {
        return $this->get('pages');
    }

    public function addPage()
    {
        return $this->set('pages', $this->getPages() + 1);
    }

    /**
     * @inheritDoc
     */
    public function getPictures()
    {
        return $this->get('pictures');
    }

    public function setPictures($pictures)
    {
        return $this->set('pictures', $pictures);
    }

    public function addPictures($pictures)
    {
        return $this->set('pictures', $this->getPictures() + $pictures);
    }

    public function getTime()
    {
        return $this->get('time');
    }

    public function setTime($time)
    {
        return $this->set('time', $time);
    }

    public function addTime($time)
    {
        return $this->set('time', $this->getTime() + $time);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return new DateTime($this->get('created_at'));
    }
}
