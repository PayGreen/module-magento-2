<?php
/**
 * 2014 - 2019 Watt Is It
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
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.2
 */

namespace Paygreen\Payment\Block;

use Paygreen\Payment\Foundations\AbstractTemplate;

class DisplayMessage extends AbstractTemplate
{
    public function getTitle()
    {
        return $this->getData('title');
    }

    public function hasMessage()
    {
        return ($this->hasData('message') && $this->getMessage());
    }

    public function getMessage()
    {
        return $this->getData('message');
    }

    public function hasErrors()
    {
        return ($this->hasData('errors') && is_array($this->getErrors()));
    }

    public function getErrors()
    {
        return $this->getData('errors');
    }

    public function hasRedirection()
    {
        return ($this->hasData('url') && is_array($this->getData('url')));
    }

    public function getRedirection($key)
    {
        $redirection = $this->getData('url');

        return $redirection[$key];
    }

    public function hasExceptions()
    {
        return $this->hasData('exceptions');
    }

    public function getExceptions()
    {
        return $this->getData('exceptions');
    }
}
