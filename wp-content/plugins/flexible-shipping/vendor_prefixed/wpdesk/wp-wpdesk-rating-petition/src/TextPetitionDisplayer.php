<?php

namespace FSVendor\WPDesk\RepositoryRating;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FSVendor\WPDesk\RepositoryRating\DisplayStrategy\DisplayDecision;
/**
 * Can display text petition.
 */
class TextPetitionDisplayer implements Hookable
{
    const SCRIPTS_VERSION = '2';
    /**
     * @var string
     */
    private $display_on_action;
    /**
     * @var DisplayDecision
     */
    private $display_decision;
    /**
     * @var PetitionText
     */
    private $petition_text_generator;
    /**
     * @param string $display_on_action
     * @param DisplayDecision $display_decision
     * @param PetitionText $petition_text_generator
     */
    public function __construct(string $display_on_action, DisplayDecision $display_decision, PetitionText $petition_text_generator)
    {
        $this->display_on_action = $display_on_action;
        $this->display_decision = $display_decision;
        $this->petition_text_generator = $petition_text_generator;
    }
    public function hooks()
    {
        add_action($this->display_on_action, [$this, 'display_petition_if_should']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_css_if_should']);
    }
    /**
     * @internal
     */
    public function enqueue_css_if_should()
    {
        if ($this->display_decision->should_display()) {
            wp_enqueue_style('flexible-shipping', plugin_dir_url(__DIR__ . '/../assets/css/style.css') . 'style.css', array(), self::SCRIPTS_VERSION);
        }
    }
    /**
     * @internal
     */
    public function display_petition_if_should()
    {
        if ($this->display_decision->should_display()) {
            echo wp_kses_post($this->petition_text_generator->get_petition_text());
        }
    }
}
