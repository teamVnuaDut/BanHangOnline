<?php

namespace FSVendor\WPDesk\FS\Shipment\RestApi;

/**
 * Data providers factory.
 * @codeCoverageIgnore
 */
class OrderDataProvidersFactory
{
    /**
     * Providers.
     *
     * @var OrderDataProvidersCollection
     */
    private static $data_providers;
    /**
     * Get data providers.
     *
     * @return OrderDataProvidersCollection
     */
    public static function get_providers()
    {
        if (empty(self::$data_providers)) {
            self::$data_providers = new OrderDataProvidersCollection();
        }
        return self::$data_providers;
    }
}
