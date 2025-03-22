<?php
/**
 * Class DayOfTheWeek
 */

namespace WPDesk\FS\TableRate\Rule\Condition\Pro;

use WPDesk\FS\TableRate\Rule\Condition\AbstractCondition;

/**
 * Day Of The Week condition.
 */
class DayOfTheWeek extends AbstractCondition {

	const CONDITION_ID = 'day_of_the_week';

	/**
	 * Product constructor.
	 *
	 * @param int $priority .
	 */
	public function __construct( int $priority = 10 ) {
		$this->condition_id = self::CONDITION_ID;
		$this->name         = __( 'Day of the week', 'flexible-shipping-rules' );
		$this->group        = __( 'Destination & Time', 'flexible-shipping-rules' );
		$this->priority     = $priority;
		$this->is_disabled  = true;

		$this->name .= ' ' . __( '(PRO feature)', 'flexible-shipping-rules' );
	}
}
