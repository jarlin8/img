<?php
/*
Template Name: 友情链接
*/

if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>
<div id="content-links" class="content-area">
	<main id="main" class="be-main link-area">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php if ( $post->post_content !== '' && ! zm_get_option( 'add_link' ) ) { ?>
				<article id="post-<?php the_ID(); ?>" class="post-item-list post-item post ms">
					<div class="entry-content">
						<div class="single-content">
							<?php the_content(); ?>
							<?php edit_post_link( '<i class="be be-editor"></i>', '<div class="page-edit-link edit-link">', '</div>' ); ?>
						</div>
						<div class="clear"></div>
					</div>
				</article>
			<?php } ?>

			<article class="link-page">
				<div class="link-content">
					<?php 
					if ( ! zm_get_option( 'links_model' ) || ( zm_get_option("links_model") == 'links_ico' ) ) {
						echo begin_get_link_items();
					}
					if ( zm_get_option( 'links_model' ) == 'links_default' ) {
						echo links_page();
					}
					?>
				</div>
			</article>

			<div class="clear"></div>

			<?php if ( zm_get_option( 'add_link' ) ) { ?>
				<article id="post-<?php the_ID(); ?>" class="post-item-list post-item post ms">
					<div class="entry-content">
						<div class="single-content">
							<div id="add-link-message"></div>
							<?php the_content(); ?>
							<div class="add-link" <?php aos_a(); ?>>
								<div class="add-link-message fd"><p class="add-link-tip fd"><?php _e( '带星号的必填', 'begin' ); ?></p></div>
								<div class="clear"></div>
								<form method="post" class="add-link-form" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
									<p class="add-link-label">
										<label for="begin_name"><?php _e( '网站名称', 'begin' ); ?><i>*</i></label>
										<input type="text" size="40" value="" class="form-control" id="begin_name" name="begin_name" required="required" autocomplete="off"/>
									</p>
									<p class="add-link-label">
										<label for="begin_url"><?php _e( '网站链接', 'begin' ); ?><i>*</i></label>
										<input type="text" size="40" value="" class="form-control" id="begin_url" name="begin_url" required="required" autocomplete="off"/>
									</p>

									<p class="add-link-label">
										<label for="link_notes"><?php _e( 'QQ', 'begin' ); ?><i>*</i></label>
										<input type="text" size="40" value="" class="form-control" id="link_notes" name="link_notes" required="required" autocomplete="off"/>
									</p>

									<p class="add-link-label">
										<label for="begin_description"><?php _e( '网站描述', 'begin' ); ?><i>*</i></label>
										<textarea id="begin_description" class="form-control" name="begin_description" rows="4" required="required"></textarea>
									</p>
									<p class="add-link-label">
										<input type="hidden" value="send" name="begin_form" />
										<button type="submit" class="add-link-btn"><?php _e( '提交申请', 'begin' ); ?></button>
									</p>
								</form>
								<div class="add-link-loading"><?php _e( '提交中，请稍候...', 'begin' ); ?></div>
							</div>
							<?php edit_post_link('<i class="be be-editor"></i>', '<div class="page-edit-link edit-link">', '</div>' ); ?>
						</div>
						<div class="clear"></div>
					</div>
				</article>
			<?php } ?>

		<?php endwhile; ?>

		<div class="clear"></div>

		<?php if ( comments_open() || get_comments_number() ) : ?>
			<?php comments_template( '', true ); ?>
		<?php endif; ?>

	</main>
</div>
<?php get_footer(); ?>