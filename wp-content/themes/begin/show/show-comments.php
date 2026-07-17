<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php while ( have_posts() ) : the_post(); ?>
<?php if ( comments_open() || get_comments_number() ) : ?>
<div class="g-row s-comments show-white">
	<div class="g-col">
		<div class="group-title">
			<h3><?php _e( '给我留言', 'begin' ); ?></h3>
			<div class="clear"></div>
		</div>
		<div class="section-box show-comments">
			<?php comments_template( '', true ); ?>
		</div>
		<div class="clear"></div>
	</div>
</div>
<?php endif; ?>
<?php endwhile; ?>