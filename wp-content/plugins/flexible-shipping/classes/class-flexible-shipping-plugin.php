<?php
/**
 * Plugin.
 *
 * @package Flexible Shippign
 */

use FSVendor\Octolize\Blocks\IntegrationData;
use FSVendor\Octolize\Blocks\Registrator;
use FSVendor\Octolize\Blocks\StoreEndpoint;
use FSVendor\Octolize\Brand\Assets\AdminAssets;
use FSVendor\Octolize\Brand\UpsellingBox\ShippingMethodShouldShowStrategy;
use FSVendor\Octolize\ShippingExtensions\ShippingExtensions;
use FSVendor\Octolize\Tracker\DeactivationTracker\OctolizeReasonsFactory;
use FSVendor\Octolize\Tracker\OptInNotice\ShouldDisplayAndConditions;
use FSVendor\Octolize\Tracker\OptInNotice\ShouldDisplayGetParameterValue;
use FSVendor\Octolize\Tracker\OptInNotice\ShouldDisplayOrConditions;
use FSVendor\Octolize\Tracker\OptInNotice\ShouldDisplayShippingMethodInstanceSettings;
use FSVendor\Octolize\Tracker\TrackerInitializer;
use FSVendor\Psr\Log\LoggerInterface;
use FSVendor\Psr\Log\NullLogger;
use FSVendor\WPDesk\FS\Compatibility\PluginCompatibility;
use FSVendor\WPDesk\FS\Shipment\ShipmentFunctionality;
use FSVendor\WPDesk\FS\TableRate\Logger\Assets;
use FSVendor\WPDesk\Logger\SimpleLoggerFactory;
use FSVendor\WPDesk\Mutex\WordpressPostMutex;
use FSVendor\WPDesk\Notice\AjaxHandler;
use FSVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;
use FSVendor\WPDesk\PluginBuilder\Plugin\Activateable;
use FSVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use FSVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use FSVendor\WPDesk\PluginBuilder\Plugin\TemplateLoad;
use FSVendor\WPDesk\RepositoryRating\DisplayStrategy\ShippingMethodDisplayDecision;
use FSVendor\WPDesk\RepositoryRating\RepositoryRatingPetitionText;
use FSVendor\WPDesk\RepositoryRating\TextPetitionDisplayer;
use FSVendor\WPDesk\Session\SessionFactory;
use FSVendor\WPDesk\View\Resolver\ChainResolver;
use FSVendor\WPDesk\View\Resolver\DirResolver;
use FSVendor\WPDesk\View\Resolver\WPThemeResolver;
use FSVendor\WPDesk\WooCommerce\CurrencySwitchers\FilterConvertersFactory;
use FSVendor\WPDesk\WooCommerce\CurrencySwitchers\ShippingIntegrations;
use WPDesk\FS\Blocks\FreeShipping\FreeShippingBlock;
use WPDesk\FS\Blocks\FreeShipping\FreeShippingStoreEndpointData;
use WPDesk\FS\Helpers\FlexibleShippingMethodsChecker;
use WPDesk\FS\Helpers\WooSettingsPageChecker;
use WPDesk\FS\Integration\ExternalPluginAccess;
use WPDesk\FS\Onboarding\TableRate\FinishOption;
use WPDesk\FS\Onboarding\TableRate\Onboarding;
use WPDesk\FS\Onboarding\TableRate\OptionAjaxUpdater;
use WPDesk\FS\Onboarding\TableRate\PopupData;
use WPDesk\FS\Plugin\PluginActivation;
use WPDesk\FS\ProFeatures;
use WPDesk\FS\ProVersion\ProVersionUpdateReminder;
use WPDesk\FS\Shipment;
use WPDesk\FS\TableRate\Beacon\Beacon;
use WPDesk\FS\TableRate\Beacon\BeaconClickedAjax;
use WPDesk\FS\TableRate\Beacon\BeaconDeactivationTracker;
use WPDesk\FS\TableRate\Beacon\BeaconDisplayStrategy;
use WPDesk\FS\TableRate\ContextualInfo;
use WPDesk\FS\TableRate\Debug\DebugTracker;
use WPDesk\FS\TableRate\Debug\MultipleShippingZonesMatchedSameTerritoryNotice;
use WPDesk\FS\TableRate\Debug\MultipleShippingZonesMatchedSameTerritoryTracker;
use WPDesk\FS\TableRate\Debug\NoShippingMethodsNotice;
use WPDesk\FS\TableRate\FreeShipping\FreeShippingNotice;
use WPDesk\FS\TableRate\FreeShipping\FreeShippingNoticeGenerator;
use WPDesk\FS\TableRate\FreeShipping\FreeShippingNoticeRenderer;
use WPDesk\FS\TableRate\FreeShipping\NoticeTextSettings;
use WPDesk\FS\TableRate\FreeShipping\ProgressBarSettings;
use WPDesk\FS\TableRate\ImporterExporter\Exporter;
use WPDesk\FS\TableRate\ImporterExporter\ExporterData;
use WPDesk\FS\TableRate\ImporterExporter\ImporterData;
use WPDesk\FS\TableRate\MultiCurrency;
use WPDesk\FS\TableRate\Order\ItemMeta;
use WPDesk\FS\TableRate\Rule\PreconfiguredScenarios;
use WPDesk\FS\TableRate\Rule\TrackerData;
use WPDesk\FS\TableRate\RulesSettingsField;
use WPDesk\FS\TableRate\ShippingMethod\BlockEditing\BlockEditing;
use WPDesk\FS\TableRate\ShippingMethod\Convert\ConvertAction;
use WPDesk\FS\TableRate\ShippingMethod\Convert\ConvertNotice;
use WPDesk\FS\TableRate\ShippingMethod\Convert\ConvertTracker;
use WPDesk\FS\TableRate\ShippingMethod\Duplicate\DuplicateAction;
use WPDesk\FS\TableRate\ShippingMethod\Duplicate\DuplicateNotice;
use WPDesk\FS\TableRate\ShippingMethod\Duplicate\DuplicateScript;
use WPDesk\FS\TableRate\ShippingMethod\Duplicate\DuplicateTracker;
use WPDesk\FS\TableRate\ShippingMethod\Duplicate\DuplicatorChecker;
use WPDesk\FS\TableRate\ShippingMethod\Management\ShippingMethodManagement;
use WPDesk\FS\TableRate\ShippingMethod\MethodDescription;
use WPDesk\FS\TableRate\ShippingMethod\MethodTitle;
use WPDesk\FS\TableRate\ShippingMethodSingle;
use WPDesk\FS\TableRate\ShippingMethodsIntegration\ShippingRate;
use WPDesk\FS\TableRate\Tax\Tracker;
use WPDesk\FS\TableRate\UserFeedback;
use WPDesk\WooCommerceCartWeight\Block\StoreEndpointData;

