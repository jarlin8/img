<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('post_no_margin')) { ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
<?php } else { ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
<?php } ?>

	<figure class="thumbnail">
		<?php zm_thumbnail(); ?>
		<?php if (zm_get_option('no_thumbnail_cat')) { ?><span class="cat cat-roll"><?php } else { ?><span class="cat"><?php } ?><?php zm_category(); ?></span>
	</figure>

	<header class="entry-header">
		<?php if ( get_post_meta($post->ID, 'mark', true) ) { ?>
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a><span class="t-mark">' . $mark = get_post_meta($post->ID, 'mark', true) . '</span></h2>' ); ?>
		<?php } else { ?>
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<?php } ?>
	</header>

	<div class="entry-content">
		<div class="archive-content">
			<?php begin_trim_words(); ?>
		</div>
		<div class="clear"></div>
		<span class="title-l"></span>
		<span class="entry-meta">
			<?php begin_entry_meta(); ?>
		</span>
		<div class="clear"></div>
	</div>
	<?php entry_more(); ?>
</article>