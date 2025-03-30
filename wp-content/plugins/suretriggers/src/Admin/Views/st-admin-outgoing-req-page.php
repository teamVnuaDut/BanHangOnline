<?php
/**
 * SureTriggers Outgoing Requests Page.
 * php version 5.6
 *
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

use SureTriggers\Controllers\WebhookRequestsController;
global $wpdb;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * SureTriggersWebhookRequestsTable - List table for Webhook requests.
 *
 * @category SureTriggersWebhookRequestsTable
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class SureTriggersWebhookRequestsTable extends WP_List_Table {

	/**
	 * Webhook Requests List Table name.
	 *
	 * @var string
	 */
	private $table_name;

	/**
	 * Initialise data.
	 * 
	 * @param string $table_name Table Name.
	 */
	public function __construct( $table_name ) {
		parent::__construct(
			[
				'singular' => 'webhook_request',
				'plural'   => 'webhook_requests',
				'ajax'     => false,
			] 
		);

		$this->table_name = $table_name;
	}

	/**
	 * Table Classes.
	 *
	 * @return array
	 */
	protected function table_classes() {
		return [ 'wp-list-table', 'widefat', 'striped' ];
	}

	/**
	 * Table Display.
	 *
	 * @return void
	 */
	public function display() {
		$this->display_tablenav( 'top' );
		?>
		<table class="<?php echo esc_attr( implode( ' ', $this->table_classes() ) ); ?>">
			<thead>
				<?php $this->print_column_headers(); ?>
			</thead>
			<tbody id="the-list" data-wp-lists="list:<?php echo esc_attr( $this->_args['singular'] ); ?>">
				<?php $this->display_rows_or_placeholder(); ?>
			</tbody>
			<tfoot>
				<?php $this->print_column_headers( false ); ?>
			</tfoot>
		</table>
		<?php
	}

	/**
	 * Get Columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		return [
			'id'            => 'ID',
			'response_code' => 'Response Code',
			'status'        => 'Status',
			'trigger_event' => 'Trigger Event',
			'error_info'    => 'Error Info',
			'created_at'    => 'Created At',
			'retry'         => 'Retry',
		];
	}

	/**
	 * Get Sortable Columns.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return [
			'id'         => [ 'id', true ],
			'created_at' => [ 'created_at', false ],
		];
	}

	/**
	 * Column retry.
	 * 
	 * @param array $item Item.
	 *
	 * @return string|void
	 */
	public function column_retry( $item ) {
		$id = esc_attr( $item['id'] );
		if ( 'failed' == $item['status'] ) {
			return '
				<input type="submit" class="button button-primary st-retry-btn" name="retry_st_request" value="Retry" data-id="' . $id . '">
			';
		} else {
			return;
		}
	}

	/**
	 * Column retry.
	 * 
	 * @param array $item Item.
	 *
	 * @return mixed|string
	 */
	public function column_trigger_event( $item ) {
		$data = $item['request_data'];
		$data = json_decode( $data, true );
		if ( is_array( $data ) && isset( $data['body']['trigger'] ) ) {
			return $data['body']['trigger'];
		}
		return '';
	}

	/**
	 * Prepare Items.
	 *
	 * @return void
	 */
	public function prepare_items() {
		global $wpdb;

		$columns  = $this->get_columns();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = [ $columns, [], $sortable ];

		if ( isset( $_POST['suretriggers_requests_nonce'] ) && wp_verify_nonce( sanitize_text_field( $_POST['suretriggers_requests_nonce'] ), 'suretriggers_requests_nonce_action' ) ) {
			if ( isset( $_POST['retry_st_request'] ) && isset( $_POST['st_retry_id'] ) ) {
				$id = absint( $_POST['st_retry_id'] );
				if ( $id ) {
					WebhookRequestsController::suretriggers_retry_trigger_request( $id );
				}
			}
		}

		$status_filter = isset( $_REQUEST['status_filter'] ) ? sanitize_text_field( $_REQUEST['status_filter'] ) : '';
		$orderby       = isset( $_REQUEST['orderby'] ) ? sanitize_text_field( $_REQUEST['orderby'] ) : 'id';
		$order         = isset( $_REQUEST['order'] ) ? sanitize_text_field( $_REQUEST['order'] ) : 'ASC';
		$per_page      = $this->get_items_per_page( 'webhook_requests_per_page', 10 );
		$current_page  = $this->get_pagenum();

		$where = '';
		if ( ! empty( $status_filter ) ) {
			$where = $wpdb->prepare( 'WHERE status = %s', $status_filter );
		}

		$offset = ( $current_page - 1 ) * $per_page;

		$this->items = $wpdb->get_results( $wpdb->prepare( "SELECT id, response_code, request_data, status, error_info, created_at FROM $this->table_name $where ORDER BY %s %s LIMIT %d OFFSET %d", $orderby, $order, $per_page, $offset ), ARRAY_A );  //phpcs:ignore

		$total_items = $wpdb->get_var( "SELECT COUNT(*) FROM {$this->table_name} $where" );  //phpcs:ignore

		$this->set_pagination_args(
			[
				'total_items' => $total_items,
				'per_page'    => $per_page,
			] 
		);
	}

	/**
	 * Column Default.
	 *   
	 * @param array  $item Item.
	 * @param string $column_name Column Name.
	 * 
	 * @return string
	 */
	public function column_default( $item, $column_name ) {
		return isset( $item[ $column_name ] ) ? esc_html( $item[ $column_name ] ) : '';
	}

	/**
	 * Extra table navigation.
	 * 
	 * @param string $which Which.
	 *
	 * @return void
	 */
	public function extra_tablenav( $which ) {
		if ( isset( $_REQUEST['suretriggers_requests_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['suretriggers_requests_nonce'] ), 'suretriggers_requests_nonce_action' ) ) {
			return;
		}
		?>
		<div class="alignleft actions">
			<select name="status_filter">
				<option value=""><?php esc_html_e( 'All Requests', 'suretriggers' ); ?></option>
				<option value="success" <?php selected( isset( $_REQUEST['status_filter'] ) ? sanitize_text_field( $_REQUEST['status_filter'] ) : '', 'success' ); ?>>
					<?php esc_html_e( 'Success Requests', 'suretriggers' ); ?>
				</option>
				<option value="failed" <?php selected( isset( $_REQUEST['status_filter'] ) ? sanitize_text_field( $_REQUEST['status_filter'] ) : '', 'failed' ); ?>>
					<?php esc_html_e( 'Failed Requests', 'suretriggers' ); ?>
				</option>
			</select>
			<input type="submit" name="suretriggers_filter_request" id="suretriggers_filter_request" class="button" value="Filter">
		</div>
		<?php
	}
	
}
$table_name = WebhookRequestsController::get_table_name();
$list_table = new SureTriggersWebhookRequestsTable( $table_name );
?>
<form id="suretriggers-requests-table-form" method="post">
	<input type="hidden" name="page" value="suretriggers-status" />
	<input type="hidden" name="tab" value="st_outgoing_requests" />
	<input type="hidden" name="st_retry_id" value="">
	<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'suretriggers_tab_nonce' ) ); ?>" />
	<?php 
	wp_nonce_field( 'suretriggers_requests_nonce_action', 'suretriggers_requests_nonce' );
	if ( isset( $_REQUEST['suretriggers_requests_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['suretriggers_requests_nonce'] ), 'suretriggers_requests_nonce_action' ) ) {
		if ( isset( $_REQUEST['status_filter'] ) ) {
			echo '<input type="hidden" name="status_filter" value="' . esc_attr( sanitize_text_field( $_REQUEST['status_filter'] ) ) . '">';
		}
	}   
	$list_table->prepare_items(); 
	$list_table->display();
	echo '<div style="margin-top: 10px;">
        <p style="font-style: italic;color: #666;margin-left: 55%;">Note: Successful outgoing requests will be automatically deleted after 30 days, while failed outgoing requests will be automatically deleted after 60 days.</p>
    </div>';
	?>
</form>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		// Remove extra nonce.
		$('#suretriggers-requests-table-form #_wpnonce').remove();
		// Handle retry button click.
		$('.st-retry-btn').on('click', function() {
			var retryId = $(this).data('id');
			$('input[name="st_retry_id"]').val(retryId);
			$('form#suretriggers-requests-table-form').submit();
		});
		// Handle filter button click.
		$('#suretriggers_filter_request').on('click', function(e) {
			e.preventDefault();
			$('<input>').attr({
			type: 'hidden',
			name: 'paged',
			value: '1'
		}).appendTo('#suretriggers-requests-table-form');
			$('#suretriggers-requests-table-form').submit();
		});
		// Handle pagination.
		$(document).on('click', '.tablenav-pages a', function(e) {
			let paged = $(this).attr('href').match(/paged=(\d+)/);
			if (paged && paged[1]) {
				e.preventDefault();
				$('<input>').attr({
					type: 'hidden',
					name: 'paged',
					value: paged[1]
				}).appendTo('#suretriggers-requests-table-form');
				let filterValue = $('select[name="status_filter"]').val();
				if (filterValue) {
					$('<input>').attr({
						type: 'hidden',
						name: 'status_filter',
						value: filterValue
					}).appendTo('#suretriggers-requests-table-form');
				}
				$('#suretriggers-requests-table-form').submit();
			}
		});
	});
</script>
