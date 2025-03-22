<?php

namespace FSVendor\Octolize\ShippingExtensions\Plugin;

class PluginSorter
{
    /**
     * @var Plugin[]
     */
    private $plugins;
    /**
     * @var array|mixed
     */
    private $plugins_priority;
    /**
     * @var array|mixed
     */
    private $category_priority;
    /**
     * @param Plugin[] $plugins
     * @param array $plugins_priority
     * @param array $category_priority
     */
    public function __construct(array $plugins, array $plugins_priority = [], array $category_priority = [])
    {
        $this->plugins = $plugins;
        $this->plugins_priority = $plugins_priority;
        $this->category_priority = $category_priority;
    }
    /**
     * @return Plugin[]
     */
    public function sort(): array
    {
        usort($this->plugins, [$this, 'compare']);
        return $this->plugins;
    }
    public function compare(Plugin $a, Plugin $b): int
    {
        return $this->get_plugin_priority($a) <=> $this->get_plugin_priority($b);
    }
    private function get_plugin_priority(Plugin $plugin)
    {
        $priority = 0;
        $plugin_priority = array_search($plugin->get_plugin_file(), $this->plugins_priority);
        if ($plugin_priority !== \false) {
            $priority += -1000 + $plugin_priority * 100;
        }
        $category_priority = array_search($plugin->get_category(), $this->category_priority);
        if ($category_priority !== \false) {
            $priority += -100 + $category_priority;
        }
        return $priority;
    }
}
