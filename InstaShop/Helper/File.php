<?php

namespace WindAndeddu\InstaShop\Helper;

class File extends \Magento\Framework\App\Helper\AbstractHelper
{

    const INSTASHOP_PATH_TO_IMAGE_DIR = 'instashop/images';
    const INSTASHOP_PATH_TO_VIDEO_DIR = 'instashop/video';
    const INSTASHOP_MEDIA_URL = 'instashop';
    const INSTASHOP_CACHE_DIR_PATH = 'instashop';
    const INSTASHOP_IMAGE_GROUP_NAME = 'InstaShopImageGroup';
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;


    /**
     * File constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Helper\Context $context
    )
    {
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * @param $fileName
     * @param $fileType
     * @return string|void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getImageUrl($imageName)
    {
        if (!$imageName) {
            return;
        }
        $pathToFileDir = self::INSTASHOP_PATH_TO_IMAGE_DIR;
        $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $fileUrl = $mediaUrl . $pathToFileDir . '/' . $imageName;
        return $fileUrl;
    }

    public function getVideoUrl($videoName)
    {
        if (!$videoName) {
            return;
        }
        $pathToFileDir = self::INSTASHOP_PATH_TO_VIDEO_DIR;
        $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $fileUrl = $mediaUrl . $pathToFileDir . '/' . $videoName;
        return $fileUrl;
    }

}
