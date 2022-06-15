<?php

namespace WindAndeddu\InstaShop\Model\ResourceModel\InstaShop;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    protected $_serializer;

    /**_viewImage
     * Collection constructor.
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\Serialize\SerializerInterface $serializer,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    )
    {
        $this->_serializer = $serializer;

        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $connection,
            $resource
        );
    }

    protected function _construct()
    {
        $this->_init(\WindAndeddu\InstaShop\Model\InstaShop::class, \WindAndeddu\InstaShop\Model\ResourceModel\InstaShop::class);
    }

    protected function _afterLoad()
    {
        $this->_convertRowDataToArray();

        parent::_afterLoad();


        return $this;
    }

    protected function _convertRowDataToArray()
    {
        foreach ($this->getItems() as $item) {
            $item->setCaption($this->_serializer->unserialize($item->getCaption()));
            $item->setImages($this->_serializer->unserialize($item->getImages()));
            if ($productSkus = $item->getProductSkus()) {
                $item->setProductSkus(explode(',', $productSkus));
            }
        }
        return $this;
    }
}
