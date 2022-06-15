<?php
declare(strict_types=1);

namespace WindAndeddu\InstaShopGraphQl\Model\Resolver;

class InstaShop implements \Magento\Framework\GraphQl\Query\ResolverInterface
{
    const INSTASHOP_PAGE_LIMIT = 12;

    /**
     * @var \Magento\CatalogGraphQl\Model\Resolver\Product\Price\ProviderPool
     */
    protected $_priceProviderPool;

    /**
     * @var \Magento\CatalogInventory\Helper\Stock
     */
    protected $_stockFilter;

    /**
     * @var \Magento\CatalogInventory\Model\ResourceModel\Stock\StatusFactory
     */
    protected $_stockStatusResourceFactory;

    /**
     * @var \Magento\Catalog\Helper\ImageFactory
     */
    protected $_imageFactory;

    /**
     * @var \Magento\Catalog\Model\Config
     */
    protected $_catalogConfig;

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $_productStatus;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_productVisibility;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\ProductFactory
     */
    protected $_productResourceFactory;

    /**
     * @var \Magento\Store\Model\App\Emulation
     */
    protected $_appEmulation;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \WindAndeddu\Catalog\Helper\Product\Label
     */
    protected $_productLabelHelper;

    /**
     * @var \WindAndeddu\Catalog\Model\ProductRelations
     */
    protected $_productRelations;

    /**
     * @var \WindAndeddu\Catalog\Model\Product\Url
     */
    protected $_vogaProductUrlBuilder;

    /**
     * @var  \WindAndeddu\Eav\Model\Entity\Attribute\Source\Table
     */
    protected $_sourceTable;

    /**
     * @var \WindAndeddu\InstaShop\Helper\File
     */
    protected $_instaShopFileHelper;

    /**
     * @var \WindAndeddu\InstaShop\Helper\InstaShopImage
     */
    protected $_instaShopImageHelper;

    /**
     * @var \WindAndeddu\InstaShop\Model\ResourceModel\InstaShop\CollectionFactory
     */
    protected $_instaShopCollection;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var \WindAndeddu\Mapping\Helper\Data
     */
    protected $_mappingHelper;

    /**
     * @var string
     */
    protected $_baseUrl;

    /**
     * @var array
     */
    protected $_sortedSizeOptions;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;

    /**
     * @param \Magento\CatalogGraphQl\Model\Resolver\Product\Price\ProviderPool $priceProviderPool
     * @param \Magento\CatalogInventory\Helper\Stock $stockFilter
     * @param \Magento\CatalogInventory\Model\ResourceModel\Stock\StatusFactory $stockStatusResourceFactory
     * @param \Magento\Catalog\Helper\ImageFactory $imageFactory
     * @param \Magento\Catalog\Model\Config $catalogConfig
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus
     * @param \Magento\Catalog\Model\Product\Visibility $productVisibility
     * @param \Magento\Catalog\Model\ResourceModel\ProductFactory $productResourceFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Framework\Message\ManagerInterface $managerInterface
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Store\Model\App\Emulation $appEmulation
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \WindAndeddu\Catalog\Helper\Product\Label $productLabelHelper
     * @param \WindAndeddu\Catalog\Model\ProductRelations $productRelations
     * @param \WindAndeddu\Catalog\Model\Product\Url $vogaProductUrlBuilder
     * @param \WindAndeddu\Eav\Model\Entity\Attribute\Source\Table $sourceTable
     * @param \WindAndeddu\InstaShop\Helper\File $instaShopFileHelper
     * @param \WindAndeddu\InstaShop\Helper\InstaShopImage $instaShopImageHelper
     * @param \WindAndeddu\InstaShop\Model\ResourceModel\InstaShop\CollectionFactory $instaShopCollectionFactory
     * @param \WindAndeddu\Mapping\Helper\Data $mappingHelper
     */
    public function __construct(
        \Magento\CatalogGraphQl\Model\Resolver\Product\Price\ProviderPool $priceProviderPool,
        \Magento\CatalogInventory\Helper\Stock $stockFilter,
        \Magento\CatalogInventory\Model\ResourceModel\Stock\StatusFactory $stockStatusResourceFactory,
        \Magento\Catalog\Helper\ImageFactory $imageFactory,
        \Magento\Catalog\Model\Config $catalogConfig,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Catalog\Model\ResourceModel\ProductFactory $productResourceFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\Message\ManagerInterface $managerInterface,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Store\Model\App\Emulation $appEmulation,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \WindAndeddu\Catalog\Helper\Product\Label $productLabelHelper,
        \WindAndeddu\Catalog\Model\ProductRelations $productRelations,
        \WindAndeddu\Catalog\Model\Product\Url $vogaProductUrlBuilder,
        \WindAndeddu\Eav\Model\Entity\Attribute\Source\Table $sourceTable,
        \WindAndeddu\InstaShop\Helper\File $instaShopFileHelper,
        \WindAndeddu\InstaShop\Helper\InstaShopImage $instaShopImageHelper,
        \WindAndeddu\InstaShop\Model\ResourceModel\InstaShop\CollectionFactory $instaShopCollectionFactory,
        \WindAndeddu\Mapping\Helper\Data $mappingHelper
    )
    {
        $this->_priceProviderPool = $priceProviderPool;
        $this->_stockFilter = $stockFilter;
        $this->_stockStatusResourceFactory = $stockStatusResourceFactory;
        $this->_imageFactory = $imageFactory;
        $this->_catalogConfig = $catalogConfig;
        $this->_productStatus = $productStatus;
        $this->_productVisibility = $productVisibility;
        $this->_productResourceFactory = $productResourceFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_messageManager = $managerInterface;
        $this->_appEmulation = $appEmulation;
        $this->_storeManager = $storeManager;
        $this->_productLabelHelper = $productLabelHelper;
        $this->_productRelations = $productRelations;
        $this->_vogaProductUrlBuilder = $vogaProductUrlBuilder;
        $this->_sourceTable = $sourceTable;
        $this->_instaShopFileHelper = $instaShopFileHelper;
        $this->_instaShopImageHelper = $instaShopImageHelper;
        $this->_instaShopCollection = $instaShopCollectionFactory;
        $this->_mappingHelper = $mappingHelper;

        $this->_baseUrl = $urlBuilder->getBaseUrl();
    }

