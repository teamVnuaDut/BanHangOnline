<?php
/**
 * Class ShippingClass
 */

namespace WPDesk\FS\TableRate\Rule\Condition\Pro;

use WPDesk\FS\TableRate\Rule\Condition\AbstractCondition;

/**
 * Max Dimension condition.
 */
class ShippingClass extends AbstractCondition {

	const CONDITION_ID = 'shipping_class';

	/**
	 * Product constructor.
	 *
	 * @param int $priority .
	 */
	public function __construct( int $priority = 10 ) {
		$this->condition_id = self::CONDITION_ID;
		$this->name         = __( 'Shipping class', 'flexible-shipping-rules' );
		$this->group        = __( 'Product', 'flexible-shipping-rules' );
		$this->priority     = $priority;
		$this->is_disabled  = true;

		$this->name .= ' ' . __( '(PRO feature)', 'flexible-shipping-rules' );
	}
}
