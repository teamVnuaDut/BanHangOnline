<?php

namespace FSVendor\WPDesk\ShowDecision;

/**
 * Show when pages associated with given post type is displayed.
 */
class PostTypeStrategy implements ShouldShowStrategy
{
    /** @var string */
    private $post_type;
    public function __construct($post_type)
    {
        $this->post_type = $post_type;
    }
    /**
     * Should Beacon be visible?
     *
     * @return bool
     */
    public function shouldDisplay()
    {
        return $this->is_current_post_type_automation() || isset($_GET['post_type']) && $_GET['post_type'] === $this->post_type;
    }
    /**
     * @return bool
     */
    private function is_current_post_type_automation()
    {
        if (isset($_GET['post'])) {
            $post = get_post((int) $_GET['post']);
            if ($post instanceof \WP_Post) {
                return $post->post_type === $this->post_type;
            }
        }
        return \false;
    }
}
