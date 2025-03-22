<?php
/**
 * Class CartLineItem
 *
 * @package WPDesk\FS\TableRate\Rule\Condition
 */

namespace WPDesk\FS\TableRate\Rule\Condition\Pro;

use WPDesk\FS\TableRate\Rule\Condition\AbstractCondition;

/**
 * Cart line item.
 */
class CartLineItem extends AbstractCondition {

	const CONDITION_ID = 'cart_line_item';

	/**
	 * Product constructor.
	 *
	 * @param int $priority .
	 */
	public function __construct( int $priority = 10 ) {
		$this->condition_id = self::CONDITION_ID;
		$this->name         = __( 'Cart line item', 'flexible-shipping-rules' );
		$this->group        = __( 'Cart', 'flexible-shipping-rules' );
		$this->priority     = $priority;
		$this->is_disabled  = true;

		$this->name .= ' ' . __( '(PRO feature)', 'flexible-shipping-rules' );
	}
}
