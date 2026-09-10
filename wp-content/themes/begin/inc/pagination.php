<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 分页
if ( zm_get_option( 'input_number' ) && ! zm_get_option( 'no_pagination' ) ) {
	function page_nav_form() {
		global $wp_query;
		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		$page_max_num = $wp_query->max_num_pages;
		if ( $page_max_num > 1 ) :
	?>
		<form class="page-nav-form" action="<?php $_SERVER['REQUEST_URI']; ?>" autocomplete="off" method="get">
			<input class="input-number" type="number" min="1" max="<?php echo $page_max_num; ?>" onblur="if (this.value==''){this.value='<?php echo $paged; ?>';}" onfocus="if (this.value=='<?php echo $paged; ?>'){this.value='';}" value="<?php echo $paged; ?>" name="paged" />
			<?php if ( is_search() ) { ?>
				<input type="hidden" id="s" name="s" value="<?php echo get_search_query(); ?>" />
			<?php } ?>
			<div class="page-button-box"><input class="page-button<?php echo cur(); ?>" value="" type="submit"></div>
		</form>
		<?php
			if ( $paged > 1 ) :
			echo '<span class="max-num">';
			echo $wp_query->max_num_pages;
			echo '</span>';
			endif;
		?>
	<?php endif;
	}
}

function begin_pagenav() {
	if ( is_paged() ) {
		echo '<div class="clear"></div>';
	}

	if ( zm_get_option( 'turn_small' ) ) {
		$turn = 'turn-small';
	} else {
		$turn = 'turn-normal';
	}

	if ( zm_get_option( 'no_pagination' ) ) {
		$navno = ' pagenav-no';
	} else {
		$navno = '';
	}

	echo '<div class="turn betip ' . $turn . $navno .'">';

	if ( zm_get_option('input_number' ) && ! zm_get_option( 'no_pagination' ) ) {
		echo page_nav_form();
	}

	if ( zm_get_option( 'infinite_post' ) && ! zm_get_option( 'no_pagination' ) ) {
		global $wp_query;
		if ( $wp_query->max_num_pages > 1 ) : ?>
			<nav id="nav-below">
				<div class="nav-next"><?php previous_posts_link(''); ?></div>
				<div class="nav-previous"><?php next_posts_link(''); ?></div>
			</nav>
		<?php endif;
	}
	if ( ! zm_get_option( 'no_pagination' ) ) {
		if ( ! is_paged() ) {
			the_posts_pagination( array(
				'mid_size'           => zm_get_option( 'first_mid_size' ),
				'prev_text'          => '<i class="be be-arrowleft"></i>',
				'next_text'          => '<i class="be be-arrowright"></i>',
			) );
		} else {
			the_posts_pagination( array(
				'mid_size'           => zm_get_option( 'mid_size' ),
				'prev_text'          => '<i class="be be-arrowleft"></i>',
				'next_text'          => '<i class="be be-arrowright"></i>',
			) );
		}
	} else {
		echo '<nav class="no-pagination"><i class="be be-more"></i></nav>';
	}
	be_help( $text = '主题选项 → 基本设置 → 页号显示' );
	echo '<div class="clear"></div></div>';
}