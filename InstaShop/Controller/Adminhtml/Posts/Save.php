<?php

namespace WindAndeddu\InstaShop\Controller\Adminhtml\Posts;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \WindAndeddu\InstaShop\Model\InstaShopFactory
     */
    protected $_postFactory;

    /**
     * Save constructor.
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

        $data = $this->getRequest()->getPostValue();
        if (!$data) {
            $this->_redirect('*/*/index');
            return;
        }

        if (isset($data['video']) && $data['video']) {
            $data['video'] = $data['video'][0]['name'];
        } else {
            $data['video'] = '';
        }

        foreach ($data['images'] as $key => $image) {
            $data['images'][$key] = $image['name'];
        }

        try {
            $postData = $this->_postFactory->create();
            if (isset($data['id'])) {
                $postData->load($data['id']);
            }
            if (!$postData->getId() && isset($data['id'])) {
                $this->messageManager->addError(__('Post no longer exist'));
            } else {
                $postData->setData($data);
                $postData->save();
                $this->messageManager->addSuccess(__('Post data has been successfully saved.'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('*/*/index');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('WindAndeddu_InstaShop::edit');
    }
}