    /**
     * @param \Magento\Framework\GraphQl\Config\Element\Field $field
     * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
     * @param \Magento\Framework\GraphQl\Schema\Type\ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\GraphQl\Exception\GraphQlInputException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function resolve(
        \Magento\Framework\GraphQl\Config\Element\Field $field,
        $context,
        \Magento\Framework\GraphQl\Schema\Type\ResolveInfo $info,
        array $value = null,
        array $args = null
    ): array
    {
        if (!isset($args['current_page']) || empty($args['current_page'])) {
            throw new \Magento\Framework\GraphQl\Exception\GraphQlInputException(__("'current_page' input argument is required."));
        }

        // RonisBT VCPWA-340 VP-170 Website Optimization
        $this->_sourceTable->preloadOptions(
            \Magento\Catalog\Model\Product::ENTITY,
            [
                'designer',
                'size',
            ],
            $this->_storeManager->getStore()->getId()
        );

        $currentPage = $args['current_page'];

        $postCollection = $this->_instaShopCollection
            ->create()
            ->addFieldToSelect(['name', 'images', 'video', 'caption', 'publish_date', 'product_skus', 'url'])
            ->addFieldToFilter('status', 1)
            ->setOrder('publish_date', 'DESC')
            ->setOrder('id', 'DESC')
            ->setPageSize(self::INSTASHOP_PAGE_LIMIT)
            ->setCurPage($currentPage);

        $allProductSkus = [];
        foreach ($postCollection as $item) {
            if ($productSkus = $item->getProductSkus()) {
                foreach (array_unique($productSkus) as $sku) {
                    $allProductSkus[$sku] = $sku;
                }
            }
        }

        $products = [];
        if ($allProductSkus) {
            $products = $this->_prepareProducts($allProductSkus);
        }

        $posts = [];
        foreach ($postCollection as $item) {
            $caption = $item->getCaption();
            $video = null;
            if ($item->getVideo()) {
                $video = $this->_instaShopFileHelper->getVideoUrl($item->getVideo());
            }

            $listingImage = null;
            $popupImages = [];
            if ($imageNames = $item->getImages()) {
                foreach ($imageNames as $imageName) {
                    $popupImages[] = $this->_instaShopImageHelper->getImageUrl('instashop_popup_images', $imageName);
                }
                $listingImage = $this->_instaShopImageHelper->getImageUrl('instashop_images', array_shift($imageNames));
            }

            $postProducts = [];
            if ($postProductSkus = $item->getProductSkus()) {
                foreach (array_unique($postProductSkus) as $sku) {
                    if (isset($products[$sku])) {
                        $postProducts[] = $products[$sku];
                    }
                }
            }

            $storeCode = $this->_storeManager->getStore()->getCode();

            $posts[] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'listing_image' => $listingImage,
                'popup_images' => $popupImages,
                'video' => $video,
                'caption' => (!empty($caption[$storeCode])) ? $caption[$storeCode] : $caption[\Magento\Store\Model\Store::ADMIN_CODE],
                'publish_date' => $this->_convertDateToTimestamp($item->getPublishDate()) ?? '',
                'url' => $item->getUrl() ?? '',
                'products' => $postProducts,
            ];
        }

        return ['items' => $posts, 'page_count' => $postCollection->getLastPageNumber()];
    }

    /**
     * @param array $allProductSkus
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _prepareProducts(array $allProductSkus): array
    {
        $productCollection = $this->_productCollectionFactory
            ->create()
            ->addAttributeToFilter('sku', ['in' => $allProductSkus])
            ->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()])
            ->addAttributeToSelect(['name', 'url_key', 'image', 'image_label', 'gender', 'designer', 'supplier'])
            ->addBlockedCountriesAttributeToCollection()
            ->addCategoriesBlockedCountriesAttributeToCollection()
            ->setVisibility($this->_productVisibility->getVisibleInSiteIds());

        $this->_stockStatusResourceFactory
            ->create()
            ->addStockDataToCollection($productCollection, false);

        $parentIds = array_keys($productCollection->getItems());

        $variantIds = [];
        $productRelationsParentIds = [];
        foreach ($this->_productRelations->getProductsChildIds($parentIds) as $productRelationsParentId => $_item) {
            $productRelationsParentIds[] = $productRelationsParentId;
            $variantIds = array_merge($variantIds, $_item);
        }

        $variantProductCollection = $this->_productCollectionFactory
            ->create()
            ->addIdFilter($variantIds)
            ->addAttributeToSelect(['name', 'size', 'price', 'special_price', 'special_from_date', 'special_to_date'])
            ->addBlockedCountriesAttributeToCollection()
            ->addCategoriesBlockedCountriesAttributeToCollection();

        $this->_stockStatusResourceFactory
            ->create()
            ->addStockDataToCollection($variantProductCollection, false);

        $variants = [];
        foreach ($variantProductCollection as $variantProduct) {
            $variants[$variantProduct->getId()] = $variantProduct;
        }

        $visibleProducts = [];
        $visibleProductsData = [];
        $countryCode = $this->_storeManager->getStore()->getCurrentCountryCode();
        foreach ($productCollection as $product) {
            if (!in_array($product->getId(), $productRelationsParentIds)
                || $product->isRestrictedForCountry($countryCode)
            ) {
                continue;
            }
            $visibleProductsData[$product->getSku()] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'is_salable' => (bool)$product->getData('is_salable'),
                'url' => $this->_buildProductUrl($product),
                'sku' => $product->getSku(),
                'product_label' => $this->_productLabelHelper->getProductLabels($product, $product, ['few_left']),
            ];
            $visibleProducts[] = $product;
        }

        $this->_appendProductImages($visibleProducts, $visibleProductsData);
        $this->_appendVariants($visibleProducts, $variants, $visibleProductsData);
        $this->_appendPrices($visibleProducts, $variants, $visibleProductsData);

        return $visibleProductsData;
    }

    /**
     * @param array $products
     * @param array $productsData
     * @return InstaShop
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _appendProductImages(array $products, array &$productsData): InstaShop
    {
        $storeId = $this->_storeManager->getStore()->getId();

        $this->_appEmulation->startEnvironmentEmulation($storeId, \Magento\Framework\App\Area::AREA_FRONTEND, true);
        foreach ($products as $product) {

            $baseImage = $this->_imageFactory
                ->create()
                ->init($product, 'category_page_grid');

            $productsData[$product->getSku()]['product_image'] = [
                'url' => $baseImage->getUrl(),
                'label' => $baseImage->getLabel(),
            ];

        }
        $this->_appEmulation->stopEnvironmentEmulation();

        return $this;
    }

    /**
     * @param array $products
     * @param array $variants
     * @param array $productsData
     * @return InstaShop
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _appendPrices(array $products, array $variants, array &$productsData): InstaShop
    {
        $currentCurrencyCode = $this->_storeManager->getStore()->getCurrentCurrency()->getCode();

        foreach ($products as $product) {
            $priceProvider = $this->_priceProviderPool->getProviderByProductType($product->getTypeId());
            $price = $priceProvider->getMinimalRegularPrice($product)->getValue();
            $specialPrice = $priceProvider->getMinimalFinalPrice($product)->getValue();

            // Todo
            // simple fix
            // price becomes zero when all simple products are out of stock
            // this is fixed by VCPWA-664
            if ($specialPrice > $price) {
                $price = $specialPrice;
            }
            //end Todo

            $productsData[$product->getSku()]['price'] = [
                'regular_price' => [
                    'value' => $price,
                    'currency' => $currentCurrencyCode,
                ],
                'special_price' => [
                    'value' => $specialPrice > 0 ? $specialPrice : $price,
                    'currency' => $currentCurrencyCode,
                ],
            ];

            if (isset($productsData[$product->getSku()]['product_options'])) {
                foreach ($productsData[$product->getSku()]['product_options'] as &$productOption) {
                    if (!isset($productOption['variant_id'])) {
                        continue;
                    }

                    $variantId = (int)$productOption['variant_id'];
                    $variant = isset($variants[$variantId]) ? $variants[$variantId] : null;

                    if ($variant) {
                        $priceProvider = $this->_priceProviderPool->getProviderByProductType($variant->getTypeId());
                        $price = $priceProvider->getMinimalRegularPrice($variant)->getValue();
                        $specialPrice = $priceProvider->getMinimalFinalPrice($variant)->getValue();

                        if ($specialPrice > $price) {
                            $price = $specialPrice;
                        }

                        $productOption['price'] = [
                            'regular_price' => [
                                'value' => $price,
                                'currency' => $currentCurrencyCode,
                            ],
                            'special_price' => [
                                'value' => $specialPrice > 0 ? $specialPrice : $price,
                                'currency' => $currentCurrencyCode,
                            ],
                        ];
                    }
                }
            }
        }

        return $this;
    }

    /**
     * @param array $products
     * @param array $variants
     * @param array $productsData
     * @return InstaShop
     */
    protected function _appendVariants(array $products, array $variants, array &$productsData): InstaShop
    {
        $variantsByParentId = [];
        $parentIds = [];
        foreach ($products as $product) {
            if ($product->getTypeId() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
                $parentIds[$product->getId()] = $product->getId();
            }
        }

        if (count($parentIds) > 0) {
            $variantIdsByParentIds = $this->_productRelations->getProductsChildIds($parentIds);

            foreach ($variantIdsByParentIds as $parentId => $variantIds) {
                $variantsForParent = [];
                foreach ($variantIds as $variantId) {
                    if (!$variant = $variants[$variantId]) {
                        continue;
                    }

                    $variantsForParent[$variantId] = $variant;
                }

                if (count($variantsForParent) > 0) {
                    $variantsByParentId[$parentId] = $variantsForParent;
                }
            }
        }

        foreach ($products as $product) {
            $variantsData = [];

            if (
                $product->getTypeId() != \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE ||
                !isset($variantsByParentId[$product->getId()])
            ) {
                $productsData[$product->getSku()]['product_options'] = $variantsData;
                continue;
            }

            foreach ($variantsByParentId[$product->getId()] as $variant) {
                $sizeLabel = $this->_mappingHelper->getSizeLabel($variant->getSize(), $product);
                $variantId = $variant->getId();
                $variantsData[$variantId] = [
                    'variant_id' => $variantId,
                    'title' => $sizeLabel ?: $variant->getAttributeText('size'),
                    'value' => $variant->getSize(),
                    'sku' => $variant->getSku(),
                    'is_in_stock' => (bool)$variant->getData('is_salable'),
                ];
            }

            usort($variantsData, [$this, '_sortVariants']);

            $productsData[$product->getSku()]['product_options'] = $variantsData;
        }

        return $this;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getSortedSizeOptions(): array
    {
        if ($this->_sortedSizeOptions === null) {
            $this->_sortedSizeOptions = [];
            $productResource = $this->_productResourceFactory->create();
            $sizeAttribute = $productResource->getAttribute('size');
            $i = 0;

            foreach ($sizeAttribute->getSource()->getAllOptions() as $sizeOption) {
                $this->_sortedSizeOptions[$sizeOption['value']] = $i++;
            }
        }

        return $this->_sortedSizeOptions;
    }

    /**
     * @param array $variantA
     * @param array $variantB
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _sortVariants(array $variantA, array $variantB): bool
    {
        $sortedSizeOptions = $this->_getSortedSizeOptions();
        $variantAPosition = $sortedSizeOptions[$variantA['value']];
        $variantBPosition = $sortedSizeOptions[$variantB['value']];

        return $variantAPosition > $variantBPosition;
    }

    /**
     * @param \WindAndeddu\Catalog\Model\Product\Interceptor $product
     * @return string
     */
    protected function _buildProductUrl(\WindAndeddu\Catalog\Model\Product\Interceptor $product): string
    {
        return str_replace($this->_baseUrl, '', $this->_vogaProductUrlBuilder->getUrl($product));
    }

    /**
     * @param string $date
     * @return string|null
     */
    private function _convertDateToTimestamp(string $date): ?string
    {
        if (!$date) {
            return null;
        }

        try {
            $formattedDate = new \Zend_Date($date, \Magento\Framework\Stdlib\DateTime::DATETIME_INTERNAL_FORMAT);
            return $formattedDate->getTimestamp();
        } catch (\Exception $e) {
            $this->_messageManager->addExceptionMessage($e);
        }

        return null;
    }
}
