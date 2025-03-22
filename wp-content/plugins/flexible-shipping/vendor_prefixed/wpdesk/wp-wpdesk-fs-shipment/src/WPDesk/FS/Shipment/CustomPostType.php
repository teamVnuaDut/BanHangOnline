<?php

/**
 * Class CustomPostType
 *
 * @package WPDesk\FS\Shipment
 */
namespace FSVendor\WPDesk\FS\Shipment;

use FSVendor\WPDesk\Mutex\WordpressPostMutex;
use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Can register custom post type.
 */
class CustomPostType implements Hookable
{
    const POST_TYPE_SHIPMENT = 'shipment';
    /**
     * Hooks.
     */
    public function hooks()
    {
        add_action('init', array($this, 'register_post_types'), 20);
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'), 20, 2);
        add_action('woocommerce_order_details_after_order_table', array($this, 'woocommerce_order_details_after_order_table'));
        add_action('woocommerce_email_after_order_table', array($this, 'woocommerce_email_after_order_table'), 10, 2);
    }
    /**
     * Register post types.
     */
    public function register_post_types()
    {
        if (post_type_exists(self::POST_TYPE_SHIPMENT)) {
            return;
        }
        register_post_type(self::POST_TYPE_SHIPMENT, array('labels' => array('name' => __('Shipments', 'flexible-shipping'), 'singular_name' => __('Shipment', 'flexible-shipping'), 'menu_name' => __('Shipments', 'flexible-shipping'), 'parent_item_colon' => '', 'all_items' => __('Shipments', 'flexible-shipping'), 'view_item' => __('View Shipments', 'flexible-shipping'), 'add_new_item' => __('Add new Shipment', 'flexible-shipping'), 'add_new' => __('Add new Shipment', 'flexible-shipping'), 'edit_item' => __('Edit Shipment', 'flexible-shipping'), 'update_item' => __('Save Shipment', 'flexible-shipping'), 'search_items' => __('Search Shipments', 'flexible-shipping'), 'not_found' => __('Shipment not found', 'flexible-shipping'), 'not_found_in_trash' => __('Shipment not found in trash', 'flexible-shipping')), 'description' => __('Shipments.', 'flexible-shipping'), 'public' => \false, 'show_ui' => \false, 'capability_type' => 'post', 'capabilities' => array(), 'map_meta_cap' => \true, 'publicly_queryable' => \false, 'exclude_from_search' => \true, 'hierarchical' => \false, 'query_var' => \true, 'supports' => array('title'), 'has_archive' => \false, 'show_in_nav_menus' => \true, 'menu_icon' => 'dashicons-upload'));
        $shipment_statuses = apply_filters('flexible_shipping_register_shipment_statuses', array('fs-new' => array('label' => _x('New', 'Shipment status', 'flexible-shipping'), 'public' => \false, 'exclude_from_search' => \false, 'show_in_admin_all_list' => \true, 'show_in_admin_status_list' => \true, 'label_count' => _n_noop('New <span class="count">(%s)</span>', 'New <span class="count">(%s)</span>', 'flexible-shipping')), 'fs-created' => array('label' => _x('Created', 'Shipment status', 'flexible-shipping'), 'public' => \false, 'exclude_from_search' => \false, 'show_in_admin_all_list' => \true, 'show_in_admin_status_list' => \true, 'label_count' => _n_noop('Created <span class="count">(%s)</span>', 'Created <span class="count">(%s)</span>', 'flexible-shipping')), 'fs-confirmed' => array('label' => _x('Confirmed', 'Shipment status', 'flexible-shipping'), 'public' => \false, 'exclude_from_search' => \false, 'show_in_admin_all_list' => \true, 'show_in_admin_status_list' => \true, 'label_count' => _n_noop('Confirmed <span class="count">(%s)</span>', 'Confirmed <span class="count">(%s)</span>', 'flexible-shipping')), 'fs-manifest' => array('label' => _x('Manifest created', 'Shipment status', 'flexible-shipping'), 'public' => \false, 'exclude_from_search' => \false, 'show_in_admin_all_list' => \true, 'show_in_admin_status_list' => \true, 'label_count' => _n_noop('Confirmed <span class="count">(%s)</span>', 'Confirmed <span class="count">(%s)</span>', 'flexible-shipping')), 'fs-failed' => array('label' => _x('Failed', 'Shipment status', 'flexible-shipping'), 'public' => \false, 'exclude_from_search' => \false, 'show_in_admin_all_list' => \true, 'show_in_admin_status_list' => \true, 'label_count' => _n_noop('Failed <span class="count">(%s)</span>', 'Failed <span class="count">(%s)</span>', 'flexible-shipping'))));
        foreach ($shipment_statuses as $shipment_status => $values) {
            register_post_status($shipment_status, $values);
        }
    }
    /**
     * Prepares class name for integration.
     *
     * @param string $integration .
     *
     * @return string
     */
    public function prepare_integration_class_name($integration)
    {
        return apply_filters('flexible_shipping_shipment_class', 'WPDesk_Flexible_Shipping_Shipment_' . $integration, $integration);
    }
    /**
     * @param string   $post_type .
     * @param \WP_Post $post .
     */
    public function add_meta_boxes($post_type, $post)
    {
        if (self::POST_TYPE_SHIPMENT === $post_type) {
            add_meta_box('shipment_meta_box', __('Shipment data', 'flexible-shipping'), array($this, 'metabox'), 'shipment', 'normal', 'high');
        }
        if (in_array($post_type, array('shop_order', 'shop_subscription', 'woocommerce_page_wc-orders'), \true)) {
            $order = wc_get_order($post);
            $shipments = fs_get_order_shipments($order->get_id());
            foreach ($shipments as $shipment) {
                if ($this->should_add_metabox($shipment)) {
                    $args = array('shipment' => $shipment);
                    add_meta_box('shipment_meta_box_' . $shipment->get_id(), $shipment->get_order_metabox_title(), array($this, 'order_metabox'), null, $shipment->get_order_metabox_context(), 'default', $args);
                }
            }
        }
    }
    /**
     * @param \WPDesk_Flexible_Shipping_Shipment $shipment .
     *
     * @return bool
     */
    private function should_add_metabox($shipment)
    {
        $avaliable_integrations = apply_filters('flexible_shipping_add_shipping_options', array());
        return isset($avaliable_integrations[$shipment->get_integration()]);
    }
    /**
     * @param \WP_Post $post .
     * @param array   $args .
     */
    public function order_metabox($post, $args)
    {
        /** @var \WPDesk_Flexible_Shipping_Shipment $shipment */
        $shipment = $args['args']['shipment'];
        $shipment_id = $shipment->get_id();
        $message = $shipment->get_error_message();
        $message_heading = $shipment->get_order_metabox_title();
        $message_css_style = '';
        include __DIR__ . '/views/order-metabox.php';
    }
    /**
     * .
     */
    public function metabox()
    {
        global $post;
        echo '<pre>';
        print_r($post);
        echo '</pre>';
        $meta_data = get_post_meta($post->ID);
        foreach ($meta_data as $key => $val) {
            echo '<pre>';
            echo esc_html($key);
            echo ' = ';
            print_r(maybe_unserialize($val[0]));
            echo '</pre>';
        }
    }
    /**
     * Hook woocommerce_order_details_after_order_table.
     *
     * @param \WC_Abstract_Order $order Order.
     */
    public function woocommerce_order_details_after_order_table($order)
    {
        $order_id = $order->get_id();
        $shipments = fs_get_order_shipments($order_id);
        foreach ($shipments as $shipment) {
            echo wp_kses_post($shipment->get_after_order_table() ?? '');
        }
    }
    /**
     * @param \WC_Order $order .
     * @param bool      $sent_to_admin .
     */
    public function woocommerce_email_after_order_table($order, $sent_to_admin)
    {
        $order_id = $order->get_id();
        $shipments = fs_get_order_shipments($order_id);
        foreach ($shipments as $shipment) {
            echo wp_kses_post($shipment->get_email_after_order_table() ?? '');
        }
    }
}