/**
 * Class Flexible_Shipping_Plugin
 */
class Flexible_Shipping_Plugin extends AbstractPlugin implements HookableCollection, Activateable {

	use HookableParent;
	use TemplateLoad;

	/*
	 * Plugin file
	 */
	const PLUGIN_FILE = 'flexible-shipping/flexible-shipping.php';

	const PRIORITY_BEFORE_SHARED_HELPER = -35;
	const PRIORITY_LAST                 = 999999;
	const PRIORITY_AFTER_DEFAULT        = 20;
	const FS_FREE_SHIPPING_NOTICE_NAME  = 'fs_free_shipping_notice';

	/**
	 * Scripts version.
	 *
	 * @var string
	 */
	private $scripts_version = '2';

	/**
	 * Admin notices.
	 *
	 * @var WPDesk_Flexible_Shipping_Admin_Notices
	 */
	public $admin_notices;

	/**
	 * Default settings tab.
	 *
	 * @var string
	 */
	private $default_settings_tab = 'connect';

	/**
	 * Renderer.
	 *
	 * @var FSVendor\WPDesk\View\Renderer\Renderer;
	 */
	private $renderer;

	/**
	 * Is order processed on checkout?
	 *
	 * @var bool
	 */
	private $is_order_processed_on_checkout = false;

	/**
	 * Logger.
	 *
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * Flexible_Invoices_Reports_Plugin constructor.
	 *
	 * @param FSVendor\WPDesk_Plugin_Info $plugin_info Plugin info.
	 */
	public function __construct( FSVendor\WPDesk_Plugin_Info $plugin_info ) {
		$this->scripts_version = FLEXIBLE_SHIPPING_VERSION . '.' . $this->scripts_version;
		$this->plugin_info     = $plugin_info;
		parent::__construct( $this->plugin_info );
		$this->init_logger();
		$this->init_logger_on_shipment();
	}

