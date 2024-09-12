<?php

namespace AAWP\ClickTracking;

/**
 * Core class for click tracking listtable.
 */
class Init {

	/**
	 * Initialize.
	 *
	 * @since 3.20
	 */
	public function init() {

		if ( ! \aawp_is_license_valid() ) {
			return;
		}

		add_action( 'init', [ $this, 'start' ] );
		add_action( 'aawp_admin_menu', [ $this, 'add_clicks_submenu' ], 35 );
		add_action( 'wp_ajax_aawp_clicks_graph_save_data', [ $this, 'save_data' ] );
	}

	/**
	 * Start the click tracking module.
	 */
	public function start() {

		$db = new DB();

		if ( $db->is_click_tracking_enabled() ) {

			$db->create_table();
			// Create DB tables.

			// Load Frontend Class.
			$frontend = new Frontend();
			$frontend->init();

			
			do_action( 'clicktracking_init' );
		}

		add_action( 'admin_enqueue_scripts', [ $this, 'graph_scripts' ] );
	}

	/**
	 * The clicks submenu under AAWP Menu.
	 *
	 * @param string $menu_slug AAWP Menu Slug (aawp).
	 *
	 * @since 3.20
	 */
	public function add_clicks_submenu( $menu_slug ) {

		$hook = add_submenu_page(
			$menu_slug,
			esc_html__( 'AAWP - Clicks', 'aawp' ),
			esc_html__( 'Clicks', 'aawp' ),
			'edit_pages',
			'aawp-clicks',
			[ $this, 'render_page' ]
		);

		add_action( "load-$hook", [ $this, 'screen_options' ] );
	}

	/**
	 * Add screen options to the Clicks Listtable.
	 *
	 * @since 3.20
	 */
	public function screen_options() {

		global $listtable;

		add_screen_option(
			'clicks_per_page',
			[
				'label'   => 'Clicks',
				'default' => 10,
				'option'  => 'aawp_click_items_per_page',
			]
		);

		$listtable = new ListTable( new DB() );
	}

	/**
	 * Render Clicks Page.
	 */
	public function render_page() {

		$pages = apply_filters( 'aawp_clicks_pages', [ 'Clicks', 'Settings' ] );

		ob_start();
		?>
			<div class="wrap aawp-wrap">
				<h2> <!-- h2 is required for the core navigation stylings to work properly -->
					<?php esc_html_e( 'Clicks', 'aawp' ); ?>
				</h2>
				<nav class="nav-tab-wrapper">
					<?php
					foreach ( $pages as $page ) {
						echo '<a href="' . esc_url( wp_nonce_url( admin_url( 'admin.php?page=aawp-clicks&section=' . strtolower( $page ) ), 'aawp-' . strtolower( $page ) ) ) . '" 
										class="nav-tab ' . ( isset( $_GET['section'] ) && strtolower( $page ) === $_GET['section'] || ( ! isset( $_GET['section'] ) && strtolower( $page ) === 'clicks' ) ? 'nav-tab-active' : '' ) //phpcs:ignore WordPress.Security.NonceVerification.Recommended
										. '"
									>' // phpcs:ignore 
								. esc_html( $page ) .
							'</a>';
					}
					?>
				</nav>
			</div>
			<br/>
		<?php

		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		// Display Settings or Clicks Overview.
		if ( ! empty( $_GET['section'] ) && 'settings' === $_GET['section'] ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$settings = new Settings();
			$settings->init();

		} else {

			$listtable = new ListTable( new DB() );

			$this->clicks_graph();
			$listtable->display_page();
		}
	}

