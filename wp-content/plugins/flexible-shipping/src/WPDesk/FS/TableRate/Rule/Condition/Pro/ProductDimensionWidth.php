<?php

namespace WPDesk\FS\TableRate\Rule\Condition\Pro;

use WPDesk\FS\TableRate\Rule\Condition\AbstractCondition;

/**
 * Product width condition.
 */
class ProductDimensionWidth extends AbstractCondition {

	const CONDITION_ID = 'product_width';

	/**
	 * Product constructor.
	 *
	 * @param int $priority .
	 */
	public function __construct( int $priority = 10 ) {
		$this->condition_id = self::CONDITION_ID;
		$this->name         = __( 'Width', 'flexible-shipping-rules' );
		$this->description  = __( 'Shipping cost based on the product\'s width', 'flexible-shipping-rules' );
		$this->group        = __( 'Product', 'flexible-shipping-rules' );
		$this->priority     = $priority;
		$this->is_disabled  = true;

		$this->name .= ' ' . __( '(PRO feature)', 'flexible-shipping-rules' );
	}
}
