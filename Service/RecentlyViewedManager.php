<?php

namespace RecentlyViewed\Service;

use RecentlyViewed\RecentlyViewed;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Core\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * Class RecentlyViewedManager
 *
 * @package RecentlyViewed\Service
 * @author  Baixas Alban <abaixas@openstudio.fr>
 */
class RecentlyViewedManager
{
    const RECENTLY_VIEWED_SESSION_NAME = 'recentlyviewed';

    public function __construct(
        protected RequestStack $requestStack
    )
    {
    }

    /**
     * add a product in recently viewed
     */
    public function add($productId): bool
    {
        if ($productId === null) {
            return false;
        }

        $recentlyViewed = $this->getRequest()->getSession()->get(self::RECENTLY_VIEWED_SESSION_NAME);
        if (null === $recentlyViewed) {
            return $this->save($productId);
        }

        if ($this->isAlreadyRegister($productId)) {
            return false;
        }

        if (count($recentlyViewed) <= RecentlyViewed::MAX) {
            return $this->save($productId);
        }

        array_shift($recentlyViewed);

        return $this->save($productId);
    }

    /**
     *
     * @param null $productId
     * @return array|mixed
     */
    public function getRecentlyViewed($productId = null)
    {
        if ($productId === null) {
            return $this->getRequest()->getSession()->get(self::RECENTLY_VIEWED_SESSION_NAME);
        }

        return $this->extractProduct($productId);
    }

    /**
     * Check if product in already in recently
     * @param $productId
     * @return bool
     */
    public function isAlreadyRegister($productId)
    {
        $recentlyViewed = $this->getRecentlyViewed();

        foreach ($recentlyViewed as $value) {
            if ($value === $productId) {
                return true;
            }
        }

        return false;
    }

    /**
     * register a product in recently viewed
     * @param $productId
     * @return bool
     */
    protected function save($productId)
    {
        $recentlyViewed = $this->getRecentlyViewed();

        $recentlyViewed[] = $productId;

        $this->setRecentlyViewed($recentlyViewed);

        return true;
    }

    /**
     * Remove a product in a recently viewed
     * @param $productId
     * @return array|mixed
     */
    protected function extractProduct($productId)
    {
        $recentlyViewed = $this->getRequest()->getSession()->get(self::RECENTLY_VIEWED_SESSION_NAME);
        if ($recentlyViewed !== null) {
            unset($recentlyViewed[array_search($productId, $recentlyViewed)]);
        }

        return $recentlyViewed;
    }

    /**
     * @param $recentlyViewed
     * @return $this
     */
    protected function setRecentlyViewed($recentlyViewed)
    {
        $this->getRequest()->getSession()->set(self::RECENTLY_VIEWED_SESSION_NAME, $recentlyViewed);

        return $this;
    }

    protected function getRequest(): Request
    {
        return $this->requestStack->getCurrentRequest();
    }

}
