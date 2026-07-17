<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( get_post_meta( get_the_ID(), 'header_img', true ) ) : ?>
<?php if ( is_single() || is_page() ) : ?>
<div class="header-sub header-sub-img<?php if ( get_post_meta( get_the_ID(), 'no_sidebar', true ) || ( zm_get_option('single_no_sidebar') ) ) { ?> full-header-title<?php } ?>">
	<div id="slideshow">
		<div id="slider-title" class="owl-carousel slider-title slider-current be-wol">
			<?php
				$image = get_post_meta( get_the_ID(), 'header_img', true );
				$image=explode( "\n", $image );
				foreach( $image as $key => $header_img ) {
					echo '<div class="slider-title-img"><img src="' . $header_img . '"></div>';
				}
			?>
		</div>
		<?php if ( get_post_meta( get_the_ID(), 'show_title', true ) ) { ?>
			<div class="header-title-main">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php the_title( '<h1 class="header-title">', '</h1>' ); ?>
				<?php endwhile; ?>
			</div>
		<?php } ?>
		<?php wp_reset_query(); ?>

		<div class="lazy-img ajax-owl-loading">
				<?php
					$image = get_post_meta( get_the_ID(), 'header_img', true );
					$image = explode( "\n", $image );
					foreach ( $image as $key => $header_img ) {
						echo '<div class="slider-title-img"><img src="' . $header_img . '"></div>';
						break;
					}
				?>
			<?php wp_reset_query(); ?>
			<div class="clear"></div>
		</div>

		<div class="clear"></div>
	</div>
	<?php subjoin_menu(); ?>
	<?php be_help( $text = '编辑时，在下面“标题幻灯”面板，上传添加图片' ); ?>
</div>
<?php endif; ?>
<?php endif; ?>

<?php if ( get_post_meta( get_the_ID(), 'header_bg', true) ) { ?>
<?php if ( is_single() || is_page() ) : ?>
<?php if ( is_page() ) { ?>
	<div class="header-sub<?php if ( get_post_meta( get_the_ID(), 'no_sidebar', true ) ) { ?> full-header-title<?php } ?>">
<?php } else { ?>
	<div class="header-sub<?php if ( get_post_meta( get_the_ID(), 'no_sidebar', true ) || ( zm_get_option('single_no_sidebar') ) ) { ?> full-header-title<?php } ?>">
<?php }?>
	<div class="cat-des"<?php if ( zm_get_option( 'title_img_h' ) ) { ?> style="max-height: <?php echo zm_get_option( 'title_img_height' ); ?>px;"<?php } ?> <?php aos_a(); ?>>
		<?php
		$image = get_post_meta( get_the_ID(), 'header_bg', true );
		$image=explode( "\n",$image );
		foreach( $image as $key => $header_bg ) { ?>
			<div class="cat-des-img<?php if ( zm_get_option( 'cat_des_img_zoom' ) ) { ?> cat-des-img-zoom<?php } ?>"><img src="<?php echo $header_bg;?>"><div class="clear"></div></div>
		<?php }?>
		<?php if ( get_post_meta( get_the_ID(), 'img_title', true ) ) { ?>
			<div class="header-title-main">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php the_title( '<h1 class="header-title fadeInUp animated">', '</h1>' ); ?>
				<?php endwhile; ?>
			</div>
		<?php } ?>
		<?php wp_reset_query(); ?>
		<div class="clear"></div>
	</div>
	<?php subjoin_menu(); ?>
	<?php be_help( $text = '编辑时，在下面“标题图片”面板，上传添加图片' ); ?>
</div>
<?php endif; ?>
<?php }
function subjoin_menu() { ?>
	<?php if ( zm_get_option( 'subjoin_menu' ) ) { ?>
	<?php if ( get_post_meta( get_the_ID(), 'header_bg', true ) || get_post_meta( get_the_ID(), 'header_img', true ) ) { ?>
	<nav class="submenu-nav submenu-nav-bg">
		<?php
			wp_nav_menu( array(
				'theme_location' => 'submenu',
				'menu_class'     => 'submenu',
				'fallback_cb'    => 'assign'
			) );
		?>
		<div class="clear"></div>
	</nav>
	<?php } ?>
	<?php } ?>
<?php }
?>