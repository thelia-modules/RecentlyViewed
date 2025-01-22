<?php

namespace RecentlyViewed\Loop;

use Propel\Runtime\ActiveQuery\Criteria;
use RecentlyViewed\Service\RecentlyViewedManager;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Core\Template\Loop\Product as ProductLoop;
use Thelia\Model\ProductQuery;

/**
 * Class ProductRecentlyViewedLoop
 *
 * @package RecentlyViewed\Loop
 * @author  Baixas Alban <abaixas@openstudio.fr>
 */
class ProductRecentlyViewedLoop extends ProductLoop
{

    public function __construct(private readonly RecentlyViewedManager $recentlyViewedManager)
    {
    }

    /**
     * @return ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        /** @var ArgumentCollection $argumentCollection */
        $argumentCollection = parent::getArgDefinitions();

        $argumentCollection->addArgument(Argument::createIntTypeArgument('current_product'));

        return $argumentCollection;
    }

    /**
     * @return ProductQuery
     */
    public function buildModelCriteria()
    {
        /** @var ProductQuery $search */
        $search = parent::buildModelCriteria();

        $productIds = $this->recentlyViewedManager->getRecentlyViewed($this->getCurrentProduct());

        $search->filterById($productIds, Criteria::IN);

        return $search;
    }
}
