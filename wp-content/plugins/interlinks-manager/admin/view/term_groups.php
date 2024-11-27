<?php

if ( ! current_user_can(get_option($this->shared->get('slug') . '_term_groups_menu_required_capability'))) {
    wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'daim'));
}

$supported_terms = intval(get_option($this->shared->get('slug') . '_supported_terms'), 10);

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

    //prepare data -----------------------------------------------------------------------------------------------------
    $data['name'] = sanitize_text_field($_POST['name']);

	for ($i = 1; $i <= 50; $i++) {

		//If the "Supported Terms" are less than 50 give a default value to the non-submitted fields
		if ( ! isset($_POST['post_type_' . $i])) {
			$data['post_type_' . $i] = '';
		}else{
			$data['post_type_' . $i] = sanitize_key($_POST['post_type_' . $i]);
		}
		if ( ! isset($_POST['taxonomy_' . $i])) {
			$data['taxonomy_' . $i] = '';
		}else{
			$data['taxonomy_' . $i] = sanitize_key($_POST['taxonomy_' . $i]);
		}
		if ( ! isset($_POST['term_' . $i])) {
			$data['term_' . $i] = 0;
		}else{
			$data['term_' . $i] = intval($_POST['term_' . $i], 10);
		}

		//Set post type and taxonomy to an empty value if the related term is not set
		if (intval($data['term_' . $i], 10) === 0) {
			$data['post_type_' . $i] = '';
			$data['taxonomy_' . $i]  = '';
		}

	}
    
    //validation -------------------------------------------------------------------------------------------------------

    $invalid_data_message = '';

    //validation on "name"
    if (mb_strlen(trim($data['name'])) === 0 or mb_strlen(trim($data['name'])) > 100) {
        $invalid_data_message .= '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__('Please enter a valid value in the "Name" field.',
                'daim') . '</p></div>';
        $invalid_data         = true;
    }

    //Require that at least one term is set
    $one_term_is_set = false;
    for ($i = 1; $i <= 50; $i++) {
        if (intval($data['term_' . $i], 10) !== 0) {
            $one_term_is_set = true;
        }
    }
    if ( ! $one_term_is_set) {
        $invalid_data_message .= '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__('Please specify at least one term.',
                'daim') . '</p></div>';
        $invalid_data         = true;
    }

}

// Prepare the partial query
$query_part = '';
for ($i = 1; $i <= 50; $i++) {
    $query_part .= 'post_type_' . $i . ' = %s,';
    $query_part .= 'taxonomy_' . $i . ' = %s,';
    $query_part .= 'term_' . $i . ' = %d';
    if ($i !== 50) {
        $query_part .= ',';
    }
}

