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
 * @version   2.0.0
 *
 */

/**
 * Class BOModuleControllersReleaseNoteController
 * @package BOModule\Controllers
 */
class BOModuleControllersReleaseNoteController extends BOModuleFoundationsAbstractBackofficeController
{
    const DEFAULT_NB_NOTES_DISPLAY_BY_RELEASE = 5;

    /** @var PGSystemServicesPathfinder */
    private $pathfinder;

    /** @var PGModuleServicesLogger */
    private $logger;

    public function __construct(
        PGSystemServicesPathfinder $pathfinder,
        PGModuleServicesLogger $logger
    ) {
        $this->pathfinder = $pathfinder;
        $this->logger = $logger;
    }

    public function displayListAction()
    {
        $filepath = $this->pathfinder->toAbsolutePath('data', '/release_note.php');

        $releasesNotes = new PGSystemComponentsStoragesPHPFile($filepath);

        $data = $releasesNotes->getData();

        if (empty($data['releases'])) {
            $this->logger->error('An error occured during releases notes data recovery.');
        } else {
            $this->logger->debug('Releases notes data successfully recovered.');
        }

        return $this->buildTemplateResponse('release-note/block-list')
            ->addData('releases', array_reverse($data['releases']))
            ->addData('nbNotes', self::DEFAULT_NB_NOTES_DISPLAY_BY_RELEASE)
        ;
    }
}
