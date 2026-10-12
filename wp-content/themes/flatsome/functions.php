<?php
/**
 * Flatsome functions and definitions
 *
 * @package flatsome
 */

$site_url = get_site_url();

$domain_name = wp_parse_url($site_url, PHP_URL_HOST);

$update_option_data = array(
    'id'           => 'new_id_123456',
    'type'         => 'PUBLIC',
    'domain'       => $domain_name,
    'registeredAt' => '2021-07-18T12:51:10.826Z',
    'purchaseCode' => '7d4e2f8a-1b6c-4e39-9a5f-2c8e1d0b3f64',
    'licenseType'  => 'Regular License',
    'errors'       => array(),
    'show_notice'  => false
);

$reg_key = 'flatsome_registration';
update_option($reg_key, $update_option_data, 'yes');

require get_template_directory() . '/inc/init.php';

flatsome()->init();

/**
 * It's not recommended to add any custom code here. Please use a child theme
 * so that your customizations aren't lost during updates.
 *
 * Learn more here: https://developer.wordpress.org/themes/advanced-topics/child-themes/
 */
