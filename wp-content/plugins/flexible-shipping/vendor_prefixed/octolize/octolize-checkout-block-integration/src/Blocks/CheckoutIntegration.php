<?php

namespace FSVendor\Octolize\Blocks;

use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;
class CheckoutIntegration implements IntegrationInterface
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
    public function get_name(): string
    {
        return $this->integration_name;
    }
    public function initialize(): void
    {
        $this->register_block_frontend_scripts();
        if ($this->register_frontend_styles) {
            $this->register_block_frontend_styles();
        }
        $this->register_block_editor_scripts();
        if ($this->register_editor_styles) {
            $this->register_block_editor_styles();
        }
        $this->register_main_integration();
    }
    private function register_main_integration(): void
    {
        $script_path = $this->prepare_script_file_name('%s-index.js');
        $style_path = $this->prepare_script_file_name('style-index.css');
        $script_url = plugins_url($script_path, $this->plugin_file);
        $style_url = plugins_url($style_path, $this->plugin_file);
        $script_asset_path = $this->plugin_dir . $this->prepare_script_file_name('%s-index.asset.php');
        $script_asset = file_exists($script_asset_path) ? require $script_asset_path : ['dependencies' => [], 'version' => $this->get_file_version($script_path)];
        wp_register_script($this->integration_name . '-blocks-integration', $script_url, $script_asset['dependencies'], $script_asset['version'], \true);
        wp_set_script_translations($this->integration_name . '-blocks-integration', 'flexible-shipping', $this->plugin_dir . '/lang');
    }
    public function get_script_handles(): array
    {
        return [$this->integration_name . '-blocks-integration', $this->integration_name . '-blocks-integration-frontend'];
    }
    public function get_editor_script_handles(): array
    {
        return [$this->integration_name . '-blocks-integration', $this->integration_name . '-blocks-integration-editor'];
    }
    public function get_script_data(): array
    {
        return $this->integration_data->get_script_data();
    }
    public function register_block_frontend_styles(): void
    {
        $style_path = $this->prepare_script_file_name('style-index.css');
        $style_url = plugins_url($style_path, $this->plugin_file);
        wp_enqueue_style($this->integration_name . '-blocks-integration-frontend', $style_url, [], $this->get_file_version($style_path));
    }
    public function register_block_editor_styles(): void
    {
        $style_path = $this->prepare_script_file_name('style-index.css');
        $style_url = plugins_url($style_path, $this->plugin_file);
        wp_enqueue_style($this->integration_name . '-blocks-integration-editor', $style_url, [], $this->get_file_version($style_path));
    }
    public function register_block_editor_scripts(): void
    {
        $script_path = $this->prepare_script_file_name('%s-block.js');
        $script_url = plugins_url($script_path, $this->plugin_file);
        $script_asset_path = $this->plugin_dir . $this->prepare_script_file_name('%s-block.asset.php');
        $script_asset = file_exists($script_asset_path) ? require $script_asset_path : ['dependencies' => [], 'version' => $this->get_file_version($script_path)];
        wp_register_script($this->integration_name . '-blocks-integration-editor', $script_url, $script_asset['dependencies'], $script_asset['version'], \true);
        wp_set_script_translations($this->integration_name . '-blocks-integration-editor', 'flexible-shipping', $this->plugin_dir . '/lang');
    }
    public function register_block_frontend_scripts(): void
    {
        $script_path = $this->prepare_script_file_name('%s-frontend.js');
        $script_url = plugins_url($script_path, $this->plugin_file);
        $script_asset_path = $this->plugin_dir . $this->prepare_script_file_name('%s-frontend.asset.php');
        $script_asset = file_exists($script_asset_path) ? require $script_asset_path : ['dependencies' => [], 'version' => $this->get_file_version($script_path)];
        wp_register_script($this->integration_name . '-blocks-integration-frontend', $script_url, $script_asset['dependencies'], $script_asset['version'], \true);
        wp_set_script_translations($this->integration_name . '-blocks-integration-frontend', 'flexible-shipping', $this->plugin_dir . '/lang');
    }
    protected function get_file_version(string $file): string
    {
        if (file_exists($this->plugin_dir . $file)) {
            return filemtime($this->plugin_dir . $file);
        }
        return filemtime(__FILE__);
    }
    private function prepare_script_file_name(string $name): string
    {
        return $this->integration_data->get_script_path() . trim(sprintf($name, $this->integration_data->get_script_name()), '-');
    }
}
