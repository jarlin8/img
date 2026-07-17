<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 网址
function sites_favorites() {
	global $post;
?>
<div class="sites-item<?php if ( cx_get_option( 'sites_boxs' ) ) { ?> boxs1<?php } ?>">
	<article class="post-item-list post sites-post" <?php aos_a(); ?>>
		<?php if ( cx_get_option( 'sites_adorn' ) ) { ?><div class="rec-adorn-s"></div><div class="rec-adorn-x"></div><?php } ?>
		<?php $sites_link = get_post_meta( get_the_ID(), 'sites_link', true ); ?>
		<?php $sites_url = get_post_meta( get_the_ID(), 'sites_url', true ); ?>
		<?php $sites_description = get_post_meta( get_the_ID(), 'sites_description', true ); ?>
		<?php $sites_des = get_post_meta( get_the_ID(), 'sites_des', true ); ?>
		<?php $sites_ico = get_post_meta( get_the_ID(), 'sites_ico', true ); ?>

		<?php if ( cx_get_option( 'site_link_go' ) ) { ?>
			<a class="sites-to-url" href="<?php echo esc_url( get_permalink( cx_get_option( 'site_link_go_id' ) ) ); ?>?target=<?php echo $sites_link; ?>" target="_blank"></a>
		<?php } else { ?>
			<a class="sites-to-url" href="<?php echo $sites_link; ?>" rel="external nofollow" target="_blank"></a>
		<?php } ?>

		<?php if ( cx_get_option( 'sites_ico' ) ) { ?>
			<div class="sites-ico load">
				<?php if ( get_post_meta( get_the_ID(), 'sites_ico', true ) ) { ?>
					<img class="sites-img sites-ico-custom" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" data-original="<?php echo $sites_ico; ?>" alt="<?php the_title(); ?>">
				<?php } else { ?>
					<img class="sites-img" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" data-original="<?php echo zm_get_option( 'favicon_api' ); ?><?php echo $sites_link; ?>" alt="<?php the_title(); ?>">
				<?php } ?>
			</div>
		<?php } ?>

		<h2 class="sites-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

		<div class="sites-excerpt ease">
			<?php 
				if ( ! get_post_meta( get_the_ID(), 'sites_url', true ) ) {
					if ( get_post_meta( get_the_ID(), 'sites_des', true ) ) {
						echo $sites_des;
					} elseif ( get_post_meta( get_the_ID(), 'sites_description', true ) ) {
						echo $sites_description;
					} else {
						echo $sites_link;
					}
				} else {
					if ( get_post_meta( get_the_ID(), 'sites_des', true ) ) {
						echo $sites_des;
					} else {
						echo $sites_url;
					}
				}
			?>
		</div>

		<div class="sites-link-but ease<?php if ( ! cx_get_option( 'sites_ico' ) ) { ?> sites-link-but-noico<?php } ?>">
			<div class="sites-link mo">
				<i class="be be-sort"></i>
			</div>
			<div class="sites-more"><a href="<?php the_permalink(); ?>" target="_blank" data-hover="<?php _e( '简介', 'begin' ); ?>"><i class="be be-more ri"></i></a></div>
			<div class="clear"></div>
		</div>
	</article>
</div>
<?php }