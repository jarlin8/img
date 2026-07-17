<?php

namespace AAWP\ActivityLogs;

use WP_List_Table;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Class ListTable.
 *
 * @since 3.19
 */
class ListTable extends WP_List_Table {

	/**
	 * DB Class.
	 *
	 * @var \AAWP\ActivityLogs\DB DB.
	 */
	private $db;

	/**
	 * ListTable constructor.
	 *
	 * @since 3.19
	 *
	 * @param \AAWP\ActivityLogs\DB $db DB.
	 */
	public function __construct( $db ) {

		$this->db = $db;

		parent::__construct(
			[
				'plural'   => esc_html__( 'Logs', 'aawp' ),
				'singular' => esc_html__( 'Log', 'aawp' ),
			]
		);
	}

	/**
	 * Prepare table list items.
	 *
	 * @global wpdb $wpdb
	 */
	public function prepare_items() {

		if ( ! $this->db->is_logging_enabled() ) {
			return;
		}

		global $wpdb;

		$this->prepare_column_headers();

		$per_page = $this->get_items_per_page( 'aawp_log_items_per_page', 15 );

		$where  = $this->get_items_query_where();
		$order  = $this->get_items_query_order();
		$limit  = $this->get_items_query_limit();
		$offset = $this->get_items_query_offset();

		$query_items = "
			SELECT id, timestamp, user_id, component, level, message, context
			FROM {$wpdb->prefix}aawp_logs
			{$where} {$order} {$limit} {$offset}
		";

		$this->items = $wpdb->get_results( $query_items ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery

		$total_items = $this->get_total();

		$this->set_pagination_args(
			[
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total_items / $per_page ),
			]
		);
	}

	/**
	 * Get prepared LIMIT clause for items query
	 *
	 * @global wpdb $wpdb
	 *
	 * @return string Prepared LIMIT clause for items query.
	 */
	protected function get_items_query_limit() {
		global $wpdb;

		$per_page = $this->get_items_per_page( 'aawp_log_items_per_page', 15 );
		return $wpdb->prepare( 'LIMIT %d', $per_page );
	}

	/**
	 * Get prepared OFFSET clause for items query
	 *
	 * @global wpdb $wpdb
	 *
	 * @return string Prepared OFFSET clause for items query.
	 */
	protected function get_items_query_offset() {
		global $wpdb;

		$per_page     = $this->get_items_per_page( 'aawp_log_items_per_page', 15 );
		$current_page = $this->get_pagenum();
		if ( 1 < $current_page ) {
			$offset = $per_page * ( $current_page - 1 );
		} else {
			$offset = 0;
		}

		return $wpdb->prepare( 'OFFSET %d', $offset );
	}

