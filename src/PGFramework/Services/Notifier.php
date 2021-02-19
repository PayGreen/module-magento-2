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
 * @version   1.2.3
 *
 */

class PGFrameworkServicesNotifier
{
    const STATE_SUCCESS = 'SUCCESS';
    const STATE_NOTICE = 'NOTICE';
    const STATE_FAILURE = 'FAILURE';

    const STATE_DEFAULT = self::STATE_SUCCESS;

    const SESSION_KEY = 'paygreen_notices';

    private static $VALID_TYPES = array(self::STATE_SUCCESS, self::STATE_NOTICE, self::STATE_FAILURE);

    /** @var PGFrameworkInterfacesHandlersSessionHandlerInterface */
    private $sessionHandler;

    public function __construct(PGFrameworkInterfacesHandlersSessionHandlerInterface $sessionHandler)
    {
        $this->sessionHandler = $sessionHandler;
    }

    /**
     * @return array
     */
    public function collect()
    {
        $notices = $this->loadNotices();

        $this->sessionHandler->rem(self::SESSION_KEY);

        return $notices;
    }

    public function add($type, $text = null)
    {
        if ($text === null) {
            $text = $type;
            $type = self::STATE_DEFAULT;
        }

        $this->validate($type);

        $notices = $this->loadNotices();

        $notices[] = array(
            'type' => $type,
            'text' => $text
        );

        $this->sessionHandler->set(self::SESSION_KEY, $notices);

        return $this;
    }

    public function count($type = null)
    {
        $nb = 0;

        $notices = $this->loadNotices();

        if ($type === null) {
            $nb = count($notices);
        } else {
            $this->validate($type);

            foreach ($notices as $notice) {
                if ($notice['type'] === $type) {
                    $nb++;
                }
            }
        }

        return $nb;
    }

    protected function loadNotices()
    {
        $notices = array();

        if ($this->sessionHandler->has(self::SESSION_KEY)) {
            $notices = $this->sessionHandler->get(self::SESSION_KEY);
        }

        return $notices;
    }

    protected function validate($type)
    {
        if (!in_array(strtoupper($type), self::$VALID_TYPES)) {
            throw new LogicException("Unrecognized notice type : '$type'.");
        }
    }
}
