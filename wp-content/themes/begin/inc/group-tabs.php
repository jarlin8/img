<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Ajax_group_tab {
	public function __construct() {
		add_action('wp_ajax_group-tabs-content', array(&$this, 'ajax_group_tab_content'));
		add_action('wp_ajax_nopriv_group-tabs-content', array(&$this, 'ajax_group_tab_content'));
	}

	function ajax_group_tab_content() {
		$tab = $_POST['tab'];
		$page = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : '';
		if ($page < 1) $page = 1;

		$becat = explode(',',zm_get_option('group_tab_cat_id') ); foreach ($becat as $category) {
			switch ($tab) {
				case $category:
					?>
					<ul>
						<?php
						$recent = new WP_Query('showposts='.zm_get_option('group_tab_n').'&cat=' . $category . '&orderby=post_date&order=desc&post_status=publish&paged='. $page);
						$last_page = $recent->max_num_pages;
						while ($recent->have_posts()) : $recent->the_post();
						?>
						<div class="xl4 xm4 stab-<?php echo zm_get_option('stab_f'); ?>">
							<div id="post-<?php the_ID(); ?>" class="picture bk">
								<figure class="picture-cms-img">
									<?php zm_thumbnail(); ?>
								</figure>
								<?php the_title( sprintf( '<h2><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
								<?php if ( zm_get_option('group_tab_meta')) { ?><?php group_tab_meta(); ?><?php } ?>
							</div>
						</div>
						<?php endwhile; wp_reset_query(); ?>
					</ul>
					<div class="clear"></div>
					<?php $this->tab_pagination($page, $last_page); ?>
					<div class="tabs-more">
						<a href="<?php echo get_category_link($category); ?>" title="<?php _e( '更多', 'begin' ); ?>"><i class="be be-more"></i></a>	
					</div>
					<?php
				break;
			}
		}
		die();
	}

	function tab_pagination($page, $last_page) {
		?>
		<div class="tab-pagination da">
			<?php if ($page != $last_page) : ?>
				<a href="#" class="next"></a>
			<?php else : ?>
				<span>&nbsp;</span>
			<?php endif; ?>
			<?php if ($page > 1) : ?>
				<a href="#" class="previous"></a>
			<?php endif; ?>
		</div>
		<input type="hidden" class="page_num" name="page_num" value="<?php echo $page; ?>" />
		<?php
	}
}

function group_tab_meta() { ?>
<div class="group-tab-meta">
		<div class="group-date"><?php time_ago( $time_type ='post' ); ?></div>
		<?php views_group(); ?>
		<div class="clear"></div>
	</div>
</div>
<?php }

function add_Ajax_group_tab() {
	new Ajax_group_tab();
}
add_action( 'init', 'add_Ajax_group_tab');