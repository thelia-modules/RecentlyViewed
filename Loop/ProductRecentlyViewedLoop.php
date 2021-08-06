<?php


namespace RecentlyViewed\Loop;

use Propel\Runtime\ActiveQuery\Criteria;
use RecentlyViewed\Service\RecentlyViewedManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Thelia\Core\Security\SecurityContext;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Core\Template\Loop\Product as ProductLoop;
use Thelia\Model\ProductQuery;

/**
 * Class ProductRecentlyViewedLoop
 *
 * @package RecentlyViewed\Loop
 * @author Baixas Alban <abaixas@openstudio.fr>
 */
class ProductRecentlyViewedLoop extends ProductLoop
{

    /** @var  RecentlyViewedManager */
    protected $recentlyViewedManager;

    /**
     * ProductRecentlyViewedLoop constructor.
     * @param \Psr\Container\ContainerInterface $container
     * @param RequestStack $requestStack
     * @param EventDispatcherInterface $eventDispatcher
     * @param SecurityContext $securityContext
     * @param TranslatorInterface $translator
     * @param array $theliaParserLoops
     * @param $kernelEnvironment
     */
    public function __construct(\Psr\Container\ContainerInterface $container, RequestStack $requestStack, EventDispatcherInterface $eventDispatcher, SecurityContext $securityContext, TranslatorInterface $translator, array $theliaParserLoops, $kernelEnvironment)
    {
        parent::__construct($container, $requestStack, $eventDispatcher, $securityContext, $translator, $theliaParserLoops, $kernelEnvironment);

        $this->recentlyViewedManager = $container->get(RecentlyViewedManager::SERVICE_ID);
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
