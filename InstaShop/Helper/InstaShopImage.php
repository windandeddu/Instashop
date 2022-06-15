<?php

namespace WindAndeddu\InstaShop\Helper;

class InstaShopImage extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \WindAndeddu\Core\Helper\Data
     */
    protected $_coreHelper;
    /**
     * @var \WindAndeddu\ImageUploader\Model\View\Image
     */
    protected $_viewImage;

    /**
     * InstaShopImage constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \WindAndeddu\Core\Helper\Data $coreHelper
     * @param \WindAndeddu\ImageUploader\Model\View\Image $viewImage
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \WindAndeddu\Core\Helper\Data $coreHelper,
        \WindAndeddu\ImageUploader\Model\View\Image $viewImage
    )
    {
        $this->_coreHelper = $coreHelper;
        $this->_viewImage = $viewImage;

        parent::__construct($context);
    }

    /**
     * @param $imageViewId
     * @param $imageName
     * @return string
     */
    public function getImageUrl($imageViewId, $imageName)
    {
        return $this->_viewImage->getImageUrl($imageViewId, $imageName);
    }
}
