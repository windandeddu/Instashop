<?php

namespace WindAndeddu\InstaShop\Controller\Adminhtml\Posts;

class Edit extends \Magento\Backend\App\Action
{

    /**
     * @var \WindAndeddu\InstaShop\Model\InstaShopFactory
     */
    protected $_postFactory;

    /**
     * EditPost constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \WindAndeddu\InstaShop\Model\InstaShopFactory $postFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \WindAndeddu\InstaShop\Model\InstaShopFactory $postFactory
    )
    {
        parent::__construct($context);
        $this->_postFactory = $postFactory;
    }


    public function execute()
    {
        $postId = (int)$this->getRequest()->getParam('id');
        $postData = $this->_postFactory->create();
        if ($postId) {
            $postData = $postData->load($postId);
            if (!$postData->getId()) {
                $this->messageManager->addError(__('post data no longer exist.'));
                $this->_redirect('*/*/index');
                return;
            }
        }
        $postTitle = $postData->getName();
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        $title = __('Edit Post Data %1', $postTitle);
        $resultPage->setActiveMenu('WindAndeddu_InstaShop::grid');
        $resultPage->getConfig()->getTitle()->prepend($title);
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('WindAndeddu_InstaShop::edit');
    }
}
