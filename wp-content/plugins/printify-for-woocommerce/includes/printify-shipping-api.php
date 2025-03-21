<?php

class Printify_Shipping_API
{
    const PRINTIFY_API_SHIPPING_RATES_URL = 'https://api-prod.printify.com/v1/callbacks/woo/shipping?version=%s';

    private $version;

    public function __construct($version)
    {
        $this->version = $version;
    }

    public function get_shipping_rates(array $package)
    {
        $response = wp_remote_post(
            sprintf(
                self::PRINTIFY_API_SHIPPING_RATES_URL,
                $this->version
            ),
            [
                'timeout' => 10,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'body' => isset($package) ? json_encode($package) : null,
            ]
        );

        if(is_wp_error($response)) {
            error_log($response->get_error_message());
            return null;
        }

        return json_decode($response['body'], true);
    }
}
