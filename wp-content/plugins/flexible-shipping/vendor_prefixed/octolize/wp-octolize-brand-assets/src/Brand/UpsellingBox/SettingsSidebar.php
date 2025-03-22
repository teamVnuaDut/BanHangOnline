<?php

/**
 * Settings sidebar.
 */
namespace FSVendor\Octolize\Brand\UpsellingBox;

use FSVendor\Octolize\Brand\Assets\AdminAssets;
use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FSVendor\WPDesk\ShowDecision\ShouldShowStrategy;
/**
 * Can display settings sidebar.
 */
class SettingsSidebar implements Hookable
{
    /**
     * @var string
     */
    private $action;
    /**
     * @var ShouldShowStrategy
     */
    private $should_show_strategy;
    /**
     * @var string
     */
    private $title;
    /**
     * @var array
     */
    private $features;
    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $label;
    /**
     * @var int
     */
    private $min_width;
    /**
     * @var int
     */
    private $position_right;
    /**
     * @var string
     */
    private string $additional_content;
    /**
     * @var string
     */
    private $align_top_to_element;
    public function __construct($action, ShouldShowStrategy $should_show_strategy, $title, array $features, $url, $label, $min_width = 1000, $position_right = 20, $align_top_to_element = '#mainform h2:first', $additional_content = '')
    {
        $this->action = $action;
        $this->should_show_strategy = $should_show_strategy;
        $this->title = $title;
        $this->features = $features;
        $this->url = $url;
        $this->label = $label;
        $this->min_width = $min_width;
        $this->position_right = $position_right;
        $this->align_top_to_element = $align_top_to_element;
        $this->additional_content = $additional_content;
    }
    /**
     * Hooks.
     */
    public function hooks(): void
    {
        add_action($this->action, [$this, 'maybe_display_settings_sidebar']);
    }
    public function set_additional_content(string $additional_content): void
    {
        $this->additional_content = $additional_content;
    }
    /**
     * Maybe display settings sidebar.
     */
    public function maybe_display_settings_sidebar(): void
    {
        if ($this->should_show_strategy->shouldDisplay()) {
            $title = $this->title;
            $features = $this->features;
            $url = $this->url;
            $label = $this->label;
            $min_width = $this->min_width;
            $position_right = $this->position_right;
            $align_top_to_element = $this->align_top_to_element;
            $additional_content = $this->additional_content;
            include __DIR__ . '/view/settings-sidebar-html.php';
        }
    }
}
