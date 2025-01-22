<?php

namespace RecentlyViewed\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use RecentlyViewed\Api\State\RecentlyViewedProvider;
use Thelia\Api\Resource\Product;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/recently_viewed',
            normalizationContext: ['groups' => [Product::GROUP_FRONT_READ]],
            provider: RecentlyViewedProvider::class,
        )
    ],
)]
class RecentlyViewed
{

}