	/**
	 * Init logger on WPDesk_Flexible_Shipping_Shipment class.
	 */
	private function init_logger_on_shipment() {
//		WPDesk_Flexible_Shipping_Shipment::set_fs_logger( $this->logger );
	}

	/**
	 * Init logger on WPDesk_Flexible_Shipping class.
	 *
	 * @internal
	 */
	public function init_logger_on_shipping_method() {
		WPDesk_Flexible_Shipping::set_fs_logger( $this->logger );
		ShippingMethodSingle::set_fs_logger( $this->logger );
		ShippingRate::set_fs_logger( $this->logger );
	}

	/**
	 * Initialize $this->logger
	 *
	 * @return LoggerInterface
	 */
	private function init_logger() {
		$logger_settings = new WPDesk_Flexible_Shipping_Logger_Settings();
		if ( $logger_settings->is_enabled() ) {
			add_filter( 'wpdesk_is_wp_log_capture_permitted', '__return_false' );

			return $this->logger = ( new SimpleLoggerFactory( $logger_settings->get_logger_channel_name() ) )->getLogger();
		}

		return $this->logger = new NullLogger();
	}


	/**
	 * Load dependencies.
	 */
	private function load_dependencies() {
		global $wpdb;

		require_once __DIR__ . '/../inc/functions.php';

		$session_factory = new SessionFactory();

		$this->add_hookable( new ShipmentFunctionality( $this->logger, trailingslashit( $this->get_plugin_assets_url() ) . '../vendor_prefixed/wpdesk/wp-wpdesk-fs-shipment/assets/', $this->scripts_version ) );

		$this->add_hookable( new Shipment\FilterOrders() );
		$this->add_hookable( new Shipment\ModifyStatuses() );
		$this->add_hookable( new Shipment\DispatchLabelFile() );
		$this->add_hookable( new Shipment\BulkAction( $session_factory ) );
		$this->add_hookable( new Shipment\AdminNotices( $session_factory ) );
		$this->add_hookable( new Shipment\ModifyOrderTable( $session_factory ) );

		new WPDesk_Flexible_Shipping_Multilingual( $this );

		$this->admin_notices = new WPDesk_Flexible_Shipping_Admin_Notices( $this );

		$this->add_hookable( new WPDesk\FS\Rate\WPDesk_Flexible_Shipping_Rate_Notice() );

		if ( class_exists( '\WC_Shipping_Zones' ) ) {
			$this->add_hookable(
				new TextPetitionDisplayer(
					'woocommerce_after_settings_shipping',
					new ShippingMethodDisplayDecision( new \WC_Shipping_Zones(), ShippingMethodSingle::SHIPPING_METHOD_ID ),
					new RepositoryRatingPetitionText(
						'Octolize',
						$this->plugin_info->get_plugin_name(),
						'https://octol.io/fs-rate',
						'center'
					)
				)
			);
		}


		$this->add_hookable( new WPDesk_Flexible_Shipping_Shorcode_Unit_Weight() );
		$this->add_hookable( new WPDesk_Flexible_Shipping_Shorcode_Unit_Dimension() );

		$this->add_hookable( new AjaxHandler( trailingslashit( $this->get_plugin()->get_plugin_url() ) . 'vendor_prefixed/wpdesk/wp-notice/assets' ) );

		$this->add_hookable( new WPDesk_Flexible_Shipping_Method_Created_Tracker_Deactivation_Data() );

		$this->add_hookable( new ShippingIntegrations( 'flexible_shipping' ) );

		$this->add_hookable( new WPDesk_Flexible_Shipping_Order_Item_Meta() );

		$this->add_hookable( new Assets( trailingslashit( $this->get_plugin_url() ) . 'vendor_prefixed/wpdesk/wp-wpdesk-fs-table-rate/assets', $this->scripts_version ) );

		$this->add_hookable( new NoShippingMethodsNotice(
			current_user_can( 'manage_woocommerce' ) && 'yes' === get_option( 'woocommerce_shipping_debug_mode', 'no' )
		) );

		$this->add_hookable( new DebugTracker() );

		$this->add_hookable( new PluginCompatibility() );

		$this->add_hookable( new UserFeedback() );

		//Onboarding
		$setting_page_checker = new WooSettingsPageChecker();
		$fs_methods_checker   = new FlexibleShippingMethodsChecker();

		$finish_option = new FinishOption();
		$this->add_hookable( new OptionAjaxUpdater( $finish_option ) );
		$this->add_hookable( new Onboarding(
				$finish_option,
				$this->scripts_version,
				trailingslashit( $this->get_plugin_assets_url() ),
				$setting_page_checker,
				$fs_methods_checker
			)
		);
		$this->add_hookable( new \WPDesk\FS\Onboarding\TableRate\Tracker( $finish_option ) );

		$this->add_hookable( new ShippingExtensions( $this->plugin_info ) );

		$this->add_hookable( new MethodTitle() );
		$this->add_hookable( new MethodDescription( $this->renderer ) );

		$this->add_hookable( new Exporter() );
		$this->add_hookable( new ImporterData() );
		$this->add_hookable( new ExporterData() );

		$shipping_method_management = new ShippingMethodManagement( $wpdb );

		// Converter.
		$this->add_hookable( new ConvertAction( $shipping_method_management ) );
		$this->add_hookable( new ConvertNotice() );
		$this->add_hookable( new ConvertTracker() );

		// Duplicate FS Method.
		$duplicator_checker = new DuplicatorChecker( $shipping_method_management );
		$this->add_hookable( new DuplicateAction( $duplicator_checker, $shipping_method_management ) );
		$this->add_hookable( new DuplicateNotice() );
		$this->add_hookable( new DuplicateTracker() );
		$this->add_hookable( new DuplicateScript(
				trailingslashit( $this->get_plugin_assets_url() ),
				$this->scripts_version
			)
		);

		// Block Settings.
		$this->add_hookable( new BlockEditing() );

		$this->add_hookable( new ItemMeta() );

		$this->add_hookable( new PreconfiguredScenarios\Tracker\AjaxTracker() );
		$this->add_hookable( new PreconfiguredScenarios\Tracker\Tracker() );

		// Pro Features.
		$tracking_data = new ProFeatures\Tracker\TrackingData();
		$this->add_hookable( new ProFeatures\Tracker\AjaxTracker( $tracking_data ) );
		$this->add_hookable( new ProFeatures\Tracker\Tracker( $tracking_data ) );


		$this->add_hookable( new PluginActivation() );

		$this->add_hookable( new MultipleShippingZonesMatchedSameTerritoryTracker() );

		$this->add_hookable( new Tracker() );

		$this->add_hookable( new ProVersionUpdateReminder( 'pl_PL' === get_locale() ) );

		$this->add_hookable( new \WPDesk\FS\Plugin\PluginLinks( $this->get_plugin_file_path() ) );

		$brand_assets_url = $this->get_plugin_assets_url() . '../vendor_prefixed/octolize/wp-octolize-brand-assets/assets/';

		$this->add_hookable( new \WPDesk\FS\AdvertMetabox\ProPluginAdvertMetabox( $brand_assets_url ) );
		$this->add_hookable( new \WPDesk\FS\AdvertMetabox\FsiePluginAdvertMetabox( $brand_assets_url ) );

		$should_show_strategy = new ShippingMethodShouldShowStrategy( \WPDesk_Flexible_Shipping_Settings::METHOD_ID );
		$this->add_hookable( new AdminAssets( $brand_assets_url, 'fs', $should_show_strategy ) );

		// Shipping methods integration.
		$this->add_hookable( new \WPDesk\FS\TableRate\ShippingMethodsIntegration\Integration() );

		// Upgrade onboarding
		$this->add_hookable( new WPDesk\FS\Plugin\UpgradeOnboarding( $this->plugin_info ) );

	}

