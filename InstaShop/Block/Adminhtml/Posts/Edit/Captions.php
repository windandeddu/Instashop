<?php

namespace WindAndeddu\InstaShop\Block\Adminhtml\Posts\Edit;


class Captions extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Ui\Component\Layout\Tabs\TabInterface
{

    /**
     * @var \WindAndeddu\InstaShop\Model\InstaShopFactory
     */
    protected $_instaShopFactory;

    /**
     * Captions constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \WindAndeddu\InstaShop\Model\InstaShopFactory $instaShopFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \WindAndeddu\InstaShop\Model\InstaShopFactory $instaShopFactory,
        array $data = []
    )
    {
        $this->_instaShopFactory = $instaShopFactory;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return null
     */
    public function getTabClass()
    {
        return null;
    }

    /**
     * @return null
     */
    public function getTabUrl()
    {
        return null;
    }

    /**
     * @return bool
     */
    public function isAjaxLoaded()
    {
        return true;
    }

    /**
     * @return \Magento\Framework\Phrase|string
     */
    public function getTabLabel()
    {
        return __('Titles');
    }

    /**
     * @return \Magento\Framework\Phrase|string
     */
    public function getTabTitle()
    {
        return __('Titles');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return false
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @return Captions
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $id = $this->getRequest()->getParam('id');
        $post = $this->_instaShopFactory->create()->load($id);
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('post_');

        if ($post->getID()) {
            $this->_createStoreSpecificFieldset($form, $post->getCaption());
        } else{
            $this->_createStoreSpecificFieldset($form, null);
        }

        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * @param $form
     * @param $captions
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _createStoreSpecificFieldset($form, $captions)
    {
        $fieldset = $form->addFieldset(
            'store_caption_fieldset',
            ['legend' => __('Captions')]
        );
        $renderer = $this->getLayout()->createBlock(
            \Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset::class
        );
        $fieldset->setRenderer($renderer);
        foreach ($this->_storeManager->getWebsites() as $website) {

            foreach ($website->getGroups() as $group) {
                $stores = $group->getStores();
                if (count($stores) == 0) {
                    continue;
                }
                $fieldset->addField(
                    "s_" . \Magento\Store\Model\Store::ADMIN_CODE,
                    'textarea',
                    [
                        'name' => 'caption['. \Magento\Store\Model\Store::ADMIN_CODE .']',
                        'title' => 'Default Caption for All Store Views',
                        'label' => 'Default Caption for All Store Views',
                        'required' => true,
                        'value' => isset($captions[\Magento\Store\Model\Store::ADMIN_CODE]) ? $captions[\Magento\Store\Model\Store::ADMIN_CODE] : '',
                        'fieldset_html_class' => 'store',
                        'data-form-part' => 'instashop_post_form'
                    ]
                );

                foreach ($stores as $store) {
                    $fieldset->addField(
                        "s_{$store->getCode()}",
                        'textarea',
                        [
                            'name' => 'caption[' . $store->getCode() . ']',
                            'title' => $store->getName(),
                            'label' => $store->getName(),
                            'required' => false,
                            'value' => isset($captions[$store->getCode()]) ? $captions[$store->getCode()] : '',
                            'fieldset_html_class' => 'store',
                            'data-form-part' => 'instashop_post_form'
                        ]
                    );
                }
            }
        }

        return $fieldset;
    }
}
