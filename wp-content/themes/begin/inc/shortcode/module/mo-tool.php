<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
// 工具模块
function module_box_tool( $atts, $content = null ) {
	ob_start();
	$html = '<div class="module-area group-tool-box">';
	$html .= do_shortcode( $content );
	$html .= '<div class="clear"></div>';
	$html .= '</div>';
	return $html;
	return ob_get_clean();
}
// [boxtool][/boxtool]
add_shortcode( 'boxtool', 'module_box_tool' );

function module_tool( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'title' => '工具模块',
		'col'   => '5',
		'text'  => '',
		'img'   => '',
		'ico'   => '',
		'svg'   => '',
		'btn'   => '',
		'btnurl'=> '',
		'text'  => '',
	),
	$atts ) );

	ob_start();
	?>
	<div class="sx4 edit-buts stool-<?php echo $col; ?>">
		<span class="boxs6">
			<div class="group-tool <?php if ( ! empty( $btn ) ) { ?> group-tool-have<?php } ?>">
				<div class="group-tool-img">
					<div class="group-tool-img-top"></div>
					<div class="img40 lazy"><div class="bgimg" style="background-image: url(<?php echo $img; ?>) !important;"></div></div>
				</div>

				<div class="group-tool-pu">
					<div class="group-tool-ico" <?php aos_b(); ?>>
						<?php if ( ! empty( $svg ) ) { ?>
							<svg class="icon" aria-hidden="true"><use xlink:href="#<?php echo $svg; ?>"></use></svg>
						<?php } ?>
						<?php if ( ! empty( $ico ) ) { ?>
							<i class="<?php echo $ico; ?>"></i>
						<?php } ?>
					</div>

					<?php if ( ! empty( $title ) ) { ?>
						<h3 class="group-tool-title" <?php aos_b(); ?>><?php echo $title; ?></h3>
					<?php } ?>

					<?php if ( ! empty( $text ) ) { ?>
						<p class="group-tool-p group-tool-c group-tool-h">
							<?php echo $text; ?>
						</p>
					<?php } ?>
				</div>
				<?php if ( ! empty( $btn ) ) { ?>
					<div class="group-tool-link"><a href="<?php echo $btnurl; ?>" target="_blank" rel="external nofollow" data-hover="<?php echo $btn; ?>"><span><i class="be be-more"></i></span></a></div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
// [tool title="标题" text="描述" col="5" ico="be be-skyatlas" svg="" btn="按钮" btnurl="链接" img="图片"]
add_shortcode( 'tool', 'module_tool' );