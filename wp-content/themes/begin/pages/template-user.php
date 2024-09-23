<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/*
Template Name: 用户中心
*/
?>
<?php if ( is_user_logged_in() ) { ?>
<?php get_header(); ?>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/user-center.css" />
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/responsive-tabs.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($){
	$('#usertab').easyResponsiveTabs({
		type: 'vertical',
		width: 'auto',
		fit: true,
		closed: 'accordion',
		tabidentify: 'hor_1',
		activate: function(event) {
			var $tab = $(this);
			var $info = $('#nested-tabInfo2');
			var $name = $('span', $info);
			$name.text($tab.text());
			$info.show();
		}
	});
});
</script>

<div id="personal" class="personal">
	<div class="personal-top dahxy">
		<?php global $current_user, $userdata, $user_identity; wp_get_current_user();
			echo '<div class="personal-img load">';
			if ( get_option( 'show_avatars' ) ) {
				if ( zm_get_option( 'cache_avatar' ) ){
					echo begin_avatar($userdata->user_email, 96, '', $user_identity);
				} else {
					echo be_avatar_user();
				}
			} else {
				echo '<i class="be be-timerauto"></i>';
			}
			echo '</div>';
			echo '<div class="personal-name"><span class="personal-name-text">' . __('欢迎回来！', 'begin' ) . '</strong>' . '</span>';
			echo '' . $current_user->display_name . "\n";
			echo '</div>';
		?>
		<?php edit_post_link('<i class="be be-editor"></i>', '<div class="user-edit-link">', '</div>' ); ?>
		<div class="clear"></div>
	</div>
	<div id="container">
		<div id="usertab">
			<ul class="resp-tabs-list hor_1 resp-tab-active">
				<li class="myli"><i class="cx cx-haibao"></i><?php _e( '我的信息', 'begin' ); ?></li>

				<?php if ( class_exists( 'WShop' ) ) { ?>
					<li><i class="be be-clipboard"></i><?php _e( '我的订单', 'begin' ); ?></li>
				<?php } ?>
				<?php if ( class_exists( 'WShop_Membership' ) ) { ?>
					<li><i class="be be-star"></i><?php _e( '购买VIP', 'begin' ); ?></li>
				<?php } ?>
				<li><i class="be be-file"></i><?php _e( '我的文章', 'begin' ); ?></li>
				<li><i class="be be-speechbubble"></i><?php _e( '我的评论', 'begin' ); ?></li>

				<?php if (zm_get_option('shar_favorite')) { ?>
					<li><i class="be be-favoriteoutline"></i><?php _e( '我的收藏', 'begin' ); ?></li>
				<?php } ?>
				<?php if ( !zm_get_option('tou_url') == '' ) { ?>
				<li class="tou-url"><a href="<?php echo get_permalink( zm_get_option( 'tou_url' ) ); ?>" target="_blank"><i class="be be-editor"></i><?php echo zm_get_option( 'tou_txt' ); ?></a></li>
				<?php } ?>
			</ul>

			<div class="resp-tabs-container hor_1">

				<div>
					<h4><?php _e( '我的信息', 'begin' ); ?></h4>
					<div class="my-user">
						<?php my_inf(); ?>
						<?php my_data(); ?>
						<div class="user-content">
							<?php the_content(); ?>
						</div>
						<?php get_template_part( 'inc/my-data' ); ?>
					</div>
					<div class="clear"></div>
				</div>

				<?php if ( class_exists( 'WShop' ) ) { ?>
					<div>
						<h4><?php _e( '我的订单', 'begin' ); ?></h4>
						<div class="my-user"><?php get_template_part( 'inc/my-order' ); ?></div>
						<div class="clear"></div>
					</div>
				<?php } ?>

				<?php if ( class_exists( 'WShop_Membership' ) ) { ?>
					<div>
						<h4><?php _e( '购买VIP', 'begin' ); ?></h4>
						<div class="my-user"><?php get_template_part( 'inc/my-vip' ); ?></div>
						<div class="clear"></div>
					</div>
				<?php } ?>

				<div>
					<h4><?php _e( '我的文章', 'begin' ); ?><span class="m-number"><?php echo count_user_posts( $current_user->ID, array( 'post', 'bulletin', 'picture', 'video', 'tao' ), false ); ?><span></h4>
					<div class="my-user"><?php my_post(); ?></div>
				</div>

				<div>
					<h4><?php _e( '我的评论', 'begin' ); ?><span class="m-number"><?php echo $comments = get_comments( array( 'user_id' => $current_user->ID, 'count' => true ) ); ?><span></h4>
					<div class="my-user"><?php my_comment(); ?></div>
				</div>

				<?php if ( zm_get_option( 'shar_favorite' ) ) { ?>
					<div>
						<h4><?php _e( '我的收藏', 'begin' ); ?></h4>
							<div class="my-user"><?php my_favorite(); ?></div>
						<div class="clear"></div>
					</div>
				<?php } ?>

				<?php if ( ! zm_get_option('tou_url') == '' ) { ?>
					<div>
						<h4><?php echo zm_get_option( 'tou_txt' ); ?></h4>
							<div class="my-user"><?php my_tou(); ?></div>
						<div class="clear"></div>
					</div>
				<?php } ?>

			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>


<?php get_footer(); ?>
<?php } else {
	wp_redirect( home_url() );
	exit;
} ?>