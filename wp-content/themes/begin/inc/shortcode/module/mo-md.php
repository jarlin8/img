<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
// 特色模块
function module_box_md( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'title' => '特色模块',
		'col'   => '6',
		'text'  => '',
		'img'   => '',
		'ico'   => '',
		'svg'   => '',
	),
	$atts ) );

	ob_start();

	$html = '<div class="grid-md grid-md' . $col . '">';
		$html .= '<div class="md-main">';
			$html .= do_shortcode( $content );
		$html .= '</div>';
		$html .= '<div class="clear"></div>';
	$html .= '</div>';
	return $html;
	return ob_get_clean();
}
// [boxmd col="6"][/boxmd]
add_shortcode( 'boxmd', 'module_box_md' );

function module_md( $atts ) {
	extract( shortcode_atts( array(
		'title'  => '关于本站',
		'col'    => '6',
		'text'   => '',
		'img'    => '',
		'ico'    => '',
		'svg'    => '',
	),
	$atts ) );

	ob_start();
	?>
		<div class="module-area gw-box edit-buts gw-box<?php echo $col; ?>" <?php aos_a(); ?>>
			<span class="boxs6">
				<div class="gw-main tra ms gw-main-b">
					<?php if ( ! empty( $img ) ) { ?>
						<div class="gw-img">
							<div class="img100 lazy"><div class="bgimg" style="background-image: url(<?php echo $img; ?>) !important;"></div></div>
						</div>
					<?php } ?>
		
					<div class="gw-area" <?php aos_b(); ?>>
						<?php if ( ! empty( $svg ) ) { ?>
							<div class="gw-ico"><svg class="icon" aria-hidden="true"><use xlink:href="#<?php echo $svg; ?>"></use></svg></div>
						<?php } ?>
						<?php if ( ! empty( $ico ) ) { ?>
							<div class="gw-ico"><i class="<?php echo $ico; ?>"></i></div>
						<?php } ?>
			
						<?php if ( ! empty( $title ) ) { ?>
							<h3 class="gw-title"><?php echo $title; ?></h3>
						<?php } ?>

						<?php if ( ! empty( $text) ) { ?>
							<div class="gw-content"><?php echo $text; ?></div>
						<?php } ?>
					</div>
				</div>
			</span>
		</div>

	<?php
	return ob_get_clean();
}
// [md title="标题" text="描述" col="6" ico="be be-skyatlas" svg="" img=""]
add_shortcode( 'md', 'module_md' );