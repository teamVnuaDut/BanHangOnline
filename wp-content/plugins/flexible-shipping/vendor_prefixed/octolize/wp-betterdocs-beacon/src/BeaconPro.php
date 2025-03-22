<?php

namespace FSVendor\Octolize\BetterDocs\Beacon;

use FSVendor\WPDesk\Beacon\BeaconShouldShowStrategy;
/**
 * Can display BetterDocs Beacon without confirmation.
 */
class BeaconPro extends Beacon
{
    public function __construct(BeaconOptions $beacon_options, BeaconShouldShowStrategy $strategy, $assets_url, $beacon_search_elements_class = 'search-input')
    {
        parent::__construct($beacon_options, $strategy, $assets_url, $beacon_search_elements_class);
        $this->confirmation_message = '';
    }
}
