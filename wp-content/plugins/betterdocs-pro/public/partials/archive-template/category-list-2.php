<?php
/**
 * Template archive docs
 *
 * @link       https://wpdeveloper.net
 * @since      1.0.0
 *
 * @package    BetterDocs
 * @subpackage BetterDocs/public
 */

get_header(); 

?>
<div class="betterdocs-wraper betterdocs-main-wraper">
	<?php 
	$live_search = BetterDocs_DB::get_settings('live_search');
	if($live_search == 1){
	?>
	<div class="betterdocs-search-form-wrap cat-layout-4">
		<?php echo do_shortcode( '[betterdocs_search_form]' ); ?>
	</div><!-- .betterdocs-search-form-wrap -->
	<?php } ?>
	<div class="betterdocs-archive-wrap betterdocs-archive-main cat-layout-4">
		<?php

			if ( is_tax( 'knowledge_base' ) && BetterDocs_Multiple_Kb::$enable == 1 ) {
				echo do_shortcode( '[betterdocs_category_grid_2 multiple_knowledge_base=true]' );
			} else {
				echo do_shortcode( '[betterdocs_category_grid_2]' );
			}

		?>
	</div><!-- .betterdocs-archive-wrap -->

</div><!-- .betterdocs-wraper -->

<?php
get_footer();
