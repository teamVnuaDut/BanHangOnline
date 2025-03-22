<?php

namespace WPDesk\FS\TableRate\Rule\Condition\Pro;

use WPDesk\FS\TableRate\Rule\Condition\AbstractCondition;

/**
 * Shipping cost condition.
 */
class ShippingCost extends AbstractCondition {

	private const CONDITION_ID = 'shipping_cost';

	public function __construct( $priority = 10 ) {
		$this->priority     = $priority;
		$this->condition_id = self::CONDITION_ID;
		$this->name         = __( 'Shipping cost', 'flexible-shipping-rules' );
		$this->description  = __( 'Shipping cost based on current shipping cost', 'flexible-shipping-rules' );
		$this->group        = __( 'Shipping', 'flexible-shipping-rules' );
		$this->is_disabled  = true;

		$this->name .= ' ' . __( '(PRO feature)', 'flexible-shipping-rules' );
	}
}
