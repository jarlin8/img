<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'build_content' ) ) {
	if ( ! be_build( get_the_ID(), 'content_bg' ) || ( be_build( get_the_ID(), 'content_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'content_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'content_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-scheme<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<main id="main" class="be-main site-main<?php if (zm_get_option('p_first') ) { ?> p-em<?php } ?><?php if (get_post_meta(get_the_ID(), 'sub_section', true) ) { ?> sub-h<?php } ?>" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" class="post-item">
					<div class="entry-content">
						<div class="single-content">
							<?php the_content(); ?>
							<?php begin_link_pages(); ?>
						</div>
						<div class="clear"></div>
					</div>
				</article>
			<?php endwhile; ?>
		</main>
		<?php bu_help( $text = '正文', $number = 'build_content_s' ); ?>
	</div>
</div>
<?php } ?>