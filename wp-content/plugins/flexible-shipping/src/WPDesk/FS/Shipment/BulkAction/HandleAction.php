<?php
/**
 * Class HandleAction
 */

namespace WPDesk\FS\Shipment\BulkAction;

/**
 * .
 */
class HandleAction {

	/**
	 * @var HandleActionStrategyInterface
	 */
	private $strategy;

	/**
	 * @param HandleActionStrategyInterface $strategy
	 *
	 * @return $this
	 */
	public function set_strategy( HandleActionStrategyInterface $strategy ): self {
		$this->strategy = $strategy;

		return $this;
	}

	/**
	 * @param string $redirect_to .
	 * @param array  $post_ids    .
	 *
	 * @return string
	 */
	public function handle( string $redirect_to, array $post_ids ): string {
		return $this->strategy->handle( $redirect_to, $post_ids );
	}
}
