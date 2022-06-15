<?php

namespace WindAndeddu\InstaShop\Controller\Adminhtml\Posts;

class Add extends \Magento\Backend\App\Action
{
    /**
     * Add constructor.
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context
    )
    {
        parent::__construct($context);
    }


    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        $title = __('Add New Post');
        $resultPage->setActiveMenu('WindAndeddu_InstaShop::grid');
        $resultPage->getConfig()->getTitle()->prepend($title);
        return $resultPage;
    }

    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('WindAndeddu_InstaShop::edit');
    }
}
