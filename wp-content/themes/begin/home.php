<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! be_get_option( 'layout' ) || ( be_get_option( 'layout' ) == 'blog' ) ) {
	get_template_part( 'template/blog' );
}
if ( be_get_option( 'layout' ) == 'img' ) {
	get_template_part( 'template/grid' );
}
if ( be_get_option( 'layout' ) == 'grid' ) {
	get_template_part( 'template/grid-cat' );
}
if ( be_get_option( 'layout' ) == 'cms' ) {
	get_template_part( 'template/cms' );
}
if ( be_get_option( 'layout' ) == 'group' ) {
	get_template_part( 'template/group' );
}