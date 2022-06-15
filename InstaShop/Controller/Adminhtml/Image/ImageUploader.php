<?php

namespace WindAndeddu\InstaShop\Controller\Adminhtml\Image;

class ImageUploader extends \WindAndeddu\ImageUploader\Controller\Adminhtml\Image\ImageUploader
{
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('WindAndeddu_InstaShop::edit');
    }
}