//Update or add the record in the database
if( !is_null($data['update_id']) and !isset($invalid_data) ){

        //Update

        global $wpdb;
        $table_name = $wpdb->prefix . $this->shared->get('slug') . "_term_group";
        $safe_sql   = $wpdb->prepare("UPDATE $table_name SET
                name = %s,
                $query_part
                WHERE term_group_id = %d",
            $data['name'],
            $data['post_type_1'], $data['taxonomy_1'], $data['term_1'],
            $data['post_type_2'], $data['taxonomy_2'], $data['term_2'],
            $data['post_type_3'], $data['taxonomy_3'], $data['term_3'],
            $data['post_type_4'], $data['taxonomy_4'], $data['term_4'],
            $data['post_type_5'], $data['taxonomy_5'], $data['term_5'],
            $data['post_type_6'], $data['taxonomy_6'], $data['term_6'],
            $data['post_type_7'], $data['taxonomy_7'], $data['term_7'],
            $data['post_type_8'], $data['taxonomy_8'], $data['term_8'],
            $data['post_type_9'], $data['taxonomy_9'], $data['term_9'],
            $data['post_type_10'], $data['taxonomy_10'], $data['term_10'],
            $data['post_type_11'], $data['taxonomy_11'], $data['term_11'],
            $data['post_type_12'], $data['taxonomy_12'], $data['term_12'],
            $data['post_type_13'], $data['taxonomy_13'], $data['term_13'],
            $data['post_type_14'], $data['taxonomy_14'], $data['term_14'],
            $data['post_type_15'], $data['taxonomy_15'], $data['term_15'],
            $data['post_type_16'], $data['taxonomy_16'], $data['term_16'],
            $data['post_type_17'], $data['taxonomy_17'], $data['term_17'],
            $data['post_type_18'], $data['taxonomy_18'], $data['term_18'],
            $data['post_type_19'], $data['taxonomy_19'], $data['term_19'],
            $data['post_type_20'], $data['taxonomy_20'], $data['term_20'],
            $data['post_type_21'], $data['taxonomy_21'], $data['term_21'],
            $data['post_type_22'], $data['taxonomy_22'], $data['term_22'],
            $data['post_type_23'], $data['taxonomy_23'], $data['term_23'],
            $data['post_type_24'], $data['taxonomy_24'], $data['term_24'],
            $data['post_type_25'], $data['taxonomy_25'], $data['term_25'],
            $data['post_type_26'], $data['taxonomy_26'], $data['term_26'],
            $data['post_type_27'], $data['taxonomy_27'], $data['term_27'],
            $data['post_type_28'], $data['taxonomy_28'], $data['term_28'],
            $data['post_type_29'], $data['taxonomy_29'], $data['term_29'],
            $data['post_type_30'], $data['taxonomy_30'], $data['term_30'],
            $data['post_type_31'], $data['taxonomy_31'], $data['term_31'],
            $data['post_type_32'], $data['taxonomy_32'], $data['term_32'],
            $data['post_type_33'], $data['taxonomy_33'], $data['term_33'],
            $data['post_type_34'], $data['taxonomy_34'], $data['term_34'],
            $data['post_type_35'], $data['taxonomy_35'], $data['term_35'],
            $data['post_type_36'], $data['taxonomy_36'], $data['term_36'],
            $data['post_type_37'], $data['taxonomy_37'], $data['term_37'],
            $data['post_type_38'], $data['taxonomy_38'], $data['term_38'],
            $data['post_type_39'], $data['taxonomy_39'], $data['term_39'],
            $data['post_type_40'], $data['taxonomy_40'], $data['term_40'],
            $data['post_type_41'], $data['taxonomy_41'], $data['term_41'],
            $data['post_type_42'], $data['taxonomy_42'], $data['term_42'],
            $data['post_type_43'], $data['taxonomy_43'], $data['term_43'],
            $data['post_type_44'], $data['taxonomy_44'], $data['term_44'],
            $data['post_type_45'], $data['taxonomy_45'], $data['term_45'],
            $data['post_type_46'], $data['taxonomy_46'], $data['term_46'],
            $data['post_type_47'], $data['taxonomy_47'], $data['term_47'],
            $data['post_type_48'], $data['taxonomy_48'], $data['term_48'],
            $data['post_type_49'], $data['taxonomy_49'], $data['term_49'],
            $data['post_type_50'], $data['taxonomy_50'], $data['term_50'],
            $data['update_id']);

        $query_result = $wpdb->query($safe_sql);

        if ($query_result !== false) {
            $process_data_message = '<div class="updated settings-error notice is-dismissible below-h2"><p>' . esc_html__('The term has been successfully updated.',
                    'daim') . '</p></div>';
        }

    }else{

        //Add
        if ( ! is_null( $data['form_submitted'] ) and ! isset( $invalid_data ) ) {

	        global $wpdb;
	        $table_name = $wpdb->prefix . $this->shared->get('slug') . "_term_group";
	        $safe_sql   = $wpdb->prepare("INSERT INTO $table_name SET
            name = %s,
            $query_part",
		        $data['name'],
		        $data['post_type_1'], $data['taxonomy_1'], $data['term_1'],
		        $data['post_type_2'], $data['taxonomy_2'], $data['term_2'],
		        $data['post_type_3'], $data['taxonomy_3'], $data['term_3'],
		        $data['post_type_4'], $data['taxonomy_4'], $data['term_4'],
		        $data['post_type_5'], $data['taxonomy_5'], $data['term_5'],
		        $data['post_type_6'], $data['taxonomy_6'], $data['term_6'],
		        $data['post_type_7'], $data['taxonomy_7'], $data['term_7'],
		        $data['post_type_8'], $data['taxonomy_8'], $data['term_8'],
		        $data['post_type_9'], $data['taxonomy_9'], $data['term_9'],
		        $data['post_type_10'], $data['taxonomy_10'], $data['term_10'],
		        $data['post_type_11'], $data['taxonomy_11'], $data['term_11'],
		        $data['post_type_12'], $data['taxonomy_12'], $data['term_12'],
		        $data['post_type_13'], $data['taxonomy_13'], $data['term_13'],
		        $data['post_type_14'], $data['taxonomy_14'], $data['term_14'],
		        $data['post_type_15'], $data['taxonomy_15'], $data['term_15'],
		        $data['post_type_16'], $data['taxonomy_16'], $data['term_16'],
		        $data['post_type_17'], $data['taxonomy_17'], $data['term_17'],
		        $data['post_type_18'], $data['taxonomy_18'], $data['term_18'],
		        $data['post_type_19'], $data['taxonomy_19'], $data['term_19'],
		        $data['post_type_20'], $data['taxonomy_20'], $data['term_20'],
		        $data['post_type_21'], $data['taxonomy_21'], $data['term_21'],
		        $data['post_type_22'], $data['taxonomy_22'], $data['term_22'],
		        $data['post_type_23'], $data['taxonomy_23'], $data['term_23'],
		        $data['post_type_24'], $data['taxonomy_24'], $data['term_24'],
		        $data['post_type_25'], $data['taxonomy_25'], $data['term_25'],
		        $data['post_type_26'], $data['taxonomy_26'], $data['term_26'],
		        $data['post_type_27'], $data['taxonomy_27'], $data['term_27'],
		        $data['post_type_28'], $data['taxonomy_28'], $data['term_28'],
		        $data['post_type_29'], $data['taxonomy_29'], $data['term_29'],
		        $data['post_type_30'], $data['taxonomy_30'], $data['term_30'],
		        $data['post_type_31'], $data['taxonomy_31'], $data['term_31'],
		        $data['post_type_32'], $data['taxonomy_32'], $data['term_32'],
		        $data['post_type_33'], $data['taxonomy_33'], $data['term_33'],
		        $data['post_type_34'], $data['taxonomy_34'], $data['term_34'],
		        $data['post_type_35'], $data['taxonomy_35'], $data['term_35'],
		        $data['post_type_36'], $data['taxonomy_36'], $data['term_36'],
		        $data['post_type_37'], $data['taxonomy_37'], $data['term_37'],
		        $data['post_type_38'], $data['taxonomy_38'], $data['term_38'],
		        $data['post_type_39'], $data['taxonomy_39'], $data['term_39'],
		        $data['post_type_40'], $data['taxonomy_40'], $data['term_40'],
		        $data['post_type_41'], $data['taxonomy_41'], $data['term_41'],
		        $data['post_type_42'], $data['taxonomy_42'], $data['term_42'],
		        $data['post_type_43'], $data['taxonomy_43'], $data['term_43'],
		        $data['post_type_44'], $data['taxonomy_44'], $data['term_44'],
		        $data['post_type_45'], $data['taxonomy_45'], $data['term_45'],
		        $data['post_type_46'], $data['taxonomy_46'], $data['term_46'],
		        $data['post_type_47'], $data['taxonomy_47'], $data['term_47'],
		        $data['post_type_48'], $data['taxonomy_48'], $data['term_48'],
		        $data['post_type_49'], $data['taxonomy_49'], $data['term_49'],
		        $data['post_type_50'], $data['taxonomy_50'], $data['term_50']
	        );

	        $query_result = $wpdb->query($safe_sql);

	        if ($query_result !== false) {
		        $process_data_message = '<div class="updated settings-error notice is-dismissible below-h2"><p>' . esc_html__('The term group has been successfully added.',
				        'daim') . '</p></div>';
	        }

        }

}

