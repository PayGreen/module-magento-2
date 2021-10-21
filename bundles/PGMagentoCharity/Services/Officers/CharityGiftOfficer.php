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
 * @version   2.4.0
 *
 */

namespace PGI\Module\PGMagentoCharity\Services\Officers;

use Exception;
use Magento\Framework\App\State as LocalAppState;
use Magento\Catalog\Api\ProductRepositoryInterface as LocalProductRepository;
use Magento\Catalog\Api\Data\ProductInterfaceFactory as LocalProductFactory;
use Magento\Catalog\Model\Product\Attribute\Source\Status as LocalProductStatus;
use Magento\Catalog\Model\Product as LocalProduct;
use Magento\Store\Model\Group as LocalShop;
use PGI\Module\PGCharity\Interfaces\Officers\CharityGiftOfficerInterface;
use PGI\Module\PGFramework\Foundations\AbstractService;
use PGI\Module\PGModule\Entities\Setting;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGShop\Interfaces\Entities\ProductEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\ShopEntityInterface;
use PGI\Module\PGShop\Services\Managers\ProductManager;

/**
 * Class ShopOfficer
 * @package PGMagento\Services\Officers
 */
class CharityGiftOfficer extends AbstractService implements CharityGiftOfficerInterface
{
    /** @var LocalProductFactory */
    private $localProductFactory;

    /** @var LocalProductRepository */
    private $localProductRepository;

    /** @var LocalAppState */
    private $localAppState;

    /** @var ProductManager */
    private $productManager;

    /** @var Settings */
    private $settings;

    /** @var CharityGiftPictureOfficer */
    private $giftPictureOfficer;

    /** @var CharityGiftTranslationOfficer */
    private $giftTranslationOfficer;

    /** @var CharityGiftStockOfficer */
    private $giftStockOfficer;

    public function __construct(
        LocalProductFactory $localProductFactory,
        LocalProductRepository $localProductRepository,
        LocalAppState $localAppState,
        ProductManager $productManager,
        Settings $settings,
        CharityGiftPictureOfficer $giftPictureOfficer,
        CharityGiftTranslationOfficer $giftTranslationOfficer,
        CharityGiftStockOfficer $giftStockOfficer
    ) {
        $this->localProductFactory = $localProductFactory;
        $this->localProductRepository = $localProductRepository;
        $this->localAppState = $localAppState;
        $this->productManager = $productManager;
        $this->settings = $settings;
        $this->giftPictureOfficer = $giftPictureOfficer;
        $this->giftTranslationOfficer = $giftTranslationOfficer;
        $this->giftStockOfficer = $giftStockOfficer;
    }

    /**
     * @inheritDoc
     */
    public function isValid($gift, ShopEntityInterface $shop)
    {
        $isAssociatedWithShop = $this->isAssociatedWithShop($gift, $shop);
        $isActive = $this->isActive($gift);
        $isVirtual = $this->isVirtual($gift);
        $isStockAvailable = $this->giftStockOfficer->isValid($gift);
        $isNotEligibleToTaxes = $this->isNotEligibleToTaxes($gift);

        return ($isAssociatedWithShop && $isActive && $isVirtual && $isStockAvailable && $isNotEligibleToTaxes);
    }

