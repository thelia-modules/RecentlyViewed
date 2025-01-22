<?php

namespace RecentlyViewed\Api\Controller;

use RecentlyViewed\Service\RecentlyViewedManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Thelia\Api\Bridge\Propel\Service\ApiResourcePropelTransformerService;
use Thelia\Api\Resource\Product;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\ProductQuery;

#[AsController]
readonly class RecentlyViewedController
{
    public function __construct(
        private ApiResourcePropelTransformerService $apiResourcePropelTransformerService,
        private RecentlyViewedManager $recentlyViewedManager
    ) {
    }

    public function __invoke(Request $request): array
    {
        $productIds = $this->recentlyViewedManager->getRecentlyViewed();

        $products      = ProductQuery::create()->filterById($productIds)->find();
        $operation     = $request->get('_api_operation');
        $productModels = [];
        foreach ($products as $product) {
            $productModels[] = $this->apiResourcePropelTransformerService->modelToResource(Product::class, $product,
                $operation->getNormalizationContext());
        }

        return $productModels;
    }
}
