<?php

namespace WindAndeddu\InstaShop\Ui\Component\Listing\Grid\Column;


class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    protected $_serializer;
    /**
     * @var \WindAndeddu\InstaShop\Model\InstaShopFactory
     */
    protected $_postFactory;
    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $_imageHelper;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \WindAndeddu\InstaShop\Helper\File
     */
    protected $_fileHelper;


    /**
     * Thumbnail constructor.
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     * @param \WindAndeddu\InstaShop\Model\InstaShopFactory $postFactory
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\Serialize\SerializerInterface $serializer,
        \WindAndeddu\InstaShop\Model\InstaShopFactory $postFactory,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \WindAndeddu\InstaShop\Helper\File $fileHelper,
        array $components = [],
        array $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->_serializer = $serializer;
        $this->_storeManager = $storeManager;
        $this->_postFactory = $postFactory;
        $this->_imageHelper = $imageHelper;
        $this->_fileHelper = $fileHelper;
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getName();
            foreach ($dataSource['data']['items'] as & $item) {
                $imagePlaceholder = $this->_imageHelper->getDefaultPlaceholderUrl('image');
                $id = $item['id'];
                $post = $this->_postFactory->create()->load($id);
                $imageUrl = $imagePlaceholder;
                if ($post->getImages() && is_array($post->getImages())) {
                    $imageUrl = $this->_fileHelper->getImageUrl($post->getImages()[0]);
                }

                $item[$fieldName . '_src'] = $imageUrl;
                $item[$fieldName . '_alt'] = $post->getName() . '_preview';
            }
        }
        return $dataSource;
    }
}
