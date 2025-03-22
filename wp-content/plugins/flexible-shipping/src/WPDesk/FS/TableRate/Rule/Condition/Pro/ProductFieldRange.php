<?php

namespace WPDesk\FS\TableRate\Rule\Condition\Pro;


use WPDesk\FS\TableRate\Rule\Condition\AbstractCondition;

class ProductFieldRange extends AbstractCondition {

	private const CONDITION_ID = 'product_field_range';


	public function __construct( int $priority = 10 ) {
		$this->condition_id = self::CONDITION_ID;
		$this->name         = __( 'Field range', 'flexible-shipping-rules' );
		$this->description  = __( 'Shipping cost based on the product\'s field range', 'flexible-shipping-rules' );
		$this->group        = __( 'Product', 'flexible-shipping-rules' );
		$this->priority     = $priority;
		$this->is_disabled  = true;

		$this->name .= ' ' . __( '(PRO feature)', 'flexible-shipping-rules' );
	}
}
