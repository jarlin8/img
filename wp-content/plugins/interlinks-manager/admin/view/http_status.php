<?php

if ( ! current_user_can( get_option( $this->shared->get( 'slug' ) . '_http_status_menu_required_capability' ) ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'daim' ) );
}

?>

<!-- process data -->
<?php

//Sanitization -------------------------------------------------------------------------------------------------

//Filter and sort data
$data       = [];
$data['s']  = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : null;
$data['sc'] = isset( $_GET['sc'] ) ? sanitize_text_field( $_GET['sc'] ) : null;
$data['sb'] = isset( $_GET['sb'] ) ? sanitize_text_field( $_GET['sb'] ) : null;
$data['or'] = isset( $_GET['or'] ) ? intval( $_GET['or'], 10 ) : null;

?>

<!-- output -->

<div class="wrap">

    <h2><?php esc_html_e( 'Interlinks Manager - HTTP Status', 'daim' ); ?></h2>

    <div id="daext-menu-wrapper" class="daext-clearfix">

        <!-- list of http status -->
        <div class="interlinks-container">

			<?php

			//check in the "_http_status" db table if all the links have been checked (if there are zero links to check)
			global $wpdb;
			$table_name  = $wpdb->prefix . $this->shared->get( 'slug' ) . "_http_status";
			$count       = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE checked = 0" );
			$count_total = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );

			if ( $count_total === 0 ) {

				echo '<p>' . esc_html__( 'There are no internal links to check.', 'daim' ) . '</p>';

			} else if ( $count > 0 ) {

				echo '<p>' . esc_html__('The plugin is using', 'daim') . ' <a href="https://developer.wordpress.org/plugins/cron/">WP-Cron</a> ' . esc_html__('to test the HTTP response status code of the URLs used in the internal links. The process is in progress, and there are still', 'daim') . ' ' . esc_html($count) . ' ' . esc_html__('out of', 'daim') . ' ' . esc_html($count_total) . ' ' . esc_html('URLs to check.', 'daim') . '</p>';
				echo '<p>' . esc_html__('Note that you can configure this task in the', 'daim') . ' ' . '<a href="' . esc_url(get_admin_url() . 'admin.php?page=daim-options&tab=advanced_options') . '">' . esc_html__('Advanced', 'daim') . '</a> ' . esc_html__('plugin options.', 'daim') . '</p>';

			} else {

				//status code
				if ( ! is_null( $data['sc'] ) and
				     ( trim( $data['sc'] ) !== 'all' ) ) {

                    switch(trim( $data['sc'] )){

	                    case 'unknown':
		                    $filter = "WHERE code = ''";
		                    break;

	                    case '1xx':
		                    $filter = "WHERE (code = '100'";
		                    $filter .= " OR code = '101'";
		                    $filter .= " OR code = '102'";
		                    $filter .= " OR code = '103')";
		                    break;

                        case '2xx':
                            $filter = "WHERE (code = '200'";
                            $filter .= " OR code = '201'";
                            $filter .= " OR code = '202'";
                            $filter .= " OR code = '203'";
                            $filter .= " OR code = '204'";
                            $filter .= " OR code = '205'";
                            $filter .= " OR code = '206'";
                            $filter .= " OR code = '207'";
                            $filter .= " OR code = '208'";
                            $filter .= " OR code = '226')";
                            break;

	                    case '3xx':
		                    $filter = "WHERE (code = '300'";
		                    $filter .= " OR code = '301'";
		                    $filter .= " OR code = '302'";
                            $filter .= " OR code = '303'";
                            $filter .= " OR code = '304'";
                            $filter .= " OR code = '305'";
                            $filter .= " OR code = '305'";
                            $filter .= " OR code = '306'";
                            $filter .= " OR code = '307'";
                            $filter .= " OR code = '308')";
		                    break;

	                    case '4xx':
		                    $filter = "WHERE (code = '400'";
		                    $filter .= " OR code = '401'";
		                    $filter .= " OR code = '402'";
		                    $filter .= " OR code = '403'";
		                    $filter .= " OR code = '404'";
                            $filter .= " OR code = '405'";
                            $filter .= " OR code = '406'";
                            $filter .= " OR code = '407'";
                            $filter .= " OR code = '408'";
                            $filter .= " OR code = '409'";
                            $filter .= " OR code = '410'";
                            $filter .= " OR code = '411'";
                            $filter .= " OR code = '412'";
                            $filter .= " OR code = '413'";
                            $filter .= " OR code = '414'";
                            $filter .= " OR code = '415'";
                            $filter .= " OR code = '416'";
                            $filter .= " OR code = '417'";
                            $filter .= " OR code = '418'";
                            $filter .= " OR code = '421'";
                            $filter .= " OR code = '422'";
                            $filter .= " OR code = '423'";
                            $filter .= " OR code = '424'";
                            $filter .= " OR code = '426'";
                            $filter .= " OR code = '428'";
                            $filter .= " OR code = '429'";
                            $filter .= " OR code = '431'";
                            $filter .= " OR code = '451')";

		                    break;

	                    case '5xx':
		                    $filter = "WHERE (code = '500'";
		                    $filter .= " OR code = '501'";
		                    $filter .= " OR code = '502'";
		                    $filter .= " OR code = '503'";
		                    $filter .= " OR code = '504'";
		                    $filter .= " OR code = '505'";
		                    $filter .= " OR code = '506'";
		                    $filter .= " OR code = '507'";
		                    $filter .= " OR code = '508'";
		                    $filter .= " OR code = '510'";
		                    $filter .= " OR code = '511')";

		                    break;

                    }

				} else {
					$filter = '';
				}

				//search
				if ( ! is_null( $data['s'] ) and strlen( trim( $data['s'] ) ) > 0 ) {
					$search_string = $data['s'];
					global $wpdb;
					if ( strlen( trim( $filter ) ) > 0 ) {
						$filter .= $wpdb->prepare( ' AND (post_title LIKE %s)', '%' . $search_string . '%' );
					} else {
						$filter .= $wpdb->prepare( 'WHERE (post_title LIKE %s)', '%' . $search_string . '%' );
					}
				} else {
					$filter .= '';
				}

				//sort -------------------------------------------------

				//sort by
				if ( ! is_null( $data['sb'] ) ) {

					/*
					* verify if the value is valid, if the value is invalid
					*  default to the "post_date"
					*/
					switch ( $data['sb'] ) {

						case 'ti':
							$sort_by = 'post_title';
							break;

						case 'an':
							$sort_by = 'anchor';
							break;

						case 'ur':
							$sort_by = 'url';
							break;

						case 'hr':
							$sort_by = 'code';
							break;

						case 'lc':
							$sort_by = 'last_check_date';
							break;

						default:
							$sort_by = 'last_check_date';
							break;
					}

				} else {
					$sort_by = 'last_check_date';
				}

				//order
				if ( ! is_null( $data['or'] ) and $data['or'] === 0 ) {
					$order = "ASC";
				} else {
					$order = "DESC";
				}

				//retrieve the total number of http status
				global $wpdb;
				$table_name  = $wpdb->prefix . $this->shared->get( 'slug' ) . "_http_status";
				$total_items = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name " . $filter );

				//Initialize the pagination class
				require_once( $this->shared->get( 'dir' ) . '/admin/inc/class-daim-pagination.php' );
				$pag = new daim_pagination();
				$pag->set_total_items( $total_items );//Set the total number of items
				$pag->set_record_per_page( intval(get_option($this->shared->get('slug') . '_pagination_http_status_menu'), 10) ); //Set records per page
				$pag->set_target_page( "admin.php?page=" . $this->shared->get( 'slug' ) . "-http-status" );//Set target page
				$pag->set_current_page();//set the current page number from $_GET

				?>

                <!-- Query the database -->
				<?php
				$query_limit = $pag->query_limit();
				$results     = $wpdb->get_results( "SELECT * FROM $table_name " . $filter . " ORDER BY $sort_by $order $query_limit ", ARRAY_A ); ?>

				<?php if ( count( $results ) > 0 ) : ?>

                    <div class="daext-items-container">

                        <table class="daext-items">
                            <thead>
                            <tr>
                                <th>
                                    <div><?php esc_html_e( 'Post', 'daim' ); ?></div>
                                    <div class="help-icon" title="<?php esc_attr_e( 'The post that includes the link.', 'daim' ); ?>"></div>
                                </th>
                                <th>
                                    <div><?php esc_html_e( 'Anchor Text', 'daim' ); ?></div>
                                    <div class="help-icon" title="<?php esc_attr_e( 'The anchor text of the link.', 'daim' ); ?>"></div>
                                </th>
                                <th>
                                    <div><?php esc_html_e( 'URL', 'daim' ); ?></div>
                                    <div class="help-icon" title="<?php esc_attr_e( 'The URL of the link.', 'daim' ); ?>"></div>
                                </th>
                                <th>
                                    <div><?php esc_html_e( 'Status Code', 'daim' ); ?></div>
                                    <div class="help-icon" title="<?php esc_attr_e( 'The HTTP response status code.', 'daim' ); ?>"></div>
                                </th>
                                <th>
                                    <div><?php esc_html_e( 'Last Check', 'daim' ); ?></div>
                                    <div class="help-icon" title="<?php esc_attr_e( 'The date on which the HTTP response status code has been tested.', 'daim' ); ?>"></div>
                                </th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

							<?php foreach ( $results as $result ) : ?>

                                <tr>
                                    <td>
										<?php

										if ( get_post_status( $result['post_id'] ) === false ) {
											echo esc_html( $result['post_title'] );
										} else {
											echo '<a href="' . get_permalink( $result['post_id'] ) . '">' . esc_html( apply_filters( 'the_title',
													$result['post_title'], $result['post_id'] ) ) . '</a>';
										}

										?>
                                    </td>
                                    <td>
                                        <?php echo esc_html($result['anchor']); ?>
                                    </td>
                                    <td>
	                                    <?php echo '<a target="_blank" href="' . esc_url($result['url']) . '">' . esc_html($result['url']) . '</a>'; ?>
                                    </td>
                                    <td>
                                        <div class="http-status-color-group <?php echo 'http-status-color-group-' . esc_attr($this->shared->get_http_response_status_code_group($result['code'])); ?>">
		                                    <?php echo esc_html($result['code'] . ' ' . $this->shared->get_status_code_description($result['code'])); ?>
                                        </div>
                                    </td>
                                    <td><?php echo mysql2date( get_option('date_format') , $result['last_check_date'] ); ?></td>
                                    <td class="icons-container">
										<?php if ( get_post_status( $result['post_id'] ) !== false ) : ?>
                                            <a class="menu-icon edit"
                                               href="post.php?post=<?php echo esc_attr($result['post_id']); ?>&action=edit"></a>
										<?php endif; ?>
                                    </td>
                                </tr>

							<?php endforeach; ?>

                            </tbody>
                        </table>

                    </div>

				<?php else : ?>

					<?php

					if ( strlen( trim( $filter ) ) > 0 ) {
						echo '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__( 'There are no results that match your filter.',
								'daim' ) . '</p></div>';
					} else {
						echo '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__( 'There are no data at moment, click on the "Generate Data" button to generate data about the HTTP response status codes of the URLs used in the internal links.',
								'daim' ) . '</p></div>';
					}

					?>

				<?php endif; ?>

                <!-- Display the pagination -->
				<?php if ( $pag->total_items > 0 ) : ?>
                    <div class="daext-tablenav daext-clearfix">
                        <div class="daext-tablenav-pages">
                            <span class="daext-displaying-num"><?php echo esc_html($pag->total_items); ?>&nbsp<?php esc_html_e( 'items', 'daim' ); ?></span>
							<?php $pag->show(); ?>
                        </div>
                    </div>
				<?php endif; ?>

				<?php

			}

			?>

        </div><!-- #subscribers-container -->

        <div class="sidebar-container">

            <div class="daext-widget">

                <h3 class="daext-widget-title"><?php esc_html_e('HTTP Status Data', 'daim'); ?></h3>

                <div class="daext-widget-content">

                    <p><?php esc_html_e( 'This procedure allows you to generate data about the HTTP response status codes of the URLs used in the internal links.', 'daim' ); ?></p>

                </div><!-- .daext-widget-content -->

                <div class="daext-widget-submit">
                    <input id="ajax-request-status" type="hidden" value="inactive">
                    <input class="button" id="update-http-status-archive" type="button"
                           value="<?php esc_attr_e( 'Generate Data', 'daim' ); ?>">
                    <img id="ajax-loader"
                         src="<?php echo esc_url($this->shared->get( 'url' ) . 'admin/assets/img/ajax-loader.gif'); ?>">
                </div>

            </div>

            <div class="daext-widget">

                <h3 class="daext-widget-title"><?php esc_html_e( 'Export CSV', 'daim' ); ?></h3>

                <div class="daext-widget-content">

                    <p><?php esc_html_e( 'The downloaded CSV file can be imported in your favorite spreadsheet software.', 'daim' ); ?></p>

                </div><!-- .daext-widget-content -->

                <!-- the data sent through this form are handled by
				the export_csv_controller() method called with the
				WordPress init action -->
                <form method="POST" action="admin.php?page=daim-http-status">

                    <div class="daext-widget-submit">
                        <input name="export_csv" class="button" type="submit"
                               value="<?php esc_attr_e( 'Download', 'daim' ); ?>" <?php if ( !$this->shared->complete_http_status_data_exists() ) {
							echo 'disabled="disabled"';
						} ?>>
                    </div>

                </form>

            </div>

            <div class="daext-widget" id="filter-and-sort">

                <h3 class="daext-widget-title"><?php esc_html_e( 'Filter & Sort', 'daim' ); ?></h3>

                <form method="GET" action="admin.php">

                    <input type="hidden" name="page" value="<?php echo esc_attr($this->shared->get( 'slug' )); ?>-http-status">

                    <div class="daext-widget-content">

                        <h3><?php esc_html_e( 'Search', 'daim' ); ?></h3>
                        <p>
							<?php
							if ( strlen( trim( $data['s'] ) ) > 0 ) {
								$search_string = $data['s'];
							} else {
								$search_string = '';
							}
							?>
                            <input id="filter-and-sort-search" type="text" name="s"
                                   value="<?php echo esc_attr( stripslashes( $search_string ) ); ?>" autocomplete="off"
                                   maxlength="255">
                        </p>

                        <h3><?php esc_html_e( 'Status Code', 'daim' ); ?></h3>
                        <p>
                            <select name="sc" id="sc">
                                <option value="all" <?php selected( $data['sc'], 'all' ); ?>><?php esc_html_e( 'All', 'daim' ); ?></option>
                                <option value="unknown" <?php selected( $data['sc'], 'unknown' ); ?>><?php esc_html_e( 'Unknown', 'daim' ); ?></option>
                                <option value="1xx" <?php selected( $data['sc'], '1xx' ); ?>><?php esc_html_e( '1xx - Informational responses', 'daim' ); ?></option>
                                <option value="2xx" <?php selected( $data['sc'], '2xx' ); ?>><?php esc_html_e( '2xx - Successful responses', 'daim' ); ?></option>
                                <option value="3xx" <?php selected( $data['sc'], '3xx' ); ?>><?php esc_html_e( '3xx - Redirection messages', 'daim' ); ?></option>
                                <option value="4xx" <?php selected( $data['sc'], '4xx' ); ?>><?php esc_html_e( '4xx - Client error responses', 'daim' ); ?></option>
                                <option value="5xx" <?php selected( $data['sc'], '5xx' ); ?>><?php esc_html_e( '5xx - Server error responses', 'daim' ); ?></option>
                            </select>
                        </p>


                        <h3><?php esc_html_e( 'Sort By', 'daim' ); ?></h3>
                        <p>
                            <select name="sb" id="sb">
                                <option value="ti" <?php selected( $data['sb'], 'ti' ); ?>><?php esc_html_e( 'Post Title', 'daim' ); ?></option>
                                <option value="an" <?php selected( $data['sb'], 'an' ); ?>><?php esc_html_e( 'Anchor', 'daim' ); ?></option>
                                <option value="ur" <?php selected( $data['sb'], 'ur' ); ?>><?php esc_html_e( 'URL', 'daim' ); ?></option>
                                <option value="hr" <?php selected( $data['sb'], 'hr' ); ?>><?php esc_html_e( 'Status Code', 'daim' ); ?></option>
                                <option value="lc" <?php if(is_null($data['sb']) or $data['sb'] === 'lc'){echo 'selected="selected"';} ?>><?php esc_html_e( 'Last Check', 'daim' ); ?></option>
                            </select>
                        </p>


                        <h3><?php esc_html_e( 'Order', 'daim' ); ?></h3>
                        <p>
                            <select name="or" id="or">
                                <option value="1" <?php selected( $data['or'], 1 ); ?>><?php esc_html_e( 'Descending', 'daim' ); ?></option>
                                <option value="0" <?php selected( $data['or'], 0 ); ?>><?php esc_html_e( 'Ascending', 'daim' ); ?></option>
                            </select>
                        </p>

                    </div><!-- .daext-widget-content -->

                    <div class="daext-widget-submit">
                        <input class="button" type="submit" value="<?php esc_attr_e( 'Apply Query', 'daim' ); ?>">
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>