    /**
     * @inheritDoc
     */
    public function validate($gift, ShopEntityInterface $shop)
    {
        /** @var LocalProduct $localProduct */
        $localProduct = $gift->getLocalEntity();

        /** @var LocalShop $localShop */
        $localShop = $shop->getLocalEntity();

        if (!$this->isAssociatedWithShop($gift, $shop)) {
            $ids = $localProduct->getWebsiteIds();
            $ids[] = $localShop->getWebsite()->getId();
            $localProduct->setWebsiteIds($ids);
            $this->log()->info("Successfully associate gift with current shop.");
        }

        if (!$this->isActive($gift)) {
            $localProduct->setStatus(LocalProductStatus::STATUS_ENABLED);
            $this->log()->info("Successfully activate gift.");
        }

        if (!$this->isVirtual($gift)) {
            $localProduct->setTypeId('virtual');
            $this->log()->info("Successfully set gift as virtual product.");
        }

        if (!$this->giftStockOfficer->isValid($gift)) {
            $this->giftStockOfficer->validate($gift);
        }

        if (!$this->isNotEligibleToTaxes($gift)) {
            $localProduct->setTaxClassId(0);
            $this->log()->info("Successfully deactivate gift tax management.");
        }

        $this->saveLocalProduct($localProduct);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function disable($gift, ShopEntityInterface $shop)
    {
        /** @var LocalProduct $localProduct */
        $localProduct = $gift->getLocalEntity();

        $localProduct->setStatus(LocalProductStatus::STATUS_DISABLED);

        $this->saveLocalProduct($localProduct);
    }

    /**
     * @inheritDoc
     */
    public function create(ShopEntityInterface $shop)
    {
        /** @var ProductEntityInterface|null $product */
        $product = null;

        /** @var LocalShop $localShop */
        $localShop = $shop->getLocalEntity();

        try {
            /** @var LocalProduct $localProduct */
            $localProduct = $this->localProductFactory->create();

            $localProduct->setName('Charity Gift');
            $localProduct->setTypeId('virtual');
            $localProduct->setAttributeSetId(4);
            $localProduct->setSku($this->getConfig('gift_reference'));
            $localProduct->setWebsiteIds([$localShop->getWebsite()->getId()]);
            $localProduct->setVisibility(4);
            $localProduct->setStatus(LocalProductStatus::STATUS_ENABLED);
            $localProduct->setPrice(0);
            $localProduct->setTaxClassId(0);

            $this->giftPictureOfficer->install($localProduct);

            /** @var LocalProduct $localProduct */
            $localProduct = $this->saveLocalProduct($localProduct);

            $this->giftTranslationOfficer->installTranslations($shop, $localProduct->getId());

            $this->giftStockOfficer->configure($localProduct);

            /** @var ProductEntityInterface $product */
            $product = $this->productManager->getRepository()->wrapEntity($localProduct);

            $this->saveGiftPrimary($product->id());

            $this->log()->notice("Charity Gift product successfully created for shop #{$shop->id()}.");
        } catch (Exception $exception) {
            $this->log()->critical("An error occurred during local product creation.", $exception);

            throw $exception;
        }

        return $product;
    }

    protected function saveLocalProduct(LocalProduct $localProduct)
    {
        return $this->localAppState->emulateAreaCode(
            'adminhtml',
            function (LocalProductRepository $localProductRepository, LocalProduct $localProduct) {
                return $localProductRepository->save($localProduct);
            },
            [$this->localProductRepository, $localProduct]
        );
    }

    protected function saveGiftPrimary($id)
    {
        $this->settings->set($this->getConfig('gift_primary_setting'), $id);
    }

    /**
     * @inheritDoc
     */
    public function getPrimary(ShopEntityInterface $shop)
    {
        /** @var Setting|null $setting */
        $id = $this->settings->get($this->getConfig('gift_primary_setting'));

        if ($id === null) {
            $localProduct = $this->localProductRepository->get($this->getConfig('gift_reference'));

            if ($localProduct === null) {
                /** @var ProductEntityInterface $product */
                $product = $this->productManager->getRepository()->wrapEntity($localProduct);

                $this->saveGiftPrimary($product->id());

                $id = (int) $product->id();
            } else {
                $this->log()->debug("Unable to retrieve '{$this->getConfig('gift_primary_setting')}' setting.");
            }
        }

        return $id;
    }

    /**
     * @param ProductEntityInterface $gift
     * @return bool
     */
    protected function isActive(ProductEntityInterface $gift)
    {
        /** @var LocalProduct $localProduct */
        $localProduct = $gift->getLocalEntity();

        $test = ((int) $localProduct->getStatus() === LocalProductStatus::STATUS_ENABLED);

        if (!$test) {
            $this->log()->warning("Inactivation detected for Charity Gift #{$gift->id()}.");
        }

        return $test;
    }

    /**
     * @param ProductEntityInterface $gift
     * @return bool
     */
    protected function isVirtual(ProductEntityInterface $gift)
    {
        /** @var LocalProduct $localProduct */
        $localProduct = $gift->getLocalEntity();

        $test = ($localProduct->getTypeId() === 'virtual');

        if (!$test) {
            $this->log()->warning("Current Charity Gift #{$gift->id()} is not virtual product.");
        }

        return $test;
    }

    /**
     * @param ProductEntityInterface $gift
     * @return bool
     */
    protected function isNotEligibleToTaxes(ProductEntityInterface $gift)
    {
        /** @var LocalProduct $localProduct */
        $localProduct = $gift->getLocalEntity();

        $test = ((int) $localProduct->getTaxClassId() === 0);

        if (!$test) {
            $this->log()->warning("Invalid tax detected for Charity Gift #{$gift->id()}.");
        }

        return $test;
    }

    /**
     * @param ProductEntityInterface $gift
     * @param ShopEntityInterface $shop
     * @return bool
     */
    protected function isAssociatedWithShop(ProductEntityInterface $gift, ShopEntityInterface $shop)
    {
        /** @var LocalProduct $localProduct */
        $localProduct = $gift->getLocalEntity();

        /** @var LocalShop $localShop */
        $localShop = $shop->getLocalEntity();

        $test = in_array($localShop->getWebsite()->getId(), $localProduct->getWebsiteIds());

        if (!$test) {
            $this->log()->warning("Invalid shop detected for Charity Gift #{$gift->id()}.");
        }

        return $test;
    }
}
