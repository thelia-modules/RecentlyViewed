<?php

namespace RecentlyViewed\Service;

use RecentlyViewed\RecentlyViewed;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Thelia\Core\HttpFoundation\Request;

/**
 * Class RecentlyViewedManager
 *
 * @package RecentlyViewed\Service
 * @author Baixas Alban <abaixas@openstudio.fr>
 */
class RecentlyViewedManager
{
    const SERVICE_ID = 'recently.viewed.manager';

    /** @var  Session */
    protected $session;

    /** @var  array */
    protected $recentlyViewed;

    /**
     * @param Request $request
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
        if($this->session)
        {
            $this->recentlyViewed = $this->session->get('recentlyviewed');
        }
    }

    /**
     * add a product in recently viewed
     * @param $productId
     * @return bool
     */
    public function add($productId)
    {
        if ($productId === null) {
            return false;
        }

        if (null === $recentlyViewed = $this->getRecentlyViewed()) {
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
            return $this->recentlyViewed;
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
        if ($this->recentlyViewed !== null) {
            unset($this->recentlyViewed[array_search($productId, $this->recentlyViewed)]);
        }

        return $this->recentlyViewed;
    }

    /**
     * @param $recentlyViewed
     * @return $this
     */
    protected function setRecentlyViewed($recentlyViewed)
    {
        $this->session->set('recentlyviewed', $recentlyViewed);

        return $this;
    }
}
