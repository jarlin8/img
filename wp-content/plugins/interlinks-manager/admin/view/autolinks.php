<?php

        if ( !current_user_can(get_option( $this->shared->get('slug') . "_ail_menu_required_capability")) )  {
                wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'daim' ) );
        }

        ?>

        <!-- process data -->

        <?php

        //Sanitization ---------------------------------------------------------------------------------------------

        //Actions
        $data['edit_id'] = isset($_GET['edit_id']) ? intval($_GET['edit_id'], 10) : null;
        $data['delete_id'] = isset($_POST['delete_id']) ? intval($_POST['delete_id'], 10) : null;
        $data['clone_id'] = isset($_POST['clone_id']) ? intval($_POST['clone_id'], 10) : null;
        $data['update_id']    = isset( $_POST['update_id'] ) ? intval( $_POST['update_id'], 10 ) : null;
        $data['form_submitted']    = isset( $_POST['form_submitted'] ) ? intval( $_POST['form_submitted'], 10 ) : null;

        //Filter and search data
        $data['s'] = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : null;
        $data['cf'] = isset($_GET['cf']) ? sanitize_text_field($_GET['cf']) : null;

        if( !is_null( $data['update_id'] ) or !is_null($data['form_submitted']) ){

             //Main Form data
             $data['name']           = sanitize_text_field( $_POST['name'] );
             $data['category_id']    = intval($_POST['category_id'], 10);
             $data['keyword']      = sanitize_text_field( $_POST['keyword'] );
             $data['url']          = substr(esc_url_raw( 'https://example.com' . $_POST['url']), 19);
             $data['title']        = sanitize_text_field( $_POST['title'] );
             $data['open_new_tab'] = intval( $_POST['open_new_tab'], 10 );
             $data['use_nofollow'] = intval( $_POST['use_nofollow'], 10 );

             if ( isset( $_POST['activate_post_types'] ) and is_array( $_POST['activate_post_types'] ) ) {

	             //sanitize all the post types in the array
	             $data['activate_post_types'] = array_map( 'sanitize_key', $_POST['activate_post_types'] );

             }else{
                 $data['activate_post_types'] = '';
             }

             if ( isset( $_POST['categories'] ) and is_array( $_POST['categories'] ) ) {

	             //sanitize (convert to integer base 10) all the category id in the array
	             $data['categories'] = array_map( function ( $value ) {
		             return intval( $value, 10 );
	             }, $_POST['categories'] );

             }else{
                 $data['categories'] = '';
             }

             if ( isset( $_POST['tags'] ) and is_array( $_POST['tags'] ) ) {

	             //sanitize (convert to integer base 10) all the tag id in the array
	             $data['tags'] = array_map( function ( $value ) {
		             return intval( $value, 10 );
	             }, $_POST['tags'] );

             }else{
                 $data['tags'] = '';
             }

             $data['term_group_id']         = intval( $_POST['term_group_id'], 10 );
             $data['case_insensitive_search'] = intval( $_POST['case_insensitive_search'], 10 );
             $data['string_before']         = intval( $_POST['string_before'], 10 );
             $data['string_after']        = intval( $_POST['string_after'], 10 );
             $data['keyword_before']        = sanitize_text_field( $_POST['keyword_before'] );
             $data['keyword_after']         = sanitize_text_field( $_POST['keyword_after'] );
             $data['max_number_autolinks']                = intval( $_POST['max_number_autolinks'], 10 );
             $data['priority']              = intval( $_POST['priority'], 10 );

        }

        //Validation -----------------------------------------------------------------------------------------------

        if( !is_null( $data['update_id'] ) or !is_null($data['form_submitted']) ){

	        $invalid_data_message = '';

	        //validation on "name"
	        if (mb_strlen(trim($data['name'])) === 0 or mb_strlen(trim($data['name'])) > 100) {
		        $invalid_data_message .= '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__('Please enter a valid value in the "Name" field.',
				        'daim') . '</p></div>';
		        $invalid_data         = true;
	        }

	        //validation on "Keyword"
	        if( strlen( trim($data['keyword']) ) == 0 or strlen($data['keyword']) > 255 ){
		        $invalid_data_message .= '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__('Please enter a valid value in the "Keyword" field.', 'daim') . '</p></div>';
		        $invalid_data = true;
	        }

	        /*
			 * Do not allow only numbers as a keyword. Only numbers in a keyword would cause the index of the protected block to
			 * be replaced. For example the keyword "1" would cause the "1" present in the index of the following protected
			 * blocks to be replaced with an autolink:
			 *
			 * - [pb]1[/pb]
			 * - [pb]31[/pb]
			 * - [pb]812[/pb]
			 */
	        if(preg_match('/^\d+$/', $data['keyword']) === 1){
		        $invalid_data_message .= '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__('The specified keyword is not allowed.',
				        'daim') . '</p></div>';
		        $invalid_data         = true;
		        $specified_keyword_not_allowed = true;
	        }

	        /*
			 * Do not allow to create specific keywords that would be able to replace the start delimiter of the
			 * protected block [pb], part of the start delimiter, the end delimited [/pb] or part of the end delimiter.
			 */
	        if(preg_match('/^\[$|^\[p$|^\[pb$|^\[pb]$|^\[\/$|^\[\/p$|^\[\/pb$|^\[\/pb\]$|^\]$|^b\]$|^pb\]$|^\/pb\]$|^p$|^pb$|^pb\]$|^\/$|^\/p$|^\/pb$|^\/pb]$|^b$|^b\$/i', $data['keyword']) === 1){
		        $invalid_data_message .= '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__('The specified keyword is not allowed.',
				        'daim') . '</p></div>';
		        $invalid_data         = true;
		        $specified_keyword_not_allowed = true;
	        }

	        /*
			 * Do not allow to create specific keywords that would be able to replace the start delimiter of the
			 * autolink [ail], part of the start delimiter, the end delimited [/ail] or part of the end delimiter.
			 */
	        if(!isset($specified_keyword_not_allowed) and preg_match('/^\[$|^\[a$|^\[ai$|^\[ail$|^\[ail\]$|^a$|^ai$|^ail$|^ail\]$|^i$|^il$|^il\]$|^l$|^l\]$|^\]$|^\[$|^\[\/$|^\[\/a$|^\[\/ai$|^\[\/ail$|^\[\/ail\]$|^\/$|^\/]$|^\/a$|^\/ai$|^\/ail$|^\/ail\]$/i', $data['keyword']) === 1){
		        $invalid_data_message .= '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__('The specified keyword is not allowed.',
				        'daim') . '</p></div>';
		        $invalid_data         = true;
	        }

	        //validation on "Title"
	        if( strlen($data['title']) > 1024 ){
		        $invalid_data_message .= '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__('Please enter a valid value in the "Title" field.', 'daim') . '</p></div>';
		        $invalid_data = true;
	        }

	        //validation on "Post Types"

	        if( !is_array($data['activate_post_types']) or count($data['activate_post_types']) === 0 ){
		        $invalid_data_message .= '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__('Please enter at least one post type in the "Post Types" field.', 'daim') . '</p></div>';
		        $invalid_data = true;
	        }

	        //validation on "keyword_before"
	        if (mb_strlen(trim($data['keyword_before'])) > 255) {
		        $invalid_data_message .= '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__('Please enter a valid value in the "Keyword Before" field.',
				        'daim') . '</p></div>';
		        $invalid_data         = true;
	        }

	        //validation on "keyword_after"
	        if (mb_strlen(trim($data['keyword_after'])) > 255) {
		        $invalid_data_message .= '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__('Please enter a valid value in the "Keyword After" field.',
				        'daim') . '</p></div>';
		        $invalid_data         = true;
	        }

	        //validation on "Max Number AIL"
	        if( !preg_match($this->shared->regex_number_ten_digits, $data['max_number_autolinks']) or intval($data['max_number_autolinks'], 10) < 1 or intval($data['max_number_autolinks'], 10) > 1000000 ){
		        $invalid_data_message .= '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__('Please enter a number from 1 to 1000000 in the "Limit" field.', 'daim') . '</p></div>';
		        $invalid_data = true;
	        }

	        //validation on "Priority"
	        if( !preg_match($this->shared->regex_number_ten_digits, $data['priority']) or intval($data['priority'], 10) > 1000000 ){
		        $invalid_data_message .= '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__('Please enter a number from 0 to 1000000 in the "Priority" field.', 'daim') . '</p></div>';
		        $invalid_data = true;
	        }

        }

        //update ---------------------------------------------------------------
        if( !is_null($data['update_id']) and !isset($invalid_data) ){
            
            //update the database
            global $wpdb;
            $table_name = $wpdb->prefix . $this->shared->get('slug') . "_autolinks";
            $safe_sql = $wpdb->prepare("UPDATE $table_name SET 
                name = %s,
                category_id = %d,
                keyword = %s,
                url = %s,
                title = %s,
                string_before = %d,
                string_after = %d,
                keyword_before = %s,
                keyword_after = %s,
                activate_post_types = %s,
                categories = %s,
                tags = %s,
                term_group_id = %d,
                max_number_autolinks = %d,
                case_insensitive_search = %d,
                open_new_tab = %d,
                use_nofollow = %d,
                priority = %d
                WHERE id = %d",
	            $data['name'],
	            $data['category_id'],
                $data['keyword'],
                $data['url'],
                $data['title'],
                $data['string_before'],
                $data['string_after'],
                $data['keyword_before'],
                $data['keyword_after'],
                maybe_serialize($data['activate_post_types']),
                maybe_serialize($data['categories']),
                maybe_serialize($data['tags']),
                $data['term_group_id'],
                $data['max_number_autolinks'],
                $data['case_insensitive_search'],
                $data['open_new_tab'],
                $data['use_nofollow'],
                $data['priority'],
                $data['update_id']);

            $query_result = $wpdb->query( $safe_sql );

            if($query_result !== false){
                $process_data_message = '<div class="updated settings-error notice is-dismissible below-h2"><p>' . esc_html__('The AIL has been successfully updated.', 'daim') . '</p></div>';
            }
            
        }else{
            
            //add ------------------------------------------------------------------
            if( !is_null($data['form_submitted']) and !isset($invalid_data) ){

                //insert into the database
                global $wpdb;
                $table_name = $wpdb->prefix . $this->shared->get('slug') . "_autolinks";
                $safe_sql = $wpdb->prepare("INSERT INTO $table_name SET 
                    name = %s,
                    category_id = %d,
                    keyword = %s,
                    url = %s,
                    title = %s,
                    string_before = %d,
                    string_after = %d,
                    keyword_before = %s,
                    keyword_after = %s,
                    activate_post_types = %s,
                    categories = %s,
                    tags = %s,
                    term_group_id = %d,
                    max_number_autolinks = %d,
                    case_insensitive_search = %d,
                    open_new_tab = %d,
                    use_nofollow = %d,
                    priority = %d",
	                $data['name'],
	                $data['category_id'],
                    $data['keyword'],
                    $data['url'],
                    $data['title'],
                    $data['string_before'],
                    $data['string_after'],
                    $data['keyword_before'],
                    $data['keyword_after'],
                    maybe_serialize($data['activate_post_types']),
                    maybe_serialize($data['categories']),
                    maybe_serialize($data['tags']),
                    $data['term_group_id'],
                    $data['max_number_autolinks'],
                    $data['case_insensitive_search'],
                    $data['open_new_tab'],
                    $data['use_nofollow'],
                    $data['priority']
                    );

                $query_result = $wpdb->query( $safe_sql );

                if($query_result !== false){
                    $process_data_message = '<div class="updated settings-error notice is-dismissible below-h2"><p>' . esc_html__('The AIL has been successfully added.', 'daim') . '</p></div>';
                }

            }
            
        }
        
        //delete an autolink
        if( !is_null($data['delete_id']) ){

            global $wpdb;

            //delete this game
            $table_name = $wpdb->prefix . $this->shared->get('slug') . "_autolinks";
            $safe_sql = $wpdb->prepare("DELETE FROM $table_name WHERE id = %d ", $data['delete_id']);

            $query_result = $wpdb->query( $safe_sql ); 

            if($query_result !== false){
                $process_data_message = '<div class="updated settings-error notice is-dismissible below-h2"><p>' . esc_html__('The AIL has been successfully deleted.', 'daim') . '</p></div>';
            }
            
        }

        //clone the term group
        if (!is_null($data['clone_id'])) {

            global $wpdb;

            //clone the autolink
            $table_name = $wpdb->prefix . $this->shared->get('slug') . "_autolinks";
            $wpdb->query("CREATE TEMPORARY TABLE daim_temporary_table SELECT * FROM $table_name WHERE id = " . $data['clone_id']);
            $wpdb->query("UPDATE daim_temporary_table SET id = NULL");
            $wpdb->query("INSERT INTO $table_name SELECT * FROM daim_temporary_table");
            $wpdb->query("DROP TEMPORARY TABLE IF EXISTS daim_temporary_table");

        }

        //get the autolink data
        if(!is_null($data['edit_id'])){
            global $wpdb;
            $table_name = $wpdb->prefix . $this->shared->get('slug') . "_autolinks";
            $safe_sql = $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d ", $data['edit_id']);
            $autolink_obj = $wpdb->get_row($safe_sql); 
        }

        //Get the value of the custom filter
        if (!is_null($data['cf'])) {
            if($data['cf'] !== 'all'){
	            $category_id_in_cf = intval($data['cf'], 10);
            }else{
	            $category_id_in_cf = false;
            }
        } else {
	        $category_id_in_cf = false;
        }

        ?>

        <!-- output -->

        <div class="wrap">

            <div id="daext-header-wrapper" class="daext-clearfix">

                <h2><?php esc_html_e('Interlinks Manager - AIL', 'daim'); ?></h2>

                <form action="admin.php" method="get" id="daext-search-form">

                    <input type="hidden" name="page" value="daim-autolinks">

                    <p><?php esc_html_e('Perform your Search', 'daim'); ?></p>

		            <?php
		            if (!is_null($data['s'])) {
		                if(mb_strlen(trim($data['s'])) > 0){
			                $search_string = $data['s'];
                        }else{
			                $search_string = '';
                        }
		            } else {
			            $search_string = '';
		            }

		            //Custom Filter
		            if ($category_id_in_cf !== false) {
			            echo '<input type="hidden" name="cf" value="' . $category_id_in_cf . '">';
		            }

		            ?>

                    <input type="text" name="s"
                           value="<?php echo esc_attr(stripslashes($search_string)); ?>" autocomplete="off" maxlength="255">
                    <input type="submit" value="">

                </form>

                <!-- Filter Form -->

                <form method="GET" action="admin.php" id="daext-filter-form">

                    <input type="hidden" name="page" value="<?php echo $this->shared->get('slug'); ?>-autolinks">

                    <p><?php esc_html_e('Filter by Category', 'daim'); ?></p>

                    <select id="cf" name="cf" class="daext-display-none">

                        <option value="all" <?php if (!is_null($data['cf'])) {
				            selected($data['cf'], 'all');
			            } ?>><?php esc_html_e('All', 'daim'); ?></option>

			            <?php

			            global $wpdb;
			            $table_name   = $wpdb->prefix . $this->shared->get('slug') . "_category";
			            $safe_sql     = "SELECT category_id, name FROM $table_name ORDER BY category_id DESC";
			            $categories_a = $wpdb->get_results($safe_sql, ARRAY_A);

			            foreach ($categories_a as $key => $category) {

				            if (!is_null($data['cf'])) {
					            echo '<option value="' . $category['category_id'] . '" ' . selected($data['cf'],
							            $category['category_id'],
							            false) . '>' . esc_html(stripslashes($category['name'])) . '</option>';
				            } else {
					            echo '<option value="' . $category['category_id'] . '">' . esc_html(stripslashes($category['name'])) . '</option>';

				            }

			            }

			            ?>

                    </select>

                </form>

            </div>

            <div id="daext-menu-wrapper">

            <?php if(isset($invalid_data_message)){echo $invalid_data_message;} ?>
            <?php if(isset($process_data_message)){echo $process_data_message;} ?>
            
            <!-- table -->

            <?php

            //custom filter
            if ($category_id_in_cf === false) {
	            $filter = '';
            } else {
	            global $wpdb;
	            $filter = $wpdb->prepare("WHERE category_id = %d", $category_id_in_cf);
            }

            //create the query part used to filter the results when a search is performed
            if (!is_null($data['s'])) {

                if(mb_strlen(trim($data['s'])) > 0){

	                global $wpdb;

	                //create the query part used to filter the results when a search is performed
	                if ((mb_strlen(trim($filter)) > 0)) {
		                $filter .= $wpdb->prepare(' AND (name LIKE %s OR keyword LIKE %s OR url LIKE %s)',
			                '%' . $data['s'] . '%',
			                '%' . $data['s'] . '%',
			                '%' . $data['s'] . '%');
	                } else {
		                $filter = $wpdb->prepare('WHERE (name LIKE %s OR keyword LIKE %s OR url LIKE %s)',
			                '%' . $data['s'] . '%',
			                '%' . $data['s'] . '%',
			                '%' . $data['s'] . '%');
	                }

                }

            }

            //retrieve the total number of autolinks
            global $wpdb;
            $table_name=$wpdb->prefix . $this->shared->get('slug') . "_autolinks";
            $total_items = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name $filter");

            //Initialize the pagination class
            require_once( $this->shared->get('dir') . '/admin/inc/class-daim-pagination.php' );
            $pag = new daim_pagination();
            $pag->set_total_items( $total_items );//Set the total number of items
            $pag->set_record_per_page( intval(get_option($this->shared->get('slug') . '_pagination_ail_menu'), 10) ); //Set records per page
            $pag->set_target_page( "admin.php?page=" . $this->shared->get('slug') . "-autolinks" );//Set target page
            $pag->set_current_page();//set the current page number from $_GET

            ?>

            <!-- Query the database -->
            <?php
            $query_limit = $pag->query_limit();
            $results = $wpdb->get_results("SELECT * FROM $table_name $filter ORDER BY id DESC $query_limit", ARRAY_A); ?>

            <?php if( count($results) > 0 ) : ?>

                <div class="daext-items-container">

                    <!-- list of tables -->
                    <table class="daext-items">
                        <thead>
                            <tr>
                                <th>
                                    <div><?php esc_html_e('AIL ID', 'daim'); ?></div>
                                    <div class="help-icon" title="<?php esc_attr_e('The ID of the AIL.', 'daim'); ?>"></div>
                                </th>
                                <th>
                                    <div><?php esc_html_e('Name', 'daim'); ?></div>
                                    <div class="help-icon" title="<?php esc_attr_e('The name of the AIL.', 'daim'); ?>"></div>
                                </th>
                                <th>
                                    <div><?php esc_html_e('Category', 'daim'); ?></div>
                                    <div class="help-icon"
                                         title="<?php esc_attr_e('The category of the AIL.', 'daim'); ?>"></div>
                                </th>
                                <th>
                                    <div><?php esc_html_e('Keyword', 'daim'); ?></div>
                                    <div class="help-icon" title="<?php esc_attr_e('The keyword that will be converted to a link.', 'daim'); ?>"></div>
                                </th>
                                <th>
                                    <div><?php esc_html_e('Target', 'daim'); ?></div>
                                    <div class="help-icon" title="<?php esc_attr_e('The target of the link automatically generated on the keyword.', 'daim'); ?>"></div>
                                </th>
                                <th>
                                    <div><?php esc_html_e('Title', 'daim'); ?></div>
                                    <div class="help-icon" title="<?php esc_attr_e('The title of the link automatically generated on the keyword.', 'daim'); ?>"></div>
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php foreach($results as $result) : ?>
                            <tr>
                                <td><?php echo intval($result['id'], 10); ?></td>
                                <td><?php echo esc_html(stripslashes($result['name'])); ?></td>
                                <td><?php echo esc_html(stripslashes($this->shared->get_category_name($result['category_id']))); ?></td>
                                <td><?php echo esc_html(stripslashes($result['keyword'])); ?></td>
                                <td><a href="<?php echo esc_url( get_home_url() . $result['url'] ); ?>"><?php echo esc_url( get_home_url() . $result['url'] ); ?></a></td>
                                <td><?php echo strlen(trim($result['title'])) > 0 ? esc_html(stripslashes($result['title'])) : esc_html__('None', 'daim'); ?></td>
                                <td class="icons-container">
                                    <form method="POST"
                                          action="admin.php?page=<?php echo $this->shared->get('slug'); ?>-autolinks">
                                        <input type="hidden" name="clone_id" value="<?php echo $result['id']; ?>">
                                        <input class="menu-icon clone help-icon" type="submit" value="">
                                    </form>
                                    <a class="menu-icon edit" href="admin.php?page=<?php echo $this->shared->get('slug'); ?>-autolinks&edit_id=<?php echo $result['id']; ?>"></a>
                                    <form id="form-delete-<?php echo $result['id']; ?>" method="POST" action="admin.php?page=<?php echo $this->shared->get('slug'); ?>-autolinks">
                                        <input type="hidden" value="<?php echo $result['id']; ?>" name="delete_id" >
                                        <input class="menu-icon delete" type="submit" value="">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

                <!-- Display the pagination -->
                <?php if($pag->total_items > 0) : ?>
                    <div class="daext-tablenav daext-clearfix">
                        <div class="daext-tablenav-pages">
                            <span class="daext-displaying-num"><?php echo $pag->total_items; ?> <?php esc_html_e('items', 'daim'); ?></span>
                            <?php $pag->show(); ?>
                        </div>
                    </div>
                <?php endif; ?>

            <?php else : ?>

                <?php

	            if (mb_strlen(trim($filter)) > 0) {
		            echo '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__('There are no results that match your filter.',
				            'daim') . '</p></div>';
	            }

                ?>

            <?php endif; ?>

             <form method="POST" action="admin.php?page=<?php echo $this->shared->get('slug'); ?>-autolinks" autocomplete="off">

                <input type="hidden" value="1" name="form_submitted">
                 
                <?php if(!is_null($data['edit_id'])) : ?>

                    <!-- Edit an Autolink -->

                    <div class="daext-form-container">

                        <h3 class="daext-form-title"><?php esc_html_e('Edit AIL', 'daim'); ?> <?php echo $autolink_obj->id; ?></h3>

                        <table class="daext-form daext-form-table">

                            <input type="hidden" name="update_id" value="<?php echo $autolink_obj->id; ?>" />

                            <!-- Name -->
                            <tr valign="top">
                                <th><label for="name"><?php esc_html_e('Name', 'daim'); ?></label></th>
                                <td>
                                    <input value="<?php echo esc_attr(stripslashes($autolink_obj->name)); ?>" type="text"
                                           id="name" maxlength="100" size="30" name="name"/>
                                    <div class="help-icon"
                                         title="<?php esc_attr_e('The name of the AIL.', 'daim'); ?>"></div>
                                </td>
                            </tr>

                            <!-- Category ID -->
                            <tr>
                                <th scope="row"><label for="tags"><?php esc_html_e('Category', 'daim'); ?></label></th>
                                <td>
			                        <?php

			                        $html = '<select id="category-id" name="category_id" class="daext-display-none">';

			                        $html .= '<option value="0" ' . selected($autolink_obj->category_id, 0,
					                        false) . '>' . esc_html__('None', 'daim') . '</option>';

			                        global $wpdb;
			                        $table_name = $wpdb->prefix . $this->shared->get('slug') . "_category";
			                        $sql        = "SELECT category_id, name FROM $table_name ORDER BY category_id DESC";
			                        $category_a = $wpdb->get_results($sql, ARRAY_A);

			                        foreach ($category_a as $key => $category) {
				                        $html .= '<option value="' . $category['category_id'] . '" ' . selected($autolink_obj->category_id,
						                        $category['category_id'],
						                        false) . '>' . esc_html(stripslashes($category['name'])) . '</option>';
			                        }

			                        $html .= '</select>';
			                        $html .= '<div class="help-icon" title="' . esc_attr__('The category of the AIL.',
					                        'daim') . '"></div>';

			                        echo $html;

			                        ?>
                                </td>
                            </tr>

                             <!-- Keyword -->
                             <tr valign="top">
                                 <th scope="row"><label for="keyword"><?php esc_html_e('Keyword', 'daim'); ?></label></th>
                                 <td>
                                     <input value="<?php echo esc_attr(stripslashes($autolink_obj->keyword)); ?>" type="text" id="keyword" maxlength="255" size="30" name="keyword" placeholder="<?php esc_attr_e('The Keyword', 'daim'); ?>" />
                                     <div class="help-icon" title="<?php esc_attr_e('The keyword that will be converted to a link.', 'daim'); ?>"></div>
                                 </td>
                             </tr>

                             <!-- URL -->
                             <tr valign="top">
                                 <th scope="row"><label for="url"><?php esc_html_e('Target (URL Path and/or File)', 'daim'); ?></label></th>
                                 <td>
                                     <input value="<?php echo esc_attr(stripslashes($autolink_obj->url)); ?>" type="text" id="url" maxlength="2083" size="30" name="url" placeholder="<?php esc_attr_e('/hello-world/', 'daim'); ?>" />
                                     <div class="help-icon" title="<?php esc_attr_e('The target of the link automatically generated on the keyword. Please note that the URL scheme and domain are implied.', 'daim'); ?>"></div>
                                 </td>
                             </tr>

                            <!-- HTML Options ---------------------------------------------------------------------- -->
                            <tr class="group-trigger" data-trigger-target="html-options">
                                <th class="group-title"><?php esc_html_e('HTML', 'daim'); ?></th>
                                <td>
                                    <div class="expand-icon"></div>
                                </td>
                            </tr>

                            <!-- Title -->
                            <tr class="html-options">
                                <th scope="row"><label for="title"><?php esc_html_e('Title', 'daim'); ?></label></th>
                                <td>
                                    <input value="<?php echo esc_attr(stripslashes($autolink_obj->title)); ?>" type="text" id="title" maxlength="1024" size="30" name="title" />
                                    <div class="help-icon" title="<?php esc_attr_e('The title attribute of the link automatically generated on the keyword.', 'daim'); ?>"></div>
                                </td>
                            </tr>

                            <!-- Open New Tab -->
                            <tr class="html-options">
                                <th scope="row"><?php esc_html_e('Open New Tab', 'daim'); ?></th>
                                <td>
                                    <select id="open-new-tab" name="open_new_tab" class="daext-display-none">
                                        <option value="0" <?php selected($autolink_obj->open_new_tab, 0); ?>><?php esc_html_e('No', 'daim'); ?></option>
                                        <option value="1" <?php selected($autolink_obj->open_new_tab, 1); ?>><?php esc_html_e('Yes', 'daim'); ?></option>
                                    </select>
                                    <div class="help-icon" title='<?php esc_attr_e('If you select "Yes" the link generated on the defined keyword opens the linked document in a new tab.', 'daim'); ?>'></div>
                                </td>
                            </tr>

                            <!-- Use Nofollow -->
                            <tr class="html-options">
                                <th scope="row"><?php esc_html_e('Use Nofollow', 'daim'); ?></th>
                                <td>
                                    <select id="use-nofollow" name="use_nofollow" class="daext-display-none">
                                        <option value="0" <?php selected($autolink_obj->use_nofollow, 0); ?>><?php esc_html_e('No', 'daim'); ?></option>
                                        <option value="1" <?php selected($autolink_obj->use_nofollow, 1); ?>><?php esc_html_e('Yes', 'daim'); ?></option>
                                    </select>
                                    <div class="help-icon" title='<?php esc_attr_e('If you select "Yes" the link generated on the defined keyword will include the rel="nofollow" attribute.', 'daim'); ?>'></div>
                                </td>
                            </tr>

                            <!-- Affected Posts Options ------------------------------------------------------------ -->
                            <tr class="group-trigger" data-trigger-target="affected-posts-options">
                                <th class="group-title"><?php esc_html_e('Affected Posts', 'daim'); ?></th>
                                <td>
                                    <div class="expand-icon"></div>
                                </td>
                            </tr>

                            <!-- Activate Post Types -->
                            <tr class="affected-posts-options">
                                <th scope="row"><label for="activate-post-types"><?php esc_html_e('Post Types', 'daim'); ?></label>
                                </th>
                                <td>
                                    <?php

                                    $current_post_types_a = maybe_unserialize($autolink_obj->activate_post_types);

                                    $available_post_types_a = get_post_types(array(
                                        'public'  => true,
                                        'show_ui' => true
                                    ));

                                    //Remove the "attachment" post type
                                    $available_post_types_a = array_diff($available_post_types_a, array('attachment'));

                                    $html = '<select id="activate-post-types" name="activate_post_types[]" class="daext-display-none" multiple>';

                                    foreach ($available_post_types_a as $key => $single_post_type) {
                                        if (is_array($current_post_types_a) and in_array($single_post_type,
                                                $current_post_types_a)) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                        $post_type_obj = get_post_type_object($single_post_type);
                                        $html          .= '<option value="' . $single_post_type . '" ' . $selected . '>' . esc_html($post_type_obj->label) . '</option>';
                                    }

                                    $html .= '</select>';

                                    $html .= '<div class="help-icon" title="' . esc_attr__('With this option you are able to determine in which post types the defined keywords will be automatically converted to a link.',
                                            'daim') . '"></div>';

                                    echo $html;

                                    ?>
                                </td>
                            </tr>

                            <!-- Categories -->
                            <tr class="affected-posts-options">
                                <th scope="row"><label for="categories"><?php esc_html_e('Categories', 'daim'); ?></label>
                                </th>
                                <td>
                                    <?php

                                    $current_categories_a = maybe_unserialize($autolink_obj->categories);

                                    $html = '<select id="categories" name="categories[]" class="daext-display-none" multiple>';

                                    $categories = get_categories(array(
                                        'hide_empty' => 0,
                                        'orderby'    => 'term_id',
                                        'order'      => 'DESC'
                                    ));

                                    foreach ($categories as $key => $category) {
                                        if (is_array($current_categories_a) and in_array($category->term_id,
                                                $current_categories_a)) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                        $html .= '<option value="' . $category->term_id . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
                                    }

                                    $html .= '</select>';
                                    $html .= '<div class="help-icon" title="' . esc_attr__('With this option you are able to determine in which categories the defined keywords will be automatically converted to a link. Leave this field empty to convert the keyword in any category.',
                                            'daim') . '"></div>';

                                    echo $html;

                                    ?>
                                </td>
                            </tr>

                            <!-- Tags -->
                            <tr class="affected-posts-options">
                                <th scope="row"><label for="tags"><?php esc_html_e('Tags', 'daim'); ?></label></th>
                                <td>
                                    <?php

                                    $current_tags_a = maybe_unserialize($autolink_obj->tags);

                                    $html = '<select id="tags" name="tags[]" class="daext-display-none" multiple>';

                                    $categories = get_categories(array(
                                        'hide_empty' => 0,
                                        'orderby'    => 'term_id',
                                        'order'      => 'DESC',
                                        'taxonomy'   => 'post_tag'
                                    ));

                                    foreach ($categories as $key => $category) {
                                        if (is_array($current_tags_a) and in_array($category->term_id, $current_tags_a)) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                        $html .= '<option value="' . $category->term_id . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
                                    }

                                    $html .= '</select>';
                                    $html .= '<div class="help-icon" title="' . esc_attr__('With this option you are able to determine in which tags the defined keywords will be automatically converted to a link. Leave this field empty to convert the keyword in any tag.',
                                            'daim') . '"></div>';

                                    echo $html;

                                    ?>
                                </td>
                            </tr>

                            <!-- Term Group -->
                            <tr class="affected-posts-options">
                                <th scope="row"><label for="tags"><?php esc_html_e('Term Group', 'daim'); ?></label></th>
                                <td>
                                    <?php

                                    $html = '<select id="term-group-id" name="term_group_id" class="daext-display-none">';

                                    $html .= '<option value="0">' . esc_html__('None', 'daim') . '</option>';

                                    global $wpdb;
                                    $table_name   = $wpdb->prefix . $this->shared->get('slug') . "_term_group";
                                    $sql          = "SELECT term_group_id, name FROM $table_name ORDER BY term_group_id DESC";
                                    $term_group_a = $wpdb->get_results($sql, ARRAY_A);

                                    foreach ($term_group_a as $key => $term_group) {
                                        $html .= '<option value="' . $term_group['term_group_id'] . '" ' . selected($autolink_obj->term_group_id,
                                                $term_group['term_group_id'],
                                                false) . '>' . esc_html(stripslashes($term_group['name'])) . '</option>';
                                    }

                                    $html .= '</select>';
                                    $html .= '<div class="help-icon" title="' . esc_attr__('The terms that will be compared with the ones available on the posts where the AIL are applied. Please note that when a term group is selected the "Categories" and "Tags" options will be ignored.',
                                            'daim') . '"></div>';

                                    echo $html;

                                    ?>
                                </td>
                            </tr>

                            <!-- Advanced Match Options ------------------------------------------------------------ -->
                            <tr class="group-trigger" data-trigger-target="advanced-match-options">
                                <th class="group-title"><?php esc_html_e('Advanced Match', 'daim'); ?></th>
                                <td>
                                    <div class="expand-icon"></div>
                                </td>
                            </tr>

                            <!-- Case Insensitive Search -->
                            <tr class="advanced-match-options">
                                <th scope="row"><?php esc_html_e('Case Insensitive Search', 'daim'); ?></th>
                                <td>
                                    <select id="case-insensitive-search" name="case_insensitive_search" class="daext-display-none">
                                        <option value="0" <?php selected($autolink_obj->case_insensitive_search, 0); ?>><?php esc_html_e('No', 'daim'); ?></option>
                                        <option value="1" <?php selected($autolink_obj->case_insensitive_search, 1); ?>><?php esc_html_e('Yes', 'daim'); ?></option>
                                    </select>
                                    <div class="help-icon" title='<?php esc_attr_e('If you select "Yes" your keyword will match both lowercase and uppercase variations.', 'daim'); ?>'></div>
                                </td>
                            </tr>

                            <!-- Left Boundary -->
                            <tr class="advanced-match-options">
                                <th scope="row"><?php esc_html_e('Left Boundary', 'daim'); ?></th>
                                <td>
                                    <select id="left-boundary" name="string_before" class="daext-display-none">
                                        <option value="1" <?php selected($autolink_obj->string_before, 1); ?>><?php esc_html_e('Generic', 'daim'); ?></option>
                                        <option value="2" <?php selected($autolink_obj->string_before, 2); ?>><?php esc_html_e('White Space', 'daim'); ?></option>
                                        <option value="3" <?php selected($autolink_obj->string_before, 3); ?>><?php esc_html_e('Comma', 'daim'); ?></option>
                                        <option value="4" <?php selected($autolink_obj->string_before, 4); ?>><?php esc_html_e('Point', 'daim'); ?></option>
                                        <option value="5" <?php selected($autolink_obj->string_before, 5); ?>><?php esc_html_e('None', 'daim'); ?></option>
                                    </select>
                                    <div class="help-icon" title='<?php esc_attr_e('The "Left Boundary" option can be used to target keywords preceded by a generic boundary or by a specific character.', 'daim'); ?>'></div>
                                </td>
                            </tr>

                            <!-- Right Boundary -->
                            <tr class="advanced-match-options">
                                <th scope="row"><?php esc_html_e('Right Boundary', 'daim'); ?></th>
                                <td>
                                    <select id="right-boundary" name="string_after" class="daext-display-none">
                                        <option value="1" <?php selected($autolink_obj->string_after, 1); ?>><?php esc_html_e('Generic', 'daim'); ?></option>
                                        <option value="2" <?php selected($autolink_obj->string_after, 2); ?>><?php esc_html_e('White Space', 'daim'); ?></option>
                                        <option value="3" <?php selected($autolink_obj->string_after, 3); ?>><?php esc_html_e('Comma', 'daim'); ?></option>
                                        <option value="4" <?php selected($autolink_obj->string_after, 4); ?>><?php esc_html_e('Point', 'daim'); ?></option>
                                        <option value="5" <?php selected($autolink_obj->string_after, 5); ?>><?php esc_html_e('None', 'daim'); ?></option>
                                    </select>
                                    <div class="help-icon" title='<?php esc_attr_e('The "Right Boundary" option can be used to target keywords followed by a generic boundary or by a specific character.', 'daim'); ?>'></div>
                                </td>
                            </tr>

                            <!-- Keyword Before -->
                            <tr class="advanced-match-options">
                                <th scope="row"><label for="keyword-before"><?php esc_html_e('Keyword Before',
                                            'daim'); ?></label></th>
                                <td>
                                    <input value="<?php echo esc_attr(stripslashes($autolink_obj->keyword_before)); ?>"
                                           type="text" id="keyword-before" maxlength="255" size="30" name="keyword_before"/>
                                    <div class="help-icon"
                                         title="<?php esc_attr_e('Use this option to match occurences preceded by a specific string.',
                                             'daim'); ?>"></div>
                                </td>
                            </tr>

                            <!-- Keyword After -->
                            <tr class="advanced-match-options">
                                <th scope="row"><label for="keyword-after"><?php esc_html_e('Keyword After',
                                            'daim'); ?></label></th>
                                <td>
                                    <input value="<?php echo esc_attr(stripslashes($autolink_obj->keyword_after)); ?>"
                                           type="text" id="keyword-after" maxlength="255" size="30" name="keyword_after"/>
                                    <div class="help-icon"
                                         title="<?php esc_attr_e('Use this option to match occurences followed by a specific string.',
                                             'daim'); ?>"></div>
                                </td>
                            </tr>

                            <!-- Max Number Autolinks  -->
                            <tr class="advanced-match-options">
                                <th scope="row"><label for="max-number-autolinks"><?php esc_html_e('Limit', 'daim'); ?></label></th>
                                <td>
                                    <input value="<?php echo esc_attr(stripslashes($autolink_obj->max_number_autolinks)); ?>" type="text" id="max-number-autolinks" maxlength="7" size="30" name="max_number_autolinks" placeholder="<?php esc_attr_e('The Max Number of Autolinks', 'daim'); ?>" />
                                    <div class="help-icon" title="<?php esc_attr_e('With this option you can determine the maximum number of matches of the defined keyword automatically converted to a link.', 'daim'); ?>"></div>
                                </td>
                            </tr>

                            <!-- Priority -->
                            <tr class="advanced-match-options">
                                <th scope="row"><label for="priority"><?php esc_html_e('Priority', 'daim'); ?></label></th>
                                <td>
                                    <input value="<?php echo intval($autolink_obj->priority, 10); ?>" type="text" id="priority" maxlength="7" size="30" name="priority" placeholder="<?php esc_attr_e('The Priority', 'daim'); ?>" />
                                    <div class="help-icon" title='<?php esc_attr_e('The priority value determines the order used to apply the AIL on the post.', 'daim'); ?>'></div>
                                </td>
                            </tr>
                            
                        </table>

                        <!-- submit button -->
                        <div class="daext-form-action">
                            <input class="button" type="submit" value="<?php esc_attr_e('Update AIL', 'daim'); ?>" >
                            <input id="cancel" class="button" type="submit" value="<?php esc_attr_e('Cancel', 'daim'); ?>">
                        </div>

                <?php else : ?>

                    <!-- Create New Autolink -->

                    <div class="daext-form-container">

                        <div class="daext-form-title"><?php esc_html_e('Create New AIL', 'daim'); ?></div>

                             <table class="daext-form daext-form-table">

                                 <!-- Name -->
                                 <tr valign="top">
                                     <th scope="row"><label for="name"><?php esc_html_e('Name', 'daim'); ?></label></th>
                                     <td>
                                         <input type="text" id="keyword" maxlength="100" size="30" name="name" />
                                         <div class="help-icon" title="<?php esc_attr_e('The name of the AIL.', 'daim'); ?>"></div>
                                     </td>
                                 </tr>

                                 <!-- Category ID -->
                                 <tr>
                                     <th scope="row"><label for="category-id"><?php esc_html_e('Category', 'daim'); ?></label></th>
                                     <td>
			                             <?php

			                             $html = '<select id="category-id" name="category_id" class="daext-display-none">';

			                             $html .= '<option value="0" ' . selected(intval(get_option($this->shared->get('slug') . "_default_category_id")),
					                             0, false) . '>' . esc_html__('None', 'daim') . '</option>';

			                             global $wpdb;
			                             $table_name = $wpdb->prefix . $this->shared->get('slug') . "_category";
			                             $sql        = "SELECT category_id, name FROM $table_name ORDER BY category_id DESC";
			                             $category_a = $wpdb->get_results($sql, ARRAY_A);

			                             foreach ($category_a as $key => $category) {
				                             $html .= '<option value="' . $category['category_id'] . '" ' . selected(intval(get_option($this->shared->get('slug') . "_default_category_id")),
						                             $category['category_id'],
						                             false) . '>' . esc_html(stripslashes($category['name'])) . '</option>';
			                             }

			                             $html .= '</select>';
			                             $html .= '<div class="help-icon" title="' . esc_attr__('The category of the AIL.',
					                             'daim') . '"></div>';

			                             echo $html;

			                             ?>
                                     </td>
                                 </tr>

                                 <!-- Keyword -->
                                 <tr valign="top">
                                     <th scope="row"><label for="keyword"><?php esc_html_e('Keyword', 'daim'); ?></label></th>
                                     <td>
                                         <input type="text" id="keyword" maxlength="255" size="30" name="keyword" placeholder="<?php esc_attr_e('The Keyword', 'daim'); ?>" />
                                         <div class="help-icon" title="<?php esc_attr_e('The keyword that will be converted to a link.', 'daim'); ?>"></div>
                                     </td>
                                 </tr>
                                 
                                 <!-- URL -->
                                 <tr valign="top">
                                     <th scope="row"><label for="url"><?php esc_html_e('Target (URL Path and/or File)', 'daim'); ?></label></th>
                                     <td>
                                         <input type="text" id="url" maxlength="2083" size="30" name="url" placeholder="<?php esc_attr_e('/hello-world/', 'daim'); ?>" />
                                         <div class="help-icon" title="<?php esc_attr_e('The target of the link automatically generated on the keyword. Please note that the URL scheme and domain are implied.', 'daim'); ?>"></div>
                                     </td>
                                 </tr>

                                 <!-- HTML Options ---------------------------------------------------------------------- -->
                                 <tr class="group-trigger" data-trigger-target="html-options">
                                     <th class="group-title"><?php esc_html_e('HTML', 'daim'); ?></th>
                                     <td>
                                         <div class="expand-icon"></div>
                                     </td>
                                 </tr>

                                 <!-- Title -->
                                 <tr class="html-options">
                                     <th scope="row"><label for="title"><?php esc_html_e('Title', 'daim'); ?></label></th>
                                     <td>
                                         <input value="<?php echo esc_attr(get_option($this->shared->get('slug') . '_default_title')); ?>" type="text" id="title" maxlength="1024" size="30" name="title" />
                                         <div class="help-icon" title="<?php esc_attr_e('The title attribute of the link automatically generated on the keyword.', 'daim'); ?>"></div>
                                     </td>
                                 </tr>

                                 <!-- Open New Tab -->
                                 <tr class="html-options">
                                     <th scope="row"><?php esc_html_e('Open New Tab', 'daim'); ?></th>
                                     <td>
                                         <select id="open-new-tab" name="open_new_tab" class="daext-display-none">
                                             <option value="0" <?php selected(intval(get_option($this->shared->get('slug') . '_default_open_new_tab'), 10), 0); ?>><?php esc_html_e('No', 'daim'); ?></option>
                                             <option value="1" <?php selected(intval(get_option($this->shared->get('slug') . '_default_open_new_tab'), 10), 1); ?>><?php esc_html_e('Yes', 'daim'); ?></option>
                                         </select>
                                         <div class="help-icon" title='<?php esc_attr_e('If you select "Yes" the link generated on the defined keyword opens the linked document in a new tab.', 'daim'); ?>'></div>
                                     </td>
                                 </tr>

                                 <!-- Use Nofollow -->
                                 <tr class="html-options">
                                     <th scope="row"><?php esc_html_e('Use Nofollow', 'daim'); ?></th>
                                     <td>
                                         <select id="use-nofollow" name="use_nofollow" class="daext-display-none">
                                             <option value="0" <?php selected(intval(get_option($this->shared->get('slug') . '_default_use_nofollow'), 10), 0); ?>><?php esc_html_e('No', 'daim'); ?></option>
                                             <option value="1" <?php selected(intval(get_option($this->shared->get('slug') . '_default_use_nofollow'), 10), 1); ?>><?php esc_html_e('Yes', 'daim'); ?></option>
                                         </select>
                                         <div class="help-icon" title='<?php esc_attr_e('If you select "Yes" the link generated on the defined keyword will include the rel="nofollow" attribute.', 'daim'); ?>'></div>
                                     </td>
                                 </tr>

                                 <!-- Affected Posts Options ------------------------------------------------------------ -->
                                 <tr class="group-trigger" data-trigger-target="affected-posts-options">
                                     <th class="group-title"><?php esc_html_e('Affected Posts', 'daim'); ?></th>
                                     <td>
                                         <div class="expand-icon"></div>
                                     </td>
                                 </tr>

                                 <!-- Activate Post Types -->
                                 <tr class="affected-posts-options">
                                     <th scope="row"><label for="activate-post-types"><?php esc_html_e('Post Types',
                                                 'daim'); ?></label></th>
                                     <td>
                                         <?php

                                         $defaults_post_types_a = get_option("daim_default_activate_post_types");

                                         $available_post_types_a = get_post_types(array(
                                             'public'  => true,
                                             'show_ui' => true
                                         ));

                                         //Remove the "attachment" post type
                                         $available_post_types_a = array_diff($available_post_types_a, array('attachment'));

                                         $html = '<select id="activate-post-types" name="activate_post_types[]" class="daext-display-none" multiple>';

                                         foreach ($available_post_types_a as $key => $single_post_type) {
                                             if (is_array($defaults_post_types_a) and in_array($single_post_type,
                                                     $defaults_post_types_a)) {
                                                 $selected = 'selected';
                                             } else {
                                                 $selected = '';
                                             }
                                             $post_type_obj = get_post_type_object($single_post_type);
                                             $html          .= '<option value="' . $single_post_type . '" ' . $selected . '>' . esc_html($post_type_obj->label) . '</option>';
                                         }

                                         $html .= '</select>';

                                         $html .= '<div class="help-icon" title="' . esc_attr__('With this option you are able to determine in which post types the defined keywords will be automatically converted to a link.',
                                                 'daim') . '"></div>';

                                         echo $html;

                                         ?>
                                     </td>
                                 </tr>

                                 <!-- Categories -->
                                 <tr class="affected-posts-options">
                                     <th scope="row"><label for="categories"><?php esc_html_e('Categories',
                                                 'daim'); ?></label></th>
                                     <td>
                                         <?php

                                         $default_categories_a = get_option("daim_default_categories");

                                         $html = '<select id="categories" name="categories[]" class="daext-display-none" multiple>';

                                         $categories = get_categories(array(
                                             'hide_empty' => 0,
                                             'orderby'    => 'term_id',
                                             'order'      => 'DESC'
                                         ));

                                         foreach ($categories as $key => $category) {
                                             if (is_array($default_categories_a) and in_array($category->term_id,
                                                     $default_categories_a)) {
                                                 $selected = 'selected';
                                             } else {
                                                 $selected = '';
                                             }
                                             $html .= '<option value="' . $category->term_id . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
                                         }

                                         $html .= '</select>';
                                         $html .= '<div class="help-icon" title="' . esc_attr__('With this option you are able to determine in which categories the defined keywords will be automatically converted to a link. Leave this field empty to convert the keyword in any category.',
                                                 'daim') . '"></div>';

                                         echo $html;

                                         ?>
                                     </td>
                                 </tr>

                                 <!-- Tags -->
                                 <tr class="affected-posts-options">
                                     <th scope="row"><label for="tags"><?php esc_html_e('Tags', 'daim'); ?></label></th>
                                     <td>
                                         <?php

                                         $default_tags_a = get_option("daim_default_tags");

                                         $html = '<select id="tags" name="tags[]" class="daext-display-none" multiple>';

                                         $categories = get_categories(array(
                                             'hide_empty' => 0,
                                             'orderby'    => 'term_id',
                                             'order'      => 'DESC',
                                             'taxonomy'   => 'post_tag'
                                         ));

                                         foreach ($categories as $key => $category) {
                                             if (is_array($default_tags_a) and in_array($category->term_id,
                                                     $default_tags_a)) {
                                                 $selected = 'selected';
                                             } else {
                                                 $selected = '';
                                             }
                                             $html .= '<option value="' . $category->term_id . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
                                         }

                                         $html .= '</select>';
                                         $html .= '<div class="help-icon" title="' . esc_attr__('With this option you are able to determine in which tags the defined keywords will be automatically converted to a link. Leave this field empty to convert the keyword in any tag.',
                                                 'daim') . '"></div>';

                                         echo $html;

                                         ?>
                                     </td>
                                 </tr>

                                 <!-- Term Group -->
                                 <tr class="affected-posts-options">
                                     <th scope="row"><label for="tags"><?php esc_html_e('Term Group', 'daim'); ?></label>
                                     </th>
                                     <td>
                                         <?php

                                         $html = '<select id="term-group-id" name="term_group_id" class="daext-display-none">';
                                         $temp = intval(get_option($this->shared->get('slug') . "_defaults_term_group_id"), 10);
                                         $html .= '<option value="0" ' . selected(intval(get_option($this->shared->get('slug') . "_defaults_term_group_id"), 10),
                                                 0, false) . '>' . esc_html__('None', 'daim') . '</option>';

                                         global $wpdb;
                                         $table_name   = $wpdb->prefix . $this->shared->get('slug') . "_term_group";
                                         $sql          = "SELECT term_group_id, name FROM $table_name ORDER BY term_group_id DESC";
                                         $term_group_a = $wpdb->get_results($sql, ARRAY_A);

                                         foreach ($term_group_a as $key => $term_group) {
                                             $html .= '<option value="' . $term_group['term_group_id'] . '" ' . selected(intval(get_option($this->shared->get('slug') . "_default_term_group_id"), 10),
                                                     $term_group['term_group_id'],
                                                     false) . '>' . esc_html(stripslashes($term_group['name'])) . '</option>';
                                         }

                                         $html .= '</select>';
                                         $html .= '<div class="help-icon" title="' . esc_attr__('The terms that will be compared with the ones available on the posts where the AIL are applied. Please note that when a term group is selected the "Categories" and "Tags" options will be ignored.',
                                                 'daim') . '"></div>';

                                         echo $html;

                                         ?>
                                     </td>
                                 </tr>

                                 <!-- Advanced Match Options ------------------------------------------------------------ -->
                                 <tr class="group-trigger" data-trigger-target="advanced-match-options">
                                     <th class="group-title"><?php esc_html_e('Advanced Match', 'daim'); ?></th>
                                     <td>
                                         <div class="expand-icon"></div>
                                     </td>
                                 </tr>

                                 <!-- Case Insensitive Search -->
                                 <tr class="advanced-match-options">
                                     <th scope="row"><?php esc_html_e('Case Insensitive Search', 'daim'); ?></th>
                                     <td>
                                         <select id="case-insensitive-search" name="case_insensitive_search" class="daext-display-none">
                                             <option value="0" <?php selected(intval(get_option($this->shared->get('slug') . '_default_case_insensitive_search'), 10), 0); ?>><?php esc_html_e('No', 'daim'); ?></option>
                                             <option value="1" <?php selected(intval(get_option($this->shared->get('slug') . '_default_case_insensitive_search'), 10), 1); ?>><?php esc_html_e('Yes', 'daim'); ?></option>
                                         </select>
                                         <div class="help-icon" title='<?php esc_attr_e('If you select "Yes" your keyword will match both lowercase and uppercase variations.', 'daim'); ?>'></div>
                                     </td>
                                 </tr>

                                <!-- Left Boundary -->
                                <tr class="advanced-match-options">
                                    <th scope="row"><?php esc_html_e('Left Boundary', 'daim'); ?></th>
                                    <td>
                                        <select id="left-boundary" name="string_before" class="daext-display-none">
                                            <option value="1" <?php selected(intval(get_option($this->shared->get('slug') . "_default_string_before")), 1); ?>><?php esc_html_e('Generic', 'daim'); ?></option>
                                            <option value="2" <?php selected(intval(get_option($this->shared->get('slug') . "_default_string_before")), 2); ?>><?php esc_html_e('White Space', 'daim'); ?></option>
                                            <option value="3" <?php selected(intval(get_option($this->shared->get('slug') . "_default_string_before")), 3); ?>><?php esc_html_e('Comma', 'daim'); ?></option>
                                            <option value="4" <?php selected(intval(get_option($this->shared->get('slug') . "_default_string_before")), 4); ?>><?php esc_html_e('Point', 'daim'); ?></option>
                                            <option value="5" <?php selected(intval(get_option($this->shared->get('slug') . "_default_string_before")), 5); ?>><?php esc_html_e('None', 'daim'); ?></option>
                                        </select>
                                        <div class="help-icon" title='<?php esc_attr_e('The "Left Boundary" option can be used to target keywords preceded by a generic boundary or by a specific character.', 'daim'); ?>'></div>
                                    </td>
                                </tr>
                                 
                                <!-- Right Boundary -->
                                <tr class="advanced-match-options">
                                    <th scope="row"><?php esc_html_e('Right Boundary', 'daim'); ?></th>
                                    <td>
                                        <select id="right-boundary" name="string_after" class="daext-display-none">
                                            <option value="1" <?php selected(intval(get_option($this->shared->get('slug') . "_default_string_after")), 1); ?>><?php esc_html_e('Generic', 'daim'); ?></option>
                                            <option value="2" <?php selected(intval(get_option($this->shared->get('slug') . "_default_string_after")), 2); ?>><?php esc_html_e('White Space', 'daim'); ?></option>
                                            <option value="3" <?php selected(intval(get_option($this->shared->get('slug') . "_default_string_after")), 3); ?>><?php esc_html_e('Comma', 'daim'); ?></option>
                                            <option value="4" <?php selected(intval(get_option($this->shared->get('slug') . "_default_string_after")), 4); ?>><?php esc_html_e('Point', 'daim'); ?></option>
                                            <option value="5" <?php selected(intval(get_option($this->shared->get('slug') . "_default_string_after")), 5); ?>><?php esc_html_e('None', 'daim'); ?></option>
                                        </select>
                                        <div class="help-icon" title='<?php esc_attr_e('The "Right Boundary" option can be used to target keywords followed by a generic boundary or by a specific character.', 'daim'); ?>'></div>
                                    </td>
                                </tr>

                                 <!-- Keyword Before -->
                                 <tr class="advanced-match-options">
                                     <th scope="row"><label for="keyword-before"><?php esc_html_e('Keyword Before',
                                                 'daim'); ?></label></th>
                                     <td>
                                         <input value="<?php echo esc_attr(get_option($this->shared->get('slug') . '_default_keyword_before')); ?>" type="text" id="keyword-before" maxlength="255" size="30"
                                                name="keyword_before"/>
                                         <div class="help-icon"
                                              title="<?php esc_attr_e('Use this option to match occurences preceded by a specific string.',
                                                  'daim'); ?>"></div>
                                     </td>
                                 </tr>

                                 <!-- Keyword After -->
                                 <tr class="advanced-match-options">
                                     <th scope="row"><label for="keyword-after"><?php esc_html_e('Keyword After',
                                                 'daim'); ?></label></th>
                                     <td>

                                         <input value="<?php echo esc_attr(get_option($this->shared->get('slug') . '_default_keyword_after')); ?>" type="text" id="keyword-after" maxlength="255" size="30"
                                                name="keyword_after"/>
                                         <div class="help-icon"
                                              title="<?php esc_attr_e('Use this option to match occurences followed by a specific string.',
                                                  'daim'); ?>"></div>
                                     </td>
                                 </tr>

                                 <!-- Max Number Autolinks  -->
                                 <tr class="advanced-match-options">
                                     <th scope="row"><label for="max-number-autolinks"><?php esc_html_e('Limit', 'daim'); ?></label></th>
                                     <td>
                                         <input value="<?php echo intval(get_option($this->shared->get('slug') . '_default_max_number_autolinks_per_keyword'), 10); ?>" type="text" id="max-number-autolinks" maxlength="7" size="30" name="max_number_autolinks" placeholder="<?php esc_attr_e('The Max Number of Autolinks', 'daim'); ?>" />
                                         <div class="help-icon" title="<?php esc_attr_e('With this option you can determine the maximum number of matches of the defined keyword automatically converted to a link.', 'daim'); ?>"></div>
                                     </td>
                                 </tr>

                                 <!-- Priority -->
                                 <tr class="advanced-match-options">
                                     <th scope="row"><label for="priority"><?php esc_html_e('Priority', 'daim'); ?></label></th>
                                     <td>
                                         <input value="<?php echo intval(get_option($this->shared->get('slug') . '_default_priority'), 10); ?>" type="text" id="priority" maxlength="7" size="30" name="priority" placeholder="<?php esc_attr_e('The Priority of this Keyword', 'daim'); ?>" />
                                        <div class="help-icon" title='<?php esc_attr_e('The priority value determines the order used to apply the AIL on the post.', 'daim'); ?>'></div>
                                     </td>
                                 </tr>

                            </table>
                        
                            <!-- submit button -->
                            <div class="daext-form-action">
                                <input class="button" type="submit" value="<?php esc_attr_e('Add AIL', 'daim'); ?>" >
                            </div>

                        <?php endif; ?>

                    </div>

            </form>

        </div>

    </div>

<!-- Dialog Confirm -->
<div id="dialog-confirm" title="<?php esc_attr_e('Delete the autolink?', 'daim'); ?>" class="daext-display-none">
    <p><?php esc_html_e('This autolink will be permanently deleted and cannot be recovered. Are you sure?',
            'daim'); ?></p>
</div>