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
 * @version   2.5.0
 *
 */

namespace PGI\Module\PGMagentoCharity\Services\Officers;

use Exception;
use Magento\Framework\App\Config\ScopeConfigInterface as LocalScopeConfig;
use Magento\Catalog\Api\Data\ProductInterfaceFactory as LocalProductFactory;
use Magento\Catalog\Model\ResourceModel\Product as LocalProductResourceModel;
use Magento\Catalog\Model\Product as LocalProduct;
use Magento\Store\Model\ScopeInterface as LocalScope;
use Magento\Store\Model\Group as LocalStoreGroup;
use Magento\Store\Api\Data\StoreInterface as LocalStore;
use PGI\Module\PGFramework\Foundations\AbstractService;
use PGI\Module\PGIntl\Services\Translator;
use PGI\Module\PGShop\Interfaces\Entities\ShopEntityInterface;

/**
 * Class CharityGiftTranslationOfficer
 * @package PGMagentoCharity\Services\Officers
 */
class CharityGiftTranslationOfficer extends AbstractService
{
    /** @var LocalProductFactory */
    private $localProductFactory;

    /** @var LocalProductResourceModel */
    private $localProductResourceModel;

    /** @var LocalScopeConfig */
    private $localScopeConfig;

    /** @var Translator */
    private $translator;

    public function __construct(
        LocalProductFactory $localProductFactory,
        LocalProductResourceModel $localProductResourceModel,
        LocalScopeConfig $localScopeConfig,
        Translator $translator
    ) {
        $this->localProductFactory = $localProductFactory;
        $this->localProductResourceModel = $localProductResourceModel;
        $this->localScopeConfig = $localScopeConfig;
        $this->translator = $translator;
    }

    /**
     * @param ShopEntityInterface $shop
     * @param int $id
     * @throws Exception
     */
    public function installTranslations(ShopEntityInterface $shop, int $id)
    {
        $this->log()->debug("Install charity gift translations.");

        /** @var LocalStoreGroup $localStoreGroup */
        $localStoreGroup = $shop->getLocalEntity();

        /** @var LocalStore $localStoreView */
        foreach ($localStoreGroup->getStores() as $localStoreView) {
            $this->installTranslation($localStoreView, $id);
        }
    }

    /**
     * @param LocalStore $localStoreView
     * @param int $id
     * @throws Exception
     */
    protected function installTranslation(LocalStore $localStoreView, int $id)
    {
        $this->log()->debug("Install charity gift translation for StoreView #{$localStoreView->getId()}.");

        $name = $this->translator->get(
            $this->getConfig('gift_name_translation_key'),
            $this->getStoreLanguage($localStoreView)
        );

        $this->saveProductName($localStoreView, $id, $name);
    }

    /**
     * @param LocalStore $localStoreView
     * @return string
     */
    protected function getStoreLanguage(LocalStore $localStoreView): string
    {
        $locale = $this->localScopeConfig->getValue(
            'general/locale/code',
            LocalScope::SCOPE_STORE,
            $localStoreView->getId()
        );

        return strstr($locale, '_', true);
    }

    /**
     * @param LocalStore $localStoreView
     * @param int $id
     * @param string $name
     * @throws Exception
     */
    protected function saveProductName(LocalStore $localStoreView, int $id, string $name)
    {
        /** @var LocalProduct $product */
        $product = $this->localProductFactory->create();
        $this->localProductResourceModel->load($product, $id);

        $product->setStoreId($localStoreView->getId())->setName($name);
        $this->localProductResourceModel->saveAttribute($product, 'name');
    }
}