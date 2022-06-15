<?php

namespace WindAndeddu\InstaShop\Block\Adminhtml;

class AddPost extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Add constructor.
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    )
    {
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $this->_blockGroup = 'WindAndeddu_InstaShop';
        parent::_construct();
    }
}
