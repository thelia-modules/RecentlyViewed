<?php

namespace RecentlyViewed\EventListeners;

use RecentlyViewed\Service\RecentlyViewedManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class Registration
 *
 * @package RecentlyViewed\EventListeners
 * @author  Baixas Alban <abaixas@openstudio.fr>
 */
class Registration implements EventSubscriberInterface
{

    /** @var RecentlyViewedManager */
    protected $recentlyViewedManager;

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::CONTROLLER => ['register', 35]];
    }

    /**
     * @param $recentlyViewedManager
     */
    public function __construct(RecentlyViewedManager $recentlyViewedManager)
    {
        $this->recentlyViewedManager = $recentlyViewedManager;
    }

    public function register(ControllerEvent $event)
    {
        $request = $event->getRequest();
        $view = $request->attributes->get('view') ?? $request->query->get('view');
        $productId = $request->attributes->get('product_id') ?? $request->query->get('product_id');

        if (('product' === $view || 'product' === $request->attributes->get('_view'))
            && null !== $productId
        ) {
            $this->recentlyViewedManager->add($productId, $request);
        }
    }
}
