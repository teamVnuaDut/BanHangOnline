<?php

/**
 * TimeWatcher interface.
 *
 * @package WPDesk\RepositoryRating
 */
namespace FSVendor\WPDesk\RepositoryRating;

/**
 * Simple interface for tracking creation time of certaing things.
 *
 * @package WPDesk\RepositoryRating
 */
interface TimeWatcher
{
    /**
     * Return creation time of watched item.
     *
     * @return string Returns date string. Can also return '' when no creation time is found.
     */
    public function get_creation_time();
}
