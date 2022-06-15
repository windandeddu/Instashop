<?php

namespace WindAndeddu\InstaShop\Ui\Component\Form;


class DataProvider extends \Magento\Ui\DataProvider\ModifierPoolDataProvider
{

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;
    /**
     * @var \WindAndeddu\InstaShop\Model\InstaShopFactory
     */
    protected $_postFactory;
    /**
     * @var \WindAndeddu\InstaShop\Helper\File
     */
    protected $_fileHelper;

    protected $loadedData;


    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param \WindAndeddu\InstaShop\Model\ResourceModel\InstaShop\CollectionFactory $collectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \WindAndeddu\InstaShop\Model\InstaShopFactory $postFactory
     * @param array $meta
     * @param array $data
     * @param \Magento\Ui\DataProvider\Modifier\PoolInterface|null $pool
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \WindAndeddu\InstaShop\Model\ResourceModel\InstaShop\CollectionFactory $collectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\RequestInterface $request,
        \WindAndeddu\InstaShop\Model\InstaShopFactory $postFactory,
        \WindAndeddu\InstaShop\Helper\File $fileHelper,
        array $meta = [],
        array $data = [],
        \Magento\Ui\DataProvider\Modifier\PoolInterface $pool = null
    )
    {
        $this->collection = $collectionFactory->create();
        $this->_storeManager = $storeManager;
        $this->_request = $request;
        $this->_postFactory = $postFactory;
        $this->_fileHelper = $fileHelper;


        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getData()
    {

        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $baseUrl = $this->_storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        $id = $this->_request->getParam('id');
        $postData = $this->_postFactory->create()->load($id)->getData();

        if (isset($postData['video']) && is_string($postData['video'])) {
            $videoName = $postData['video'];
            $videoUrl = $baseUrl . \WindAndeddu\InstaShop\Helper\File::INSTASHOP_PATH_TO_VIDEO_DIR . '/' . $videoName;
            $postData['video'] = [
                0 => [
                    'url' => $videoUrl,
                    'previewType' => 'video',
                    'name' => $videoName,
                    'file' => $videoName,
                    'type' => 'video/mp4']];
        }

        if (isset($postData['images']) && is_array($postData['images'])) {
            $images = $postData['images'];
            foreach ($images as $key => $image) {
                $imageName = $image;
                $imageUrl = $this->_fileHelper->getImageUrl($imageName);
                $images[$key] = [
                    'url' => $imageUrl,
                    'previewType' => 'image',
                    'name' => $imageName,
                    'file' => $imageName,
                    'type' => 'image'];
            }
            $postData['images'] = $images;
        }
        $this->loadedData[$id] = $postData;

        return $this->loadedData;
    }
}
