<?php

namespace FSVendor\Octolize\BetterDocs\Beacon;

use FSVendor\WPDesk\Beacon\BeaconShouldShowStrategy;
/**
 * Can display Better Beacon.
 */
class Beacon
{
    /**
     * When to display beacon.
     *
     * @var BeaconShouldShowStrategy
     */
    private $activation_strategy;
    /**
     * @var string
     */
    private $assets_url;
    /**
     * @var string
     */
    private $beacon_search_elements_class;
    /**
     * @var string
     */
    protected $confirmation_message;
    /**
     * @var string
     */
    private $beacon_image_content;
    /**
     * @var BeaconOptions
     */
    private $beacon_options;
    public function __construct(BeaconOptions $beacon_options, BeaconShouldShowStrategy $strategy, $assets_url, $beacon_search_elements_class = 'search-input', $beacon_image_content = '')
    {
        $this->beacon_options = $beacon_options;
        $this->activation_strategy = $strategy;
        $this->assets_url = $assets_url;
        $this->beacon_search_elements_class = $beacon_search_elements_class;
        $this->confirmation_message = __('When you click OK we will open our BetterDocs beacon where you can find answers to your questions. This beacon will load our help articles and also potentially set cookies.', 'flexible-shipping');
        $this->beacon_image_content = $beacon_image_content;
    }
    /**
     * Hooks.
     */
    public function hooks()
    {
        add_action('admin_footer', [$this, 'add_beacon_to_footer']);
        add_action('admin_enqueue_scripts', [$this, 'add_beacon_js']);
    }
    /**
     * Should display beacon?
     *
     * @return bool
     */
    protected function should_display_beacon()
    {
        return $this->activation_strategy->shouldDisplay();
    }
    public function add_beacon_js()
    {
        if ($this->should_display_beacon()) {
        }
    }
    /**
     * Display Beacon script.
     */
    public function add_beacon_to_footer()
    {
        if ($this->should_display_beacon()) {
            $beacon_search_elements_class = $this->beacon_search_elements_class;
            $confirmation_message = $this->confirmation_message;
            $beacon_image_content = $this->beacon_image_content;
            $assets_url = $this->assets_url;
            $betterdocs_options = $this->beacon_options->get_options();
            include __DIR__ . '/views/html-beacon-script.php';
        }
    }
}
