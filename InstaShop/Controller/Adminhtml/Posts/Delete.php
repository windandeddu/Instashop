<?php

namespace WindAndeddu\InstaShop\Controller\Adminhtml\Posts;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var \WindAndeddu\InstaShop\Model\InstaShopFactory
     */
    protected $_model;

    /**
     * Delete constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \WindAndeddu\InstaShop\Model\InstaShopFactory $model
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \WindAndeddu\InstaShop\Model\InstaShopFactory $model
    )
    {
        $this->_model = $model;
        parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();
        $model = $this->_model->create()->load($id);
        if (!$model->getId()) {
            $this->messageManager->addError(__('We can\'t find a post to delete.'));
            $this->_redirect('*/*/index');
            return;
        }
        try {
            $model->delete();
            $this->messageManager->addSuccess(__('The post has been deleted.'));
            return $resultRedirect->setPath('*/*/index');
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
            return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
        }
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('WindAndeddu_InstaShop::edit');
    }
}