	/**
	 * Load graph-specific scripts.
	 *
	 * @since 3.20
	 *
	 * @param string $hook_suffix The current admin page.
	 */
	public function graph_scripts( $hook_suffix ) {

		if ( $hook_suffix !== 'aawp_page_aawp-clicks' ) { //phpcs:ignore WordPress.PHP.YodaConditions.NotYoda
			return;
		}

		wp_enqueue_style(
			'aawp-clicks-graph',
			AAWP_PLUGIN_URL . 'assets/graph/clicks-graph.css',
			[],
			AAWP_VERSION
		);

		wp_enqueue_script(
			'aawp-chart',
			AAWP_PLUGIN_URL . 'assets/graph/chart.min.js',
			[],
			'2.7.2',
			true
		);

		wp_enqueue_script(
			'aawp-clicks-graph',
			AAWP_PLUGIN_URL . 'assets/graph/clicks-graph.js',
			[ 'jquery', 'aawp-chart' ],
			AAWP_VERSION,
			true
		);

		wp_localize_script(
			'aawp-clicks-graph',
			'aawp_clicks_graph',
			[
				'nonce'            => wp_create_nonce( 'aawp_clicks_graph_nonce' ),
				'slug'             => 'clicks-graph',
				'empty_chart_html' => $this->get_empty_chart_html(),
				'chart_data'       => ! empty( get_option( 'aawp_clicks_settings' )['enable'] ) ? $this->get_clicks_data_by(
					! empty( get_option( 'aawp_clicks_settings' )['group'] ) ? get_option( 'aawp_clicks_settings' )['group'] : 'date',
					! empty( get_option( 'aawp_clicks_settings' )['filter'] ) ? get_option( 'aawp_clicks_settings' )['filter'] : '',
					! empty( $_REQUEST['timespan'] ) ? wp_unslash( sanitize_text_field( $_REQUEST['timespan'] ) ) :
						( ! empty( get_option( 'aawp_clicks_settings' )['timespan'] ) ? get_option( 'aawp_clicks_settings' )['timespan'] : 'last_30' ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				) : [],
				'chart_type'       => ! empty( get_option( 'aawp_clicks_settings' )['type'] ) ? get_option( 'aawp_clicks_settings' )['type'] : 'line',
				'i18n'             => [
					'total_clicks' => esc_html__( 'Total Clicks', 'aawp' ),
					'clicks'       => esc_html__( 'Clicks', 'aawp' ),
				],
			]
		);
	}

	/**
	 * Get total clicks data by days.
	 *
	 * @param string $group The x_axis param/group such as 'source_id', 'product_id' etc..
	 * @param string $filter Filter data by, example tracking_id.
	 * @param string $timespan Timespan values.
	 *
	 * @return array The result in array with 'count', 'x_axis' as keys.
	 */
	public function get_clicks_data_by( $group, $filter, $timespan ) { //phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded

		global $wpdb;

		$listtable = new ListTable( new DB() );

		if ( ! $listtable->table_exists() ) {
			return [];
		}

		// Filter by dates, start date, enddate etc.
		$where = $listtable->get_items_query_where();

		// Filter by tracking id.
		if ( $filter ) {
			$where .= $wpdb->prepare( ' AND tracking_id = %s', $filter );
		}

		// Date is separated as it needs to be manipulated in JS as well.
		// Count is y_axis, groups such as posts, products, tracking_ids etc. is x_axix.
		if ( 'date' === $group || empty( $group ) ) {
			$query = "SELECT COUNT( created_at ) as count, DATE( created_at ) as x_axis FROM {$wpdb->prefix}aawp_clicks {$where} GROUP BY DATE( created_at )";
		} elseif ( isset( $this->get_group_options()[ $group ] ) ) {

			$limit       = apply_filters( 'aawp_clicks_graph_data_limit', 10 );
			$source_type = 'source_id' === $group ? ', source_type' : '';

			$query = "SELECT COUNT( {$group} ) as count, {$group} as x_axis {$source_type} FROM {$wpdb->prefix}aawp_clicks {$where} GROUP BY {$group} {$source_type} ORDER BY count( {$group} ) DESC LIMIT {$limit}";
		}

		$sql_results = $wpdb->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared

		if ( empty( $sql_results ) ) {
			return [];
		}

		foreach ( $sql_results as $key => $result ) {

			switch ( $group ) {
				case 'source_id':
					$sql_results[ $key ]['x_axis'] = ! empty( $result['source_type'] ) ? $listtable->get_source( $result['source_type'], $result['x_axis'] )['title'] : '';
					break;

				case 'product_id':
					$products = new \AAWP_DB_Products();
					// @todo:: optimize.
					$sql_results[ $key ]['x_axis'] = ! empty( $products->get_product_by( 'id', $result['x_axis'] )['title'] ) ? $products->get_product_by( 'id', $result['x_axis'] )['title'] : '';
					break;
			}
		}

		// Manipulate data for date group, to fill all the dates with 0 data.
		if ( $group === 'date' || empty( $group ) ) { //phpcs:ignore WordPress.PHP.YodaConditions.NotYoda

			$data  = [];
			$dates = $this->get_dates_by_timespan( $timespan );

			foreach ( $dates as $date ) {
				$data[] = $this->get_result_by_date( $date, $sql_results );
			}

			return array_reverse( $data );
		} else {

			return $sql_results;
		}
	}

	/**
	 * Get the results by date. Specifically used to fill the emtpy dates of last n days.
	 *
	 * @param string $date The date.
	 * @param array  $results The whole SQL results.
	 *
	 * @return array The result of specific date.
	 */
	public function get_result_by_date( $date, $results ) {

		foreach ( $results as $result ) {

			// The result already exists. So, no need to do anything.
			if ( $result['x_axis'] === $date ) {
				return $result;
			}
		}

		return [
			'count'  => 0,
			'x_axis' => $date,
		];
	}

	//phpcs:disable

	/**
	 * Get dates by timespan.
	 *
	 * @param string $timespan The selected timespan from the dropdown.
	 *
	 * @return array Last N days dates.
	 */
	public function get_dates_by_timespan( $timespan ) { //phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded

		$dates = [];

		switch ( $timespan ) {
			case 'today':
				$dates[] = gmdate( 'Y-m-d' );
				break;
			case 'yesterday':
				$dates[] = gmdate( 'Y-m-d', strtotime( '-1 day' ) );
				break;
			case 'last_7':
				for ( $i = 0; $i < 7; $i++ ) {
					$dates[] = gmdate( 'Y-m-d', strtotime( "-$i days" ) );
				}
				break;
			case 'last_30':
				for ( $i = 0; $i < 30; $i++ ) {
					$dates[] = gmdate( 'Y-m-d', strtotime( "-$i days" ) );
				}
				break;
			case 'this_month':
				$currentDay     = date( 'd' );
				$numDaysInMonth = date( 't' );
				for ( $day = 1; $day <= $numDaysInMonth; $day++ ) {
					if ( $day > $currentDay ) {
						break;
					}
					$formattedDate = date( 'Y-m-d', strtotime( date( 'Y-m' ) . "-$day" ) );
					$dates[]       = $formattedDate;
				}

				$dates = array_reverse( $dates );
				return $dates;
				break;
			case 'last_month':
				$year  = gmdate( 'Y' );
				$month = gmdate( 'm' );

				if ( $month == 1 ) {
					$year--;
					$month = 12;
				} else {
					$month--;
				}

				$numDaysInMonth = gmdate( 't', strtotime( "$year-$month-01" ) );
				// get the number of days in the month
				for ( $i = 1; $i <= $numDaysInMonth; $i++ ) {
					$dates[] = gmdate( "$year-$month-" . str_pad( $i, 2, '0', STR_PAD_LEFT ) );
					// pad single digit days with a leading zero
				}
				$dates = array_reverse( $dates );
				break;
			case 'this_year':
				$currentMonth = date( 'm' );
				for ( $month = 1; $month <= 12; $month++ ) {
					if ( $month > $currentMonth ) {
						break;
					}
					$numDaysInMonth = date( 't', strtotime( date( 'Y' ) . "-$month-01" ) );
					// get the number of days in the month
					for ( $day = 1; $day <= $numDaysInMonth; $day++ ) {
						if ( $month == $currentMonth && $day > date( 'd' ) ) {
							break 2;
						}
						$formattedDate = date( 'Y-m-d', strtotime( date( 'Y' ) . "-$month-$day" ) );
						$dates[]       = $formattedDate;
					}
				}
				$dates = array_reverse( $dates );
				return $dates;
				break;
			case 'last_year':
				for ( $month = 1; $month <= 12; $month++ ) {
					$numDaysInMonth = gmdate( 't', strtotime( ( gmdate( 'Y' ) - 1 ) . "-$month-01" ) );
					// get the number of days in the month
					for ( $day = 1; $day <= $numDaysInMonth; $day++ ) {
						$formattedDate = date( 'Y-m-d', strtotime( ( gmdate( 'Y' ) - 1 ) . "-$month-$day" ) );
						$dates[]       = $formattedDate;
					}
				}
				  $dates = array_reverse( $dates );
				break;
			case 'all_time':
				$min_created_at = $this->get_smallest_created_at_date();

				$start = strtotime( $min_created_at );

				// Set the end date to the current date
				$end = time();

				// Create an array to store the dates
				$dates = [];

				// Loop through the date range and add each date to the array
				for ( $i = $start; $i <= $end; $i += 86400 ) {
					$dates[] = gmdate( 'Y-m-d', $i );
				}
				$dates = array_reverse( $dates );
				break;
			case 'custom':
				$start_date = ! empty( $_REQUEST['startdate'] ) ? wp_unslash( sanitize_text_field( $_REQUEST['startdate'] ) ) : '';
				$end_date   = ! empty( $_REQUEST['enddate'] ) ? wp_unslash( sanitize_text_field( $_REQUEST['enddate'] ) ) : '';

				$start_date = empty( $start_date ) ? $this->get_smallest_created_at_date() : $start_date;

				$current_date = strtotime( $start_date );
				$end_date     = empty( $end_date ) ? time() : strtotime( $end_date );

				while ( $current_date <= $end_date ) {
					$dates[]      = gmdate( 'Y-m-d', $current_date );
					$current_date = strtotime( '+1 day', $current_date );
				}

				return array_reverse( $dates );

				break;

		}//end switch

		return $dates;
	}

	//phpcs:enable

	/**
	 * Get smallest created at date. Especially required when displying all time records. We need a starting date to display
	 * dates in graph from the starting date to now.
	 *
	 * @since 3.20.s
	 */
	private function get_smallest_created_at_date() {

		global $wpdb;

		$query       = "SELECT DATE( MIN(created_at) ) as min_created_at FROM {$wpdb->prefix}aawp_clicks";
		$sql_results = $wpdb->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared

		return isset( $sql_results[0]['min_created_at'] ) ? $sql_results[0]['min_created_at'] : '';
	}

	/**
	 * Get empty chart HTML.
	 *
	 * @since 3.20
	 */
	public function get_empty_chart_html() {

		\ob_start();
		?>

		<div class="aawp-error aawp-error-no-data-chart">
			<div class="aawp-clicks-graph-modal">
				<?php
				if ( ! empty( get_option( 'aawp_clicks_settings' )['enable'] ) ) {
					echo '<h2>' . \esc_html__( 'No clicks found with the selected filters.', 'aawp' ) . '</h2>';
					\esc_html_e( 'Please select a different dates or check back later.', 'aawp' );
				} else {
					echo '<h2>' . \esc_html__( 'Click Tracking Disabled.', 'aawp' ) . '</h2>';
					printf(
						wp_kses( /* translators: %s - AAWP Settings Page. */
							__( 'Please <a href="%s">enable click tracking</a> to track Amazon affiliate links clicks.', 'aawp' ),
							[
								'a' => [
									'href' => [],
								],
							]
						),
						esc_url( wp_nonce_url( admin_url( 'admin.php?page=aawp-clicks&section=settings' ), 'aawp-settings' ) )
					);
				}
				?>
			</div>
		</div>

		<?php
		return \ob_get_clean();
	}

	/**
	 * Render clicks graph content.
	 *
	 * @since 3.20
	 */
	private function clicks_graph() {

		global $listtable;

		ob_start();
		?>
			<div class="aawp-clicks-graph-block-container">
				<div class="aawp-clicks-graph-heading-block">
					<h3 id="aawp-clicks-graph-chart-title">
						<?php echo '<div id="total-clicks-count">Total Clicks: 0</div>'; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</h3>
					<div class="aawp-clicks-graph-settings">
						<div class="aawp-clicks-graph-other-options">
							<div class="chart-type">
								<strong> <?php echo esc_html__( 'Chart Type', 'aawp' ); ?> </strong><br/>
								<?php echo $this->charttype_select_html(); ?>
							</div>
							<div class="group-by">
								<strong> <?php echo esc_html__( 'Group By', 'aawp' ); ?> </strong><br/>
								<?php echo $this->group_select_html(); ?>
							</div>
							<div class="filter-by">
								<strong> <?php echo esc_html__( 'Filter By', 'aawp' ); ?> </strong><br/>
								<?php echo $this->filter_select_html(); ?>
							</div>
						</div>

						<div class="aawp-clicks-graph-date-filter-options">
							<?php
								echo '<form action="admin.php?page=aawp-clicks" method="post">';
									$listtable->timespan_select_html();
									$listtable->date_select();
									submit_button( esc_html__( 'Filter', 'aawp' ), '', '', false );
								echo '</form>';
							?>
						</div>
					</div>
				</div>

				<div class="aawp-clicks-graph-body-block">
					<canvas  width="400" height="300" id="aawp-clicks-graph-chart"></canvas>
					<div class="aawp-clicks-graph-overlay"></div>
				</div>
			</div>
		<?php

		echo ob_get_clean(); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Group select HTML. The comparison of clicks count vs {what}? default is 'date'.
	 *
	 * @since 3.20
	 */
	protected function group_select_html() {
		?>
		<select id="aawp-clicks-graph-group" class="aawp-clicks-graph-select-group" title="<?php esc_attr_e( 'Select group', 'aawp' ); ?>" >
			<?php
			$this->group_options_html(
				$this->get_group_options()
			);
			?>
		</select>

		<?php
	}

	/**
	 * Filter select HTML. Filter data by tracking_id.
	 *
	 * @since 3.20
	 */
	protected function filter_select_html() {
		$group = ! empty( get_option( 'aawp_clicks_settings' )['group'] ) ? get_option( 'aawp_clicks_settings' )['group'] : 'date';
		?>
		<select <?php echo 'tracking_id' === $group ? 'disabled' : ''; ?> id="aawp-clicks-graph-filter" class="aawp-clicks-graph-select-filter" title="<?php esc_attr_e( 'Select filter', 'aawp' ); ?>" >
			<?php
			$this->filter_options_html(
				$this->get_filter_options()
			);
			?>
		</select>

		<?php
	}

	/**
	 * Filter select options HTML.
	 *
	 * @since 3.20
	 *
	 * @param array $options filter options.
	 */
	protected function filter_options_html( $options ) {

		$filter = ! empty( get_option( 'aawp_clicks_settings' )['filter'] ) ? get_option( 'aawp_clicks_settings' )['filter'] : '';

		foreach ( $options as $key => $option ) :
			?>
			<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $filter, esc_attr( $key ) ); ?> >
				<?php echo esc_html( $option ); ?>
			</option>
			<?php
		endforeach;
	}

	/**
	 * Get group options for the dropdown.
	 *
	 * @since 3.20
	 */
	public function get_group_options() {
		return apply_filters(
			'aawp_clicktracking_graph_group_options',
			[
				'date'        => esc_html__( 'Date', 'aawp' ),
				'source_id'   => esc_html__( 'Source', 'aawp' ),
				'tracking_id' => esc_html__( 'Tracking ID', 'aawp' ),
			]
		);
	}

	/**
	 * Get filter options for the dropdown
	 *
	 * @since 3.20
	 */
	public function get_filter_options() {

		global $wpdb, $listtable;

		$sql_results = [];

		if ( $listtable->table_exists() ) {

			$query       = "SELECT DISTINCT tracking_id FROM {$wpdb->prefix}aawp_clicks";
			$sql_results = $wpdb->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
		}

		$tracking_ids = [];

		foreach ( $sql_results as $result ) {
			if ( empty( $result['tracking_id'] ) ) {
				continue;
			}

			$tracking_ids[ $result['tracking_id'] ] = $result['tracking_id'];
		}

		array_unshift( $tracking_ids, esc_html__( '-- Select a tracking id --', 'aawp' ) );

		return $tracking_ids;
	}

	/**
	 * Group select options HTML.
	 *
	 * @since 3.20
	 *
	 * @param array $options group options.
	 */
	protected function group_options_html( $options ) {

		$group = ! empty( get_option( 'aawp_clicks_settings' )['group'] ) ? get_option( 'aawp_clicks_settings' )['group'] : 'date';

		foreach ( $options as $key => $option ) :
			?>
			<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $group, esc_attr( $key ) ); ?> >
				<?php echo esc_html( $option ); ?>
			</option>
			<?php
		endforeach;
	}

