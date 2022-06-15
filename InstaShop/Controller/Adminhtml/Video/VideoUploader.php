<?php

namespace WindAndeddu\InstaShop\Controller\Adminhtml\Video;

class VideoUploader extends \WindAndeddu\ImageUploader\Controller\Adminhtml\Image\ImageUploader
{
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('WindAndeddu_InstaShop::edit');
    }
}
