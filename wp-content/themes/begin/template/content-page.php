<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( is_single() ) : ?>
<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('ms bk da'); ?>>
<?php else : ?>
<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('post ms bk da scl'); ?>>
<?php endif; ?>
	<?php if ( get_post_meta(get_the_ID(), 'header_img', true) || get_post_meta(get_the_ID(), 'header_bg', true) ) { ?>
	<?php } else { ?>
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