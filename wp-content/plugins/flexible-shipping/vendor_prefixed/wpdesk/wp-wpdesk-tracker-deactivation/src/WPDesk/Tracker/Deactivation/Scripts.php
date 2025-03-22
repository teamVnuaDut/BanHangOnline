<?php

namespace FSVendor\WPDesk\Tracker\Deactivation;

/**
 * Can generate deactivation scripts.
 */
class Scripts
{
    use DeactivationContent;
    /**
     * Constructor.
     *
     * @param PluginData  $plugin_data .
     * @param string|null $view_file .
     */
    public function __construct(PluginData $plugin_data, ReasonsFactory $reasons_factory, $view_file = null)
    {
        $this->plugin_data = $plugin_data;
        $this->reasons_factory = $reasons_factory;
        if (!empty($view_file)) {
            $this->view_file = $view_file;
        } else {
            $this->view_file = __DIR__ . '/views/scripts.php';
        }
    }
}
