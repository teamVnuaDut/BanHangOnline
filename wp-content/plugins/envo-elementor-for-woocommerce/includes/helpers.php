<?php

/**
 * Helpers functions
 *
 * @package ETWW WordPress plugin
 */
// No direct access, please
if (!defined('ABSPATH'))
    exit;

/**
 * Get title tags
 *
 * @since 1.1.0
 *
 */
if (!function_exists('etww_get_available_tags')) {

    function etww_get_available_tags() {

        $tags = array(
            'h1' => __('H1', 'etww'),
            'h2' => __('H2', 'etww'),
            'h3' => __('H3', 'etww'),
            'h4' => __('H4', 'etww'),
            'h5' => __('H5', 'etww'),
            'h6' => __('H6', 'etww'),
            'div' => __('div', 'etww'),
            'span' => __('span', 'etww'),
            'p' => __('p', 'etww'),
        );
        $tags = apply_filters('etww_title_tags', $tags);

        return $tags;
    }

}

/**
 * Validate an HTML tag against a safe allowed list.
 *
 */
function etww_validate_html_tag( $tag ) {
		static $allowed_html_wrapper_tags = [
			'article',
			'aside',
			'div',
			'footer',
			'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6',
			'header',
			'main',
			'nav',
			'p',
			'section',
			'span',
		];

		return in_array( strtolower( $tag ), $allowed_html_wrapper_tags ) ? $tag : 'div';
	}


/**
 * Numbered Pagination
 *
 * @since	1.0.0
 * @link	https://codex.wordpress.org/Function_Reference/paginate_links
 */
if (!function_exists('etww_pagination')) {

    function etww_pagination($query = '', $echo = true) {

        // Arrows with RTL support
        $prev_arrow = is_rtl() ? 'fas fa-angle-right' : 'fas fa-angle-left';
        $next_arrow = is_rtl() ? 'fas fa-angle-left' : 'fas fa-angle-right';

        // Get global $query
        if (!$query) {
            global $wp_query;
            $query = $wp_query;
        }

        // Set vars
        $total = $query->max_num_pages;
        $big = 999999999;

        // Display pagination if total var is greater then 1 (current query is paginated)
        if ($total > 1) {

            // Get current page
            if ($current_page = get_query_var('paged')) {
                $current_page = $current_page;
            } elseif ($current_page = get_query_var('page')) {
                $current_page = $current_page;
            } else {
                $current_page = 1;
            }

            // Get permalink structure
            if (get_option('permalink_structure')) {
                if (is_page()) {
                    $format = 'page/%#%/';
                } else {
                    $format = '/%#%/';
                }
            } else {
                $format = '&paged=%#%';
            }

            $args = apply_filters('etww_pagination_args', array(
                'base' => str_replace($big, '%#%', html_entity_decode(get_pagenum_link($big))),
                'format' => $format,
                'current' => max(1, $current_page),
                'total' => $total,
                'mid_size' => 3,
                'type' => 'list',
                'prev_text' => '<i class="' . $prev_arrow . '"></i>',
                'next_text' => '<i class="' . $next_arrow . '"></i>',
            ));

            // Output pagination
            if ($echo) {
                echo '<div class="etww-pagination clr">' . wp_kses_post(paginate_links($args)) . '</div>';
            } else {
                return '<div class="etww-pagination clr">' . wp_kses_post(paginate_links($args)) . '</div>';
            }
        }
    }

}

/**
 * Get available sidebars
 *
 * @since 1.1.0
 *
 */
if (!function_exists('etww_get_available_sidebars')) {

    function etww_get_available_sidebars() {
        global $wp_registered_sidebars;

        $sidebars = array();

        if (!$wp_registered_sidebars) {
            $sidebars['0'] = __('No sidebars were found', 'etww');
        } else {
            $sidebars['0'] = __('-- Select --', 'etww');

            foreach ($wp_registered_sidebars as $id => $sidebar) {
                $sidebars[$id] = $sidebar['name'];
            }
        }

        return $sidebars;
    }

}

/**
 * Get available templates
 *
 * @since 1.1.0
 *
 */
