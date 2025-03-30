<?php
/**
 * SureTriggers System Page.
 * php version 5.6
 *
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 */

use SureTriggers\Controllers\OptionController;
?>
<table class="widefat suretriggers-system-table suretriggers-system-table-trigger" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3"><h2><?php esc_html_e( 'Registered Events', 'suretriggers' ); ?></h2></th>
		</tr>
	</thead>
	<tbody>
		<?php
		$saved_triggers = OptionController::get_option( 'triggers' );
		if ( ! empty( $saved_triggers ) ) {
			$grouped_data = [];
			foreach ( (array) $saved_triggers as $row ) {
				if ( ! is_array( $row ) || ! isset( $row['trigger'], $row['integration'] ) ) {
					continue;
				}
				$grouped_data[ $row['integration'] ][] = $row['trigger'];
			}
			$output = [];
			foreach ( $grouped_data as $integration => $triggers ) {
				$output[] = [
					'integration' => $integration,
					'triggers'    => array_unique( $triggers ),
				];
			}
			foreach ( (array) $output as $key => $trigger ) {
				$count = count( $trigger['triggers'] );
				?>
				<tr>
					<th rowspan="<?php echo esc_attr( (string) $count ); ?>"><?php echo esc_html( (string) $trigger['integration'] ); ?></th>
					<td><?php echo esc_html( $trigger['triggers'][0] ); ?></td>
				</tr>
				<?php
				for ( $i = 1; $i < $count; $i++ ) {
					?>
					<tr>
						<td><?php echo esc_html( $trigger['triggers'][ $i ] ); ?></td>
					</tr>
					<?php
				}
			}
		} else {
			?>
			<tr>
				<td>
					<?php
						echo esc_html__( 'No Trigger registered yet.', 'suretriggers' ); 
					?>
				</td>
			<?php
		}
		?>
	</tbody>
</table>
<script>
	document.addEventListener("DOMContentLoaded", function () {
		const tableRows = document.querySelectorAll(".suretriggers-system-table tbody tr");
		let isOdd = true;
		tableRows.forEach(row => {
			if (row.querySelector("th")) {
				isOdd = !isOdd;
			}
			if (isOdd) {
				row.classList.add("odd-row");
			}
		});
	});
</script>
