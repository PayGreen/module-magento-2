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

/**
 * Class PGDomainServicesRepositoriesFingerPrintRepository
 * @package PGModule\Services\Repositories
 */
class PGDomainServicesRepositoriesFingerPrintRepository extends PGFrameworkFoundationsAbstractRepositoryDatabase implements PGDomainInterfacesRepositoriesFingerPrintRepositoryInterface
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function findBySession($session)
    {
        $session = $this->getRequester()->quote($session);

        return $this->findOneEntity("`session` = '$session' ORDER BY created_at DESC");
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function create($session, $browser, $device)
    {
        $createdAt = new DateTime();

        /** @var PGDomainInterfacesEntitiesFingerPrintInterface $result */
        $result = $this->wrapEntity(array(
            'session' => $session,
            'browser' => $browser,
            'device' => $device,
            'pages' => 1,
            'created_at' => $createdAt->getTimestamp()

        ));

        return $result;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function insert(PGDomainInterfacesEntitiesFingerPrintInterface $fingerprint)
    {
        return $this->insertEntity($fingerprint);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function update(PGDomainInterfacesEntitiesFingerPrintInterface $fingerprint)
    {
        return $this->updateEntity($fingerprint);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function delete(PGDomainInterfacesEntitiesFingerPrintInterface $fingerprint)
    {
        return $this->deleteEntity($fingerprint);
    }
}
