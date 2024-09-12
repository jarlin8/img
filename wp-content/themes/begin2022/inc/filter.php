<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="filter-box bk da ms" <?php aos_a(); ?>>
	<div class="filter-t"><i class="be be-sort"></i><span><?php echo zm_get_option('filter_t'); ?></span></div>
		<?php if (zm_get_option('filters_hidden')) { ?><div class="filter-box-main filter-box-main-h"><?php } else { ?><div class="filter-box-main"><?php } ?>
		<?php if (zm_get_option('filters_cat')) { ?>
		<div class="filter-main">
			<span class="filtertag" id="filtercat"<?php global $cat; if($cat!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($cat)))).'"';}?>>
				<span class="filter-name"><?php _e( '分类', 'begin' ); ?></span>
					<?php if(!$cat!='') { ?>
						<a class="filter-tag filter-all filter-on" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } else { ?>
						<a class="filter-tag filter-all" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } ?>

					<?php $display_categories = explode(',',zm_get_option('filters_cat_id') ); foreach ($display_categories as $category) { ?>
					<?php query_posts( array( 'cat' => $category) ); ?>
						<?php if($cat==$category) { ?>
							<a class="filter-tag filter-on" data="<?php echo $category; ?>"><?php single_cat_title(); ?></a>
						<?php } else { ?>
							<a class="filter-tag" data="<?php echo $category; ?>"><?php single_cat_title(); ?></a>
						<?php } ?>
					<?php wp_reset_query(); ?>
				<?php } ?>
			</span>
		</div>
		<div class="clear"></div>
		<?php } ?>

		<?php if (zm_get_option('filters_a')) { ?>
			<div class="filter-main">
				<span class="filtertag" id="filtersa"<?php global $filtersa; if($filtersa!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($filtersa)))).'"';}?>>
					<span class="filter-name"><?php echo zm_get_option('filters_a_t'); ?></span>
					<?php if(!$filtersa!='') { ?>
						<a class="filter-tag filter-all filter-on" data=""><?php _e( '不限', 'begin' ); ?></a>
					<?php } else { ?>
						<a class="filter-tag filter-all" data=""><?php _e( '不限', 'begin' ); ?></a>
					<?php } ?>
					<?php
						$terms = get_terms("filtersa");
						$count = count($terms);
						if ( $count > 0 ){
							foreach ( $terms as $term ) {
								if(strtolower(urlencode(urldecode(urldecode($filtersa))))==$term->slug){
									echo '<a class="filter-tag filter-on" data="'. $term->slug .'">' . $term->name . '</a>';
								}else{
									echo '<a class="filter-tag" data="'. $term->slug .'">' . $term->name . '</a>';
								}
							}
						}
					?>
				</span>
			</div>
		<?php } ?>

		<?php if (zm_get_option('filters_b')) { ?>
			<div class="clear"></div>
			<div class="filter-main">
				<span class="filtertag" id="filtersb"<?php global $filtersb; if($filtersb!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($filtersb)))).'"';}?>>
					<span class="filter-name"><?php echo zm_get_option('filters_b_t'); ?></span>
					<?php if(!$filtersb!='') { ?>
						<a class="filter-tag filter-all filter-on" data=""><?php _e( '不限', 'begin' ); ?></a>
					<?php } else { ?>
						<a class="filter-tag filter-all" data=""><span><?php _e( '不限', 'begin' ); ?></a>
					<?php } ?>
					<?php
						$terms = get_terms("filtersb");
						$count = count($terms);
						if ( $count > 0 ){
							foreach ( $terms as $term ) {
								if(strtolower(urlencode(urldecode(urldecode($filtersb))))==$term->slug){
									echo '<a class="filter-tag filter-on" data="'. $term->slug .'">' . $term->name . '</a>';
								}else{
									echo '<a class="filter-tag" data="'. $term->slug .'">' . $term->name . '</a>';
								}
							}
						}
					?>
				</span>
			</div>
		<?php } ?>

		<?php if (zm_get_option('filters_c')) { ?>
			<div class="clear"></div>
			<div class="filter-main">
				<span class="filtertag" id="filtersc"<?php global $filtersc; if($filtersc!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($filtersc)))).'"';}?>>
					<span class="filter-name"><?php echo zm_get_option('filters_c_t'); ?></span>
					<?php if(!$filtersc!='') { ?>
						<a class="filter-tag filter-all filter-on" data=""><?php _e( '不限', 'begin' ); ?></a>
					<?php } else { ?>
						<a class="filter-tag filter-all" data=""><?php _e( '不限', 'begin' ); ?></a>
					<?php } ?>
					<?php
						$terms = get_terms("filtersc");
						$count = count($terms);
						if ( $count > 0 ){
							foreach ( $terms as $term ) {
								if(strtolower(urlencode(urldecode(urldecode($filtersc))))==$term->slug){
									echo '<a class="filter-tag filter-on" data="'. $term->slug .'">' . $term->name . '</a>';
								}else{
									echo '<a class="filter-tag" data="'. $term->slug .'">' . $term->name . '</a>';
								}
							}
						}
					?>
				</span>
			</div>
		<?php } ?>

		<?php if (zm_get_option('filters_d')) { ?>
			<div class="clear"></div>
			<div class="filter-main">
				<span class="filtertag" id="filtersd"<?php global $filtersd; if($filtersd!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($filtersd)))).'"';}?>>
					<span class="filter-name"><?php echo zm_get_option('filters_d_t'); ?></span>
					<?php if(!$filtersd!='') { ?>
						<a class="filter-tag filter-all filter-on" data=""><?php _e( '不限', 'begin' ); ?></a>
					<?php } else { ?>
						<a class="filter-tag filter-all" data=""><?php _e( '不限', 'begin' ); ?></a>
					<?php } ?>
					<?php
						$terms = get_terms("filtersd");
						$count = count($terms);
						if ( $count > 0 ){
							foreach ( $terms as $term ) {
								if(strtolower(urlencode(urldecode(urldecode($filtersd))))==$term->slug){
									echo '<a class="filter-tag filter-on" data="'. $term->slug .'">' . $term->name . '</a>';
								}else{
									echo '<a class="filter-tag" data="'. $term->slug .'">' . $term->name . '</a>';
								}
							}
						}
					?>
				</span>
			</div>
		<?php } ?>

		<?php if (zm_get_option('filters_e')) { ?>
			<div class="clear"></div>
			<div class="filter-main">
				<span class="filtertag" id="filterse"<?php global $filterse; if($filterse!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($filterse)))).'"';}?>>
					<span class="filter-name"><?php echo zm_get_option('filters_e_t'); ?></span>
					<?php if(!$filterse!='') { ?>
						<a class="filter-tag filter-all filter-on" data=""><?php _e( '不限', 'begin' ); ?></a>
					<?php } else { ?>
						<a class="filter-tag filter-all" data=""><?php _e( '不限', 'begin' ); ?></a>
					<?php } ?>
					<?php
						$terms = get_terms("filterse");
						$count = count($terms);
						if ( $count > 0 ){
							foreach ( $terms as $term ) {
								if(strtolower(urlencode(urldecode(urldecode($filterse))))==$term->slug){
									echo '<a class="filter-tag filter-on" data="'. $term->slug .'">' . $term->name . '</a>';
								}else{
									echo '<a class="filter-tag" data="'. $term->slug .'">' . $term->name . '</a>';
								}
							}
						}
					?>
				</span>
			</div>
		<?php } ?>

		<?php if (zm_get_option('filters_f')) { ?>
			<div class="clear"></div>
			<div class="filter-main">
				<span class="filtertag" id="filtersf"<?php global $filtersf; if($filtersf!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($filtersf)))).'"';}?>>
					<span class="filter-name"><?php echo zm_get_option('filters_f_t'); ?></span>
					<?php if(!$filtersf!='') { ?>
						<a class="filter-tag filter-all filter-on" data=""><?php _e( '不限', 'begin' ); ?></a>
					<?php } else { ?>
						<a class="filter-tag filter-all" data=""><?php _e( '不限', 'begin' ); ?></a>
					<?php } ?>
					<?php
						$terms = get_terms("filtersf");
						$count = count($terms);
						if ( $count > 0 ){
							foreach ( $terms as $term ) {
								if(strtolower(urlencode(urldecode(urldecode($filtersf))))==$term->slug){
									echo '<a class="filter-tag filter-on" data="'. $term->slug .'">' . $term->name . '</a>';
								}else{
									echo '<a class="filter-tag" data="'. $term->slug .'">' . $term->name . '</a>';
								}
							}
						}
					?>
				</span>
			</div>
		<?php } ?>
		<div class="clear"></div>
	</div>
</div>