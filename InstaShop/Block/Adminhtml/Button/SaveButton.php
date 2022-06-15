<?php

namespace WindAndeddu\InstaShop\Block\Adminhtml\Button;

class SaveButton extends \WindAndeddu\InstaShop\Block\Adminhtml\Button\GenericButton implements \Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface
{
    public function getButtonData()
    {
        return [
            'label' => __('Save Post'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}
