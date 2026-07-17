<?php

if ( ! current_user_can(get_option($this->shared->get('slug') . "_export_menu_required_capability"))) {
    wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'daim'));
}

?>

<!-- output -->

<div class="wrap">

    <h2><?php esc_html_e('Interlinks Manager - Export', 'daim'); ?></h2>

    <div id="daext-menu-wrapper">

        <p><?php esc_html_e('Click the Export button to generate an XML file that includes AIL, categories and term groups.',
                'daim'); ?></p>

        <!-- the data sent through this form are handled by the export_xml_controller() method called with the WordPress init action -->
        <form method="POST" action="admin.php?page=daim-export">

            <div class="daext-widget-submit">
                <input name="daim_export" class="button button-primary" type="submit"
                       value="<?php esc_attr_e('Export',
                           'daim'); ?>" <?php if ( ! $this->shared->exportable_data_exists()) {
                    echo 'disabled="disabled"';
                } ?>>
            </div>

        </form>

    </div>

</div>