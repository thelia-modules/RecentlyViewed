<?php

namespace RecentlyViewed\Smarty\Plugins;

use RecentlyViewed\Service\RecentlyViewedManager;
use TheliaSmarty\Template\AbstractSmartyPlugin;
use TheliaSmarty\Template\SmartyPluginDescriptor;

/**
 * Class RecentlyViewed
 *
 * @package RecentlyViewed\Smarty\RecentlyViewed
 * @author Baixas Alban <abaixas@openstudio.fr>
 */
class RecentlyViewed extends AbstractSmartyPlugin
{
    /** @var  RecentlyViewedManager */
    protected $recentlyViewedManager;

    /**
     * @param $recentlyViewedManager
     */
    public function __construct($recentlyViewedManager)
    {
        $this->recentlyViewedManager = $recentlyViewedManager;
    }

    /**
     * @return array of SmartyPluginDescriptor
     */
    public function getPluginDescriptors()
    {
        return [
            new SmartyPluginDescriptor("function", "get_recently_viewed", $this, "getRecentlyViewed"),
        ];
    }

    /**
     * @param array $productId['productId'] $productId
     * @return int|string
     */
    public function getRecentlyViewed($productId)
    {
        if (isset($productId['productId'])) {
            $productId = $productId['productId'];
        } else {
            $productId = null;
        }

        if (null === $recentlyViewed = $this->recentlyViewedManager->getRecentlyViewed($productId)) {
            return '';
        }

        return implode(',', $recentlyViewed);
    }
}
