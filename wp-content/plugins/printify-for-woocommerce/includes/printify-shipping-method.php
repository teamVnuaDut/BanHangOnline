<?php

require_once 'printify-shipping-api.php';

class Printify_Shipping_Method extends WC_Shipping_Method
{
    const WOO_TRUE = 'yes';
    const WOO_FALSE = 'no';

    const DEFAULT_ENABLED = self::WOO_TRUE;
    const DEFAULT_OVERRIDE = self::WOO_TRUE;
    const VERSION = '3.0';

    private $shipping_enabled;
    private $shipping_override;
    private $printifyApiClient;
    private $isPrintifyPackage;

    public function __construct()
    {
        parent::__construct();

        $this->id = 'printify_shipping';
        $this->method_title = 'Printify Shipping';
        $this->method_description = 'Calculate live shipping rates based on actual Printify shipping costs.';
        $this->title = 'Printify Shipping';
        $this->printifyApiClient = new Printify_Shipping_API(self::VERSION);

        $this->init();

        $this->shipping_enabled = isset($this->settings['enabled']) ? $this->settings['enabled'] : self::DEFAULT_ENABLED;
        $this->shipping_override = isset($this->settings['override_defaults']) ? $this->settings['override_defaults'] : self::DEFAULT_OVERRIDE;
    }

    public function init_form_fields()
    {
        $this->form_fields = [
            'enabled' => [
                'title' => 'Enabled',
                'type' => 'checkbox',
                'label' => 'Enable Printify Shipping Method plugin',
                'default' => self::DEFAULT_ENABLED,
            ],
            'override_defaults' => [
                'title' => 'Override',
                'type' => 'checkbox',
                'label' => 'Override standard WooCommerce shipping rates',
                'default' => self::DEFAULT_OVERRIDE,
            ],
        ];
    }

    function init()
    {
        $this->init_form_fields();
        $this->init_settings();

        add_action('woocommerce_update_options_shipping_' . $this->id, [$this, 'process_admin_options']);

        add_action('woocommerce_load_shipping_methods', [$this, 'load_shipping_methods']);

        add_filter('woocommerce_shipping_methods', [$this, 'add_printify_shipping_method']);

        add_filter('woocommerce_cart_shipping_packages', [$this, 'calculate_shipping_rates']);
    }

    function add_printify_shipping_method($methods)
    {
        return self::WOO_TRUE === $this->shipping_override && true === $this->isPrintifyPackage
            ? []
            : $methods;
    }

    function load_shipping_methods($package)
    {
        $this->isPrintifyPackage = false;

        if (!$package) {
            WC()->shipping()->register_shipping_method($this);

            return;
        }

        if (self::WOO_FALSE === $this->enabled) {
            return;
        }

        if (isset($package['managed_by_printify']) && true === $package['managed_by_printify']) {
            if (self::WOO_TRUE === $this->shipping_override) {
                WC()->shipping()->unregister_shipping_methods();
            }

            $this->isPrintifyPackage = true;

            WC()->shipping()->register_shipping_method($this);
        }
    }

