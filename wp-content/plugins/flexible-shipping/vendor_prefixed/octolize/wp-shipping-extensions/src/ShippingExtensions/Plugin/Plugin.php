<?php

namespace FSVendor\Octolize\ShippingExtensions\Plugin;

use JsonSerializable;
/**
 * .
 */
class Plugin implements JsonSerializable
{
    private const DEFAULT_LOCALE = 'default';
    /**
     * @var string
     */
    private $plugin_name;
    /**
     * @var string
     */
    private $description;
    /**
     * @var string
     */
    private $icon;
    /**
     * @var string
     */
    private $plugin_file;
    /**
     * @var string
     */
    private $category;
    /**
     * @var string[]
     */
    private $urls;
    /**
     * @param string $plugin_name .
     * @param string $description .
     * @param string $icon .
     * @param string $plugin_file .
     * @param string $category .
     * @param string $plugin_url .
     */
    public function __construct(string $plugin_name, string $description, string $icon, string $plugin_file, string $category, string $plugin_url)
    {
        $this->plugin_name = $plugin_name;
        $this->description = $description;
        $this->icon = $icon;
        $this->plugin_file = $plugin_file;
        $this->category = $category;
        $this->add_url($plugin_url, self::DEFAULT_LOCALE);
    }
    /**
     * @return string
     */
    public function get_plugin_name(): string
    {
        return $this->plugin_name;
    }
    /**
     * @return string
     */
    public function get_description(): string
    {
        return $this->description;
    }
    /**
     * @param string $url
     * @param string $locale
     *
     * @return self
     */
    public function add_url(string $url, string $locale): self
    {
        $this->urls[$locale] = $url;
        return $this;
    }
    /**
     * @return string
     */
    public function get_icon(): string
    {
        return $this->icon;
    }
    /**
     * @return string
     */
    public function get_plugin_url(): string
    {
        return $this->urls[get_user_locale()] ?? $this->urls[self::DEFAULT_LOCALE];
    }
    /**
     * @return string
     */
    public function get_category(): string
    {
        return $this->category;
    }
    /**
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return array_key_exists($this->plugin_file, get_plugins());
    }
    /**
     * @return string
     */
    public function get_plugin_file(): string
    {
        return $this->plugin_file;
    }
    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return ['category' => $this->get_category(), 'icon' => $this->get_icon(), 'name' => $this->get_plugin_name(), 'description' => $this->get_description(), 'plugin_url' => $this->get_plugin_url(), 'buy_plugin_label' => $this->prepare_buy_plugin_label()];
    }
    private function prepare_buy_plugin_label(): string
    {
        return $this->category === PluginFactory::get_categories()[PluginFactory::CATEGORY_BUNDLES] ? __('Buy bundle →', 'flexible-shipping') : __('Buy plugin →', 'flexible-shipping');
    }
}
