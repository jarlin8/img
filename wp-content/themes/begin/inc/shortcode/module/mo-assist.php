<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
// 支持模块
function module_box_assist( $atts, $content = null ) {
	ob_start();
	$html = '<div class="group-assist-wrap">';
	$html .= do_shortcode( $content );
	$html .= '</div>';
	return $html;
	return ob_get_clean();
}
// [boxassist][/boxmd]
add_shortcode( 'boxassist', 'module_box_assist' );

function module_assist( $atts ) {
	extract( shortcode_atts( array(
		'title'      => '标题',
		'text'        => '',
		'ico'       => '',
		'url'    => '',
	),
	$atts ) );
	ob_start();
	?>
	<div class="group-assist-main-box">
		<div class="boxs1">
			<div class="group-assist-main ms">
				<?php if ( ! empty( $url ) ) { ?>
					<a href="<?php echo $url; ?>" rel="bookmark">
				<?php } ?>

				<div class="group-assist" <?php aos_b(); ?>>
					<div class="group-assist-content">
						<h4 class="group-assist-title gat">
							<?php if ( ! empty( $title ) ) { ?>
								<?php echo $title; ?>
							<?php } ?>
						</h4>

						<div class="group-assist-des">
							<?php if ( ! empty( $text ) ) { ?>
								<?php echo $text; ?>
							<?php } ?>
						</div>

					</div>
					<div class="group-assist-ico">
						<?php if ( ! empty( $ico ) ) { ?>
							<i class="<?php echo $ico; ?>"></i>
						<?php } ?>
					</div>
					<div class="clear"></div>
				</div>
				<?php if ( ! empty( $url ) ){ ?></a><?php } ?>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
// [assist title="标题" text="描述" ico="be be-skyatlas" url=""]
add_shortcode( 'assist', 'module_assist' );