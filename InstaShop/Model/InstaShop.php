<?php

namespace WindAndeddu\InstaShop\Model;

class InstaShop extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'instashop_posts';
    const IMAGE_VIEW_ID_PREFIX = 'instashop_';

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    protected $_serializer;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_dateTime;
    /**
     * @var Images\ImageProvider
     */
    protected $_imageProvider;
    /**
     * @var \WindAndeddu\ImageUploader\Model\ImageResize
     */
    protected $_imageResize;


    /**
     * InstaShop constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     * @param \Magento\Framework\Image\AdapterFactory $imageFactory
     * @param Images\ImageProvider $imageProvider
     * @param \WindAndeddu\ImageUploader\Model\ImageResize $imageResize
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\Serialize\SerializerInterface $serializer,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        \WindAndeddu\InstaShop\Model\Images\ImageProvider $imageProvider,
        \WindAndeddu\ImageUploader\Model\ImageResize $imageResize,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        $this->_dateTime = $dateTime;
        $this->_serializer = $serializer;
        $this->_imageProvider = $imageProvider;
        $this->_imageResize = $imageResize;

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init(\WindAndeddu\InstaShop\Model\ResourceModel\InstaShop::class);
    }

    /**
     * @return InstaShop
     */
    public function beforeSave(): InstaShop
    {
        $dateTime = $this->_dateTime->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($dateTime);
        }

        $this->setUpdatedAt($dateTime);

        if ($this->getCaption() && is_array($this->getCaption())) {
            $this->setCaption($this->_serializer->serialize($this->getCaption()));
        }

        if ($this->getImages() && is_array($this->getImages())) {
            $this->setImages($this->_serializer->serialize($this->getImages()));
        }

        if ($this->getProductSkus()) {
            $this->setProductSkus(str_replace(" ", '', $this->getProductSkus()));
        }

        return parent::beforeSave();
    }

    /**
     * @return InstaShop
     */
    public function afterLoad(): InstaShop
    {
        if ($images = $this->getImages()) {
            $this->setImages($this->_serializer->unserialize($images));
        }

        if ($caption = $this->getCaption()) {
            $this->setCaption($this->_serializer->unserialize($caption));
        }

        return parent::afterLoad();
    }

    /**
     * @return string[]
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG];
    }


    /**
     * @return InstaShop
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function afterSave(): InstaShop
    {
        if ((bool)$this->getId()) {
            if ($imageFields = $this->_imageProvider->getFields()) {
                $mainField = 'images';
                $new = $this->_serializer->unserialize($this->getImages());
                $original = $this->getOrigData($mainField);
                $instaShopImage = !empty($new) ? $new : [];
                $instaShopOriginalImage = !empty($original) ? $original : [];
                foreach ($imageFields as $imageField) {
                    foreach (array_diff($instaShopImage, $instaShopOriginalImage) as $image) {
                        if (isset($image) && $image != '') {
                            $this->_imageResize->adaptiveResizeFromImageName(
                                $image,
                                self::IMAGE_VIEW_ID_PREFIX . $imageField);
                        }
                    }
                }
            }
        }
        return parent::afterSave();
    }
}
