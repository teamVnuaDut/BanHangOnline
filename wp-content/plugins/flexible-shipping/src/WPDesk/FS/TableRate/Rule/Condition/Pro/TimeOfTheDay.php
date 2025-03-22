<?php
/**
 * Class TimeOfTheDay
 */

namespace WPDesk\FS\TableRate\Rule\Condition\Pro;

use WPDesk\FS\TableRate\Rule\Condition\AbstractCondition;

/**
 * Time Of The Day condition.
 */
class TimeOfTheDay extends AbstractCondition {

	const CONDITION_ID = 'time_of_the_day';

	/**
	 * Product constructor.
	 *
	 * @param int $priority .
	 */
	public function __construct( int $priority = 10 ) {
		$this->condition_id = self::CONDITION_ID;
		$this->name         = __( 'Time of the day', 'flexible-shipping-rules' );
		$this->group        = __( 'Destination & Time', 'flexible-shipping-rules' );
		$this->priority     = $priority;
		$this->is_disabled  = true;

		$this->name .= ' ' . __( '(PRO feature)', 'flexible-shipping-rules' );
	}
}
