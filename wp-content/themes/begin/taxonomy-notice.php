<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>

<section id="primary" class="content-area">
	<main id="main" class="be-main site-main" role="main">
		<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
		<?php if (!zm_get_option('notice_m') || (zm_get_option('notice_m') == 'notice_s')) { ?>
			<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('post ms bk shuo-site scl'); ?>>
				<div class="entry-content shuo-entry">
					<div class="meta-author-avatar shuo-avatar">
						<?php 
							if (zm_get_option('cache_avatar')) {
								echo begin_avatar( get_the_author_meta('email'), '96', '', get_the_author() );
							} else {
								echo get_avatar( get_the_author_meta('email'), '96', '', get_the_author() );
							}
						?>
						<?php 
							$author_id = get_the_author_meta( 'ID' );
							if (be_check_user_role(array('administrator'), $author_id)) {
								echo '<span class="shuo-the-role shuo-the-role1"></span>';
							}
							if (be_check_user_role(array('editor'), $author_id)) {
								echo '<span class="shuo-the-role shuo-the-role2">'. __( '编辑', 'begin' ) .'</span>';
							}
							if (be_check_user_role(array('author'), $author_id)) {
								echo '<span class="shuo-the-role shuo-the-role3">'. __( '专栏作者', 'begin' ) .'</span>';
							}
							if (be_check_user_role(array('contributor'), $author_id)) {
								echo '<span class="shuo-the-role shuo-the-role4">'. __( '自由撰稿人', 'begin' ) .'</span>';
							}
						?>
					</div>
					<div class="shuo-entry-meta">
						<span class="shuo-author">
							<?php the_author(); ?>
						</span>
						<span class="clear"></span>
						<span class="shuo-inf shuo-date"><?php time_ago( $time_type ='post' ); ?></span>
						<span class="shuo-inf shuo-time"><?php echo get_the_time('H:i:s'); ?></span>
						<span class="shuo-inf shuo-cat"><i class="be be-sort"></i><?php echo get_the_term_list( get_the_ID(), 'notice'); ?></span>
					</div>
					<div class="shuo-inf shuo-remark">
						<?php 
							$my_id = get_the_author_meta( 'ID' );
							$user_info = get_userdata( $my_id );
							if ( $my_id && $user_info->remark ) {
								echo '<span class="region-txt"><span class="dashicons dashicons-location"></span>' . $user_info->remark . '</span>';
							}
						?>
					</div>
					<div class="clear"></div>
					<div class="shuo-content">
						<?php the_content(); ?>
					</div>
					<div class="clear"></div>
				</div>
			</article>
		<?php } ?>

		<?php if (zm_get_option('notice_m') == 'notice_d') { ?>
			<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('post ms bk scl'); ?>>
				<?php 
					$content = $post->post_content;
					preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
					$n = count($strResult[1]);
					if ($n > 0) { ?>
					<figure class="thumbnail">
						<?php zm_thumbnail(); ?>
						<?php if (zm_get_option('no_thumbnail_cat')) { ?><span class="cat cat-roll"><?php } else { ?><span class="cat"><?php } ?><?php echo get_the_term_list( $post->ID,  'notice', '' ); ?></span>
					</figure>
				<?php } ?>

				<?php header_title(); ?>
					<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				</header><!-- .entry-header -->

				<div class="entry-content">
						<div class="archive-content">
							<?php begin_trim_words(); ?>
						</div>
						<div class="clear"></div>
						<?php title_l(); ?>
							<?php 
								$content = $post->post_content;
								preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
								$n = count($strResult[1]);
								if ( $n > 0 || get_post_meta(get_the_ID(), 'thumbnail', true) ) : ?>
								<span class="entry-meta lbm">
									<?php begin_entry_meta(); ?>
								</span>
							<?php else : ?>
								<span class="entry-meta-no lbm">
									<?php begin_format_meta(); ?>
								</span>
							<?php endif; ?>

					<div class="clear"></div>
				</div>

				<?php if ( ! is_single() ) : ?>
					<?php entry_more(); ?>
				<?php endif; ?>
			</article>
		<?php } ?>

		<?php endwhile; ?>

		<?php else : ?>
			<article class="post" <?php aos_a(); ?>>
				<div class="archive-content">
					<p><?php _e( '暂无文章', 'begin' ); ?></p>
				</div>
				<div class="clear"></div>
			</article>
		<?php endif; ?>

	</main><!-- .site-main -->

	<div class="pagenav-clear"><?php begin_pagenav(); ?></div>

</section><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>