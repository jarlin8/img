<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( in_category( explode( ',',zm_get_option( 'single_layout_qa' ) ) ) ) {
	get_template_part( 'single-beqa' );
} else {
	get_template_part( 'single-default' );
}
?>