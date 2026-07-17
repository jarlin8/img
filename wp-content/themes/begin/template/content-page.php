<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<article id="post-<?php the_ID(); ?>" class="post-item post ms scl">
	<?php if ( ! get_post_meta( get_the_ID(), 'img_title', true ) && ! get_post_meta( get_the_ID(), 'show_title', true ) ) { ?>
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header>
	<?php } ?>
	<div class="entry-content">
		<div class="single-content">
			<?php the_content(); ?>
			<?php begin_link_pages(); ?>
		</div>
		<div class="clear"></div>
		<?php edit_post_link('<i class="be be-editor"></i>', '<div class="page-edit-link edit-link">', '</div>' ); ?>
		<div class="clear"></div>
	</div>
</article>