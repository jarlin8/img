<?php

if ( ! current_user_can('manage_options')) {
    wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'daim'));
}

?>

<!-- output -->

<div class="wrap">

    <h2><?php esc_html_e('Interlinks Manager - Help', 'daim'); ?></h2>

    <div id="daext-menu-wrapper">

        <p><?php esc_html_e('Visit the resources below to find your answers or to ask questions directly to the plugin developers.',
                'daim'); ?></p>
        <ul>
            <li><a href="https://daext.com/doc/interlinks-manager/"><?php esc_html_e('Plugin Documentation', 'daim'); ?></a></li>
            <li><a href="https://daext.com/support/"><?php esc_html_e('Support Conditions', 'daim'); ?></li>
            <li><a href="https://daext.com"><?php esc_html_e('Developer Website', 'daim'); ?></a></li>
        </ul>
        <p>

    </div>

</div>