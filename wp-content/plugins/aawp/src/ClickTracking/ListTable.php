<?php

namespace AAWP\ClickTracking;

use WP_List_Table;

use AAWP_DB_Products as Products;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Class ClickTracking ListTable.
 *
 * @since 3.20
 */
class ListTable extends WP_List_Table {

	/**
	 * DB Class.
	 *
	 * @var \AAWP\ClickTracking\DB DB.
	 */
	private $db;

	/**
	 * Products Class.
	 *
	 * @var AAWP_DB_Products
	 */
	private $products;

	/**
	 * ListTable constructor.
	 *
	 * @since 3.20
	 *
	 * @param \AAWP\ClickTracking\DB $db DB.
	 */
	public function __construct( $db ) {

		$this->db       = $db;
		$this->products = new Products();

		parent::__construct(
			[
				'plural'   => esc_html__( 'Clicks', 'aawp' ),
				'singular' => esc_html__( 'Click', 'aawp' ),
			]
		);

		add_filter( 'default_hidden_columns', [ $this, 'default_hidden_columns' ], 10, 2 );
		add_action( 'admin_init', [ $this, 'perform_actions' ] );
	}

	/**
	 * Prepare table list items.
	 *
	 * @global wpdb $wpdb
	 */
	public function prepare_items() {

		if ( ! $this->db->is_click_tracking_enabled() ) {
			return;
		}

		global $wpdb;

		$this->prepare_column_headers();

		$per_page = $this->get_items_per_page( 'aawp_click_items_per_page', 15 );

		$where  = $this->get_items_query_where();
		$order  = $this->get_items_query_order();
		$limit  = $this->get_items_query_limit();
		$offset = $this->get_items_query_offset();

		$query_items = "
			SELECT * FROM {$wpdb->prefix}aawp_clicks
			{$where} {$order} {$limit} {$offset}
		";

		// Count query needs to be different the total number varies due to timespan filter.
		$count_query = "SELECT COUNT( id ) as count FROM {$wpdb->prefix}aawp_clicks {$where}";

		$total_items = ! empty( $wpdb->get_results( $count_query )[0]->count ) ? $wpdb->get_results( $count_query )[0]->count : 0; // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery

		$this->items = $wpdb->get_results( $query_items ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery

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

		$per_page = $this->get_items_per_page( 'aawp_click_items_per_page', 15 );
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

		$per_page     = $this->get_items_per_page( 'aawp_click_items_per_page', 15 );
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

		$valid_orders = [ 'id', 'product', 'created_at' ];

		if ( ! empty( $_REQUEST['orderby'] ) && in_array( $_REQUEST['orderby'], $valid_orders, true ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			$by = sanitize_sql_orderby( wp_unslash( $_REQUEST['orderby'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		} else {
			$by = 'created_at';
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
	 * @since 3.20
	 *
	 * @param \AAWP\ClickTracking\Click $item List table item.
	 *
	 * @return int|string
	 */
	public function column_id( $item ) {

		return absint( $item->id );
	}

	/**
	 * Column Asin.
	 *
	 * @param Object $item A listtable item.
	 *
	 * @since 3.20
	 *
	 * @return string
	 */
	public function column_asin( $item ) {

		$product = $this->products->get_product_by( 'id', $item->product_id );

		return ! empty( $product['asin'] ) ? $product['asin'] : '-';
	}

	/**
	 * Column Preview.
	 *
	 * @param Object $item A listtable item.
	 *
	 * @since 3.20
	 *
	 * @return string
	 */
	public function column_preview( $item ) {

		$item = $this->products->get_product_by( 'id', $item->product_id );

		$image_id  = ! empty( $item['image_ids'] ) ? $item['image_ids'][0] : 0;
		$title     = ! empty( $item['title'] ) ? $item['title'] : '';
		$image_url = ! empty( \aawp_build_product_image_url( $image_id ) ) ? \aawp_build_product_image_url( $image_id ) : AAWP_PLUGIN_URL . 'assets/img/placeholder-medium.jpg';

		return sprintf( '<img src="%1$s" alt="%2$s" width="50" height="50" title="%3$s" />', $image_url, $title, $title );
	}

	/**
	 * Column Title.
	 *
	 * @param Object $item A listtable item.
	 *
	 * @since 3.20
	 *
	 * @return string
	 */
	public function column_title( $item ) {

		$item = $this->products->get_product_by( 'id', $item->product_id );

		if ( empty( $item ) ) {
			return esc_html__( 'Product was removed from database.', 'aawp' );
		}

		if ( empty( $item['title'] ) ) {
			return '-';
		}

		if ( strlen( esc_html( $item['title'] ) ) > 100 ) {

			$truncate  = '<div class="truncate">';
			$truncate .= substr( esc_html( $item['title'] ), 0, 100 );
			$truncate .= '<span class="ellipsis"> ...</span>';
			$truncate .= '</div>';

			$full = '<div class="full">' . esc_html( $item['title'] ) . '</div>';

			return $truncate . $full;
		}

		return esc_html( $item['title'] );
	}

	/**
	 * Column link.
	 *
	 * @since 3.20
	 *
	 * @param \AAWP\ClickTracking\Click $item List table item.
	 *
	 * @return int|string
	 */
	public function column_link( $item ) {

		$product = $this->products->get_product_by( 'id', $item->product_id );
		$url     = ! empty( $product['url'] ) ? aawp_replace_url_tracking_id_placeholder( $product['url'], $item->tracking_id, false ) : '';

		return esc_url( $url );
	}

	/**
	 * Column link Type.
	 *
	 * @since 3.20
	 *
	 * @param \AAWP\ClickTracking\Click $item List table item.
	 *
	 * @return int|string
	 */
	public function column_link_type( $item ) {

		return ! empty( $item->link_type ) ? esc_html( ucwords( str_replace( '_', ' ', $item->link_type ) ) ) : '';
	}

	/**
	 * Column Source Title.
	 *
	 * @since 3.20
	 *
	 * @param \AAWP\ClickTracking\Click $item List table item.
	 *
	 * @return string
	 */
	public function column_source( $item ) {

		$source = $this->get_source( $item->source_type, absint( $item->source_id ) );

		return sprintf( '<a href=%1s>%2s</a>', esc_url( $source['link'] ), esc_html( $source['title'] ) );
	}

	/**
	 * Column Referer URL.
	 *
	 * @since 3.20
	 *
	 * @param \AAWP\ClickTracking\Click $item List table item.
	 *
	 * @return string
	 */
	public function column_referer_url( $item ) {

		return ! empty( $item->referer_url ) ? esc_url( $item->referer_url ) : '';
	}


	/**
	 * Column Tracking ID.
	 *
	 * @since 3.20
	 *
	 * @param \AAWP\ClickTracking\Click $item List table item.
	 *
	 * @return string
	 */
	public function column_tracking_id( $item ) {

		return ! empty( $item->tracking_id ) ? $item->tracking_id : '-';
	}

	/**
	 * Column date.
	 *
	 * @since 3.20
	 *
	 * @param \AAWP\ClickTracking\Click $item List table item.
	 *
	 * @return string
	 */
	public function column_created_at( $item ) {

		return esc_html( $item->created_at );
	}

	/**
	 * Column Browser
	 *
	 * @since 3.20
	 *
	 * @param \AAWP\ClickTracking\Click $item List table item.
	 *
	 * @return string
	 */
	public function column_browser( $item ) {

		return esc_html( $item->browser );
	}

	/**
	 * Column OS
	 *
	 * @since 3.20
	 *
	 * @param \AAWP\ClickTracking\Click $item List table item.
	 *
	 * @return string
	 */
	public function column_os( $item ) {

		return esc_html( $item->os );
	}

	/**
	 * Column Device
	 *
	 * @since 3.20
	 *
	 * @param \AAWP\ClickTracking\Click $item List table item.
	 *
	 * @return string
	 */
	public function column_device( $item ) {

		return esc_html( $item->device );
	}

	/**
	 * Column Country.
	 *
	 * @since 3.20
	 *
	 * @param \AAWP\ClickTracking\Click $item List table item.
	 *
	 * @return string
	 */
	public function column_country( $item ) {
		return isset( $item->country ) ? esc_html( $item->country ) : '';
	}

	/**
	 * Returns the columns names for rendering.
	 *
	 * @since 3.20
	 *
	 * @return array
	 */
	public function get_columns() {

		$columns = [
			'id'          => __( 'ID', 'aawp' ),
			'asin'        => __( 'ASIN', 'aawp' ),
			'preview'     => __( 'Preview', 'aawp' ),
			'title'       => __( 'Title', 'aawp' ),
			'link_type'   => __( 'Type', 'aawp' ),
			'link'        => __( 'Target', 'aawp' ),
			'source'      => __( 'Source', 'aawp' ),
			'referer_url' => __( 'Referer URL', 'aawp' ),
			'created_at'  => __( 'Created At', 'aawp' ),
			'browser'     => __( 'Browser', 'aawp' ),
			'os'          => __( 'OS', 'aawp' ),
			'device'      => __( 'Device', 'aawp' ),
		];

		if ( ! empty( get_option( 'aawp_clicks_settings' )['country'] ) ) {
			$columns['country'] = __( 'Country', 'aawp' );
		}

		return apply_filters( 'aawp_clicks_columns', $columns );
	}

	/**
	 * Get a list of sortable columns.
	 *
	 * @return array
	 */
	protected function get_sortable_columns() {
		return [
			'id'         => [ 'id', 'asc' ],
			'created_at' => [ 'created_at', 'asc' ],
			'country'    => [ 'country', 'asc' ],
			'asin'       => [ 'asin', true ],
			'title'      => [ 'title', true ],
		];
	}

	/**
	 * Prepares the _column_headers property which is used by WP_Table_List at rendering.
	 * It merges the columns and the sortable columns.
	 *
	 * @since 3.20
	 */
	private function prepare_column_headers() {

		$this->_column_headers = [
			$this->get_columns(),
			get_hidden_columns( $this->screen ),
			$this->get_sortable_columns(),
		];
	}

	/**
	 * Get hidden columns. These columns can be enabled by using Screen Options.
	 *
	 * @since 3.20
	 */
	protected function get_hidden_columns() {

		return apply_filters( 'aawp_clicks_hidden_columns', [ 'browser', 'os', 'device' ] );
	}

	/**
	 * Filter the default list of hidden columns.
	 *
	 * @since 3.20s
	 *
	 * @param string[]  $hidden Array of IDs of columns hidden by default.
	 * @param WP_Screen $screen WP_Screen object of the current screen.
	 *
	 * @return string[]
	 */
	public function default_hidden_columns( $hidden, $screen ) {

		if ( $screen->id !== 'aawp_page_aawp-clicks' ) { //phpcs:ignore WordPress.PHP.YodaConditions.NotYoda
			return $hidden;
		}

		return $this->get_hidden_columns( $this->screen );
	}

	/**
	 * Display date selector
	 *
	 * @since 3.20
	 *
	 * @return mixed HTML output
	 */
	public function date_select() {

		?>
		<div class="aawp-clicks-date-range-filter" style="display: none">
			<label for="start-date" class="screen-reader-text"><?php esc_html_e( 'Start date', 'aawp' ); ?></label>

			<?php if ( ! empty( $_REQUEST['startdate'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
				<input class="filter-dates" placeholder="<?php esc_attr_e( 'Start date', 'aawp' ); ?>" name="startdate" id="start-date" type="date" value="<?php echo esc_attr( wp_unslash( $_REQUEST['startdate'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized ?>" />
			<?php else : ?>
				<input class="filter-dates" placeholder="<?php esc_attr_e( 'Start date', 'aawp' ); ?>" name="startdate" id="start-date" type="text" onfocus="(this.type='date')" />
			<?php endif; ?>

			<label for="end-date" class="screen-reader-text"><?php esc_html_e( 'End date', 'aawp' ); ?></label>

			<?php if ( ! empty( $_REQUEST['enddate'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
				<input class="filter-dates" placeholder="<?php esc_attr_e( 'End date', 'aawp' ); ?>" name="enddate" id="end-date" type="date" value="<?php echo esc_attr( wp_unslash( $_REQUEST['enddate'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized ?>" />
			<?php else : ?>
				<input class="filter-dates" placeholder="<?php esc_attr_e( 'End date', 'aawp' ); ?>" name="enddate" id="end-date" type="text" onfocus="(this.type='date')" />
			<?php endif; ?>

		</div> 
		<?php
	}

	/**
	 * Timespan select HTML.
	 *
	 * @since 3.20
	 */
	public function timespan_select_html() {

		?>
		<select id="aawp-clicks-graph-timespan" name="timespan" class="aawp-clicks-graph-select-timespan" title="<?php esc_attr_e( 'Select timespan', 'aawp' ); ?>" >
		<?php
		$this->timespan_options_html(
			apply_filters(
				'aawp_clicktracking_graph_timespan_options',
				[
					'today'      => __( 'Today', 'aawp' ),
					'yesterday'  => __( 'Yesterday', 'aawp' ),
					'last_7'     => __( 'Last 7 days', 'aawp' ),
					'last_30'    => __( 'Last 30 days', 'aawp' ),
					'this_month' => __( 'This month', 'aawp' ),
					'last_month' => __( 'Last month', 'aawp' ),
					'this_year'  => __( 'This year', 'aawp' ),
					'last_year'  => __( 'Last year', 'aawp' ),
					'all_time'   => __( 'All time', 'aawp' ),
					'custom'     => __( 'Custom', 'aawp' ),
				]
			)
		);
		?>
		</select>

		<?php
	}

	/**
	 * Timespan select options HTML.
	 *
	 * @since 3.20
	 *
	 * @param array $options Timespan options (in days).
	 */
	protected function timespan_options_html( $options ) {

		$timespan = ! empty( get_option( 'aawp_clicks_settings' )['timespan'] ) ? get_option( 'aawp_clicks_settings' )['timespan'] : 'last_30';
		$timespan = ! empty( $_REQUEST['timespan'] ) && 'custom' === sanitize_text_field( wp_unslash( $_REQUEST['timespan'] ) ) ? 'custom' : $timespan; //phpcs:ignore WordPress.Security.NonceVerification.Recommended

		foreach ( $options as $value => $label ) :
			?>
			<option value="<?php echo sanitize_key( $value ); ?>" <?php selected( $timespan, sanitize_key( $value ) ); ?> >
				<?php /* translators: %d - Number of days. */ ?>
				<?php echo esc_html( $label ); ?>
			</option>
			<?php
		endforeach;
	}

	/**
	 * Generate the table navigation above or below the table.
	 *
	 * @since 3.20
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
			}
			$this->pagination( $which );
			?>

			<br class="clear" />
		</div>
		<?php
	}

	/**
	 * No items text.
	 *
	 * @since 3.20
	 */
	public function no_items() {

		if ( ! $this->db->is_click_tracking_enabled() ) {
			esc_html_e( 'Click Tracking is currently disabled.', 'aawp' );
		} else {

			esc_html_e( 'No clicks found.', 'aawp' );
		}
	}

	/**
	 * Display list table page.
	 *
	 * @since 3.20
	 */
	public function display_page() {

		$this->prepare_column_headers();
		$this->prepare_items();

		echo '<div class="aawp-list-table aawp-list-table--clicks">';
		echo '<form action="admin.php?page=aawp-clicks" id="' . esc_attr( $this->_args['plural'] ) . '-filter" method="post">';

		parent::display();
		echo wp_nonce_field( 'aawp_clicks_listtable_actions' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

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
	public function get_items_query_where() {

		global $wpdb;

		$where_conditions = [];
		$where_values     = [];

		if ( ! empty( $_REQUEST['timespan'] ) && 'custom' === sanitize_text_field( wp_unslash( $_REQUEST['timespan'] ) ) && ! empty( $_REQUEST['startdate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$where_conditions[] = 'DATE( created_at ) >= %s';
			$where_values[]     = sanitize_text_field( wp_unslash( $_REQUEST['startdate'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		if ( ! empty( $_REQUEST['timespan'] ) && 'custom' === sanitize_text_field( wp_unslash( $_REQUEST['timespan'] ) ) && ! empty( $_REQUEST['enddate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$where_conditions[] = 'DATE( created_at ) <= %s';
			$where_values[]     = sanitize_text_field( wp_unslash( $_REQUEST['enddate'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		// Preset last X days dropdown.
		if ( empty( $_REQUEST['startdate'] ) && empty( $_REQUEST['enddate'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended

			$option = (array) get_option( 'aawp_clicks_settings' );

			$timespan_db = ! empty( $option['timespan'] ) ? $option['timespan'] : '';

			$timespan_rq = ! empty( $_REQUEST['timespan'] ) && 'custom' !== $_REQUEST['timespan'] ? sanitize_text_field( wp_unslash( $_REQUEST['timespan'] ) ) : $timespan_db; //phpcs:ignore WordPress.Security.NonceVerification.Recommended

			$option['timespan'] = $timespan_rq;

			update_option( 'aawp_clicks_settings', $option );

			switch ( $timespan_rq ) {
				case 'today':
					$condition = 'DATE( created_at ) = CURRENT_DATE()';
					break;

				case 'yesterday':
					$condition = 'DATE( created_at ) = CURRENT_DATE() - 1';
					break;

				case 'last_7':
					$condition = 'DATE( created_at ) >= DATE( NOW() - INTERVAL 7 DAY )';
					break;

				case 'last_30':
					$condition = 'DATE( created_at ) >= DATE( NOW() - INTERVAL 30 DAY )';
					break;

				case 'this_month':
					$condition = 'MONTH(created_at) = MONTH(CURRENT_DATE())
					AND YEAR(created_at) = YEAR(CURRENT_DATE())';
					break;

				case 'last_month':
					$condition = 'YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
					AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)';
					break;

				case 'this_year':
					$condition = 'YEAR(created_at) = YEAR(CURRENT_DATE())';
					break;

				case 'last_year':
					$condition = 'YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)';
					break;

				case 'all_time':
				case 'custom':
					$condition = '1 = 1';
					break;

				default:
					$condition = '1 = 1';
			}//end switch

			return "WHERE 1 = 1 AND {$condition}";
		}//end if

		// When the timespan is not selected, handle where conditions this way because start date and end date both can be selected.
		if ( ! empty( $where_conditions ) ) {

			return $wpdb->prepare( 'WHERE 1 = 1 AND ' . implode( ' AND ', $where_conditions ), $where_values ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
		} else {
			return '';
		}
	}

	/**
	 * Process admin actions buttons, such as filter dates.
	 *
	 * @since 3.20
	 */
	public function perform_actions() {

		if ( empty( $_REQUEST['_wpnonce'] ) || empty( $_GET['page'] ) || 'aawp-clicks' !== $_GET['page'] ) {
			return;
		}

		$verify = wp_verify_nonce( sanitize_key( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'aawp_clicks_listtable_actions' );

		if ( ! empty( $_REQUEST['startdate'] ) && ! empty( $_REQUEST['enddate'] ) && $verify ) {

			$start_date = sanitize_text_field( wp_unslash( $_REQUEST['startdate'] ) );
			$end_date   = sanitize_text_field( wp_unslash( $_REQUEST['enddate'] ) );

			wp_safe_redirect( admin_url( 'admin.php?page=aawp-clicks&startdate=' . $start_date . '&enddate=' . $end_date ) );
			exit();
		} elseif ( ! empty( $_REQUEST['timespan'] ) && 'last_30' !== $_REQUEST['timespan'] ) {
			wp_safe_redirect( admin_url( 'admin.php?page=aawp-clicks&timespan=' . sanitize_text_field( wp_unslash( $_REQUEST['timespan'] ) ) ) );
			exit();
		}
		//phpcs:enable WordPress.Security.NonceVerification.Recommended
	}

	/**
	 * Get source Link & Title
	 *
	 * @param string $source_type The type of click source.
	 * @param int    $source_id   The ID of the source.
	 *
	 * @return array An array of Link & Title.
	 */
	public function get_source( $source_type, $source_id ) { //phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		switch ( $source_type ) {
			case 'post':
				$link  = get_the_permalink( $source_id );
				$title = get_the_title( $source_id );
				break;

			case 'term':
				$link  = get_term_link( absint( $source_id ) );
				$title = get_term( $source_id )->name;
				break;

			case 'front_page':
				$link  = home_url();
				$title = get_bloginfo( 'name', 'raw' );
				break;

			case 'posts_page':
				$link  = get_permalink( get_option( 'page_for_posts' ) );
				$title = get_the_title( get_option( 'page_for_posts', true ) );
				break;

			default:
				$link  = '';
				$title = '';
		}//end switch

		return [
			'link'  => $link,
			'title' => ! empty( $title ) ? $title : '(no title)',
		];
	}

	/**
	 * Check if the database table exist.
	 *
	 * @since 3.20
	 *
	 * @return bool
	 */
	public function table_exists() {

		return $this->db->table_exists();
	}

	/**
	 * Get total clicks.
	 *
	 * @since 3.20
	 *
	 * @return int
	 */
	public function get_total() {

		return $this->db->get_total();
	}
}
