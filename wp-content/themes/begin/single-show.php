<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/show.css" />
<div class="container show-hui">
	<?php get_template_part( '/show/show-slider' ); ?>
	<div id="group-section">
		<?php get_template_part( '/show/show-content' ); ?>
		<?php get_template_part( '/show/show-contact' ); ?>
		<?php get_template_part( '/show/show-comments' ); ?>
		<?php get_template_part( '/show/related-show' ); ?>
	</div>
	<div class="clear"></div>
</div>
<?php get_footer(); ?>