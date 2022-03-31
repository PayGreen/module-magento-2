<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.6.1
 *
 */

namespace PGI\Module\PGMagento\Services\Officers;

use Exception;
use Magento\Quote\Api\CartRepositoryInterface as LocalQuoteRepository;
use Magento\Framework\Exception\LocalizedException as LocalLocalizedException;
use Magento\Framework\DataObject as LocalDataObject;
use Magento\Catalog\Model\Product as LocalProduct;
use Magento\Quote\Model\Quote\Item as LocalItem;
use Magento\Quote\Model\Quote as LocalQuote;
use PGI\Module\PGFramework\Foundations\AbstractService;
use PGI\Module\PGShop\Interfaces\Entities\CartEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\ProductEntityInterface;
use PGI\Module\PGShop\Interfaces\Officers\CartOfficerInterface;

class CartOfficer extends AbstractService implements CartOfficerInterface
{
    /** @var LocalQuoteRepository */
    private $localQuoteRepository;

    public function __construct(
        LocalQuoteRepository $localQuoteRepository
    ) {
        $this->localQuoteRepository = $localQuoteRepository;
    }

    /**
     * @inheridoc
     * @throws LocalLocalizedException
     * @throws Exception
     */
    public function addItem(CartEntityInterface $cart, ProductEntityInterface $product, $cost)
    {
        $this->log()->debug("Adding product in cart with price equal to '$cost'.");

        /** @var LocalProduct $localProduct */
        $localProduct = $product->getLocalEntity();

        /** @var LocalQuote $localQuote */
        $localQuote = $cart->getLocalEntity();

        $params = new LocalDataObject([
            'product' => $product->id(),
            'qty' => 1
        ]);

        /** @var LocalItem $localItem */
        $localItem = $localQuote->addProduct($localProduct, $params);

        $localItem->setCustomPrice($cost);
        $localItem->setOriginalCustomPrice($cost);

        $localItem->setPrice($cost);
        $localItem->setBasePrice($cost);
        $localItem->setBaseRowTotal($cost);

        $localItem->setTaxPercent(0);
        $localItem->setTaxAmount(0);
        $localItem->setBaseTaxAmount(0);

        $localItem->setPriceInclTax($cost);
        $localItem->setBasePriceInclTax($cost);
        $localItem->setBaseRowTotalInclTax($cost);

        $localQuote->collectTotals();

        $this->localQuoteRepository->save($localQuote);

        $this->log()->notice("Gift successfully added in cart.");
    }

    /**
     * @inheridoc
     * @throws Exception
     */
    public function removeItem(CartEntityInterface $cart, ProductEntityInterface $product)
    {
        $this->log()->debug("Removing product from cart.");

        /** @var LocalQuote $localQuote */
        $localQuote = $cart->getLocalEntity();

        /** @var LocalItem $localItem */
        foreach($localQuote->getAllItems() as $localItem) {
            if ((int) $localItem->getProduct()->getId() === $product->id()) {
                $localQuote
                    ->removeItem($localItem->getId())
                    ->collectTotals()
                ;

                $this->localQuoteRepository->save($localQuote);

                $this->log()->notice("Gift successfully removed from cart.");
            }
        }
    }
}
