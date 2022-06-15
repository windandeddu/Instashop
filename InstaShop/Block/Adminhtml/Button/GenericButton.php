<?php

namespace WindAndeddu\InstaShop\Block\Adminhtml\Button;

class GenericButton
{
    protected $_context;

    /**
     * GenericButton constructor.
     * @param \Magento\Backend\Block\Widget\Context $context
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context
    )
    {
        $this->_context = $context;
    }

    public function getId()
    {
        try {
            return $this->_context->getRequest()->getParam('id');

        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
        }
        return null;
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->_context->getUrlBuilder()->getUrl($route, $params);
    }
}