    public function calculate_shipping_rates($packages = [])
    {
        if ($this->shipping_enabled !== self::WOO_TRUE) {
            return $packages;
        }

        $requestParameters = [
            'skus' => [],
            'address' => [],
        ];
        foreach ($packages as $package) {
            // Collect skus and quantity
            foreach ($package['contents'] as $variation) {
                /** @var WC_Product_Variation $productVariation */
                if ($variation && $variation['data']) {
                    $productVariation = $variation['data'];

                    if (!isset($requestParameters['skus'][$productVariation->get_sku()])) {
                        $requestParameters['skus'][$productVariation->get_sku()] = [
                            'sku' => $productVariation->get_sku(),
                            'product_id' => $productVariation->get_id(),
                            'parent_id' => $productVariation->get_parent_id(),
                            'permalink' => $productVariation->get_permalink(),
                            'quantity' => $variation['quantity'],
                        ];
                    } else {
                        $requestParameters['skus'][$productVariation->get_sku()] = [
                            'sku' => $productVariation->get_sku(),
                            'product_id' => $productVariation->get_id(),
                            'parent_id' => $productVariation->get_parent_id(),
                            'permalink' => $productVariation->get_permalink(),
                            'quantity' => $requestParameters['skus'][$productVariation->get_sku()]['quantity'] + $variation['quantity'],
                        ];
                    }
                }
            }
            $requestParameters['address'] = [
                'country' => $package['destination']['country'],
                'state' => $package['destination']['state'],
                'zip' => isset($package['destination']['postcode']) ? $package['destination']['postcode'] : null,
            ];
        }

        if (!count($requestParameters['address'])) {
            return $packages;
        }


        // Collect shipping rates for found skus
        $printifyShippingRates = $this->printifyApiClient->get_shipping_rates(
            [
                'items' => $requestParameters['skus'],
                'country' => $requestParameters['address']['country'],
                'state' => $requestParameters['address']['state'],
                'zip' => isset($requestParameters['address']['postcode']) ? $requestParameters['address']['postcode'] : null,
            ]
        );

        if (null === $printifyShippingRates || empty($printifyShippingRates['skus'])) {
            return $packages;
        }

        $splittedVariations = [
            'prinify' => [],
            'other' => [],
        ];

        foreach ($packages as $package) {
            foreach ($package['contents'] as $variation) {
                /** @var WC_Product_Variation $productVariation */
                $productVariation = $variation['data'];

                if (in_array($productVariation->get_sku(), $printifyShippingRates['skus'])) {
                    $expressRate = isset($printifyShippingRates['shipping_express'])
                        ? $printifyShippingRates['shipping_express']
                        : null;

                    $splittedVariations['printify']['shipping_rates'] = [
                        'standard' => $printifyShippingRates['shipping_standart'],
                        'express' => $expressRate,
                    ];
                    $splittedVariations['printify']['variations'][] = $variation;
                } else {
                    $splittedVariations['other']['variations'][] = $variation;
                }
            }
        }

        $splittedPackages = [];

        foreach ($packages as $package) {
            foreach ($splittedVariations as $variationOwner => $splittedVariation) {
                if (!count($splittedVariation)) {
                    continue;
                }

                $splittedPackage = $package;
                $splittedPackage['contents_cost'] = 0;
                $splittedPackage['contents'] = [];

                if ('printify' === $variationOwner) {
                    $splittedPackage['managed_by_printify'] = true;
                    $splittedPackage['printify_shipping_rates'] = $splittedVariation['shipping_rates'];
                }

                foreach ($splittedVariation['variations'] as $variation) {
                    /** @var WC_Product_Variation $productVariation */
                    $productVariation = $variation['data'];

                    $splittedPackage['contents'][] = $variation;

                    if ($productVariation->needs_shipping() && isset($variation['line_total'])) {
                        $splittedPackage['contents_cost'] += $variation['line_total'];
                    }
                }

                $splittedPackages[] = $splittedPackage;
            }
        }

        return $splittedPackages;
    }

    public function calculate_shipping($package = [])
    {
        if (isset($package['managed_by_printify']) && $package['managed_by_printify'] === true) {
            $this->add_rate([
                'id' => $this->id . '_s',
                'label' => 'Standard',
                'cost' => $package['printify_shipping_rates']['standard']['cost'] / 100,
                'calc_tax' => 'per_order',
            ]);
        }

        if (isset($package['managed_by_printify']) && $package['managed_by_printify'] === true && isset($package['printify_shipping_rates']['express'])) {
            $this->add_rate([
                'id' => $this->id . '_e',
                'label' => 'Express',
                'cost' => $package['printify_shipping_rates']['express']['cost'] / 100,
                'calc_tax' => 'per_order',
            ]);
        }
    }
}
