<?php
/**
 * @package WPDesk\FS\TableRate
 */

namespace WPDesk\FS\TableRate;

use WPDesk\FS\TableRate\Rule\Condition\ConditionsFactory;
use WPDesk\FS\TableRate\Rule\Cost\RuleAdditionalCostFieldsFactory;
use WPDesk\FS\TableRate\Rule\Cost\RuleAdditionalCostFactory;
use WPDesk\FS\TableRate\Rule\Cost\RuleCostFieldsFactory;
use WPDesk\FS\TableRate\Rule\PreconfiguredScenarios\PreconfiguredScenariosFactory;
use WPDesk\FS\TableRate\Rule\SpecialAction\SpecialActionFieldsFactory;
use WPDesk\FS\TableRate\Rule\SpecialAction\SpecialActionFactory;
use WPDesk\FS\ProFeatures;
use WPDesk\FS\TableRate\Rule\PreconfiguredScenarios;

/**
 * Class RulesSettings
 */
class RulesSettingsField {

	const FIELD_TYPE = 'shipping_rules';

	/**
	 * @var string
	 */
	protected static $assets_url;

	/**
	 * @var string
	 */
	private $settings_field_id;

	/**
	 * @var string
	 */
	private $settings_field_name;

	/**
	 * @var string
	 */
	private $settings_field_title;

	/**
	 * @var array
	 */
	private $settings;

	/**
	 * @var array
	 */
	private $value;

	/**
	 * @var array
	 */
	private $shipping_method_settings;

	/**
	 * RulesSettings constructor.
	 *
	 * @param string $settings_field_id        .
	 * @param string $settings_field_name      .
	 * @param string $settings_field_title     .
	 * @param array  $settings                 .
	 * @param array  $value                    .
	 * @param array  $shipping_method_settings .
	 */
	public function __construct( $settings_field_id, $settings_field_name, $settings_field_title, $settings, $value = null, $shipping_method_settings = [] ) {
		$this->settings_field_id        = $settings_field_id;
		$this->settings_field_name      = $settings_field_name;
		$this->settings_field_title     = $settings_field_title;
		$this->settings                 = $settings;
		$this->value                    = $value;
		$this->shipping_method_settings = $shipping_method_settings;
	}

	/**
	 * @param string $assets_url .
	 */
	public static function set_assets_url( $assets_url ) {
		self::$assets_url = $assets_url;
	}

	/**
	 * Render settings.
	 *
	 * @return string
	 */
	public function render() {
		ob_start();
		$settings_field_id    = $this->settings_field_id;
		$settings_field_name  = $this->settings_field_name;
		$settings_field_title = $this->settings_field_title;
		$available_conditions = $this->get_available_conditions();
		$rules_settings       = $this->get_normalized_settings( $available_conditions );
		$available_conditions = array_values( $available_conditions );
		$translations         = $this->get_translations();
		$pro_features_data    = $this->get_pro_features_data();
		$table_settings       = $this->get_table_settings();

		$cost_settings_fields    = $this->get_available_cost_settings();
		$additional_cost_fields  = $this->get_additional_cost_fields();
		$special_action_fields   = $this->get_special_actions_fields();
		$preconfigured_scenarios = $this->get_preconfigured_scenarios();

		$is_pro_activated = defined( 'FLEXIBLE_SHIPPING_PRO_VERSION' );

		$rules_table_settings = [
			'rules_settings'          => $rules_settings,
			'table_settings'          => $table_settings,
			'translations'            => $translations,
			'available_conditions'    => $available_conditions,
			'cost_settings_fields'    => $cost_settings_fields,
			'special_action_fields'   => $special_action_fields,
			'additional_cost_fields'  => $additional_cost_fields,
			'preconfigured_scenarios' => $preconfigured_scenarios,
			'is_pro_activated'        => $is_pro_activated,
			'pro_features_data'       => $pro_features_data,
		];

		$rules_table_settings = apply_filters( 'flexible-shipping/rules-table/settings', $rules_table_settings, $this->shipping_method_settings );

		include __DIR__ . '/views/shipping-method-settings-rules.php';

		return ob_get_clean();
	}

