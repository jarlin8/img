<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
// 展望模块
function module_outlook($atts, $content=null) {
	extract( shortcode_atts( array(
		'title' => '标题',
		'des'   => '内容',
		'img'   => '图片',
		'btn'   => '按钮',
		'url'   => '链接',
		'more'  => '按钮',
		'link'  => '链接',
	),
	$atts ) );
	ob_start();
	?>
	<div class="module-area group-outlook-bg g-row g-line" style="background: url('<?php echo $img; ?>') no-repeat fixed center / cover;" <?php aos(); ?>>
		<div class="group-outlook-rgb">
			<div class="group-outlook-dec"></div>
			<div class="g-col">
				<div class="group-outlook-box">
					<div class="group-outlook-title" <?php aos_b(); ?>>
						<h3><?php echo $title; ?></h3>
						<div class="clear"></div>
					</div>

					<div class="group-outlook-content text-back be-text" <?php aos_b(); ?>>
						<?php echo $des; ?>
					</div>

					<?php if ( ! empty( $atts['btn'] ) ) { ?>
						<div class="group-outlook-more" <?php aos_b(); ?>>
							<a href="<?php echo $url; ?>" rel="external nofollow"><?php echo $btn; ?></a>
							<?php if ( ! empty( $atts['more'] ) ) { ?>
								<a class="group-outlook-btn" href="<?php echo $link; ?>" target="_blank" rel="external nofollow"><?php echo $more; ?></a>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php waves(); ?>
	</div>
	<?php
	return ob_get_clean();
}
// [outlook title=标题 des=描述 img=背景图片 btn=按钮文字 url=按钮链接 more=按钮文字 link=按钮链接]
add_shortcode( 'outlook', 'module_outlook' );