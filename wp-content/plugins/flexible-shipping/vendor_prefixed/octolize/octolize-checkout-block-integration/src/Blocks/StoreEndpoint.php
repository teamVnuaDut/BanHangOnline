<?php

namespace FSVendor\Octolize\Blocks;

use Automattic\WooCommerce\StoreApi\Schemas\V1\CartSchema;
use Automattic\WooCommerce\StoreApi\Schemas\V1\CheckoutSchema;
use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Shipping Workshop Extend Store API.
 */
class StoreEndpoint implements Hookable
{
    protected string $integration_name;
    protected string $field_name;
    private bool $has_schema_callback;
    public function __construct(string $integration_name, string $field_name = 'field', bool $has_schema_callback = \true)
    {
        $this->integration_name = $integration_name;
        $this->field_name = $field_name;
        $this->has_schema_callback = $has_schema_callback;
    }
    public function hooks(): void
    {
        add_action('woocommerce_blocks_loaded', function () {
            if (function_exists('woocommerce_store_api_register_endpoint_data')) {
                woocommerce_store_api_register_endpoint_data($this->prepare_checkout_endpoint_data());
                woocommerce_store_api_register_endpoint_data($this->prepare_cart_endpoint_data());
            }
        });
    }
    protected function prepare_checkout_endpoint_data(): array
    {
        $data = $this->prepare_endpoint_data();
        $data['endpoint'] = CheckoutSchema::IDENTIFIER;
        return $data;
    }
    protected function prepare_cart_endpoint_data(): array
    {
        $data = $this->prepare_endpoint_data();
        $data['endpoint'] = CartSchema::IDENTIFIER;
        return $data;
    }
    private function prepare_endpoint_data(): array
    {
        $data = ['endpoint' => $this->field_name, 'namespace' => $this->integration_name, 'data_callback' => [$this, 'data_callback'], 'schema_type' => \ARRAY_A];
        if ($this->has_schema_callback) {
            $data['schema_callback'] = [$this, 'schema_callback'];
        }
        return $data;
    }
    public function data_callback(): array
    {
        return apply_filters('octolize-checkout-block-integration-' . $this->integration_name . '-data', [$this->field_name => '']);
    }
    public function schema_callback(): array
    {
        return apply_filters('octolize-checkout-block-integration-' . $this->integration_name . '-schema', [$this->field_name => ['description' => __('Field', 'flexible-shipping'), 'type' => ['string'], 'context' => ['view', 'edit'], 'readonly' => \false, 'optional' => \false, 'arg_options' => ['validate_callback' => function ($value) {
            return \true;
        }]]]);
    }
}