	/**
	 * @return array
	 */
	private function get_pro_features_data(): array {
		return [
			'ajax_url'    => admin_url( 'admin-ajax.php' ),
			'ajax_action' => ProFeatures\Tracker\AjaxTracker::AJAX_ACTION,
			'ajax_nonce'  => wp_create_nonce( ProFeatures\Tracker\AjaxTracker::AJAX_ACTION ),
			'fs_pro_link' => 'pl_PL' === get_user_locale()
				? 'https://octol.io/fs-pro-features-pl'
				: 'https://octol.io/fs-pro-features',
		];
	}

	/**
	 * @return array
	 */
	private function get_translations() {
		return [
			'assets_url'                  => self::$assets_url,
			'ajax_url_scenarios_tracking' => admin_url( 'admin-ajax.php?action=' . PreconfiguredScenarios\Tracker\AjaxTracker::AJAX_ACTION ),
			'scenarios_tracking_nonce'    => wp_create_nonce( PreconfiguredScenarios\Tracker\AjaxTracker::AJAX_ACTION ),
			'scenarios_docs_link'         =>
				'pl_PL' === get_locale()
					? 'https://octol.io/fs-scenarios-pl'
					: 'https://octol.io/fs-scenarios',
		];
	}

	/**
	 * @return array
	 */
	private function get_available_cost_settings() {
		$rule_costs_fields_factory = new RuleCostFieldsFactory();

		return $rule_costs_fields_factory->get_normalized_cost_fields();
	}

	/**
	 * @return array
	 */
	private function get_additional_cost_fields() {
		$rule_additional_costs_fields_factory = new RuleAdditionalCostFieldsFactory( ( new RuleAdditionalCostFactory() )->get_additional_costs() );

		return $rule_additional_costs_fields_factory->get_normalized_cost_fields();
	}

	/**
	 * @return array
	 */
	private function get_special_actions_fields() {
		$special_actions_fields_factory = new SpecialActionFieldsFactory( ( new SpecialActionFactory() )->get_special_actions() );

		return $special_actions_fields_factory->get_normalized_cost_fields();
	}

	/**
	 * @retrun array
	 */
	private function get_preconfigured_scenarios() {
		return ( new PreconfiguredScenariosFactory() )->get_scenarios();
	}

	/**
	 * @return array
	 */
	private function get_table_settings() {
		return ( new RulesTableSettings() )->get_table_settings();
	}

	/**
	 * @return Rule\Condition\Condition[]
	 */
	private function get_available_conditions() {
		return ( new ConditionsFactory() )->get_conditions();
	}

	/**
	 * @param Rule\Condition\Condition[] $available_conditions .
	 *
	 * @return array
	 */
	private function get_normalized_settings( $available_conditions ) {
		$rules_settings = RulesSettingsFactory::create_from_array( $this->get_field_value() );

		return $this->process_select_options_for_conditions( $rules_settings->get_normalized_settings(), $available_conditions );
	}

	/**
	 * @return array
	 */
	private function get_field_value(): array {
		$value = ! isset( $this->value ) ? ( $this->settings['default'] ?? [] ) : $this->value;
		if ( ! is_array( $value ) ) {
			$value = [];
		}
		return $value;
	}

	/**
	 * @param array                      $settings             .
	 * @param Rule\Condition\Condition[] $available_conditions .
	 *
	 * @return array
	 */
	private function process_select_options_for_conditions( array $settings, $available_conditions ) {
		foreach ( $settings as $rule_key => $rule ) {
			$conditions = isset( $rule['conditions'] ) && is_array( $rule['conditions'] ) ? $rule['conditions'] : [];
			foreach ( $conditions as $condition_key => $condition ) {
				if ( isset( $available_conditions[ $condition['condition_id'] ] ) ) {
					$settings[ $rule_key ]['conditions'][ $condition_key ] = $available_conditions[ $condition['condition_id'] ]->prepare_settings( $condition );
				}
			}
			$settings[ $rule_key ]['conditions'] = array_values( $settings[ $rule_key ]['conditions'] );
		}

		return $settings;
	}
}