	/**
	 * Init beacon.
	 */
	public function init_beacon() {
		if ( 'pl_PL' !== get_locale() ) {
			$strategy = new BeaconDisplayStrategy();

			$beacon = new Beacon(
				$strategy,
				trailingslashit( $this->get_plugin_url() ) . 'vendor_prefixed/octolize/wp-betterdocs-beacon/assets/'
			);
			$beacon->hooks();

			$beacon_clicked_ajax = new BeaconClickedAjax( $strategy, $this->get_plugin_assets_url(), $this->scripts_version );
			$beacon_clicked_ajax->hooks();

			( new BeaconDeactivationTracker() )->hooks();
		}
	}

	/**
	 * Init contextual info on Flexible Shipping settings fields.
	 *
	 * @internal
	 */
	public function init_contextual_info() {
		$base_location           = wc_get_base_location();
		$base_country            = $base_location['country'];
		$contextual_info_creator = new ContextualInfo\Creator( $base_country );
		$contextual_info_creator->create_contextual_info();
		$contextual_info_creator->hooks();
	}

	/**
	 * Init base variables for plugin
	 */
	public function init_base_variables() {
		$this->plugin_url = $this->plugin_info->get_plugin_url();

		$this->plugin_path   = $this->plugin_info->get_plugin_dir();
		$this->template_path = $this->plugin_info->get_text_domain();

		$this->plugin_namespace     = $this->plugin_info->get_text_domain();
		$this->template_path        = $this->plugin_info->get_text_domain();
		$this->default_settings_tab = 'main';

		$this->settings_url = admin_url( 'admin.php?page=flexible-shipping-settings' );
		$this->docs_url     = get_locale() === 'pl_PL' ? 'https://octol.io/fs-docs-pl' : 'https://octol.io/fs-docs';
	}

