<?php

namespace RecentlyViewed\Api\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use RecentlyViewed\Service\RecentlyViewedManager;
use Thelia\Api\Bridge\Propel\Service\ApiResourcePropelTransformerService;
use Thelia\Api\Resource\Product;
use Thelia\Model\LangQuery;
use Thelia\Model\Map\ProductTableMap;
use Thelia\Model\ProductQuery;

readonly class RecentlyViewedProvider implements ProviderInterface
{
    public function __construct(
        private ApiResourcePropelTransformerService $apiResourcePropelTransformerService,
        private RecentlyViewedManager $recentlyViewedManager
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $productIds = $this->recentlyViewedManager->getRecentlyViewed();
        if (!$productIds) {
            return [];
        }
        $cids  = implode(',', $productIds);
        $query = ProductQuery::create()->filterById($productIds);

        if ($context['filters']['itemsPerPage']) {
            $query->limit($context['filters']['itemsPerPage']);
        }
        $products = $query
            ->addDescendingOrderByColumn("FIELD (" . ProductTableMap::COL_ID . ", {$cids})")
            ->find();
        $langs    = LangQuery::create()->filterByActive(1)->find();

        return array_map(
            function ($product) use ($context, $langs) {
                return $this->apiResourcePropelTransformerService->modelToResource(
                    resourceClass: Product::class,
                    propelModel: $product,
                    context: $context,
                    langs: $langs
                );
            },
            iterator_to_array($products)
        );
    }
}
