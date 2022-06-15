<?php

namespace WindAndeddu\InstaShop\Model\ResourceModel;


class InstaShop extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('WindAndeddu_instashop', 'id');
    }
}