	/**
	 * Set renderer.
	 */
	private function init_renderer() {
		$resolver = new ChainResolver();
		$resolver->appendResolver( new WPThemeResolver( $this->get_template_path() ) );
		$resolver->appendResolver( new DirResolver( trailingslashit( $this->plugin_path ) . 'templates' ) );
		$this->renderer = new FSVendor\WPDesk\View\Renderer\SimplePhpRenderer( $resolver );
	}

	/**
	 * Init.
	 */
	public function init() {
		$this->init_base_variables();
		$this->init_renderer();
		$this->load_dependencies();
		$this->init_tracker();
		$this->hooks();
		$this->init_assets_url_on_rules_settings_field();
		$this->init_checkout_blocks();
	}

	/**
	 * register_activation_hook handler.
	 *
	 * @internal
	 */
	public function activate() {
		( new PluginActivation() )->add_activation_option_if_not_present();
	}

	/**
	 * Hooks.
	 */
	public function hooks() {
		parent::hooks();

		add_filter( 'woocommerce_shipping_methods', [
			$this,
			'woocommerce_shipping_methods_filter',
		], self::PRIORITY_AFTER_DEFAULT );

		add_filter(
			'flexible_shipping_method_rate_id',
			[
				$this,
				'flexible_shipping_method_rate_id',
			],
			9999999,
			2
		);

		add_filter(
			'woocommerce_shipping_chosen_method',
			[
				$this,
				'woocommerce_default_shipment_method',
			],
			10,
			3
		);

		add_action(
			'woocommerce_checkout_create_order',
			[
				$this,
				'add_flexible_shipping_order_meta_on_checkout',
			]
		);

		add_filter( 'option_woocommerce_cod_settings', [ $this, 'option_woocommerce_cod_settings' ] );

		add_action( 'woocommerce_init', [ $this, 'init_free_shipping_notice' ] );

		add_action( 'woocommerce_init', [ $this, 'init_logger_on_shipping_method' ] );

		add_action( 'woocommerce_init', [ $this, 'init_contextual_info' ] );

		add_action( 'woocommerce_init', [ $this, 'init_beacon' ] );

		add_action( 'woocommerce_init', [ $this, 'init_shipping_zones_notice' ] );

		add_action( 'woocommerce_init', [ $this, 'init_multicurrency' ] );

		add_action( 'woocommerce_init', [ $this, 'init_external_plugin_access' ] );

		$this->hooks_on_hookable_objects();
	}

	private function init_checkout_blocks() {
		( new FreeShippingBlock( [ self::FS_FREE_SHIPPING_NOTICE_NAME ] ) )->hooks();

		$integration_data = ( new IntegrationData() )
			->set_integration_name( $this->plugin_namespace . '-free-shipping-notice-block-integration' )
			->set_script_name( '' )
			->set_script_path( '/assets/blocks/free-shipping-notice-block-integration/' );
		( new StoreEndpoint( $integration_data->get_integration_name(), 'page_type', false ) )->hooks();
		( new FreeShippingStoreEndpointData( $integration_data->get_integration_name() ) )->hooks();
		( new Registrator(
			$integration_data,
			$this->plugin_path,
			$this->get_plugin_file_path(),
			false,
			false
		) )->hooks();
	}


	/**
	 * .
	 */
	public function init_multicurrency() {
		$prefix = $this->plugin_info->get_plugin_dir();
		( new FilterConvertersFactory( $prefix ) )->hooks();
		( new MultiCurrency( $this->logger, $prefix ) )->hooks();
	}

	/**
	 * .
	 */
	public function init_external_plugin_access() {
		do_action( 'flexible-shipping/core/initialized', new ExternalPluginAccess( $this->plugin_info->get_version(), $this->logger ) );
	}