if (!function_exists('etww_get_available_templates')) {

    function etww_get_available_templates() {
        $templates = get_posts(array(
            'post_type' => 'elementor_library',
            'posts_per_page' => -1
        ));

        $result = array(__('-- Select --', 'etww'));

        if (!empty($templates) && !is_wp_error($templates)) {
            foreach ($templates as $item) {
                $result[$item->ID] = $item->post_title;
            }
        }

        return $result;
    }

}



/**
 * Check if Contact Form 7 plugin is active
 *
 * @since 1.1.0
 *
 */
if (!function_exists('etww_is_contact_form_7_active')) {

    function etww_is_contact_form_7_active() {
        $return = false;

        if (class_exists('WPCF7_ContactForm')) {
            $return = true;
        }

        return $return;
    }

}


/**
 * Check if WooCommerce plugin is active
 *
 * @since 1.1.0
 *
 */
if (!function_exists('etww_is_woocommerce_active')) {

    function etww_is_woocommerce_active() {
        $return = false;

        if (class_exists('WooCommerce')) {
            $return = true;
        }

        return $return;
    }

}


/**
 * Custom excerpts based on wp_trim_words
 *
 * @since	1.0.0
 * @link	http://codex.wordpress.org/Function_Reference/wp_trim_words
 */
if (!function_exists('etww_excerpt')) {

    function etww_excerpt($length = 15) {

        // Get global post
        global $post;

        // Get post data
        $id = $post->ID;
        $excerpt = $post->post_excerpt;
        $content = $post->post_content;

        // Display custom excerpt
        if (!empty($excerpt) && !ctype_space($excerpt)) {
            $output = $excerpt;
        }

        // Check for more tag
        elseif (strpos($content, '<!--more-->')) {
            $output = get_the_content($excerpt);
        }

        // Generate auto excerpt
        else {
            $output = wp_trim_words(strip_shortcodes(get_the_content($id)), $length);
        }

        // Echo output
        echo wp_kses_post($output);
    }

}

/**
 * Ajax search
 *
 * @since	1.0.7
 */
if (!function_exists('etww_ajax_search')) {

    function etww_ajax_search() {

        $search = sanitize_text_field(wp_unslash($_POST['search']));
        $post_type = sanitize_text_field(wp_unslash($_POST['post_type']));
        $args = array(
            's' => $search,
            'post_type' => $post_type,
            'post_status' => 'publish',
            'posts_per_page' => 5,
        );
        $query = new WP_Query($args);
        $output = '';

        // Icons
        if (is_RTL()) {
            $icon = 'left';
        } else {
            $icon = 'right';
        }

        if ($query->have_posts()) {

            $output .= '<ul>';

            while ($query->have_posts()) : $query->the_post();
                $output .= '<li>';
                $output .= '<a href="' . get_permalink() . '" class="search-result-link clr">';

                if (has_post_thumbnail()) {
                    $output .= get_the_post_thumbnail(get_the_ID(), 'thumbnail', array('alt' => get_the_title(), 'itemprop' => 'image',));
                }

                $output .= '<div class="result-title">' . get_the_title() . '</div>';
                $output .= '<i class="icon fa fa-arrow-' . $icon . '" aria-hidden="true"></i>';
                $output .= '</a>';
                $output .= '</li>';
            endwhile;

            if ($query->found_posts > 1) {
                $search_link = get_search_link($search);

                /* if(strpos($search_link, '?') !== false) {
                  $search_link .= '?post_type='. $post_type;
                  } */

                $output .= '<li><a href="' . $search_link . '" class="all-results"><span>' . sprintf(esc_html__('View all %d results', 'etww'), $query->found_posts) . '<i class="fa fa-long-arrow-' . $icon . '" aria-hidden="true"></i></span></a></li>';
            }

            $output .= '</ul>';
        } else {

            $output .= '<div class="etww-no-search-results">';
            $output .= '<h6>' . esc_html__('No results', 'etww') . '</h6>';
            $output .= '<p>' . esc_html__('No search results could be found, please try another search.', 'etww') . '</p>';
            $output .= '</div>';
        }

        wp_reset_query();

        echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        die();
    }

    add_action('wp_ajax_etww_ajax_search', 'etww_ajax_search');
    add_action('wp_ajax_nopriv_etww_ajax_search', 'etww_ajax_search');
}
