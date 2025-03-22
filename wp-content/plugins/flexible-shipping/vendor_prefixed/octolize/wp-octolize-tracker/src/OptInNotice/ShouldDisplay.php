<?php

namespace FSVendor\Octolize\Tracker\OptInNotice;

/**
 * Should display notice.
 */
interface ShouldDisplay
{
    /**
     * Notice should be displayed?
     *
     * @return bool
     */
    public function should_display();
}