	/**
	 * .
	 * @internal
	 */
	public function init_shipping_zones_notice() {
		( new MultipleShippingZonesMatchedSameTerritoryNotice( WC()->countries, new WC_Shipping_Zones() ) )->hooks();
	}

	/**
	 *
	 */
	private function init_assets_url_on_rules_settings_field() {
		RulesSettingsField::set_assets_url( untrailingslashit( $this->get_plugin_assets_url() ) );
	}

	/**
	 * Init free shipping notice.
	 *
	 * @internal
	 */
	public function init_free_shipping_notice() {
		$cart    = WC()->cart;
		$session = WC()->session;
		( new FreeShippingNoticeGenerator( null, null, self::FS_FREE_SHIPPING_NOTICE_NAME ) )->hooks();
		( new FreeShippingNoticeRenderer( $this->renderer ) )->hooks();
		if ( $cart instanceof WC_Cart && $session instanceof WC_Session ) {
			( new FreeShippingNotice( $cart, $session, self::FS_FREE_SHIPPING_NOTICE_NAME ) )->hooks();
			( new \WPDesk\FS\TableRate\FreeShipping\Assets( $this->get_plugin_assets_url(), $this->scripts_version ) )->hooks();
		}
		( new NoticeTextSettings() )->hooks();
		( new ProgressBarSettings() )->hooks();
		( new \WPDesk\FS\TableRate\FreeShipping\Tracker() )->hooks();
	}

	/**
	 * Init tracker.
	 */
	private function init_tracker() {
		add_action( 'woocommerce_init', function () {
			TrackerInitializer::create_from_plugin_info(
				$this->plugin_info,
				$this->prepare_shoud_display_for_flexible_shipping(),
				new OctolizeReasonsFactory(
					'https://octol.io/fs-docs-exit-pop-up',
					'https://octol.io/fs-support-forum-exit-pop-up',
					__( 'Flexible Shipping PRO', 'flexible-shipping' ),
					'https://octol.io/fs-contact-exit-pop-up',
				)
			)->hooks();
			( new WPDesk_Flexible_Shipping_Tracker() )->hooks();
			( new TrackerData() )->hooks();
		} );
	}

	/**
	 * @return ShouldDisplayOrConditions
	 */
	private function prepare_shoud_display_for_flexible_shipping() {
		$should_display = new ShouldDisplayOrConditions();
		$should_display->add_should_diaplay_condition( new ShouldDisplayShippingMethodInstanceSettings( ShippingMethodSingle::SHIPPING_METHOD_ID ) );
		$should_display_and_conditions = new ShouldDisplayAndConditions();
		$should_display_and_conditions->add_should_diaplay_condition(
			new ShouldDisplayGetParameterValue( 'page', 'wc-settings' )
		);
		$should_display_and_conditions->add_should_diaplay_condition(
			new ShouldDisplayGetParameterValue( 'tab', 'shipping' )
		);
		$should_display_and_conditions->add_should_diaplay_condition(
			new ShouldDisplayGetParameterValue( 'section', WPDesk_Flexible_Shipping_Settings::METHOD_ID )
		);
		$should_display->add_should_diaplay_condition( $should_display_and_conditions );

		return $should_display;
	}

	/**
	 * Woocommerce shipping methods filter.
	 *
	 * @param array $methods .
	 *
	 * @return array
	 */
	public function woocommerce_shipping_methods_filter( $methods ) {
		$methods[ ShippingMethodSingle::SHIPPING_METHOD_ID ] = ShippingMethodSingle::class;
		$methods[ WPDesk_Flexible_Shipping::METHOD_ID ]      = WPDesk_Flexible_Shipping::class;

		if ( $this->can_register_fs_info() ) {
			$methods[ WPDesk_Flexible_Shipping_Settings::METHOD_ID ] = WPDesk_Flexible_Shipping_Settings::class;
		}

		return $methods;
	}

	/**
	 * @return bool
	 */
	private function can_register_fs_info() {
		if ( ! is_admin() ) {
			return false;
		}

		return ! $this->is_adding_order_page() && ! $this->is_editing_order_page();
	}

	/**
	 * @return bool
	 */
	private function is_editing_order_page() {
		$post   = filter_input( INPUT_GET, 'post' );
		$action = filter_input( INPUT_GET, 'action' );

		return 'edit' === $action && $post;
	}

