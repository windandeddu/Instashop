<?php


namespace WindAndeddu\InstaShop\Model\Images;

/**
 * Class ImageProvider
 */
class ImageProvider
{
    /**
     * Available banner image fields
     *
     * @var array
     */
    protected $_resizeImages;

    /**
     * ImageProvider constructor.
     *
     * @param array $resizeImages
     */
    public function __construct(
        array $resizeImages
    )
    {
        $this->_resizeImages = $resizeImages;
    }

    public function getImageFields()
    {
        return $this->_resizeImages;
    }

    public function getFields()
    {
        $output = [];
        foreach ($this->getImageFields() as $field => $fieldValue) {
            if ($fieldValue) {
                $output[] = $field;
            }
        }
        return $output;
    }
}
