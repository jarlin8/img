<?php

namespace AAWP\Admin\ProductsTable;

use WP_List_Table;
use AAWP_DB_Products;

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
	 * An instance of \AAWP_DB_Products
	 *
	 * @var $products_db \AAWP_DB_Products
	 */
	public $products_db;

	/**
	 * Products ListTable constructor.
	 *
	 * @since 3.19
	 */
	public function __construct() {

		$this->products_db = new AAWP_DB_Products();

		parent::__construct(
			[
				'plural'   => esc_html__( 'Products', 'aawp' ),
				'singular' => esc_html__( 'product', 'aawp' ),
			]
		);
	}

	/**
	 * No items text.
	 *
	 * @since 3.19
	 */
	public function no_items() {

		echo esc_html__( 'No products found.', 'aawp' );
	}

	/**
	 * Column checkbox to select item(s).
	 *
	 * @param Object $item A listtable item.
	 *
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item['id'] );
	}

	/**
	 * Column id.
	 *
	 * @param Object $item A listtable item.
	 *
	 * @since 3.19
	 *
	 * @return int
	 */
	public function column_id( $item ) {

		$actions = [
			'renew'  => '<a href="' . esc_url( wp_nonce_url( admin_url( 'admin.php?page=aawp-products&action=renew&id=' . $item['id'] ), 'aawp_single_product_delete' ) ) . '">' . esc_html__( 'Renew', 'aawp' ) . '</a>',
			'delete' => '<a href="' . esc_url( wp_nonce_url( admin_url( 'admin.php?page=aawp-products&action=delete&id=' . $item['id'] ), 'aawp_single_product_delete' ) ) . '">' . esc_html__( 'Delete', 'aawp' ) . '</a>',
		];

		return sprintf( '%1$s %2$s', $item['id'], $this->row_actions( $actions ) );
	}

	/**
	 * Column Asin.
	 *
	 * @param Object $item A listtable item.
	 *
	 * @since 3.19
	 *
	 * @return string
	 */
	public function column_asin( $item ) {

		return ! empty( $item['asin'] ) ? esc_html( $item['asin'] ) : '-';
	}

	/**
	 * Column Preview.
	 *
	 * @param Object $item A listtable item.
	 *
	 * @since 3.19
	 *
	 * @return string
	 */
	public function column_preview( $item ) {

		$image_id  = ! empty( $item['image_ids'] ) ? $item['image_ids'][0] : 0;
		$title     = ! empty( $item['title'] ) ? $item['title'] : '';
		$image_url = ! empty( \aawp_build_product_image_url( $image_id ) ) ? \aawp_build_product_image_url( $image_id ) : AAWP_PLUGIN_URL . 'assets/img/placeholder-medium.jpg';

		return sprintf( '<img src="%1$s" alt="%2$s" title="%3$s" />', $image_url, $title, $title );
	}

	/**
	 * Column Title.
	 *
	 * @param Object $item A listtable item.
	 *
	 * @since 3.19
	 *
	 * @return string
	 */
	public function column_title( $item ) {

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
	 * Column URL.
	 *
	 * @param Object $item A listtable item.
	 *
	 * @since 3.19
	 *
	 * @return string
	 */
	public function column_url( $item ) {

		$url = explode( '?', $item['url'] );

		return ! empty( $url[0] ) ? '<a rel="nofollow" target="_blank" href=" ' . esc_url( $url[0] ) . ' ">' . esc_html__( 'View on Amazon', 'aawp' ) . '</a>' : '-';
	}

	/**
	 * Column Clicks.
	 *
	 * @param Object $item A listtable item.
	 *
	 * @since 3.19
	 *
	 * @return int
	 */
	public function column_clicks( $item ) {

		$product_id = ! empty( $item['id'] ) ? $item['id'] : 0;

		return \AAWP\ClickTracking\DB::get_total_clicks_by( 'product_id', $product_id );
	}

	/**
	 * Column Created AT.
	 *
	 * @param Object $item A listtable item.
	 *
	 * @since 3.19
	 *
	 * @return string
	 */
	public function column_date_created( $item ) {
		return ! empty( $item['date_created'] ) ? sanitize_text_field( $item['date_created'] ) : '-';
	}

	/**
	 * Column Updated AT.
	 *
	 * @param Object $item A listtable item.
	 *
	 * @since 3.19
	 *
	 * @return string
	 */
	public function column_date_updated( $item ) {
		return ! empty( $item['date_updated'] ) ? sanitize_text_field( $item['date_updated'] ) : '-';
	}

	/**
	 * Returns the columns names for rendering.
	 *
	 * @since 3.19
	 *
	 * @return array
	 */
	public function get_columns() {

		$columns = [
			'cb'           => '<input type="checkbox" />',
			'id'           => __( 'ID', 'aawp' ),
			'asin'         => __( 'ASIN', 'aawp' ),
			'preview'      => __( 'Preview', 'aawp' ),
			'title'        => __( 'Title', 'aawp' ),
			'url'          => __( 'URL', 'aawp' )
		];

		if ( ! empty( get_option( 'aawp_clicks_settings' )['enable'] ) ) {
			$columns['clicks'] = __( 'Clicks', 'aawp' );
		}

		$columns['date_created'] = __( 'Created At', 'aawp' );
		$columns['date_updated'] = __( 'Updated At', 'aawp' );

		return apply_filters( 'aawp_admin_products_get_columns', $columns );
	}

	/**
	 * Get a list of sortable columns.
	 *
	 * @return array
	 */
	protected function get_sortable_columns() {
		return [
			'id'           => [ 'id', true ],
			'asin'         => [ 'asin', true ],
			'title'        => [ 'title', true ],
			'clicks'       => [ 'clicks', true ],
			'date_created' => [ 'date_created', true ],
			'date_updated' => [ 'date_updated', true ],
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
	 * Get bulk actions.
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		return [
			'delete' => __( 'Delete', 'aawp' ),
			'renew'  => __( 'Renew', 'aawp' ),
		];
	}

	/**
	 * Generate the table navigation above or below the table.
	 *
	 * @since 3.19
	 *
	 * @param string $which Which position.
	 */
	protected function display_tablenav( $which ) {

		?>
		<div class="tablenav <?php echo esc_attr( $which ); ?>">

			<?php

			if ( count( $this->items ) ) {
				$this->bulk_actions();
				$this->pagination( $which );
			}
			?>

			<br class="clear" />
		</div>
		<?php
	}

	/**
	 * Interface for filter products by status.
	 *
	 * @since 3.20.0
	 */
	private function filter_by_status() {

		?>
			<ul class="subsubsub">
				<li><a class="<?php echo empty( $_GET['status'] ) || ( ! empty( $_GET['status'] ) && 'all' === $_GET['status'] ) ? 'current' : ''; ?>" 
						href="<?php echo esc_url( admin_url( 'admin.php?page=aawp-products&status=all' ) ); ?>">
					<?php echo esc_html__( 'All', 'aawp' ); ?>
					<?php
					echo '(' . count(
						$this->products_db->get_products(
							[
								'status' => '',
								'number' => PHP_INT_MAX,
							]
						)
					) . ')';
					?>
					</a>
				</li> |
				<li><a class="<?php echo ! empty( $_GET['status'] ) && 'active' === $_GET['status'] ? 'current' : ''; ?>" 
						href="<?php echo esc_url( admin_url( 'admin.php?page=aawp-products&status=active' ) ); ?>">
					<?php echo esc_html__( 'Active', 'aawp' ); ?>
					<?php
					echo '(' . count(
						$this->products_db->get_products(
							[
								'status' => 'active',
								'number' => PHP_INT_MAX,
							]
						)
					) . ')';
					?>
					</a>
				</li> |
				<li><a class="<?php echo ! empty( $_GET['status'] ) && 'invalid' === $_GET['status'] ? 'current' : ''; ?>" 
						href="<?php echo esc_url( admin_url( 'admin.php?page=aawp-products&status=invalid' ) ); ?>">
					<?php echo esc_html__( 'Invalid', 'aawp' ); ?>
					<?php
					echo '(' . count(
						$this->products_db->get_products(
							[
								'status' => 'invalid',
								'number' => PHP_INT_MAX,
							]
						)
					) . ')';
					?>
					</a>
				</li>
			</ui>
		<?php
	}

	/**
	 * Display list table page.
	 *
	 * @since 3.19
	 */
	public function display_page() {

		$this->filter_by_status();
		$this->prepare_column_headers();
		$this->process_actions();
		$this->prepare_items();

		echo '<div class="aawp-list-table aawp-list-table--products">';
		echo '<form action="admin.php?page=aawp-products" id="products-filter" method="post">';

		$this->search_box( esc_html__( 'Search Product(s)', 'aawp' ), 'aawp' );

		// Do not display search dropdown if there are no products or if it's not search request
		if ( count( $this->items ) !== 0 || ! empty( $_REQUEST['s'] ) ) {
			$this->search_dropdown();
		}

		parent::display();

		echo wp_nonce_field( 'aawp_products_listtable_actions' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo '</form>';
		echo '</div>';
	}

	/**
	 * Prepare list table items.
	 *
	 * @since 3.19.
	 */
	public function prepare_items() {

		$per_page = $this->get_items_per_page( 'aawp_admin_products_listtable_per_page', 15 );
		$args     = [
			'number' => $per_page,
			'status' => '',
		];

		// Process search if required.
		if ( ! empty( $_REQUEST['search-dropdown'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$args = array_merge( $args, $this->process_search() );
		}

		if ( ! empty( $_REQUEST['orderby'] ) && ! empty( $_REQUEST['order'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$order = [
				'orderby' => sanitize_sql_orderby( wp_unslash( $_REQUEST['orderby'] ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				'order'   => 'asc' === $_REQUEST['order'] ? 'ASC' : 'DESC', //phpcs:ignore
			];

			$args = array_merge( $args, $order );
		}

		if ( ! empty( $_REQUEST['status'] ) && in_array( $_REQUEST['status'], [ 'active', 'invalid' ] ) ) {
			$args['status'] = sanitize_text_field( wp_unslash( $_REQUEST['status'] ) );
		}

		// Offset.
		$args = array_merge( $args, $this->get_items_query_offset() );

		$this->items = $this->products_db->get_products( $args );

		// Order and Orderby Clicks. Manually sorted because clicks are on different table.
		if ( ! empty( $order['orderby'] ) && $order['orderby'] === 'clicks' ) { //phpcs:ignore WordPress.PHP.YodaConditions.NotYoda

			$args['number'] = PHP_INT_MAX;
			// maybe becomes slow for large number of products.

			$this->items = $this->products_db->get_products( $args );

			$ordered_items = $this->items;
			foreach ( $this->items as $key => $item ) {
				$ordered_items[ $key ]['clicks'] = \AAWP\ClickTracking\DB::get_total_clicks_by( 'product_id', $item['id'] );
			}

			$order = 'DESC' === $order['order'] ? SORT_DESC : SORT_ASC;

			$keys = array_column( $ordered_items, 'clicks' );
			array_multisort( $keys, $order, $ordered_items );

			$this->items = $ordered_items;

			$this->items = array_slice( $this->items, 0, $per_page );
		}//end if

		$total_items = count( $this->products_db->get_products( [ 'number' => PHP_INT_MAX ] ) );

		$total_items = count(
			$this->products_db->get_products(
				[
					'status' => '',
					'number' => PHP_INT_MAX,
				]
			)
		);

		$this->set_pagination_args(
			[
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total_items / $per_page ),
			]
		);
	}

	/**
	 * Process actions: single action, bulk actions etc.
	 *
	 * Delete, Renew and Clear All actions to be precise.
	 *
	 * @todo break actions into methods.
	 *
	 * @since 3.19.
	 */
	public function process_actions() { //phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded

		if ( empty( $_REQUEST['_wpnonce'] ) || empty( $_REQUEST['action'] ) || ! ( in_array( $_REQUEST['action'], [ 'delete', 'renew' ], true ) ) ) {
			return;
		}

		$single_action   = wp_verify_nonce( sanitize_key( $_REQUEST['_wpnonce'] ), 'aawp_single_product_delete' ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$verified_action = wp_verify_nonce( sanitize_key( $_REQUEST['_wpnonce'] ), 'aawp_products_listtable_actions' ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$query           = [];

		if ( $single_action ) {

			$product_id = ! empty( $_REQUEST['id'] ) ? absint( $_REQUEST['id'] ) : 0;

			if ( 'delete' === $_REQUEST['action'] ) {

				$deleted = $this->products_db->delete( $product_id );

				if ( $deleted ) {
					/* translators: %s Product ID. */
					aawp_log( 'Product', sprintf( wp_kses( __( 'Product <code>#%s</code> deleted via products listtable.', 'aawp' ), [ 'code' => [] ] ), $product_id ) );

					$query = [ 'deleted' => 1 ];
				}
			} elseif ( 'renew' === $_REQUEST['action'] ) {

				/**
				 * Create a simplified function to renew single product by id.
				 *
				 * @todo
				 */
				$product = \aawp_get_product( $product_id );
				$renewed = \aawp_renew_products( [ $product ] );

				if ( $renewed ) {
					/* translators: %s Product ID. */
					aawp_log( 'Product', sprintf( wp_kses( __( 'Product <code>#%s</code> renewed via products listtable.', 'aawp' ), [ 'code' => [] ] ), $product_id ) );
					$query = [ 'renewed' => 1 ];
				}
			}//end if
		} elseif ( $verified_action ) {

			$product_ids = isset( $_REQUEST['product'] ) ? (array) wp_unslash( $_REQUEST['product'] ) : []; //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$product_ids = array_map( 'absint', $product_ids );

			foreach ( $product_ids as $id ) {

				if ( 'delete' === $_REQUEST['action'] ) {

					$deleted = $this->products_db->delete( $id );

					static $i = 1;

					if ( 1 === $i && $deleted ) {
						/* translators: %s Product IDs. */
						aawp_log( 'Product', sprintf( wp_kses( _n( '<code>%1$d</code> product with ID <code>#%2$s</code> deleted via products listtable bulk action.', '<code>%1$d</code> products with IDs <code>#%2$s</code> deleted via products listtable bulk action.', count( $product_ids ), 'aawp' ), [ 'code' => [] ] ), count( $product_ids ), implode( ', #', $product_ids ) ) );
						$query = [ 'deleted' => count( $product_ids ) ];
						$i++;
					}
				} elseif ( 'renew' === $_REQUEST['action'] ) {

					/**
					 * Create a simplified function to renew multiple products by id.
					 *
					 * @todo
					 */
					$product = \aawp_get_product( $id );
					$renewed = \aawp_renew_products( [ $product ] );

					static $i = 1;

					if ( 1 === $i && $renewed ) {
						/* translators: %s Product IDs. */
						aawp_log( 'Product', sprintf( wp_kses( _n( '<code>%1$d</code> product with ID <code>#%2$s</code> renewed via products listtable bulk action.', '<code>%1$d</code> products with IDs <code>#%2$s</code> renewed via products listtable bulk action.', count( $product_ids ), 'aawp' ), [ 'code' => [] ] ), count( $product_ids ), implode( ', #', $product_ids ) ) );
						$query = [ 'renewed' => count( $product_ids ) ];
						$i++;
					}
				}//end if
			}//end foreach
		}//end if

		set_transient( '_transient_aawp_products_listtable_actions_notice', true, 5 );

		$this->display_notices( $query );
	}

	/**
	 * Process search query.
	 *
	 * @since 3.19.
	 *
	 * @return array An array of arguments passed while searching products.
	 */
	public function process_search() {

		if ( empty( $_REQUEST['_wpnonce'] ) ) {
			return [];
		}

		$verified_action = wp_verify_nonce( sanitize_key( $_REQUEST['_wpnonce'] ), 'aawp_products_listtable_actions' ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		if ( $verified_action ) {

			$search_dropdown = ! empty( $_REQUEST['search-dropdown'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['search-dropdown'] ) ) : '';
			$search_term     = ! empty( $_REQUEST['s'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) : '';

			return [ $search_dropdown => $search_term ];
		}
	}

	/**
	 * Get OFFSET clause for items query
	 *
	 * @return array OFFSET clause for items query.
	 *
	 * @since 3.19
	 */
	protected function get_items_query_offset() {

		$per_page     = $this->get_items_per_page( 'aawp_admin_products_listtable_per_page', 15 );
		$current_page = $this->get_pagenum();

		if ( 1 < $current_page ) {
			$offset = $per_page * ( $current_page - 1 );
		} else {
			$offset = 0;
		}

		return [ 'offset' => $offset ];
	}

	/**
	 * Search dropdown.
	 *
	 * @since 3.19.
	 */
	private function search_dropdown() {

		$default = isset( $_REQUEST['search-dropdown'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['search-dropdown'] ) ) : ''; //phpcs:ignore

		?>
			<select class="search-dropdown" name="search-dropdown">
				<option value="asin" <?php selected( $default, 'asin' ); ?> >ASIN</option>
				<option value="title" <?php selected( $default, 'title' ); ?>><?php echo esc_html__( 'Title', 'aawp' ); ?> </option>
			</select>
		<?php
	}

	/**
	 * Display the action notices such as Renewed, Deleted etc.
	 *
	 * @param array $query The attached query string variable. Mostly, the action 'deleted', 'renewed' etc.
	 *
	 * @since 3.19.
	 */
	private function display_notices( $query ) {

		if ( ! get_transient( '_transient_aawp_products_listtable_actions_notice' ) ) {
			return;
		}

		if ( ! empty( $query ) ) {

			?>
				<div class="notice notice-success is-dismissible">
					<p>
						<?php
						echo sprintf(
							wp_kses(
								/* translators: %1$s Product Count, %2$s - Action. */
								_n( '<code>%1$s</code> Product %2$s. ', '<code>%1$s</code> Products %2$s. ', reset( $query ), 'aawp' ),
								[ 'code' => [] ]
							),
							reset( $query ),
							esc_html( array_key_first( $query ) )
						);
						?>
					</p>
				</div>
			<?php
		} else {
			?>
				<div class="notice notice-error is-dismissible">
					<p>
						<?php
						echo sprintf(
							wp_kses(
								/* translators: %1$s Link to logs page. */
								__( 'Something went wrong! Please <a href="%s">check the  logs</a>.', 'aawp' ),
								[ 'a' => [ 'href' => [] ] ]
							),
							esc_url( admin_url( 'admin.php?page=aawp-tools&tab=logs' ) )
						);
						?>
					</p>
				</div>
			<?php
		}//end if
	}
}
