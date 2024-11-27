<?php
/*
Template Name: 链接跳转
*/

if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

$targetUrl = isset( $_GET['target'] ) ? esc_url_raw( $_GET['target'] ) : '';
if ( ! filter_var( $targetUrl, FILTER_VALIDATE_URL ) ) {
	wp_redirect( home_url() );
	exit();
}
?>

<div id="content-links" class="content-area">
	<main id="main" class="be-main link-area">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" class="post-item-list post-item post ms">
				<div class="entry-content">
					<div class="single-content comment-link-go">
						<?php if ( $post->post_content == '' ) { ?>
							<div class="comment-link-go-title"><?php _e( '您即将访问外部网站，是否继续？', 'begin' ); ?></div>
						<?php } else { ?>
							<?php the_content(); ?>
						<?php } ?>

						<a class="confirm-btn confirm-yes" href="<?php echo esc_url( $targetUrl ); ?>" rel="nofollow"><?php _e( '继续', 'begin' ); ?></a>
						<?php if ( wp_is_mobile() ) { ?>
							<a class="confirm-btn confirm-back" href="#"><?php _e( '返回', 'begin' ); ?></a>
						<?php } else { ?>
							<a class="confirm-btn confirm-no" href="#"><?php _e( '取消', 'begin' ); ?></a>
						<?php } ?>
					</div>
					<?php edit_post_link('<i class="be be-editor"></i>', '<div class="page-edit-link edit-link" style="position: absolute;bottom: 15px;right: 15px;">', '</div>' ); ?>
					<script>
					document.addEventListener('DOMContentLoaded',
					function() {
						var urlParams = new URLSearchParams(window.location.search);
						var targetUrl = urlParams.get('target');

						if (targetUrl) {
							var confirmYesElement = document.querySelector('.confirm-yes');
							if (confirmYesElement) {
								confirmYesElement.href = targetUrl;
							}
						}

						var confirmNoElement = document.querySelector('.confirm-no');
						if (confirmNoElement) {
							confirmNoElement.addEventListener('click',
							function(event) {
								event.preventDefault();
								window.close();
							});
						}

						var confirmBackElement = document.querySelector('.confirm-back');
						if (confirmBackElement) {
							confirmBackElement.addEventListener('click',
							function(event) {
								event.preventDefault();
								window.history.back();
							});
						}
					});
					</script>
					<div class="clear"></div>
				</div>
			</article>
		<?php endwhile; ?>
	</main>
</div>

<?php get_footer(); ?>