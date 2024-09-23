<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="filter-box ms" <?php aos_a(); ?>>
	<div class="filter-t"><i class="dashicons dashicons-image-filter"></i><span><?php echo zm_get_option( 'filter_t' ); ?></span></div>
		<?php if ( zm_get_option( 'filters_hidden' ) ) { ?><div class="filter-box-main filter-box-main-h"><?php } else { ?><div class="filter-box-main"><?php } ?>
		<?php if ( is_array( zm_get_option( 'filters_item' ) ) && in_array( 'filters_cat', zm_get_option( 'filters_item' ) ) ) { ?>
			<div class="filter-main">
				<span class="filter-name"><?php _e( '分类', 'begin' ); ?></span>
				<span class="filtertag" id="filtercat"<?php global $cat; if ( $cat!='' ){echo ' data="'.strtolower(urlencode(urldecode(urldecode($cat)))).'"';}?>>
					<?php if ( ! $cat != '' ) { ?>
						<a class="filter-tag filter-all filter-on" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } else { ?>
						<a class="filter-tag filter-all" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } ?>

					<?php $display_categories = explode( ',', zm_get_option( 'filters_cat_id' ) ); foreach ( $display_categories as $category ) { ?>
						<?php
							$terms = get_terms( array(
								'taxonomy'   => array( 'category' ),
								'include'    => $category,
							) );

							foreach ( $terms as $term ) {
						?>
							<?php if ( $cat == $category ) { ?>
								<a class="filter-tag filter-on" data="<?php echo $category; ?>"><?php echo $term->name; ?></a>
							<?php } else { ?>
								<a class="filter-tag" data="<?php echo $category; ?>"><?php echo $term->name; ?></a>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				</span>
			</div>
			<div class="clear"></div>
		<?php } ?>

		<?php if ( is_array( zm_get_option( 'filters_item' ) ) && in_array( 'filters_a', zm_get_option( 'filters_item' ) ) ) { ?>
			<div class="filter-main">
				<span class="filter-name"><?php echo zm_get_option('filters_a_t'); ?></span>
				<span class="filtertag" id="filtersa"<?php global $filtersa; if ($filtersa!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($filtersa)))).'"';}?>>
					<?php if (!$filtersa!='') { ?>
						<a class="filter-tag filter-all filter-on" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } else { ?>
						<a class="filter-tag filter-all" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } ?>
					<?php
						$terms = get_terms("filtersa");
						$count = count($terms);
						if ( $count > 0 ){
							foreach ( $terms as $term ) {
								if (strtolower(urlencode(urldecode(urldecode($filtersa))))==$term->slug){
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

		<?php if ( is_array( zm_get_option( 'filters_item' ) ) && in_array( 'filters_b', zm_get_option( 'filters_item' ) ) ) { ?>
			<div class="clear"></div>
			<div class="filter-main">
				<span class="filter-name"><?php echo zm_get_option('filters_b_t'); ?></span>
				<span class="filtertag" id="filtersb"<?php global $filtersb; if ($filtersb!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($filtersb)))).'"';}?>>
					<?php if (!$filtersb!='') { ?>
						<a class="filter-tag filter-all filter-on" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } else { ?>
						<a class="filter-tag filter-all" data=""><span><?php _e( '全部', 'begin' ); ?></a>
					<?php } ?>
					<?php
						$terms = get_terms("filtersb");
						$count = count($terms);
						if ( $count > 0 ){
							foreach ( $terms as $term ) {
								if (strtolower(urlencode(urldecode(urldecode($filtersb))))==$term->slug){
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

		<?php if ( is_array( zm_get_option( 'filters_item' ) ) && in_array( 'filters_c', zm_get_option( 'filters_item' ) ) ) { ?>
			<div class="clear"></div>
			<div class="filter-main">
				<span class="filter-name"><?php echo zm_get_option('filters_c_t'); ?></span>
				<span class="filtertag" id="filtersc"<?php global $filtersc; if ($filtersc!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($filtersc)))).'"';}?>>
					<?php if (!$filtersc!='') { ?>
						<a class="filter-tag filter-all filter-on" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } else { ?>
						<a class="filter-tag filter-all" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } ?>
					<?php
						$terms = get_terms("filtersc");
						$count = count($terms);
						if ( $count > 0 ){
							foreach ( $terms as $term ) {
								if (strtolower(urlencode(urldecode(urldecode($filtersc))))==$term->slug){
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

		<?php if ( is_array( zm_get_option( 'filters_item' ) ) && in_array( 'filters_d', zm_get_option( 'filters_item' ) ) ) { ?>
			<div class="clear"></div>
			<div class="filter-main">
				<span class="filter-name"><?php echo zm_get_option('filters_d_t'); ?></span>
				<span class="filtertag" id="filtersd"<?php global $filtersd; if ($filtersd!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($filtersd)))).'"';}?>>
					<?php if (!$filtersd!='') { ?>
						<a class="filter-tag filter-all filter-on" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } else { ?>
						<a class="filter-tag filter-all" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } ?>
					<?php
						$terms = get_terms("filtersd");
						$count = count($terms);
						if ( $count > 0 ){
							foreach ( $terms as $term ) {
								if (strtolower(urlencode(urldecode(urldecode($filtersd))))==$term->slug){
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

		<?php if ( is_array( zm_get_option( 'filters_item' ) ) && in_array( 'filters_e', zm_get_option( 'filters_item' ) ) ) { ?>
			<div class="clear"></div>
			<div class="filter-main">
				<span class="filter-name"><?php echo zm_get_option('filters_e_t'); ?></span>
				<span class="filtertag" id="filterse"<?php global $filterse; if ($filterse!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($filterse)))).'"';}?>>
					<?php if (!$filterse!='') { ?>
						<a class="filter-tag filter-all filter-on" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } else { ?>
						<a class="filter-tag filter-all" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } ?>
					<?php
						$terms = get_terms("filterse");
						$count = count($terms);
						if ( $count > 0 ){
							foreach ( $terms as $term ) {
								if (strtolower(urlencode(urldecode(urldecode($filterse))))==$term->slug){
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

		<?php if ( is_array( zm_get_option( 'filters_item' ) ) && in_array( 'filters_f', zm_get_option( 'filters_item' ) ) ) { ?>
			<div class="clear"></div>
			<div class="filter-main">
				<span class="filter-name"><?php echo zm_get_option('filters_f_t'); ?></span>
				<span class="filtertag" id="filtersf"<?php global $filtersf; if ($filtersf!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($filtersf)))).'"';}?>>
					<?php if (!$filtersf!='') { ?>
						<a class="filter-tag filter-all filter-on" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } else { ?>
						<a class="filter-tag filter-all" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } ?>
					<?php
						$terms = get_terms("filtersf");
						$count = count($terms);
						if ( $count > 0 ){
							foreach ( $terms as $term ) {
								if (strtolower(urlencode(urldecode(urldecode($filtersf))))==$term->slug){
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

		<?php if ( is_array( zm_get_option( 'filters_item' ) ) && in_array( 'filters_g', zm_get_option( 'filters_item' ) ) ) { ?>
			<div class="clear"></div>
			<div class="filter-main">
				<span class="filter-name"><?php echo zm_get_option('filters_g_t'); ?></span>
				<span class="filtertag" id="filtersg"<?php global $filtersg; if ($filtersg!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($filtersg)))).'"';}?>>
					<?php if (!$filtersg!='') { ?>
						<a class="filter-tag filter-all filter-on" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } else { ?>
						<a class="filter-tag filter-all" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } ?>
					<?php
						$terms = get_terms("filtersg");
						$count = count($terms);
						if ( $count > 0 ){
							foreach ( $terms as $term ) {
								if (strtolower(urlencode(urldecode(urldecode($filtersg))))==$term->slug){
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

		<?php if ( is_array( zm_get_option( 'filters_item' ) ) && in_array( 'filters_h', zm_get_option( 'filters_item' ) ) ) { ?>
			<div class="clear"></div>
			<div class="filter-main">
				<span class="filter-name"><?php echo zm_get_option('filters_h_t'); ?></span>
				<span class="filtertag" id="filtersh"<?php global $filtersh; if ($filtersh!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($filtersh)))).'"';}?>>
					<?php if (!$filtersh!='') { ?>
						<a class="filter-tag filter-all filter-on" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } else { ?>
						<a class="filter-tag filter-all" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } ?>
					<?php
						$terms = get_terms("filtersh");
						$count = count($terms);
						if ( $count > 0 ){
							foreach ( $terms as $term ) {
								if (strtolower(urlencode(urldecode(urldecode($filtersh))))==$term->slug){
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

		<?php if ( is_array( zm_get_option( 'filters_item' ) ) && in_array( 'filters_i', zm_get_option( 'filters_item' ) ) ) { ?>
			<div class="clear"></div>
			<div class="filter-main">
				<span class="filter-name"><?php echo zm_get_option('filters_i_t'); ?></span>
				<span class="filtertag" id="filtersi"<?php global $filtersi; if ($filtersi!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($filtersi)))).'"';}?>>
					<?php if (!$filtersi!='') { ?>
						<a class="filter-tag filter-all filter-on" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } else { ?>
						<a class="filter-tag filter-all" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } ?>
					<?php
						$terms = get_terms("filtersi");
						$count = count($terms);
						if ( $count > 0 ){
							foreach ( $terms as $term ) {
								if (strtolower(urlencode(urldecode(urldecode($filtersi))))==$term->slug){
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

		<?php if ( is_array( zm_get_option( 'filters_item' ) ) && in_array( 'filters_j', zm_get_option( 'filters_item' ) ) ) { ?>
			<div class="clear"></div>
			<div class="filter-main">
				<span class="filter-name"><?php echo zm_get_option('filters_j_t'); ?></span>
				<span class="filtertag" id="filtersj"<?php global $filtersj; if ($filtersj!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($filtersj)))).'"';}?>>
					<?php if (!$filtersj!='') { ?>
						<a class="filter-tag filter-all filter-on" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } else { ?>
						<a class="filter-tag filter-all" data=""><?php _e( '全部', 'begin' ); ?></a>
					<?php } ?>
					<?php
						$terms = get_terms("filtersj");
						$count = count($terms);
						if ( $count > 0 ){
							foreach ( $terms as $term ) {
								if (strtolower(urlencode(urldecode(urldecode($filtersj))))==$term->slug){
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