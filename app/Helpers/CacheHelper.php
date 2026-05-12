<?php

namespace App\Helpers;

use App\Services\CacheService;

/**
 * Cache helper functions for easy access to cache invalidation
 */
class CacheHelper
{
    /**
     * Invalidate all caches related to post changes
     * Call this when a post is created, updated, deleted, or status is toggled
     *
     * @return void
     */
    public static function invalidatePostCaches()
    {
        CacheService::invalidatePostCaches();
    }

    /**
     * Invalidate all caches related to category changes
     * Call this when a category is created, updated, or deleted
     *
     * @return void
     */
    public static function invalidateCategoryCaches()
    {
        CacheService::invalidateCategoryCaches();
    }

    /**
     * Invalidate all caches related to state changes
     * Call this when a state is created, updated, or deleted
     *
     * @return void
     */
    public static function invalidateStateCaches()
    {
        CacheService::invalidateStateCaches();
    }

    /**
     * Invalidate all home-related caches
     * Call this when you need to clear all home page caches at once
     *
     * @return void
     */
    public static function invalidateAllHomeCaches()
    {
        CacheService::invalidateAllHomeCaches();
    }
}
