<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
// 关于我们
function module_contact( $atts ) {
	extract( shortcode_atts( array(
		'title'      => '关于本站',
		'col'        => '1',
		'des'        => '',
		'img'        => '',
		'more'       => '',
		'contact'    => '',
		'moreurl'    => '',
		'contacturl' => '',
	),
	$atts ) );

	ob_start();
	?>

	<div class="module-area section-box group-contact-wrap">
		<?php if ( $title ) { ?>
			<div class="group-title" <?php aos_b(); ?>>
				<h3><?php echo $title; ?></h3>
				<div class="clear"></div>
			</div>
				
		<?php } ?>
		<div class="group-contact be-text sanitize<?php if ( $col ) { ?> group-contact-lr<?php } ?>">
			<div class="single-content text-back group-contact-main-all" <?php aos_f(); ?>>
				<?php echo wpautop( $des ); ?>
			</div>

			<?php if ( $img ) { ?>
				<div class="group-contact-img" <?php aos_b(); ?>>
					<img alt="contact" src="<?php echo $img; ?>">
				</div>
			<?php } ?>

			<div class="clear"></div>
			<div class="group-contact-more">
				<span class="group-more" <?php aos_b(); ?>><a href="<?php echo $moreurl; ?>" rel="external nofollow"><?php echo $more; ?></a></span>
				<span class="group-phone" <?php aos_b(); ?>><a href="<?php echo  $contacturl; ?>" rel="external nofollow"><?php echo $contact; ?></a></span>
				<div class="clear"></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<?php
	return ob_get_clean();
}
// [contact title="标题" col="1" more="详细按钮" moreurl="详细链接" contact="关于按钮" contacturl="关于链接" img="图片" des="说明描述"]
add_shortcode( 'contact', 'module_contact' );