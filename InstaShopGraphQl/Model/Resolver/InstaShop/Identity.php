<?php
declare(strict_types=1);

namespace WindAndeddu\InstaShopGraphQl\Model\Resolver\InstaShop;

class Identity implements \Magento\Framework\GraphQl\Query\Resolver\IdentityInterface
{

    /**
     * @param array $resolvedData
     * @return array
     */
    public function getIdentities(array $resolvedData): array
    {
        $identities = [\WindAndeddu\InstaShop\Model\InstaShop::CACHE_TAG];
        foreach ($resolvedData['items'] as $data) {
            foreach ($data['products'] as $product) {
                foreach ($product['product_options'] as $simple) {
                    $identities[] = \Magento\Catalog\Model\Product::CACHE_TAG . '_' . $simple['variant_id'];
                }
                $identities[] = \Magento\Catalog\Model\Product::CACHE_TAG . '_' . $product['id'];
            }
        }
        return array_unique($identities);
    }
}
