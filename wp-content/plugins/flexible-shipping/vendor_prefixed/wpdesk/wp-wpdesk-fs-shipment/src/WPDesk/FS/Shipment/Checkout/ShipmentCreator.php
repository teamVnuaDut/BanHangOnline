<?php

namespace FSVendor\WPDesk\FS\Shipment\Checkout;

use FSVendor\WPDesk\Mutex\WordpressPostMutex;
use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
class ShipmentCreator implements Hookable
{
    /**
     * Is order processed on checkout?
     *
     * @var bool
     */
    private $is_order_processed_on_checkout = \false;
    public function hooks()
    {
        $last_priority = \PHP_INT_MAX;
        add_action('woocommerce_checkout_update_order_meta', array($this, 'create_shipping_for_order'), $last_priority);
        add_action('woocommerce_store_api_checkout_update_order_from_request', [$this, 'create_shipment_block_checkout'], $last_priority, 2);
    }
    /**
     * @param \WC_Order $order .
     * @param \WP_REST_Request $request .
     */
    public function create_shipment_block_checkout($order, $request)
    {
        if ($order && !$this->is_order_processed_on_checkout) {
            $shipments = fs_get_order_shipments($order->get_id());
            if (0 === count($shipments)) {
                $this->is_order_processed_on_checkout = \true;
                $this->create_shipping_for_order_and_cart($order, WC()->cart);
            }
        }
    }
    /**
     * Create shipping for order.
     *
     * @param int $order_id .
     */
    public function create_shipping_for_order($order_id)
    {
        $order = wc_get_order($order_id);
        if ($order && !$this->is_order_processed_on_checkout) {
            $mutex = $this->get_mutex($order);
            $mutex->acquireLock();
            $shipments = fs_get_order_shipments($order_id);
            if (0 === count($shipments)) {
                $this->is_order_processed_on_checkout = \true;
                $this->create_shipping_for_order_and_cart($order, WC()->cart);
            }
            $mutex->releaseLock();
        }
    }
    /**
     * @param \WC_Order $order .
     * @param \WC_Cart $cart .
     */
    public function create_shipping_for_order_and_cart($order, $cart)
    {
        if ($order === null || $cart === null) {
            return;
        }
        global $fs_package_id;
        $order_shipping_methods = $order->get_shipping_methods();
        $packages = $cart->get_shipping_packages();
        $current_package = -1;
        foreach ($order_shipping_methods as $shipping_id => $shipping_method) {
            $current_package++;
            $package_id = array_keys($packages)[$current_package];
            $fs_package_id = $package_id;
            $fs_method = $this->get_fs_method_from_order_shipping_method($shipping_method);
            if (!empty($fs_method['method_integration'])) {
                $integration = $fs_method['method_integration'];
                if (fs_shipment_integration_exists($integration) && $this->is_order_type_supported_by_integration($order->get_type(), $integration)) {
                    $shipment = $this->create_shipment_for_order_and_fs_shipping_method($order, $fs_method, $shipping_id, $shipping_method, $packages, $package_id);
                    /**
                     * Do actions when shipment is created via checkout.
                     *
                     * @param \WPDesk_Flexible_Shipping_Shipment $shipment Created shipment.
                     */
                    do_action('flexible_shipping_checkout_shipment_created', $shipment);
                }
            }
        }
    }
    /**
     * @param string $order_type .
     * @param string $integration .
     *
     * @return bool
     */
    private function is_order_type_supported_by_integration($order_type, $integration)
    {
        $supported = 'shop_order' === $order_type;
        $supported = apply_filters('flexible-shipping/shipment/supported-order-type/' . $integration, $supported, $order_type);
        return is_bool($supported) ? $supported : \false;
    }
    /**
     * @param \WC_Order $order .
     *
     * @return WordpressPostMutex
     * @codeCoverageIgnore
     */
    protected function get_mutex(\WC_Order $order)
    {
        return WordpressPostMutex::fromOrder($order);
    }
    /**
     * Create shipment for order and shipping method.
     *
     * @param \WC_Order               $order Order.
     * @param array                   $fs_method Flexible Shipping shipping method.
     * @param string                  $shipping_id Shipping Id.
     * @param \WC_Order_Item_Shipping $shipping_method Shipping method.
     * @param array                   $packages Packages.
     * @param int                     $package_id Package Id.
     *
     * @return \WPDesk_Flexible_Shipping_Shipment
     */
    private function create_shipment_for_order_and_fs_shipping_method(\WC_Order $order, array $fs_method, $shipping_id, \WC_Order_Item_Shipping $shipping_method, array $packages, $package_id)
    {
        $shipment = fs_create_shipment($order, $fs_method);
        try {
            $shipment->set_meta('_fs_method', $fs_method);
            $shipment->set_meta('_shipping_id', $shipping_id);
            $shipment->set_meta('_shipping_method', $shipping_method);
            $shipment->set_created_via_checkout();
            $shipment->checkout($fs_method, $packages[$package_id]);
            $shipment->save();
            return $shipment;
        } catch (\Exception $e) {
            wp_delete_post($shipment->get_id(), \true);
            throw $e;
        }
    }
    /**
     * Get Flexible Shipping method from order shipping method meta data.
     *
     * @param \WC_Order_Item_Shipping $shipping_method .
     *
     * @return array
     */
    private function get_fs_method_from_order_shipping_method($shipping_method)
    {
        $fs_method = $shipping_method->get_meta('_fs_method');
        if (!is_array($fs_method) || empty($fs_method)) {
            return [];
        }
        return $fs_method;
    }
}
