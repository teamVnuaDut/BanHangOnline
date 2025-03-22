<?php
/**
 * Class ConditionsFactory
 *
 * @package WPDesk\FS\TableRate\Rule\Condition
 */

namespace WPDesk\FS\TableRate\Rule\Condition;

use WPDesk\FS\TableRate\Rule\Condition\Pro\CartLineItem;
use WPDesk\FS\TableRate\Rule\Condition\Pro\DayOfTheWeek;
use WPDesk\FS\TableRate\Rule\Condition\Pro\DimensionalWeight;
use WPDesk\FS\TableRate\Rule\Condition\Pro\Item;
use WPDesk\FS\TableRate\Rule\Condition\Pro\MaxDimension;
use WPDesk\FS\TableRate\Rule\Condition\Pro\Product;
use WPDesk\FS\TableRate\Rule\Condition\Pro\ProductCategory;
use WPDesk\FS\TableRate\Rule\Condition\Pro\ProductDimensionHeight;
use WPDesk\FS\TableRate\Rule\Condition\Pro\ProductDimensionLength;
use WPDesk\FS\TableRate\Rule\Condition\Pro\ProductDimensionWidth;
use WPDesk\FS\TableRate\Rule\Condition\Pro\ProductFieldRange;
use WPDesk\FS\TableRate\Rule\Condition\Pro\ProductFieldValue;
use WPDesk\FS\TableRate\Rule\Condition\Pro\ProductStockQuantity;
use WPDesk\FS\TableRate\Rule\Condition\Pro\ProductStockStatus;
use WPDesk\FS\TableRate\Rule\Condition\Pro\ProductTag;
use WPDesk\FS\TableRate\Rule\Condition\Pro\ShippingClass;
use WPDesk\FS\TableRate\Rule\Condition\Pro\ShippingCost;
use WPDesk\FS\TableRate\Rule\Condition\Pro\TimeOfTheDay;
use WPDesk\FS\TableRate\Rule\Condition\Pro\TotalOverallDimensions;
use WPDesk\FS\TableRate\Rule\Condition\Pro\UserRole;
use WPDesk\FS\TableRate\Rule\Condition\Pro\Volume;

/**
 * Can provide rules conditions.
 */
class ConditionsFactory {

	/**
	 * @return Condition[]
	 */
	public function get_conditions(): array {
		$none   = new None( 0 );
		$price  = new Price( 10 );
		$weight = new Weight( 25 );

		// Pro conditions.
		$item                     = new Item( 15 );
		$volume                   = new Volume( 35 );
		$product                  = new Product( 30 );
		$product_tag              = new ProductTag( 45 );
		$product_category         = new ProductCategory( 50 );
		$product_field_value      = new ProductFieldValue( 55 );
		$product_field_range      = new ProductFieldRange( 56 );
		$product_stock_quantity   = new ProductStockQuantity( 60 );
		$product_stock_status     = new ProductStockStatus( \wc_get_product_stock_status_options(), 61 );
		$shipping_cost            = new ShippingCost( 100 );
		$time_of_the_day          = new TimeOfTheDay( 70 );
		$max_dimension            = new MaxDimension( 40 );
		$product_length           = new ProductDimensionLength( 41 );
		$product_width            = new ProductDimensionWidth( 42 );
		$product_height           = new ProductDimensionHeight( 43 );
		$cart_line_item           = new CartLineItem( 20 );
		$shipping_class           = new ShippingClass( 60 );
		$day_of_the_week          = new DayOfTheWeek( 75 );
		$total_overall_dimensions = new TotalOverallDimensions( 40 );
		$user_role                = new UserRole( 40 );
		$dimensional_weight       = new DimensionalWeight( 30 );

		$conditions = [
			$none->get_condition_id()                     => $none,
			$price->get_condition_id()                    => $price,
			$weight->get_condition_id()                   => $weight,
			$item->get_condition_id()                     => $item,
			$volume->get_condition_id()                   => $volume,
			$product->get_condition_id()                  => $product,
			$product_tag->get_condition_id()              => $product_tag,
			$product_category->get_condition_id()         => $product_category,
			$time_of_the_day->get_condition_id()          => $time_of_the_day,
			$max_dimension->get_condition_id()            => $max_dimension,
			$product_length->get_condition_id()           => $product_length,
			$product_width->get_condition_id()            => $product_width,
			$product_height->get_condition_id()           => $product_height,
			$cart_line_item->get_condition_id()           => $cart_line_item,
			$shipping_class->get_condition_id()           => $shipping_class,
			$day_of_the_week->get_condition_id()          => $day_of_the_week,
			$total_overall_dimensions->get_condition_id() => $total_overall_dimensions,
			$user_role->get_condition_id()                => $user_role,
			$dimensional_weight->get_condition_id()       => $dimensional_weight,
			$product_field_value->get_condition_id()      => $product_field_value,
			$product_field_range->get_condition_id()      => $product_field_range,
			$product_stock_quantity->get_condition_id()   => $product_stock_quantity,
			$product_stock_status->get_condition_id()     => $product_stock_status,
			$shipping_cost->get_condition_id()            => $shipping_cost,
		];

		$conditions = apply_filters( 'flexible_shipping_rule_conditions', $conditions );
		$conditions = $this->filter_conditions( $conditions );

		return $this->sort_conditions( $conditions );
	}

	/**
	 * @param Condition[] $conditions .
	 *
	 * @return Condition[]
	 */
	private function filter_conditions( array $conditions ): array {
		return array_filter(
			$conditions,
			function ( $condition ) {
				return $condition instanceof Condition;
			}
		);
	}

	/**
	 * @param Condition[] $conditions .
	 *
	 * @return Condition[]
	 */
	private function sort_conditions( array $conditions ): array {
		uasort(
			$conditions,
			function ( Condition $condition1, Condition $condition2 ) {
				return $condition1->get_priority() <=> $condition2->get_priority(); // phpcs:ignore.
			}
		);

		return $conditions;
	}
}