	/**
	 * @return bool
	 */
	private function is_adding_order_page() {
		$action = filter_input( INPUT_POST, 'action' );

		return wp_doing_ajax() && 'woocommerce_add_order_shipping' === $action;
	}

	/**
	 * Option woocommerce cod settings filter.
	 *
	 * @param array $value .
	 *
	 * @return array
	 */
	public function option_woocommerce_cod_settings( $value ) {
		if ( is_checkout() ) {
			if (
				! empty( $value )
				&& is_array( $value )
				&& 'yes' === $value['enabled']
				&& ! empty( $value['enable_for_methods'] )
				&& is_array( $value['enable_for_methods'] )
			) {
				foreach ( $value['enable_for_methods'] as $method ) {
					if ( 'flexible_shipping' === $method ) {
						$all_fs_methods          = flexible_shipping_get_all_shipping_methods();
						$all_shipping_methods    = flexible_shipping_get_all_shipping_methods();
						$flexible_shipping       = $all_shipping_methods['flexible_shipping'];
						$flexible_shipping_rates = $flexible_shipping->get_all_rates();
						foreach ( $flexible_shipping_rates as $flexible_shipping_rate ) {
							$value['enable_for_methods'][] = $flexible_shipping_rate['id_for_shipping'];
						}
						break;
					}
				}
			}
		}

		return $value;
	}

	/**
	 * Add flexible shipping order meta on checkout.
	 *
	 * @param WC_Order $order Order.
	 */
	public function add_flexible_shipping_order_meta_on_checkout( $order ) {
		if ( ! $this->is_order_processed_on_checkout ) {
			$mutex = WordpressPostMutex::fromOrder( $order );
			$mutex->acquireLock();
			$this->is_order_processed_on_checkout = true;
			$order_shipping_methods               = $order->get_shipping_methods();
			foreach ( $order_shipping_methods as $shipping_id => $shipping_method ) {
				if ( isset( $shipping_method['item_meta'] )
				     && isset( $shipping_method['item_meta']['_fs_method'] )
				) {
					$fs_method = $shipping_method['item_meta']['_fs_method'];
					if ( ! empty( $fs_method['method_integration'] ) ) {
						$order_meta = $order->get_meta( '_flexible_shipping_integration', false );
						if ( ! in_array( $fs_method['method_integration'], $order_meta, true ) ) {
							$order->add_meta_data( '_flexible_shipping_integration', $fs_method['method_integration'] );
						}
					}
				}
			}
			$mutex->releaseLock();
		}
	}

	/**
	 * Set appropriate default FS method if no method chosen.
	 *
	 * @param string             $default           Default shipping method in frontend.
	 * @param WC_Shipping_Rate[] $available_methods Available methods in frontend.
	 *                                              Function is assigned to woocommerce_default_shipment_method filter.
	 *                                              In this parameter we expecting array of WC_Shipping_Rate objects.
	 *                                              But third party plugins can change this parameter type or set it to
	 *                                              null.
	 * @param string|bool|null   $chosen_method     If false or null then no method is chosen.
	 *
	 * @return string
	 */
	public function woocommerce_default_shipment_method( $default, $available_methods, $chosen_method ) {
		if ( ! is_array( $available_methods ) ) {
			return $default;
		}
		if ( null === $chosen_method || false === $chosen_method ) {
			foreach ( $available_methods as $available_method ) {
				$method_meta = $available_method->get_meta_data();
				if ( $method_meta && isset( $method_meta[ WPDesk_Flexible_Shipping::META_DEFAULT ] ) && 'yes' === $method_meta[ WPDesk_Flexible_Shipping::META_DEFAULT ] ) {
					$candidate_id = $available_method->get_id();
					if ( array_key_exists( $candidate_id, $available_methods ) ) {
						return $candidate_id;
					}
				}
			}
		}

		return $default;
	}

	/**
	 * @param string $suffix .
	 */
	private function enqueue_rules_scripts( $suffix ) {
		wp_register_script(
			'fs_rules_settings',
			trailingslashit( $this->get_plugin_assets_url() ) . 'js/rules-settings.js',
			[ 'wp-i18n' ],
			$this->scripts_version
		);
		wp_enqueue_script( 'fs_rules_settings' );
		wp_enqueue_style(
			'fs_rules_settings',
			trailingslashit( $this->get_plugin_assets_url() ) . 'css/rules-settings.css',
			[],
			$this->scripts_version
		);
		wp_set_script_translations( 'fs_rules_settings', 'flexible-shipping', $this->plugin_path . '/lang/' );

	}

