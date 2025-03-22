<?php

namespace FSVendor\Octolize\ShippingExtensions\Tracker;

/**
 * .
 */
class ViewPageTracker
{
    private const OPTION_NAME = 'octolize_shipping_extensions_views';
    public const OPTION_DIRECT = 'direct';
    public const OPTION_PLUGINS_LIST = 'plugins_list';
    private const DEFAULT_OPTIONS = [self::OPTION_DIRECT => 0, self::OPTION_PLUGINS_LIST => 0];
    /**
     * @param string $type
     *
     * @return int
     */
    public function get_views(string $type): int
    {
        $options = $this->get_options();
        return (int) ($options[$type] ?? 0);
    }
    /**
     * @return self
     */
    public function add_view_direct(): self
    {
        return $this->update_views(self::OPTION_DIRECT);
    }
    /**
     * @return self
     */
    public function add_view_plugins_list(): self
    {
        return $this->update_views(self::OPTION_PLUGINS_LIST);
    }
    /**
     * @return bool
     */
    public function option_exists(): bool
    {
        return !empty($this->get_options([]));
    }
    /**
     * @param string $type
     *
     * @return self
     */
    private function update_views(string $type): self
    {
        $options = $this->get_options();
        $options[$type]++;
        update_option(self::OPTION_NAME, $options, 'yes');
        return $this;
    }
    /**
     * @param mixed $default .
     *
     * @return array
     */
    private function get_options($default = self::DEFAULT_OPTIONS): array
    {
        return (array) get_option(self::OPTION_NAME, $default);
    }
}