//delete a term group
if (!is_null($data['delete_id'])) {

    global $wpdb;

    //prevent deletion if the term group is associated with an autolink
    if ($this->shared->term_group_is_used($data['delete_id'])) {

        $process_data_message = '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__("This term group is associated with one or more AIL and can't be deleted.",
                'daim') . '</p></div>';

    } else {

        $table_name   = $wpdb->prefix . $this->shared->get('slug') . "_term_group";
        $safe_sql     = $wpdb->prepare("DELETE FROM $table_name WHERE term_group_id = %d ", $data['delete_id']);
        $query_result = $wpdb->query($safe_sql);

        if ($query_result !== false) {
            $process_data_message = '<div class="updated settings-error notice is-dismissible below-h2"><p>' . esc_html__('The term group has been successfully deleted.',
                    'daim') . '</p></div>';
        }

    }

}

//clone the term group
if (!is_null($data['clone_id'])) {

    global $wpdb;

    //clone the autolink
    $table_name = $wpdb->prefix . $this->shared->get('slug') . "_term_group";
    $wpdb->query("CREATE TEMPORARY TABLE daim_temporary_table SELECT * FROM $table_name WHERE term_group_id = " . $data['clone_id']);
    $wpdb->query("UPDATE daim_temporary_table SET term_group_id = NULL");
    $wpdb->query("INSERT INTO $table_name SELECT * FROM daim_temporary_table");
    $wpdb->query("DROP TEMPORARY TABLE IF EXISTS daim_temporary_table");

}