	/**
	 * Admin enqueue scripts.
	 */
	public function admin_enqueue_scripts() {
		if ( $this->should_enqueue_admin_scripts() ) {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			wp_register_script(
				'fs_admin',
				trailingslashit( $this->get_plugin_assets_url() ) . 'js/admin' . $suffix . '.js',
				[ 'jquery' ],
				$this->scripts_version
			);

			$notice_url = get_locale() == 'pl_PL' ? 'https://octol.io/fs-rate-not-good-pl' : 'https://octol.io/fs-rate-not-good';
			wp_localize_script(
				'fs_admin',
				'fs_admin',
				[
					'ajax_url'                => admin_url( 'admin-ajax.php' ),
					'notice_not_good_enought' => sprintf(
					// Translators: link.
						__( 'How can We make Flexible Shipping better for you? %1$sJust write to us.%2$s', 'flexible-shipping' ),
						'<a class="button close-fs-rate-notice" target="_blank" href="' . esc_url( $notice_url ) . '">',
						'</a>'
					),
				]
			);
			wp_enqueue_script( 'fs_admin' );

			$current_screen = get_current_screen();

			wp_register_script(
				'wpdesk_contextual_info',
				trailingslashit( $this->get_plugin_assets_url() ) . 'js/contextual-info' . $suffix . '.js',
				[ 'jquery' ],
				$this->scripts_version
			);
			wp_enqueue_script( 'wpdesk_contextual_info' );

			if ( ! empty( $current_screen ) && 'shop_order' === $current_screen->id ) {
				wp_enqueue_media();
			}

			wp_enqueue_style(
				'fs_admin',
				trailingslashit( $this->get_plugin_assets_url() ) . 'css/admin.css',
				[],
				$this->scripts_version
			);
			wp_enqueue_style(
				'fs_font',
				trailingslashit( $this->get_plugin_assets_url() ) . 'css/font' . $suffix . '.css',
				[],
				$this->scripts_version
			);

			$this->enqueue_rules_scripts( $suffix );

			do_action( 'flexible-shipping/admin/enqueue_scripts', $this, $suffix );

		}
	}

	/**
	 * Should enqueue admin scripts?
	 */
	private function should_enqueue_admin_scripts() {
		$current_screen = get_current_screen();

		if ( ! $current_screen ) {
			return false;
		}

		$wc_screen_id = sanitize_title( __( 'WooCommerce', 'woocommerce' ) );

		if (
			'woocommerce_page_wc-orders' === $current_screen->id
			|| in_array( $current_screen->post_type, [ 'shop_order', 'shop_subscription', ], true )
			|| $wc_screen_id . '_page_wc-settings' === $current_screen->id
			|| 'woocommerce_page_wc-settings' === $current_screen->id ) {
			return true;
		}

		return false;
	}

	/**
	 * Enqueue Wordpress scripts.
	 */
	public function wp_enqueue_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	}

	/**
	 * Links filter.
	 *
	 * @param array $links .
	 *
	 * @return array
	 */
	public function links_filter( $links ) {
		$settings_url = admin_url( 'admin.php?page=wc-settings&tab=shipping&section=' . \WPDesk_Flexible_Shipping_Settings::METHOD_ID );

		$plugin_links = [
			'<a href="' . $settings_url . '">' . __(
				'Settings',
				'flexible-shipping'
			) . '</a>',
		];

		if ( ! defined( 'FLEXIBLE_SHIPPING_PRO_VERSION' )  ) {
			$pro_link     = get_locale() === 'pl_PL' ? 'https://octol.io/fs-upgrade-pl' : 'https://octol.io/fs-upgrade';
			$plugin_links[] = '<a href="' . esc_url( $pro_link ) . '" target="_blank" style="color:#00B62E;font-weight:bold;">' . __(
					'Buy PRO',
					'flexible-shipping'
				) . '</a>';
		}

		return array_merge( $plugin_links, $links );
	}

	/**
	 * .
	 * @param string $method_id       .
	 * @param array  $shipping_method .
	 *
	 * @return string
	 */
	public function flexible_shipping_method_rate_id( $method_id, array $shipping_method ) {
		if ( isset( $shipping_method['id_for_shipping'] ) && '' !== $shipping_method['id_for_shipping'] ) {
			$method_id = $shipping_method['id_for_shipping'];
		}

		return $method_id;
	}


}
