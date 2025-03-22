<?php

namespace FSVendor\Octolize\Blocks;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
class Registrator implements Hookable
{
    private string $integration_name;
    private string $plugin_dir;
    private string $plugin_file;
    private IntegrationData $integration_data;
    private bool $register_frontend_styles;
    private bool $register_editor_styles;
    public function __construct(IntegrationData $integration_data, string $plugin_dir, string $plugin_file, bool $register_frontend_styles = \true, bool $register_editor_styles = \true)
    {
        $this->integration_data = $integration_data;
        $this->integration_name = $integration_data->get_integration_name();
        $this->plugin_dir = $plugin_dir;
        $this->plugin_file = $plugin_file;
        $this->register_frontend_styles = $register_frontend_styles;
        $this->register_editor_styles = $register_editor_styles;
    }
    public function hooks()
    {
        add_action('woocommerce_blocks_checkout_block_registration', function ($integration_registry) {
            $integration_registry->register(new CheckoutIntegration($this->integration_data, $this->plugin_dir, $this->plugin_file, $this->register_frontend_styles, $this->register_editor_styles));
        });
        add_action('woocommerce_blocks_cart_block_registration', function ($integration_registry) {
            $integration_registry->register(new CheckoutIntegration($this->integration_data, $this->plugin_dir, $this->plugin_file, $this->register_frontend_styles, $this->register_editor_styles));
        });
    }
    protected function get_integration_name(): string
    {
        return $this->integration_name;
    }
    protected function get_plugin_dir(): string
    {
        return $this->plugin_dir;
    }
    protected function get_plugin_file(): string
    {
        return $this->plugin_file;
    }
    protected function get_integration_data(): IntegrationData
    {
        return $this->integration_data;
    }
}
