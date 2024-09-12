<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('group_tab')) { ?>
<div class="g-row g-line sort" name="<?php echo zm_get_option('group_tab_s'); ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-tabs-content ms bk">
			<ul class="group-tabs has-tabs" <?php aos_b(); ?>>
				<?php $becat = explode(',',zm_get_option('group_tab_cat_id') ); foreach ($becat as $category) { ?>
				<?php query_posts( array( 'cat' => $category) ); ?>
					<li class="tab_title"><a class="hz" href="#" id="<?php echo $category; ?>-tab"><?php single_cat_title(); ?></a></li>
				<?php } ?>
				<?php wp_reset_query(); ?>
			</ul>
			<div class="clear"></div>
			<div class="group-tabs-inside">
				<?php $becat = explode(',',zm_get_option('group_tab_cat_id') ); foreach ($becat as $category) { ?>
				<?php query_posts( array( 'cat' => $category) ); ?>
					<div id="<?php echo $category; ?>-tab-content" class="tab-content"></div>
				<?php } ?>
				<?php wp_reset_query(); ?>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php } ?>