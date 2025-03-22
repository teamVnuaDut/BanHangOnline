<?php

namespace FSVendor\Octolize\ShippingExtensions;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FSVendor\WPDesk_Plugin_Info;
/**
 * .
 */
class PluginLinks implements Hookable
{
    private const BEFORE_LINK_ID = 'deactivate';
    private const LINK_ID = 'extensions';
    public const PLUGIN_LINKS_PAGE = 'plugins_links';
    /**
     * @var WPDesk_Plugin_Info .
     */
    private $plugin_info;
    /**
     * @param WPDesk_Plugin_Info $plugin_info .
     */
    public function __construct(WPDesk_Plugin_Info $plugin_info)
    {
        $this->plugin_info = $plugin_info;
    }
    /**
     * @return void
     */
    public function hooks(): void
    {
        add_filter('plugin_action_links_' . $this->plugin_info->get_plugin_file_name(), [$this, 'modify_plugin_actions']);
    }
    /**
     * @param mixed $actions .
     *
     * @return array
     */
    public function modify_plugin_actions($actions): array
    {
        $actions = is_array($actions) ? $actions : [];
        $new_links = [];
        foreach ($actions as $id => $link) {
            if ($id === self::BEFORE_LINK_ID) {
                $new_links[self::LINK_ID] = $this->get_extensions_link();
            }
            $new_links[$id] = $link;
        }
        return $new_links;
    }
    /**
     * @return string
     */
    private function get_extensions_link(): string
    {
        $extensions_link = add_query_arg('page', Page::MENU_SLUG, admin_url('admin.php'));
        $extensions_link = add_query_arg(self::PLUGIN_LINKS_PAGE, '', $extensions_link);
        return '<a href="' . esc_url($extensions_link) . '" style="color:#917dff;font-weight:bold;">' . _x('Extensions', 'Link on plugin list page', 'flexible-shipping') . '</a>';
    }
}
