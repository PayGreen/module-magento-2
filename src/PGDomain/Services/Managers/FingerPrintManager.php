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
 * @version   1.2.5
 *
 */

/**
 * Class PGDomainServicesManagersFingerPrintManager
 *
 * @package PGDomain\Services\Managers
 * @method PGDomainInterfacesRepositoriesFingerPrintRepositoryInterface getRepository()
 */
class PGDomainServicesManagersFingerPrintManager extends PGFrameworkFoundationsAbstractManager
{
    /**
     * @param string $session
     * @return PGDomainInterfacesEntitiesFingerPrintInterface|null
     */
    public function getBySession($session)
    {
        return $this->getRepository()->findBySession($session);
    }

    /**
     * @param string $session
     * @param string $browser
     * @param string $device
     * @param int $pictures
     * @param int $time
     * @return bool
     */
    public function saveNavigationData($session, $browser, $device, $pictures, $time)
    {
        /** @var PGDomainInterfacesEntitiesFingerPrintInterface|null $fingerPrint */
        $fingerPrint = $this->getBySession($session);

        if ($fingerPrint === null) {
            $fingerPrint = $this->getRepository()
                ->create($session, $browser, $device)
                ->setPictures($pictures)
                ->setTime($time)
            ;

            $result = $this->getRepository()->insert($fingerPrint);
        } else {
            $fingerPrint
                ->addPage()
                ->addPictures($pictures)
                ->addTime($time)
            ;

            $result = $this->getRepository()->update($fingerPrint);
        }

        return $result;
    }

    public function delete(PGDomainInterfacesEntitiesFingerPrintInterface $fingerPrint)
    {
        return $this->getRepository()->delete($fingerPrint);
    }
}
