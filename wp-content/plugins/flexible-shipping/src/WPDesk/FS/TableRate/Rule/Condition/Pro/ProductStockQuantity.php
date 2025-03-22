<?php

namespace WPDesk\FS\TableRate\Rule\Condition\Pro;

use WPDesk\FS\TableRate\Rule\Condition\AbstractCondition;

class ProductStockQuantity extends AbstractCondition {

	private const CONDITION_ID = 'product_stock_quantity';

	private const MIN = 'min';
	private const MAX = 'max';

	public function __construct( int $priority = 10 ) {
		$this->condition_id = self::CONDITION_ID;
		$this->name         = __( 'Stock quantity', 'flexible-shipping-rules' );
		$this->description  = __( 'Shipping cost based on the product\'s stock quantity', 'flexible-shipping-rules' );
		$this->group        = __( 'Product', 'flexible-shipping-rules' );
		$this->priority     = $priority;
		$this->is_disabled  = true;

		$this->name .= ' ' . __( '(PRO feature)', 'flexible-shipping-rules' );
	}
}