//get the term_group data
if (!is_null($data['edit_id'])) {
    global $wpdb;
    $table_name     = $wpdb->prefix . $this->shared->get('slug') . "_term_group";
    $safe_sql       = $wpdb->prepare("SELECT * FROM $table_name WHERE term_group_id = %d ", $data['edit_id']);
    $term_group_obj = $wpdb->get_row($safe_sql);
}

?>

<!-- output -->

<div class="wrap">

    <div id="daext-header-wrapper" class="daext-clearfix">

        <h2><?php esc_html_e('Interlinks Manager - Term Groups', 'daim'); ?></h2>

        <form action="admin.php" method="get" id="daext-search-form">

            <input type="hidden" name="page" value="daim-term-groups">

            <p><?php esc_html_e('Perform your Search', 'daim'); ?></p>

            <?php
            if (!is_null($data['s']) and mb_strlen(trim($data['s'])) > 0) {
                $search_string = $data['s'];
            } else {
                $search_string = '';
            }
            ?>

            <input type="text" name="s"
                   value="<?php echo esc_attr(stripslashes($search_string)); ?>" autocomplete="off" maxlength="255">
            <input type="submit" value="">

        </form>

    </div>

    <div id="daext-menu-wrapper">

        <?php if (isset($invalid_data_message)) {
            echo $invalid_data_message;
        } ?>
        <?php if (isset($process_data_message)) {
            echo $process_data_message;
        } ?>

        <!-- table -->

        <?php

        $filter = '';

        //create the query part used to filter the results when a search is performed
        if (mb_strlen(trim($filter)) === 0 and !is_null($data['s'])) {
            if(mb_strlen(trim($data['s'])) > 0){
	            $search_string = $data['s'];
	            global $wpdb;
	            $filter = $wpdb->prepare('WHERE (name LIKE %s)', '%' . $search_string . '%');
            }
        }

        //retrieve the total number of term groups
        global $wpdb;
        $table_name  = $wpdb->prefix . $this->shared->get('slug') . "_term_group";
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name $filter");

        //Initialize the pagination class
        require_once($this->shared->get('dir') . '/admin/inc/class-daim-pagination.php');
        $pag = new daim_pagination();
        $pag->set_total_items($total_items);//Set the total number of items
        $pag->set_record_per_page(intval(get_option($this->shared->get('slug') . '_pagination_term_groups_menu'),
            10)); //Set records per page
        $pag->set_target_page("admin.php?page=" . $this->shared->get('slug') . "-term-groups");//Set target page
        $pag->set_current_page();//set the current page number from $_GET

        ?>

        <!-- Query the database -->
        <?php
        $query_limit = $pag->query_limit();
        $results     = $wpdb->get_results("SELECT * FROM $table_name $filter ORDER BY term_group_id DESC $query_limit",
            ARRAY_A); ?>

        <?php if (count($results) > 0) : ?>

            <div class="daext-items-container">

                <!-- list of tables -->
                <table class="daext-items">
                    <thead>
                    <tr>
                        <th>
                            <div><?php esc_html_e('Term Group ID', 'daim'); ?></div>
                            <div class="help-icon"
                                 title="<?php esc_attr_e('The ID of the term group.', 'daim'); ?>"></div>
                        </th>
                        <th>
                            <div><?php esc_html_e('Name', 'daim'); ?></div>
                            <div class="help-icon"
                                 title="<?php esc_attr_e('The name of the term group.', 'daim'); ?>"></div>
                        </th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($results as $result) : ?>
                        <tr>
                            <td><?php echo intval($result['term_group_id'], 10); ?></td>
                            <td><?php echo esc_html(stripslashes($result['name'])); ?></td>
                            <td class="icons-container">
                                <form method="POST"
                                      action="admin.php?page=<?php echo $this->shared->get('slug'); ?>-term-groups">
                                    <input type="hidden" name="clone_id"
                                           value="<?php echo $result['term_group_id']; ?>">
                                    <input class="menu-icon clone help-icon" type="submit" value="">
                                </form>
                                <a class="menu-icon edit"
                                   href="admin.php?page=<?php echo $this->shared->get('slug'); ?>-term-groups&edit_id=<?php echo $result['term_group_id']; ?>"></a>
                                <form id="form-delete-<?php echo $result['term_group_id']; ?>" method="POST"
                                      action="admin.php?page=<?php echo $this->shared->get('slug'); ?>-term-groups">
                                    <input type="hidden" value="<?php echo $result['term_group_id']; ?>"
                                           name="delete_id">
                                    <input class="menu-icon delete" type="submit" value="">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

            <!-- Display the pagination -->
            <?php if ($pag->total_items > 0) : ?>
                <div class="daext-tablenav daext-clearfix">
                    <div class="daext-tablenav-pages">
                        <span class="daext-displaying-num"><?php echo $pag->total_items; ?>
                            &nbsp<?php esc_html_e('items',
                                'daim'); ?></span>
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

        <div>

            <form method="POST" action="admin.php?page=<?php echo $this->shared->get('slug'); ?>-term-groups"
                  autocomplete="off">

                <input type="hidden" value="1" name="form_submitted">

                <?php if (!is_null($data['edit_id'])) : ?>

                <!-- Edit a Term Group -->

                <div class="daext-form-container">

                    <h3 class="daext-form-title"><?php esc_html_e('Edit Term Group',
                            'daim'); ?>&nbsp<?php echo $term_group_obj->term_group_id; ?></h3>

                    <table class="daext-form daext-form-table">

                        <input type="hidden" name="update_id"
                               value="<?php echo $term_group_obj->term_group_id; ?>"/>

                        <!-- Name -->
                        <tr valign="top">
                            <th><label for="title"><?php esc_html_e('Name', 'daim'); ?></label></th>
                            <td>
                                <input value="<?php echo esc_attr(stripslashes($term_group_obj->name)); ?>" type="text"
                                       id="name" maxlength="100" size="30" name="name"/>
                                <div class="help-icon"
                                     title="<?php esc_attr_e('The name of the term group.', 'daim'); ?>"></div>
                            </td>
                        </tr>

                        <?php

                        $post_types = $this->shared->get_post_types_with_ui();

                        for ($i = 1; $i <= $supported_terms; $i++) {

                            ?>

                            <!-- Post Type <?php echo $i; ?> -->
                            <tr>
                                <th scope="row"><?php esc_html_e('Post Type', 'daim'); ?>&nbsp<?php echo $i; ?></th>
                                <td>
                                    <select id="post-type-<?php echo $i; ?>" name="post_type_<?php echo $i; ?>"
                                            class="post-type daext-display-none" data-id="<?php echo $i; ?>">
                                        <option value="" class="default"><?php esc_html_e('None', 'daim'); ?></option>
                                        <?php

                                        foreach ($post_types as $key => $post_type) {
                                            $post_type_obj = get_post_type_object($post_type);
                                            echo '<option value="' . esc_attr(stripslashes($post_type)) . '" ' . selected($post_type,
                                                    $term_group_obj->{'post_type_' . $i},
                                                    false) . '>' . esc_html(stripslashes($post_type_obj->label)) . '</option>';
                                        }

                                        ?>
                                    </select>
                                    <div class="help-icon"
                                         title='<?php esc_attr_e('The post type for which you want to retrieve the taxonomies.',
                                             'daim'); ?>'></div>
                                </td>
                            </tr>

                            <!-- Taxonomy <?php echo $i; ?> -->
                            <tr>
                                <th scope="row"><?php esc_html_e('Taxonomy', 'daim'); ?>&nbsp<?php echo $i; ?></th>
                                <td>
                                    <select id="taxonomy-<?php echo $i; ?>" name="taxonomy_<?php echo $i; ?>"
                                            class="taxonomy daext-display-none" data-id="<?php echo $i; ?>">
                                        <option value="" class="default"><?php esc_html_e('None', 'daim'); ?></option>
                                        <?php

                                        $taxonomies = get_object_taxonomies($term_group_obj->{'post_type_' . $i});
                                        foreach ($taxonomies as $key => $taxonomy) {
                                            echo '<option value="' . $taxonomy . '" ' . selected($taxonomy,
                                                    $term_group_obj->{'taxonomy_' . $i},
                                                    false) . '>' . $taxonomy . '</option>';
                                        }

                                        ?>
                                    </select>
                                    <div class="help-icon"
                                         title='<?php esc_attr_e('The taxonomy for which you want to retrieve the terms.',
                                             'daim'); ?>'></div>
                                </td>
                            </tr>

                            <!-- Term <?php echo $i; ?> -->
                            <tr>
                                <th scope="row"><?php esc_html_e('Term', 'daim'); ?>&nbsp<?php echo $i; ?></th>
                                <td>
                                    <select id="term-<?php echo $i; ?>" name="term_<?php echo $i; ?>"
                                            class="daext-display-none">
                                        <option value="0" class="default"><?php esc_html_e('None', 'daim'); ?></option>
                                        <?php

                                        $terms = get_terms(array(
                                            'hide_empty' => 0,
                                            'orderby'    => 'term_id',
                                            'order'      => 'DESC',
                                            'taxonomy'   => $term_group_obj->{'taxonomy_' . $i}
                                        ));

                                        if (is_array($terms)) {
                                            foreach ($terms as $key => $termObj) {
                                                echo '<option value="' . $termObj->term_id . '" ' . selected($termObj->term_id,
                                                        $term_group_obj->{'term_' . $i},
                                                        false) . '>' . $termObj->name . '</option>';
                                            }
                                        }

                                        ?>
                                    </select>
                                    <div class="help-icon"
                                         title='<?php esc_attr_e('The term that will be compared with the ones available on the posts where the autolinks are applied.',
                                             'daim'); ?>'></div>
                                </td>
                            </tr>

                            <?php

                        }

                        ?>

                    </table>

                    <!-- submit button -->
                    <div class="daext-form-action">
                        <input class="button" type="submit"
                               value="<?php esc_attr_e('Update Term Group', 'daim'); ?>">
                        <input id="cancel" class="button" type="submit"
                               value="<?php esc_attr_e('Cancel', 'daim'); ?>">
                    </div>

                    <?php else : ?>

                    <!-- Create New Term Group -->

                    <div class="daext-form-container">

                        <div class="daext-form-title"><?php esc_html_e('Create New Term Group', 'daim'); ?></div>

                        <table class="daext-form daext-form-table">

                            <!-- Name -->
                            <tr valign="top">
                                <th><label for="title"><?php esc_html_e('Name', 'daim'); ?></label></th>
                                <td>
                                    <input type="text" id="name" maxlength="100" size="30" name="name"/>
                                    <div class="help-icon"
                                         title="<?php esc_attr_e('The name of the term group.', 'daim'); ?>"></div>
                                </td>
                            </tr>

                            <?php

                            $post_types = $this->shared->get_post_types_with_ui();

                            for ($i = 1; $i <= $supported_terms; $i++) {

                                ?>

                                <!-- Post Type <?php echo $i; ?> -->
                                <tr>
                                    <th scope="row"><?php esc_html_e('Post Type', 'daim'); ?>&nbsp<?php echo $i; ?></th>
                                    <td>
                                        <select id="post-type-<?php echo $i; ?>" name="post_type_<?php echo $i; ?>"
                                                class="post-type daext-display-none" data-id="<?php echo $i; ?>">
                                            <option value="" class="default"><?php esc_html_e('None',
                                                    'daim'); ?></option>
                                            <?php

                                            foreach ($post_types as $key => $post_type) {
                                                $post_type_obj = get_post_type_object($post_type);
                                                echo '<option value="' . esc_attr(stripslashes($post_type)) . '">' . esc_html(stripslashes($post_type_obj->label)) . '</option>';
                                            }

                                            ?>
                                        </select>
                                        <div class="help-icon"
                                             title='<?php esc_attr_e('The post type for which you want to retrieve the taxonomies.',
                                                 'daim'); ?>'></div>
                                    </td>
                                </tr>

                                <!-- Taxonomy <?php echo $i; ?> -->
                                <tr>
                                    <th scope="row"><?php esc_html_e('Taxonomy', 'daim'); ?>&nbsp<?php echo $i; ?></th>
                                    <td>
                                        <select id="taxonomy-<?php echo $i; ?>" name="taxonomy_<?php echo $i; ?>"
                                                class="taxonomy daext-display-none" data-id="<?php echo $i; ?>">
                                            <option value="" class="default"><?php esc_html_e('None',
                                                    'daim'); ?></option>
                                        </select>
                                        <div class="help-icon"
                                             title='<?php esc_attr_e('The taxonomy for which you want to retrieve the terms.',
                                                 'daim'); ?>'></div>
                                    </td>
                                </tr>

                                <!-- Term <?php echo $i; ?> -->
                                <tr>
                                    <th scope="row"><?php esc_html_e('Term', 'daim'); ?>&nbsp<?php echo $i; ?></th>
                                    <td>
                                        <select id="term-<?php echo $i; ?>" name="term_<?php echo $i; ?>"
                                                class="daext-display-none">
                                            <option value="0" class="default"><?php esc_html_e('None',
                                                    'daim'); ?></option>
                                        </select>
                                        <div class="help-icon"
                                             title='<?php esc_attr_e('The term that will be compared with the ones available on the posts where the autolinks are applied.',
                                                 'daim'); ?>'></div>
                                    </td>
                                </tr>

                                <?php

                            }

                            ?>

                        </table>

                        <!-- submit button -->
                        <div class="daext-form-action">
                            <input class="button" type="submit"
                                   value="<?php esc_attr_e('Add Term Group', 'daim'); ?>">
                        </div>

                        <?php endif; ?>

                    </div>

            </form>

        </div>

    </div>

</div>

<!-- Dialog Confirm -->
<div id="dialog-confirm" title="<?php esc_attr_e('Delete the term group?', 'daim'); ?>" class="daext-display-none">
    <p><?php esc_html_e('This term group will be permanently deleted and cannot be recovered. Are you sure?',
            'daim'); ?></p>
</div>