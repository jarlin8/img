<?php 
function begin_get_txt_sitemap() {
	ob_start();
?>
<?php echo get_home_url(); ?>

<?php 
	$posts = get_posts( 'numberposts=' . zm_get_option( 'sitemap_n' ) . '&offset=' .zm_get_option( 'offset_n' ) . '&orderby=post_date&order=DESC' );
	foreach($posts as $post) : 
?>
<?php echo get_permalink( $post->ID ); ?>

<?php endforeach; ?>
<?php if ( zm_get_option( 'no_sitemap_pages' ) ) { ?>
<?php $pages = get_pages(); foreach ( $pages as $page ){ ?>
<?php echo get_page_link( $page->ID ); ?>

<?php } ?>
<?php } ?>
<?php if ( zm_get_option('no_sitemap_type' ) ) { ?>
<?php if (zm_get_option('no_bulletin')) { ?>
<?php 
	$posts = get_posts( 'post_type=bulletin&numberposts=' . zm_get_option( 'sitemap_n' ) . '&orderby=post_date&order=DESC' );
	foreach($posts as $post) : 
?>
<?php echo get_permalink($post->ID); ?>

<?php endforeach; ?>
<?php } ?>
<?php if ( zm_get_option( 'no_gallery' ) ) { ?>
<?php 
	$posts = get_posts( 'post_type=picture&numberposts=' .zm_get_option( 'sitemap_n' ) . '&orderby=post_date&order=DESC' );
	foreach( $posts as $post ) : 
?>
<?php echo get_permalink( $post->ID ); ?>

<?php endforeach; ?>
<?php } ?>
<?php if ( zm_get_option( 'no_videos' ) ) { ?>
<?php 
	$posts = get_posts( 'post_type=video&numberposts=' . zm_get_option( 'sitemap_n' ) . '&orderby=post_date&order=DESC' );
	foreach( $posts as $post ) : 
?>
<?php echo get_permalink( $post->ID ); ?>

<?php endforeach; ?>
<?php } ?>
<?php if (zm_get_option('no_tao')) { ?>
<?php 
	$posts = get_posts( 'post_type=tao&numberposts=' . zm_get_option( 'sitemap_n' ) . '&orderby=post_date&order=DESC' );
	foreach($posts as $post) : 
?>
<?php echo get_permalink( $post->ID ); ?>

<?php endforeach; ?>
<?php } ?>
<?php if ( zm_get_option( 'no_products' ) ) { ?>
<?php 
	$posts = get_posts( 'post_type=show&numberposts=' . zm_get_option( 'sitemap_n' ) . '&orderby=post_date&order=DESC' );
	foreach($posts as $post) : 
?>
<?php echo get_permalink( $post->ID ); ?>

<?php endforeach; ?>
<?php } ?>
<?php } ?>
<?php if ( zm_get_option( 'no_sitemap_cat' ) ) { ?>
<?php 
	$categorys = get_terms( 'category', 'orderby=name&hide_empty=0' );
	foreach ( $categorys as $category ) : 
?>
<?php echo get_term_link( $category, $category->slug ); ?>

<?php endforeach; ?>
<?php } ?>
<?php if ( zm_get_option( 'no_sitemap_tag' ) ) { ?>
<?php 
	$tags = get_terms( 'post_tag', 'orderby=name&hide_empty=0' );
	foreach ( $tags as $tag ) : 
?>
<?php echo get_term_link( $tag, $tag->slug ); ?>

<?php endforeach; ?>
<?php } ?>
<?php if ( zm_get_option( 'no_sitemap_cat' ) ) { ?>
<?php if ( zm_get_option( 'no_bulletin' ) ) { ?>
<?php 
	$categorys = get_terms( 'notice', 'orderby=name&hide_empty=0' );
	foreach ( $categorys as $category ) : 
?>
<?php echo get_term_link( $category, $category->slug ); ?>

<?php endforeach; ?>
<?php } ?>
<?php if ( zm_get_option('no_gallery' ) ) { ?>
<?php 
	$categorys = get_terms( 'gallery', 'orderby=name&hide_empty=0' );
	foreach ( $categorys as $category ) : 
?>
<?php echo get_term_link( $category, $category->slug ); ?>

<?php endforeach; ?>
<?php } ?>
<?php if ( zm_get_option( 'no_videos' ) ) { ?>
<?php 
	$categorys = get_terms( 'videos', 'orderby=name&hide_empty=0' );
	foreach ( $categorys as $category ) : 
?>
<?php echo get_term_link( $category, $category->slug ); ?>

<?php endforeach; ?>
<?php } ?>
<?php if ( zm_get_option( 'no_tao' ) ) { ?>
<?php 
	$categorys = get_terms( 'taobao', 'orderby=name&hide_empty=0' );
	foreach ( $categorys as $category ) : 
?>
<?php echo get_term_link( $category, $category->slug ); ?>

<?php endforeach; ?>
<?php } ?>
<?php if ( zm_get_option( 'no_products' ) ) { ?>
<?php 
	$categorys = get_terms( 'products', 'orderby=name&hide_empty=0' );
	foreach ( $categorys as $category ) : 
?>
<?php echo get_term_link( $category, $category->slug ); ?>

<?php endforeach; ?>
<?php } ?>
<?php } ?>
<?php 
	$sitemap_txt = ob_get_contents();
	ob_clean();
	return $sitemap_txt;
}