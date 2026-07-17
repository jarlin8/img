<?php

if ( ! current_user_can(get_option($this->shared->get('slug') . "_wizard_menu_required_capability"))) {
    wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'daim'));
}

//Sanitization ---------------------------------------------------------------------------------------------------------
$data['result'] = isset($_GET['result']) ? sanitize_text_field($_GET['result']) : null;
$data['invalid_name'] = isset($_GET['invalid_name']) ? intval($_GET['invalid_name'], 10) : null;

?>

<!-- process data -->

<!-- output -->

<div class="wrap">

    <?php

    if (!is_null($data['invalid_name'])) {
        $process_data_message = '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__('Please enter a valid value in the "Name" field.',
                'daim') . '</p></div>';
    }

    if (!is_null($data['result']) and $data['result'] !== 'error'){
        $process_data_message = '<div class="updated settings-error notice is-dismissible below-h2"><p>' . intval($data['result'],
                10) . esc_html__(' AIL have been added.', 'daim') . '</p></div>';
    }

    ?>

    <div id="daext-header-wrapper" class="daext-clearfix">

        <h2><?php esc_html_e('Interlinks Manager - Wizard', 'daim'); ?></h2>

    </div>

    <div id="daext-menu-wrapper">

        <?php if (isset($invalid_data_message)) {
            echo $invalid_data_message;
        } ?>
        <?php if (isset($process_data_message)) {
            echo $process_data_message;
        } ?>

        <!-- table -->

        <form method="POST" action="admin.php?page=<?php echo $this->shared->get('slug'); ?>-wizard" autocomplete="off">

            <!-- Create New AIL -->

            <div class="daext-form-container">

                <div class="daext-form-title"><?php esc_html_e('Wizard', 'daim'); ?></div>

                <table class="daext-form">

                    <!-- Name -->
                    <tr valign="top">
                        <th><label for="title"><?php esc_html_e('Name', 'daim'); ?></label></th>
                        <td>
                            <input type="text" id="name" maxlength="100" size="30" name="name"/>
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

                    <!-- Data -->
                    <tr valign="top">
                        <th><label for="data"><?php esc_html_e('Data', 'daim'); ?></label></th>
                        <td id="daim-table-td">
                            <div id="daim-table"></div>
                        </td>
                    </tr>

                </table>

                <!-- submit button -->
                <div class="daext-form-action">
                    <input id="generate-autolinks" class="button" type="submit"
                           value="<?php esc_attr_e('Generate AIL', 'daim'); ?>">
                </div>

            </div>

        </form>

    </div>

</div>