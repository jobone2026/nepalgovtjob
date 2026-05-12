<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Cache TTL in seconds (10 minutes)
     */
    const CACHE_TTL = 600;

    /**
     * Cache key for home page sections
     */
    const HOME_SECTIONS_KEY = 'home_sections';

    /**
     * Cache key for categories list
     */
    const CATEGORIES_LIST_KEY = 'categories_list';

    /**
     * Cache key for states list
     */
    const STATES_LIST_KEY = 'states_list';

    /**
     * Get cached home sections or generate if not cached
     *
     * @param callable $callback Function to generate home sections
     * @return mixed
     */
    public static function getHomeSections(callable $callback)
    {
        return Cache::remember(self::HOME_SECTIONS_KEY, self::CACHE_TTL, $callback);
    }

    /**
     * Get cached categories list or generate if not cached
     *
     * @param callable $callback Function to generate categories list
     * @return mixed
     */
    public static function getCategoriesList(callable $callback)
    {
        return Cache::remember(self::CATEGORIES_LIST_KEY, self::CACHE_TTL, $callback);
    }

    /**
     * Get cached states list or generate if not cached
     *
     * @param callable $callback Function to generate states list
     * @return mixed
     */
    public static function getStatesList(callable $callback)
    {
        return Cache::remember(self::STATES_LIST_KEY, self::CACHE_TTL, $callback);
    }

    /**
     * Invalidate home sections cache
     *
     * @return bool
     */
    public static function invalidateHomeSections()
    {
        return Cache::forget(self::HOME_SECTIONS_KEY);
    }

    /**
     * Invalidate categories list cache
     *
     * @return bool
     */
    public static function invalidateCategoriesList()
    {
        return Cache::forget(self::CATEGORIES_LIST_KEY);
    }

    /**
     * Invalidate states list cache
     *
     * @return bool
     */
    public static function invalidateStatesList()
    {
        return Cache::forget(self::STATES_LIST_KEY);
    }

    /**
     * Invalidate all home-related caches
     *
     * @return void
     */
    public static function invalidateAllHomeCaches()
    {
        self::invalidateHomeSections();
        self::invalidateCategoriesList();
        self::invalidateStatesList();
    }

    /**
     * Invalidate caches related to post changes
     *
     * @return void
     */
    public static function invalidatePostCaches()
    {
        self::invalidateHomeSections();
    }

    /**
     * Invalidate caches related to category changes
     *
     * @return void
     */
    public static function invalidateCategoryCaches()
    {
        self::invalidateCategoriesList();
    }

    /**
     * Invalidate caches related to state changes
     *
     * @return void
     */
    public static function invalidateStateCaches()
    {
        self::invalidateStatesList();
    }
}