	/**
	 * Charttype select HTML.
	 *
	 * @since 3.20
	 */
	protected function charttype_select_html() {

		?>
		<select id="aawp-clicks-graph-charttype" class="aawp-clicks-graph-select-charttype" title="<?php esc_attr_e( 'Select Chart Type', 'aawp' ); ?>" >
			<?php
			$this->charttype_options_html( apply_filters( 'aawp_clicktracking_graph_types', [ 'line', 'bar' ] ) );
			// Other possible values are: 'pie', 'doughnut', 'polarArea', 'bubble', 'scatter'.
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
	protected function charttype_options_html( $options ) {

		$graph_type = ! empty( get_option( 'aawp_clicks_settings' )['type'] ) ? get_option( 'aawp_clicks_settings' )['type'] : 'line';

		foreach ( $options as $option ) :
			?>
			<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $graph_type, esc_attr( $option ) ); ?> >
				<?php /* translators: %d - Number of days. */ ?>
				<?php echo esc_html( ucfirst( $option ) ) . ' ' . esc_html__( 'Chart', 'aawp' ); ?>
			</option>
			<?php
		endforeach;
	}

	/**
	 * Save data from the AJAX. The days, type of graph etc.
	 *
	 * @since 3.20
	 */
	public function save_data() {

		check_admin_referer( 'aawp_clicks_graph_nonce' );

		$group    = ! empty( $_POST['group'] ) ? sanitize_text_field( wp_unslash( $_POST['group'] ) ) : 'date';
		$filter   = ! empty( $_POST['filter'] ) ? sanitize_text_field( wp_unslash( $_POST['filter'] ) ) : '';
		$type     = ! empty( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : 'line';
		$timespan = ! empty( $_POST['timespan'] ) ? sanitize_text_field( wp_unslash( $_POST['timespan'] ) ) : 'last_30';

		$option = (array) get_option( 'aawp_clicks_settings' );

		$option['group']  = $group;
		$option['filter'] = $filter;
		$option['type']   = $type;

		update_option( 'aawp_clicks_settings', $option );

		aawp_log( 'Clicks', esc_html__( 'Clicks Settings Updated!' ) );

		$data = $this->get_clicks_data_by( $group, $filter, $timespan );

		wp_send_json( $data );
	}
}
