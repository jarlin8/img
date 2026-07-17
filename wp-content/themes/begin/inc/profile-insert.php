<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
// 信息显示
function add_submit_info( $content ) {
	if ( ! is_feed() && ! is_home() && is_singular( 'bulletin' ) && is_main_query() ) {
		$str = submit_info();
		$content .= $str;
	}
	return $content;
}
add_filter( 'the_content', 'add_submit_info' );

function submit_info() {
	global $wpdb, $post;
?>
<?php if ( get_post_meta( get_the_ID(), 'profile_name', true ) ) : ?>
	<div class="submit-info-main">
		<div class="submit-info">
			<p><strong><?php echo cx_get_option('message_name'); ?></strong><span><?php echo get_post_meta( get_the_ID(), 'profile_name', true ); ?></span></p>
			<?php 
				$form = ( array ) cx_get_option( 'message_form_add' );
				foreach ( $form as $items ) {
			?>
			
				<?php if ( empty( $items['message_select'] ) ) { ?>
					<p><strong><?php echo $items['message_title']; ?></strong><span><?php echo get_post_meta( get_the_ID(), 'profile_' . $items['message_number'], true ); ?></span></p>
				<?php } else { ?>
					<p><strong><?php echo $items['message_select']; ?></strong><span><?php echo get_post_meta( get_the_ID(), 'profile_' . $items['message_number'], true ); ?></span></p>
				<?php } ?>

			<?php } ?>
		</div>
		<p class="info-note"><strong><?php _e( '备注：', 'begin' ); ?></strong></p>
		<?php if ( ! get_the_content() ) { ?>
			<p><?php _e( '无', 'begin' ); ?></p>
		<?php } ?>
	</div>
<?php endif; ?>
<?php }