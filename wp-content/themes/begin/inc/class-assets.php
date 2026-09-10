<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 文字截断
function begin_strimwidth( $str, $start, $width, $trimmarker ){
	$output = preg_replace( '/^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$start.'}((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$width.'}).*/s','\1',$str );
	return $output . $trimmarker;
}

// begin_trim_words()
function begin_trim_words() {
	if ( has_excerpt('') ) {
		if ( get_bloginfo( 'language' ) === 'en-US' ) {
			echo wp_trim_words( get_the_excerpt(), zm_get_option( 'en_word_n' ), '...' );
		} else {
			echo wp_trim_words( get_the_excerpt(), zm_get_option( 'word_n' ), '...' );
		}
	} else {
		$content = get_the_content();
		$content = strip_shortcodes( $content );
		if ( get_bloginfo( 'language' ) === 'en-US' ) {
			echo begin_strimwidth( strip_tags( $content), 0, zm_get_option('en_words_n' ), '...' );
		} else {
			echo wp_trim_words( $content, zm_get_option( 'words_n' ), '...' );
		}
	}
}

function begin_primary_class() {
	global $wpdb, $post;
?>
	<div id="<?php if ( get_post_meta(get_the_ID(), 'sidebar_l', true) ) { ?>primary-l<?php } else { ?>primary<?php } ?>" class="content-area<?php if ( get_post_meta( get_the_ID(), 'no_sidebar', true ) || ( zm_get_option('single_no_sidebar') ) ) { ?> no-sidebar<?php } ?>">
<?php }

function begin_abstract() {
	global $wpdb, $post;
?>
	<?php if ( has_excerpt() ) { ?><span class="abstract<?php if ( get_post_meta(get_the_ID(), 'no_abstract', true) ) : ?> no_abstract<?php endif; ?>"><fieldset><legend><?php _e( '摘要', 'begin' ); ?></legend><?php echo wp_trim_words( get_the_excerpt(), zm_get_option( 'excerpt_n' ), '...' ); ?><div class="clear"></div></fieldset></span><?php }?>
<?php }

function bedown_show() {
	if ( zm_get_option( 'be_down_show' ) && ! get_post_meta( get_the_ID(), 'ed_down_start', true ) ) {
		if ( ! get_post_meta( get_the_ID(), 'down_start', true ) && get_post_meta( get_the_ID(), 'start_down', true ) || get_post_meta( get_the_ID(), 'down_url_free', true ) ){
			if ( function_exists( 'epd_assets_vip' ) ) {
				echo '<fieldset class="down-content-show erphpdown" id="erphpdown"><legend>资源下载</legend>';
				echo be_erphpdown_show();
				echo '</fieldset>';
			}
		}
	}
}

// 固定内容
function logic_notice() {
	if ( zm_get_option( 'logic_notice_enable' ) ) {
		$logic_notice = ( array ) zm_get_option( 'logic_notice' );
		foreach ( $logic_notice as $items ) {
			if ( ! empty( $items['logic_notice_id'] ) ) {
  				$cat_ids  = explode( ',', $items['logic_notice_id'] );
				$category = has_term( $cat_ids, 'category' );
				$taobao   = has_term( $cat_ids, 'taobao' );
				$products = has_term( $cat_ids, 'products' );

				if ( $taobao || $products || $category ) {
					if ( empty( $items['logic_inf_border'] ) ) {
						echo '<div class="logic-post-inf betip">';
					} else {
						echo '<div class="logic-post-inf logic-inf-border betip">';
					}

					if ( ! empty( $items['logic_notice_txt'] ) ) {
						echo '<p>';
						echo $items['logic_notice_txt'];
						echo '</p>';
					}

					if ( ! empty( $items['logic_notice_visual'] ) ) {
						echo '<p>';
						echo $items['logic_notice_visual'];
						echo '</p>';
					}
					be_help( $text = '主题选项 → 基本设置 → 固定信息 → 按分类显示' );
					echo '</div>';
				}
			}
		}
	}
}

// 正文
function content_support_general() { ?>
	<?php if ( zm_get_option( 'all_more' ) && !get_post_meta( get_the_ID(), 'not_more', true ) ) { ?><?php all_content(); ?><?php } ?>
	<?php if ( zm_get_option( 'begin_today' ) && !get_post_meta( get_the_ID(), 'no_today', true ) ) { ?><?php echo begin_today(); ?><?php } ?>
	<?php begin_link_pages(); ?>
	<?php relat_post(); ?>
	<?php if ( ! zm_get_option( 'be_like_content' ) || ( wp_is_mobile() ) ) { ?>
		<?php be_like(); ?>
	<?php } ?>
	<?php if ( zm_get_option( 'single_weixin' ) ) { ?>
		<?php get_template_part( 'template/weixin' ); ?>
	<?php } ?>

	<div class="content-empty"></div>
	<?php get_template_part( 'ad/ads', 'single-b' ); ?>
	<footer class="single-footer">
		<?php begin_single_cat(); ?>
	</footer>
<?php }

function content_support() {
	global $wpdb, $post;
?>
	<?php echo bedown_show(); ?>
	<?php if ( zm_get_option( 'copyright_info' ) ) { ?>
		<div class="copyright-post betip" >
			<?php if ( get_post_meta( get_the_ID(), 'post_info', true ) ) { ?>
				<?php echo get_post_meta( get_the_ID(), 'post_info', true ); ?>
			<?php } else { ?>
				<?php echo wpautop( zm_get_option( 'copyright_content' ) ); ?>
			<?php } ?>
			<?php be_help( $text = '主题选项 → 基本设置 → 文章设置 → 固定信息 → 通用固定信息' ); ?>
		</div>
		<div class="clear"></div>
	<?php } ?>
	<?php echo content_support_general(); ?>
<?php }

function content_support_down() {
	global $wpdb, $post;
?>
	<?php echo bedown_show(); ?>
	<?php if ( zm_get_option( 'copyright_down_info' ) ) { ?>
		<div class="copyright-post betip" >
			<?php echo wpautop( zm_get_option( 'copyright_content_down' ) ); ?>
			<?php be_help( $text = '主题选项 → 固定信息 → 下载模板信息' ); ?>
		</div>
		<div class="clear"></div>
	<?php } ?>
	<?php echo content_support_general(); ?>
<?php }

function content_support_vip() {
	global $wpdb, $post;
?>
	<?php echo bedown_show(); ?>
	<?php if ( zm_get_option( 'all_more' ) && !get_post_meta( get_the_ID(), 'not_more', true ) ) { ?><?php all_content(); ?><?php } ?>
	<?php begin_link_pages(); ?>
	<?php if ( ! zm_get_option( 'be_like_content' ) || ( wp_is_mobile() ) ) { ?>
		<?php be_like(); ?>
	<?php } ?>
	<?php if ( zm_get_option( 'single_weixin' ) ) { ?>
		<?php get_template_part( 'template/weixin' ); ?>
	<?php } ?>

	<div class="content-empty"></div>
	<?php get_template_part( 'ad/ads', 'single-b' ); ?>
	<footer class="single-footer">
		<?php begin_single_cat(); ?>
	</footer>
<?php }

function header_title() { ?>
<header class="entry-header<?php if (zm_get_option('title_c')) { ?> entry-header-c<?php } ?>">
<?php }

// 阴影判断
function boxsstart() {
	if ( ! zm_get_option( 'post_no_margin' ) ) {
		echo '<div class="boxs5 scl">';
	}
}
function boxsend() {
	if ( ! zm_get_option( 'post_no_margin' ) ) {
		if ( ! is_single() ) {
			echo '</div>';
		}
	}
}