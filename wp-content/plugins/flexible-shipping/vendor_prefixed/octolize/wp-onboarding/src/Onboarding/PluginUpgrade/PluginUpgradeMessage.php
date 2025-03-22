<?php

namespace FSVendor\Octolize\Onboarding\PluginUpgrade;

/**
 * Can provide upgrade data containing plugin upgrade message.
 */
class PluginUpgradeMessage
{
    /**
     * @var string
     */
    private $plugin_version;
    /**
     * @var string
     */
    private $image_url;
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $message;
    /**
     * @var string
     */
    private $link_text;
    /**
     * @var string
     */
    private $link_url;
    /**
     * @param string $plugin_version
     * @param string $image_url
     * @param string $title
     * @param string $message
     * @param string $link_text
     * @param string $link_url
     */
    public function __construct(string $plugin_version, string $image_url, string $title, string $message, string $link_text, string $link_url)
    {
        $this->plugin_version = $plugin_version;
        $this->image_url = $image_url;
        $this->title = $title;
        $this->message = $message;
        $this->link_text = $link_text;
        $this->link_url = $link_url;
    }
    /**
     * @return string
     */
    public function get_plugin_version(): string
    {
        return $this->plugin_version;
    }
    /**
     * @return string
     */
    public function get_image_url(): string
    {
        return $this->image_url;
    }
    /**
     * @return string
     */
    public function get_title(): string
    {
        return $this->title;
    }
    /**
     * @return string
     */
    public function get_message(): string
    {
        return $this->message;
    }
    /**
     * @return string
     */
    public function get_link_text(): string
    {
        return $this->link_text;
    }
    /**
     * @return string
     */
    public function get_link_url(): string
    {
        return $this->link_url;
    }
}
