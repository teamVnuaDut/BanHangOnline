<?php
/**
 * Class Price
 *
 * @package WPDesk\FS\TableRate\Rule\Condition
 */

namespace WPDesk\FS\TableRate\Rule\Condition;

use FSVendor\WPDesk\Forms\Field;
use Psr\Log\LoggerInterface;
use WPDesk\FS\TableRate\Rule\ShippingContents\ShippingContents;

/**
 * Price condition.
 */
if ( defined( 'FLEXIBLE_SHIPPING_PSR_NOT_PREFIXED' ) && FLEXIBLE_SHIPPING_PSR_NOT_PREFIXED ) {
	class Price extends AbstractCondition {

		const MIN = 'min';
		const MAX = 'max';

		const CONDITION_ID = 'value';

		/**
		 * Price constructor.
		 *
		 * @param int $priority .
		 */
		public function __construct( $priority = 10 ) {
			$this->condition_id = self::CONDITION_ID;
			$this->name         = __( 'Price', 'flexible-shipping-rules' );
			$this->description  = __( 'Shipping cost based on the cart total or package value', 'flexible-shipping-rules' );
			$this->group        = __( 'Cart', 'flexible-shipping-rules' );
			$this->priority     = $priority;
		}

		/**
		 * @param array            $condition_settings .
		 * @param ShippingContents $contents           .
		 * @param LoggerInterface  $logger             .
		 *
		 * @return bool
		 */
		public function is_condition_matched( array $condition_settings, ShippingContents $contents, LoggerInterface $logger ) {
			$min = (float) ( isset( $condition_settings[ self::MIN ] ) && 0 !== strlen( $condition_settings[ self::MIN ] ) ? $condition_settings[ self::MIN ] : 0 );
			$max = (float) ( isset( $condition_settings[ self::MAX ] ) && 0 !== strlen( $condition_settings[ self::MAX ] ) ? $condition_settings[ self::MAX ] : INF );
			$min = (float) apply_filters( 'flexible_shipping_value_in_currency', $min, $min );
			if ( $max !== INF ) {
				$max = (float) apply_filters( 'flexible_shipping_value_in_currency', $max, $max );
			}

			$contents_cost = $this->get_contents_cost( $contents );

			$condition_matched = $contents_cost >= $min && $contents_cost <= $max;

			$logger->debug( $this->format_for_log( $condition_settings, $condition_matched, $contents_cost ) );

			return $condition_matched;
		}

		/**
		 * @param ShippingContents $contents .
		 *
		 * @return float
		 */
		protected function get_contents_cost( ShippingContents $contents ) {
			$contents_cost =
				/**
				 * Can modify contents cost passed to Price (value) condition.
				 *
				 * @param float $contents_cost Contents cost.
				 *
				 * @since 4.1.1
				 */
				apply_filters( 'flexible-shipping/condition/contents_value', $contents->get_contents_cost() );

			return (float) $contents_cost;
		}

		/**
		 * @return Field[]
		 */
		public function get_fields() {
			return [
				( new Field\InputNumberField() )
					->set_name( self::MIN )
					->add_class( 'wc_input_decimal' )
					->add_class( 'hs-beacon-search' )
					->add_class( 'parameter_min' )
					->add_data( 'beacon_search', __( 'price is from', 'flexible-shipping-rules' ) )
					->set_placeholder( __( 'min', 'flexible-shipping-rules' ) )
					->set_label( __( 'is from', 'flexible-shipping-rules' ) ),
				( new Field\InputNumberField() )
					->set_name( self::MAX )
					->add_class( 'wc_input_decimal' )
					->add_class( 'hs-beacon-search' )
					->add_class( 'parameter_max' )
					->add_data( 'beacon_search', __( 'price to', 'flexible-shipping-rules' ) )
					->set_placeholder( __( 'max', 'flexible-shipping-rules' ) )
					->set_label( __( 'to', 'flexible-shipping-rules' ) )
					->add_data( 'suffix', get_woocommerce_currency_symbol() ),
			];
		}
	}
} else {
	class Price extends AbstractCondition {

		const MIN = 'min';
		const MAX = 'max';

		const CONDITION_ID = 'value';

		/**
		 * Price constructor.
		 *
		 * @param int $priority .
		 */
		public function __construct( $priority = 10 ) {
			$this->condition_id = self::CONDITION_ID;
			$this->name         = __( 'Price', 'flexible-shipping-rules' );
			$this->description  = __( 'Shipping cost based on the cart total or package value', 'flexible-shipping-rules' );
			$this->group        = __( 'Cart', 'flexible-shipping-rules' );
			$this->priority     = $priority;
		}

		/**
		 * @param array            $condition_settings .
		 * @param ShippingContents $contents           .
		 * @param LoggerInterface  $logger             .
		 *
		 * @return bool
		 */
		public function is_condition_matched( array $condition_settings, ShippingContents $contents, $logger ) {
			$min = (float) ( isset( $condition_settings[ self::MIN ] ) && 0 !== strlen( $condition_settings[ self::MIN ] ) ? $condition_settings[ self::MIN ] : 0 );
			$max = (float) ( isset( $condition_settings[ self::MAX ] ) && 0 !== strlen( $condition_settings[ self::MAX ] ) ? $condition_settings[ self::MAX ] : INF );
			$min = (float) apply_filters( 'flexible_shipping_value_in_currency', $min, $min );
			if ( $max !== INF ) {
				$max = (float) apply_filters( 'flexible_shipping_value_in_currency', $max, $max );
			}

			$contents_cost = $this->get_contents_cost( $contents );

			$condition_matched = $contents_cost >= $min && $contents_cost <= $max;

			$logger->debug( $this->format_for_log( $condition_settings, $condition_matched, $contents_cost ) );

			return $condition_matched;
		}

		/**
		 * @param ShippingContents $contents .
		 *
		 * @return float
		 */
		protected function get_contents_cost( ShippingContents $contents ) {
			$contents_cost =
				/**
				 * Can modify contents cost passed to Price (value) condition.
				 *
				 * @param float $contents_cost Contents cost.
				 *
				 * @since 4.1.1
				 */
				apply_filters( 'flexible-shipping/condition/contents_value', $contents->get_contents_cost() );

			return (float) $contents_cost;
		}

		/**
		 * @return Field[]
		 */
		public function get_fields() {
			return [
				( new Field\InputNumberField() )
					->set_name( self::MIN )
					->add_class( 'wc_input_decimal' )
					->add_class( 'hs-beacon-search' )
					->add_class( 'parameter_min' )
					->add_data( 'beacon_search', __( 'price is from', 'flexible-shipping-rules' ) )
					->set_placeholder( __( 'min', 'flexible-shipping-rules' ) )
					->set_label( __( 'is from', 'flexible-shipping-rules' ) ),
				( new Field\InputNumberField() )
					->set_name( self::MAX )
					->add_class( 'wc_input_decimal' )
					->add_class( 'hs-beacon-search' )
					->add_class( 'parameter_max' )
					->add_data( 'beacon_search', __( 'price to', 'flexible-shipping-rules' ) )
					->set_placeholder( __( 'max', 'flexible-shipping-rules' ) )
					->set_label( __( 'to', 'flexible-shipping-rules' ) )
					->add_data( 'suffix', get_woocommerce_currency_symbol() ),
			];
		}
	}
}