	/**
	 * Get prepared ORDER BY clause for items query
	 *
	 * @return string Prepared ORDER BY clause for items query.
	 */
	protected function get_items_query_order() {

		$valid_orders = [ 'id', 'component', 'timestamp', 'user_id' ];

		if ( ! empty( $_REQUEST['orderby'] ) && in_array( $_REQUEST['orderby'], $valid_orders, true ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			$by = sanitize_sql_orderby( wp_unslash( $_REQUEST['orderby'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		} else {
			$by = 'timestamp';
		}
		$by = esc_sql( $by );

		if ( ! empty( $_REQUEST['order'] ) && 'asc' === strtolower( $_REQUEST['order'] ) ) { // phpcs:ignore
			$order = 'ASC';
		} else {
			$order = 'DESC';
		}

		return "ORDER BY {$by} {$order}, id {$order}";
	}

	/**
	 * Column id.
	 *
	 * @since 3.19
	 *
	 * @param \AAWP\ActivityLogs\Log $item List table item.
	 *
	 * @return int|string
	 */
	public function column_id( $item ) {

		return absint( $item->id );
	}

	/**
	 * Column date.
	 *
	 * @since 3.19
	 *
	 * @param \AAWP\ActivityLogs\Log $item List table item.
	 *
	 * @return int|string
	 */
	public function column_timestamp( $item ) {

		return esc_html( $item->timestamp ) . ' (' . human_time_diff( strtotime( $item->timestamp ) ) . ' ago )';
	}

	/**
	 * Column level.
	 *
	 * @since 3.19
	 *
	 * @param \AAWP\ActivityLogs\Log $item List table item.
	 *
	 * @return int|string
	 */
	public function column_level( $item ) {

		return absint( $item->level );
	}

	/**
	 * Column User ID.
	 *
	 * @since 3.19
	 *
	 * @param \AAWP\ActivityLogs\Log $item List table item.
	 *
	 * @return int|string
	 */
	public function column_user( $item ) {

		$user_id = absint( $item->user_id );

		if ( 0 !== $user_id ) {

			$user  = get_user_by( 'id', $user_id );
			$email = ! empty( $user->user_email ) ? $user->user_email : 'User ID: ' . $user_id;
			$roles = ! empty( $user->roles ) && is_array( $user->roles ) ? implode( ',', $user->roles ) : '';

			return ! empty( $roles ) ? $email . ' (' . ucfirst( $roles ) . ')' : $email;
		}

		return esc_html__( 'System', 'aawp' );
	}

	/**
	 * Column log_component.
	 *
	 * @since 3.19
	 *
	 * @param \AAWP\ActivityLogs\Log $item List table item.
	 *
	 * @return int|string
	 */
	public function column_component( $item ) {

		return ! empty( $item->component ) ? esc_html( $item->component ) : '';
	}

	/**
	 * Column message.
	 *
	 * @since 3.19
	 *
	 * @param \AAWP\ActivityLogs\Log $item List table item.
	 *
	 * @return int|string
	 */
	public function column_message( $item ) {

		return ! empty( $item->message ) ? wp_kses_post( $item->message ) : '';
	}

	/**
	 * Column context.
	 *
	 * @since 3.19
	 *
	 * @param \AAWP\ActivityLogs\Log $item List table item.
	 *
	 * @return int|string
	 */
	public function column_context( $item ) {

		return ! empty( $item->context ) ? $item->context : '';
	}

	/**
	 * Get a list of sortable columns.
	 *
	 * @return array
	 */
	protected function get_sortable_columns() {
		return [
			'id'        => [ 'id', 'asc' ],
			'timestamp' => [ 'timestamp', 'asc' ],
			'component' => [ 'level', 'asc' ],
			'user'      => [ 'user', 'asc' ],
			'source'    => [ 'source', 'asc' ],
		];
	}

	/**
	 * Prepares the _column_headers property which is used by WP_Table_List at rendering.
	 * It merges the columns and the sortable columns.
	 *
	 * @since 3.19
	 */
	private function prepare_column_headers() {

		$this->_column_headers = [
			$this->get_columns(),
			[],
			$this->get_sortable_columns(),
		];
	}

	/**
	 * Returns the columns names for rendering.
	 *
	 * @since 3.19
	 *
	 * @return array
	 */
	public function get_columns() {

		return apply_filters(
			'aawp_activity_logs_get_columns',
			[
				'id'        => __( 'ID', 'aawp' ),
				'timestamp' => __( 'Timestamp', 'aawp' ),
				'user'      => __( 'User', 'aawp' ),
				'component' => __( 'Component', 'aawp' ),
				'message'   => __( 'Message', 'aawp' ),
			]
		);
	}

	/**
	 * Display component dropdown
	 *
	 * @global wpdb $wpdb
	 */
	public function component_dropdown() {

		$components = [
			[
				'value' => 'licensing',
				'label' => __( '	Licensing', 'aawp' ),
			],
			[
				'value' => 'aawp',
				'label' => __( 'AAWP API', 'aawp' ),
			],
			[
				'value' => 'amazon',
				'label' => __( 'Amazon API', 'aawp' ),
			],
			[
				'value' => 'settings',
				'label' => __( 'Settings', 'aawp' ),
			],
			[
				'value' => 'product',
				'label' => __( 'Product', 'aawp' ),
			],
			[
				'value' => 'plugin',
				'label' => __( 'Plugin Update', 'aawp' ),
			],
		];

		$selected_component = isset( $_REQUEST['component'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['component'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		?>
			<label for="filter-by-component" class="screen-reader-text"><?php esc_html_e( 'Filter by component', 'aawp' ); ?></label>
			<select name="component" id="filter-by-component">
				<option<?php selected( $selected_component, '' ); ?> value=""><?php esc_html_e( 'All components', 'aawp' ); ?></option>
				<?php
				foreach ( $components as $comp ) {
					printf(
						'<option%1$s value="%2$s">%3$s</option>',
						selected( $selected_component, $comp['value'], false ),
						esc_attr( $comp['value'] ),
						esc_html( $comp['label'] )
					);
				}
				?>
			</select>
		<?php
	}

	/**
	 * Display date selector
	 *
	 * @since 3.19
	 *
	 * @return mixed HTML output
	 */
	protected function date_select() {

		?>
			<label for="start-date" class="screen-reader-text"><?php esc_html_e( 'Start date', 'aawp' ); ?></label>

			<?php if ( ! empty( $_GET['startdate'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
				<input placeholder="<?php esc_attr_e( 'Start date', 'aawp' ); ?>" name="startdate" id="start-date" type="date" value="<?php echo esc_attr( wp_unslash( $_GET['startdate'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized ?>" />
			<?php else : ?>
				<input placeholder="<?php esc_attr_e( 'Start date', 'aawp' ); ?>" name="startdate" id="start-date" type="text" onfocus="(this.type='date')" />
			<?php endif; ?>

			<label for="end-date" class="screen-reader-text"><?php esc_html_e( 'End date', 'aawp' ); ?></label>

			<?php if ( ! empty( $_GET['enddate'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
				<input placeholder="<?php esc_attr_e( 'End date', 'aawp' ); ?>" name="enddate" id="end-date" type="date" value="<?php echo esc_attr( wp_unslash( $_GET['enddate'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized ?>" />
			<?php else : ?>
				<input placeholder="<?php esc_attr_e( 'End date', 'aawp' ); ?>" name="enddate" id="end-date" type="text" onfocus="(this.type='date')" />
			<?php endif; ?>

		<?php
	}

	/**
	 * Generate the table navigation above or below the table.
	 *
	 * @since 3.19
	 *
	 * @param string $which Which position.
	 */
	protected function display_tablenav( $which ) {

		if ( ! $this->get_total() ) {
			return;
		}

		?>
		<div class="tablenav <?php echo esc_attr( $which ); ?>">

			<?php
			if ( 'top' === $which ) {
				$this->extra_tablenav( $which );
				$this->clear_all();
			}
			$this->pagination( $which );
			?>

			<br class="clear" />
		</div>
		<?php
	}

	/**
	 * Table list actions.
	 *
	 * @since 3.19
	 *
	 * @param string $which Position of navigation (top or bottom).
	 */
	protected function extra_tablenav( $which ) {

		$this->component_dropdown();
		$this->date_select();
		submit_button( esc_html__( 'Filter', 'aawp' ), '', 'filter-action', false );
	}

	/**
	 * No items text.
	 *
	 * @since 3.19
	 */
	public function no_items() {

		if ( ! $this->db->is_logging_enabled() ) {
			esc_html_e( 'Activity Logging is currently disabled. Please enable logging to start saving logs in the database.', 'aawp' );
		} else {

			esc_html_e( 'No logs found.', 'aawp' );
		}
	}

	/**
	 * Clear all log logs.
	 *
	 * @since 3.19
	 */
	private function clear_all() {

		?>
		<button
			name="clear-all"
			type="submit"
			class="button"
			value="1"><?php esc_html_e( 'Delete All Logs', 'aawp' ); ?></button>
		<?php
	}

	/**
	 * Display list table page.
	 *
	 * @since 3.19
	 */
	public function render_page() {

		$this->prepare_column_headers();
		$this->process_admin_ui();
		$this->prepare_items();

		echo '<div class="aawp-list-table aawp-list-table--logs">';
		echo '<form action="admin.php?page=aawp-logs" id="' . esc_attr( $this->_args['plural'] ) . '-filter" method="post">';

		parent::display();
		echo wp_nonce_field( 'aawp_logs_listtable_actions' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo '</form>';
		echo '</div>';
	}

	/**
	 * Get prepared WHERE clause for items query
	 *
	 * @global wpdb $wpdb
	 *
	 * @return string Prepared WHERE clause for items query.
	 */
	protected function get_items_query_where() {
		global $wpdb;

		$where_conditions = [];
		$where_values     = [];

		if ( ! empty( $_REQUEST['component'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$where_conditions[] = 'component LIKE %s';
			$where_values[]     = '%' . $wpdb->esc_like( sanitize_text_field( wp_unslash( $_REQUEST['component'] ) ) ) . '%'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		if ( ! empty( $_REQUEST['startdate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$where_conditions[] = 'timestamp > %s';
			$where_values[]     = sanitize_text_field( $_REQUEST['startdate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		if ( ! empty( $_REQUEST['enddate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$where_conditions[] = 'timestamp < %s';
			$where_values[]     = sanitize_text_field( $_REQUEST['enddate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		if ( ! empty( $where_conditions ) ) {
			return $wpdb->prepare( 'WHERE 1 = 1 AND ' . implode( ' AND ', $where_conditions ), $where_values ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
		} else {
			return '';
		}
	}

	/**
	 * Update URL when table showing.
	 * _wp_http_referer is used only on bulk actions, we remove it to keep the $_GET shorter.
	 *
	 * @since 3.19
	 */
	public function process_admin_ui() {

		if ( empty( $_REQUEST['_wpnonce'] ) ) {
			return;
		}

		$verify = wp_verify_nonce( sanitize_key( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'aawp_logs_listtable_actions' );

		//phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( ! empty( $_REQUEST['clear-all'] ) && $verify ) {

			$this->db->clear_all();
		}
		//phpcs:enable WordPress.Security.NonceVerification.Recommended
	}

	/**
	 * Check if the database table exist.
	 *
	 * @since 3.19
	 *
	 * @return bool
	 */
	public function table_exists() {

		return $this->db->table_exists();
	}

	/**
	 * Get total logs.
	 *
	 * @since 3.19
	 *
	 * @return int
	 */
	public function get_total() {

		return $this->db->get_total();
	}
}
