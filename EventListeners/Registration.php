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
 * @author Baixas Alban <abaixas@openstudio.fr>
 */
class Registration implements EventSubscriberInterface
{

    /** @var RecentlyViewedManager  */
    protected $recentlyViewedManager;

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
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
        if (('product' === $event->getRequest()->get('view') || 'product' === $event->getRequest()->get('_view'))
            && null !== $productId = $event->getRequest()->get('product_id')
        ) {
            $this->recentlyViewedManager->add($productId);
        }
    }
}
