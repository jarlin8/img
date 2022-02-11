<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function decide_h() { ?>
<?php if (zm_get_option('turn_small')) { ?> site-small<?php } ?><?php if (zm_get_option('infinite_post')) { ?> site-roll<?php } else { ?> site-no-roll<?php } ?><?php if ( is_paged() ) { ?> paged-roll<?php } ?>
<?php }

// like left
function like_left() { ?>
	<?php if (zm_get_option('like_left') && is_single() && !wp_is_mobile() ) { ?>
		<div class="like-left-box fds">
			<div class="like-left fadeInDown animated"><?php be_like(); ?></div>
		</div>
	<?php } ?>
<?php }

// share
function be_like() { ?>
	<?php if (zm_get_option('shar_donate') || zm_get_option('shar_like') || zm_get_option('shar_favorite') || zm_get_option('shar_share') || zm_get_option('shar_link') || zm_get_option('shar_poster') ) { ?>
		<?php share_poster(); ?>
	<?php } ?>
<?php }

// grid md
function grid_md_cms() { ?>
<div class="grid-md grid-md<?php echo zm_get_option('grid_ico_cms_n'); ?> sort" name="<?php echo zm_get_option('grid_ico_cms_s'); ?>">
	<?php $posts = get_posts( array( 'post_type' => 'any', 'orderby' => 'menu_order', 'meta_key' => 'gw_title', 'numberposts' => '16') ); if($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
		<?php 
			$gw_ico = get_post_meta($post->ID, 'gw_ico', true);
			$gw_img = get_post_meta($post->ID, 'gw_img', true);
			$gw_title = get_post_meta($post->ID, 'gw_title', true);
			$gw_content = get_post_meta($post->ID, 'gw_content', true);
			$gw_link = get_post_meta($post->ID, 'gw_link', true);
			$gw_ico_svg = get_post_meta($post->ID, 'gw_ico_svg', true);
		?>
	<div class="gw-box sup bk edit-buts gw-box<?php echo zm_get_option('grid_ico_cms_n'); ?>" <?php aos_a(); ?>>
		<div class="gw-main gw-main-<?php if ( zm_get_option('cms_ico_b')) { ?>b<?php } ?>">
			<?php edit_post_link('<i class="be be-editor"></i>', '<span class="edit-link-but">', '</span>' ); ?>
			<?php if ( get_post_meta($post->ID, 'gw_link', true) ) { ?><a class="gw-link" href="<?php echo $gw_link; ?>" rel="bookmark"><?php } ?>
				<?php if ( get_post_meta($post->ID, 'gw_img', true) ) { ?>
					<div class="gw-img" <?php aos_b(); ?>><img src="<?php echo get_template_directory_uri().'/prune.php?src='.$gw_img.'&w=300&h=300&zc=1'; ?>" alt="<?php echo $gw_title; ?>" /></div>
				<?php } ?>
				<div class="gw-area" <?php aos_b(); ?>>
					<?php if ( get_post_meta($post->ID, 'gw_ico', true) ) { ?>
						<?php if ( get_post_meta($post->ID, 'gw_ico_svg', true) ) { ?>
							<div class="gw-ico"><svg class="icon dah" aria-hidden="true"><use xlink:href="#<?php echo $gw_ico; ?>"></use></svg></div>
						<?php } else { ?>
							<div class="gw-ico"><i class="<?php echo $gw_ico; ?> dah"></i></div>
						<?php } ?>
					<?php } ?>
					<h3 class="gw-title"><?php echo $gw_title; ?></h3>
				<?php if ( get_post_meta($post->ID, 'gw_link', true) ) { ?></a><?php } ?>
				<?php if ( get_post_meta($post->ID, 'gw_content', true) ) { ?><div class="gw-content"><?php echo $gw_content; ?></div><?php } ?>
			</div>
		</div>
		
	</div>
	<?php endforeach; endif; ?>
	<?php wp_reset_query(); ?>
	<div class="clear"></div>
</div>
<?php }

function grid_md_group() { ?>
<div class="g-row g-line sort g-stress da" name="<?php echo zm_get_option('group_ico_s'); ?>">
	<div class="g-col">
		<div class="grid-md grid-md<?php echo zm_get_option('grid_ico_group_n'); ?>">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( zm_get_option('group_ico_t') == '' ) { ?>
				<?php } else { ?>
					<h3><?php echo zm_get_option('group_ico_t'); ?></h3>
					<div class="separator"></div>
				<?php } ?>
				<div class="group-des"><?php echo zm_get_option('group_ico_des'); ?></div>
				<div class="clear"></div>
			</div>
			<?php global $post; $posts = get_posts( array( 'post_type' => 'any', 'orderby' => 'menu_order', 'meta_key' => 'gw_title', 'numberposts' => '16') ); if($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
				<?php 
					$gw_ico = get_post_meta($post->ID, 'gw_ico', true);
					$gw_img = get_post_meta($post->ID, 'gw_img', true);
					$gw_title = get_post_meta($post->ID, 'gw_title', true);
					$gw_content = get_post_meta($post->ID, 'gw_content', true);
					$gw_link = get_post_meta($post->ID, 'gw_link', true);
				?>
			<div class="gw-box sup edit-buts gw-box<?php echo zm_get_option('grid_ico_group_n'); ?>" <?php aos_c(); ?>>
				<div class="gw-main gw-main-<?php if ( zm_get_option('group_ico_b')) { ?>b<?php } ?>">
					<?php edit_post_link('<i class="be be-editor"></i>', '<span class="edit-link-but">', '</span>' ); ?>
					<?php if ( get_post_meta($post->ID, 'gw_link', true) ) { ?><a class="gw-link" href="<?php echo $gw_link; ?>" rel="bookmark"><?php } ?>
						<?php if ( get_post_meta($post->ID, 'gw_img', true) ) { ?><div class="gw-img"><img src="<?php echo get_template_directory_uri().'/prune.php?src='.$gw_img.'&w=300&h=300&zc=1'; ?>" alt="<?php echo $gw_title; ?>" /></div><?php } ?>
						<div class="gw-area" <?php aos_b(); ?>>
							<?php if ( get_post_meta($post->ID, 'gw_ico', true) ) { ?>
								<?php if ( get_post_meta($post->ID, 'gw_ico_svg', true) ) { ?>
									<div class="gw-ico"><svg class="icon dah" aria-hidden="true"><use xlink:href="#<?php echo $gw_ico; ?>"></use></svg></div>
								<?php } else { ?>
									<div class="gw-ico"><i class="<?php echo $gw_ico; ?> dah"></i></div>
								<?php } ?>
							<?php } ?>
							<h3 class="gw-title"><?php echo $gw_title; ?></h3>
						<?php if ( get_post_meta($post->ID, 'gw_link', true) ) { ?></a><?php } ?>
						<?php if ( get_post_meta($post->ID, 'gw_content', true) ) { ?><div class="gw-content"><?php echo $gw_content; ?></div><?php } ?>
					</div>
				</div>
			</div>
			<?php endforeach; endif; ?>
			<?php wp_reset_query(); ?>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php }

// menu
function menu_top() { ?>
<div class="nav-top dah">
	<?php if (zm_get_option('profile')) { ?>
		<?php login_info(); ?>
	<?php } ?>

	<div class="nav-menu-top dah">
		<?php
			if (zm_get_option('top_nav_more') ) {
				if ( wp_is_mobile()) {
					$menuclass = 'top-menu';
				} else {
					$menuclass = 'top-menu top-menu-more';
				}
			} else {
				$menuclass = 'top-menu';
			}

			wp_nav_menu( array(
				'theme_location' => 'header',
				'menu_class'     => $menuclass,
				'fallback_cb'    => 'default_top_menu'
			) );
		?>
	</div>
</div>
<?php }

function site_logo() { ?>
	<?php if (!zm_get_option('site_sign') || (zm_get_option('site_sign') == 'logo_small')) { ?>
		<a href="<?php echo esc_url( home_url('/') ); ?>">
			<span class="logo-small"><img src="<?php echo zm_get_option('logo_small_b'); ?>" alt="<?php bloginfo( 'name' ); ?>" /></span>
			<?php bloginfo( 'name' ); ?>
		</a>
	<?php } ?>

	<?php if (zm_get_option('site_sign') == 'logos') { ?>
		<a href="<?php echo esc_url( home_url('/') ); ?>">
			<img src="<?php echo zm_get_option('logo'); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>" rel="home" />
			<span class="site-name"><?php bloginfo( 'name' ); ?></span>
		</a>
	<?php } ?>

	<?php if (zm_get_option('site_sign') == 'no_logo') { ?>
		<a href="<?php echo esc_url( home_url('/') ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home" /><?php bloginfo( 'name' ); ?></a>
	<?php } ?>
<?php }

function site_description() { ?>
	<?php $description = get_bloginfo( 'description', 'display' ); if ( $description || is_customize_preview() ) : ?>
		<p class="site-description<?php if (!zm_get_option('site_sign') || (zm_get_option('site_sign') == 'logo_small')) { ?> clear-small<?php } ?>"><?php echo $description; ?></p>
	<?php endif; ?>
<?php }

function menu_logo() { ?>
	<?php if ( is_front_page() || is_home() ) : ?>
		<h1 class="site-title<?php if (!get_bloginfo( 'description', 'display' )) { ?> site-title-d<?php } ?>">
			<?php site_logo(); ?>
		</h1>

		<?php if (zm_get_option('site_sign') !== 'logos') { ?>
			<?php site_description(); ?>
		<?php } ?>
	<?php else : ?>
		<p class="site-title<?php if (!get_bloginfo( 'description', 'display' )) { ?> site-title-d<?php } ?>">
			<?php site_logo(); ?>
		</p>

		<?php if (zm_get_option('site_sign') !== 'logos') { ?>
			<?php site_description(); ?>
		<?php } ?>
	<?php endif; ?>
<?php }

function menu_nav() { ?>
<?php if (zm_get_option('nav_no')) { ?>
	<span class="nav-mobile"><a href="<?php echo get_permalink( zm_get_option('nav_url') ); ?>"><i class="be be-menu"></i></a></span>
<?php } else { ?>
	<?php if (zm_get_option('m_nav')) { ?>
		<?php if ( wp_is_mobile() ) { ?>
			<span class="nav-mobile"><i class="be be-menu"></i></span>
		<?php } else { ?>
			<span id="navigation-toggle" class="bars"><i class="be be-menu"></i></span>
		<?php } ?>
	<?php } else { ?>
		<span id="navigation-toggle" class="bars"><i class="be be-menu"></i></span>
	<?php } ?>
<?php } ?>

		<?php
			if (zm_get_option('mobile_nav') ) {
				if ( wp_is_mobile()) {
					$navtheme = 'mobile';
				} else {
					$navtheme = 'navigation';
				}
			} else {
				$navtheme = 'navigation';
			}

			if (zm_get_option('nav_more') ) {
				if ( wp_is_mobile()) {
					$menuclass = 'down-menu nav-menu';
				} else {
					$menuclass = 'down-menu nav-menu main-menu-more';
				}
			} else {
				$menuclass = 'down-menu nav-menu';
			}

			wp_nav_menu( array(
			'theme_location' => $navtheme,
			'menu_class'     => $menuclass,
			'fallback_cb'    => 'default_menu'
			) );
		?>

<div id="overlay"></div>
<?php }

function mobile_login() { ?>
	<?php if ( zm_get_option('mobile_login') ) { ?>
		<?php if( is_user_logged_in() ) { ?>
			<div class="mobile-userinfo bkxy"><?php logged_manage(); ?></div>
		<?php } else { ?>
			<div class="mobile-login-but bkxy">
				<?php if ( !zm_get_option('user_l') == '' ) { ?>
					<span class="mobile-login-l"><a href="<?php echo zm_get_option('user_l'); ?>" title="Login"><?php _e( '登录', 'begin' ); ?></a></span>
				<?php } else { ?>
					<span class="mobile-login show-layer" data-show-layer="login-layer" role="button"><?php _e( '登录', 'begin' ); ?></span>
				<?php } ?>
				<?php if (zm_get_option('menu_reg') && get_option('users_can_register')) { ?>
					 <span class="mobile-login-reg"><a class="hz" href="<?php echo zm_get_option('reg_l'); ?>"><?php _e( '注册', 'begin' ); ?></a></span>
				 <?php } ?>
			</div>
		<?php } ?>
	<?php } ?>
<?php }

// title span
function title_i() { ?>
<?php if (zm_get_option('title_i')) { ?>
<span class="title-i"><span></span><span></span><span></span><span></span></span>
<?php } else { ?>
<span class="title-h"></span>
<?php } ?>
<?php }

function more_i() { ?>
<span class="more-i<?php if ( zm_get_option('more_im') ) { ?> more-im<?php } ?>"><span></span><span></span><span></span></span>
<?php }

function vr() { ?><?php if ( !zm_get_option( 'more_w' ) ) { ?> vr<?php } else { ?> lvr<?php } ?><?php }

// entry more
function entry_more() { ?>
	<?php global $wpdb, $post; ?>
	<?php if ( get_post_meta($post->ID, 'direct', true) ) { ?>
		<?php $direct = get_post_meta($post->ID, 'direct', true); ?>
		<?php if ( zm_get_option( 'more_w' ) ) { ?><?php if (zm_get_option('more_hide')) { ?><span class="entry-more more-roll ease"><?php } else { ?><span class="entry-more"><?php } ?><a href="<?php echo $direct ?>" target="_blank" rel="external nofollow"><?php echo zm_get_option('direct_w'); ?></a></span><?php } ?>
	<?php } else { ?>
		<?php if ( zm_get_option( 'more_w' ) ) { ?><?php if ( zm_get_option( 'more_hide' ) ) { ?><span class="entry-more more-roll ease"><?php } else { ?><span class="entry-more"><?php } ?><a href="<?php the_permalink(); ?>" rel="external nofollow"><?php echo zm_get_option('more_w'); ?></a></span><?php } ?>
	<?php } ?>
<?php }

// author inf
function author_inf() { ?>
<?php
	global $wpdb;
	$author_id = get_the_author_meta( 'ID' );
	$comment_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->comments  WHERE comment_approved='1' AND user_id = '$author_id' AND comment_type not in ('trackback','pingback')" );
?>
<div class="meta-author-box bgt fd">
	<div class="arrow-up bgt"></div>
	<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>" rel="external nofollow">
		<div class="meta-author-inf yy bk load">
			<div class="meta-inf-avatar bk">
				<?php if (zm_get_option('cache_avatar')) { ?>
					<?php echo begin_avatar( get_the_author_meta('user_email'), '96', '', get_the_author() ); ?>
				<?php } else { ?>
					<?php be_avatar_author(); ?>
				<?php } ?>
			</div>
			<div class="meta-inf-name"><?php the_author(); ?></div>
			<div class="meta-inf meta-inf-posts"><span><?php the_author_posts(); ?></span><br /><?php _e( '文章', 'begin' ); ?></div>
			<div class="meta-inf meta-inf-comment"><span><?php echo $comment_count;?></span><br /><?php _e( '评论', 'begin' ); ?></div>
			<div class="clear"></div>
		</div>
	</a>
	<div class="clear"></div>
</div>
<?php }


// // author inf img
function grid_author_inf() { ?>
<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>" rel="external nofollow">
	<span class="meta-author grid-meta-author">
		<span class="meta-author-avatar load" title="<?php the_author(); ?>">
			<?php if (zm_get_option('cache_avatar')) { ?>
				<?php echo begin_avatar( get_the_author_meta('email'), '64', '', get_the_author() ); ?>
			<?php } else { ?>
				<?php be_avatar_author(); ?>
			<?php } ?>
		</span>
	</span>
</a>
<?php }

function simple_author_inf() { ?>
<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>" rel="external nofollow">
	<span class="meta-author">
		<span class="meta-author-avatar load" title="<?php the_author(); ?>">
			<?php if (zm_get_option('cache_avatar')) { ?>
				<?php echo begin_avatar( get_the_author_meta('email'), '64', '', get_the_author() ); ?>
			<?php } else { ?>
				<?php be_avatar_author(); ?>
			<?php } ?>
		</span>
	</span>
</a>
<?php }

// search
function search_class() { ?>
<div class="single-content">
	<div class="searchbar ad-searchbar">
		<form method="get" id="searchform" action="<?php echo esc_url( home_url() ); ?>/">
			<?php if (zm_get_option('search_option') == 'search_cat') { ?>
				<span class="ad-search-cat">
					<?php $args = array(
						'show_option_all' => '全部分类',
						'hide_empty'      => 0,
						'name'            => 'cat',
						'show_count'      => 0,
						'class'           => 's-veil',
						'taxonomy'        => 'category',
						'hierarchical'    => 1,
						'depth'           => -1,
						'echo'            => 1,
						'exclude'         => zm_get_option('not_search_cat'),
					); ?>
					<?php wp_dropdown_categories( $args ); ?>
				</span>
			<?php } ?>
			<span class="clear"></span>
			<span class="search-input ad-search-input">
				<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" class="da" placeholder="<?php _e( '输入搜索内容', 'begin' ); ?>" required />
				<button type="submit" id="searchsubmit" class="bk da"><i class="be be-search"></i></button>
			</span>
		</form>

		<div class="clear"></div>
		<?php if (zm_get_option('search_nav')) { ?>
			<nav class="ad-search-nav">
				<div class="ad-search-nav-t"><?php _e( '关键词', 'begin' ); ?></div>
				<div class="clear"></div>
				<?php
					wp_nav_menu( array(
						'theme_location' => 'search',
						'menu_class'     => 'ad-search-menu',
						'fallback_cb'    => 'ad-search-menu'
					) );
				?>
				<div class="clear"></div>
			</nav>
		<?php } ?>
	</div>
</div>
<?php }

// all_content
function all_content() { ?>
<?php if (word_num() > 800) { ?>
	<div class="all-content-box">
		<div class="all-content bk"><?php _e( '继续阅读', 'begin' ); ?></div>
	</div>
<?php } ?>
<?php }

// begin link
function begin_get_the_link_items( $id = null ) {
	global $wpdb,$post;
	$args  = array(
		'orderby'   => zm_get_option('rand_link'),
		'order'     => 'DESC',
		'exclude'   => zm_get_option('link_cat'),
		'category'  => $id,
	);

	$bookmarks = get_bookmarks( $args );
	$output = "";
	if ( !empty( $bookmarks ) ) {
		foreach ($bookmarks as $bookmark) {
			$output .= '<div class="link-box" data-aos="fade-up"><a href="' . $bookmark->link_url . ' " target="_blank" ><div class="link-main sup bk da">';
			if (!zm_get_option('link_favicon') || (zm_get_option("link_favicon") == 'favicon_ico')) {
				$output .= '<div class="page-link-img dah bk"><img src="' . zm_get_option("favicon_api") . '' . $bookmark->link_url . '" alt="' . $bookmark->link_name . '"></div><div class="link-name-link"><div class="page-link-name">' . $bookmark->link_name . '</div><div class="links-url">' . $bookmark->link_url . '</div></div><div class="link-des-box"><div class="link-des over">' . $bookmark->link_description . '</div></div></li>';
			}
			if (zm_get_option('link_favicon') == 'first_ico') {
				$output .= '<div class="link-letter">' . getFirstCharter($bookmark->link_name) . '</div><div class="link-name-link"><div class="page-link-name">' . $bookmark->link_name . '</div><div class="links-url">' . $bookmark->link_url . '</div></div><div class="link-des-box"><div class="link-des over">' . $bookmark->link_description . '</div></div></li>';
			}
			$output .= '<div class="rec-adorn-s"></div><div class="rec-adorn-x"></div></div></a></div>';
		}
	}
	return $output;
}

function begin_get_link_items() {
	$result = '';
	$linkcats = get_terms( 'link_category' );
	if ( !empty( $linkcats ) ) {
		foreach( $linkcats as $linkcat ){
			$result .= '<div class="clear"></div><h3 class="link-cat" data-aos="zoom-in">'.$linkcat->name.'</h3>';
			if( $linkcat->description ) $result .= '<div class="linkcat-des" data-aos="zoom-in">'.$linkcat->description .'</div>';
			$result .= begin_get_the_link_items( $linkcat->term_id );
		}
	} else {
		$result = begin_get_the_link_items();
	}
	return $result;
}

function begin_home_link_ico( $id = null ) {
	global $wpdb,$post;
	$args  = array(
		'orderby'   => 'rating',
		'order'     => 'DESC',
		'category'  => zm_get_option('link_f_cat'),
	);

	$bookmarks = get_bookmarks( $args );
	$output = "";
	if ( !empty( $bookmarks ) ) {
		foreach ($bookmarks as $bookmark) {
			$output .= '<ul class="lx7"><li class="link-f link-name sup">';
				$output .= '<a href="' . $bookmark->link_url . ' " target="_blank" ><img class="link-ico" src="' . zm_get_option("favicon_api") . '' . $bookmark->link_url . '" alt="' . $bookmark->link_name . '">' . $bookmark->link_name . '</a>';
			$output .= '</li></ul>';
		}
	}
	return $output;
}

// related article
function related_article() { ?>
	<?php if (!zm_get_option('related_mode') || (zm_get_option('related_mode') == 'related_normal')) { ?>
	<?php if (zm_get_option('post_no_margin')) { ?>
		<article id="post-<?php the_ID(); ?>" class="post ms bk doclose" <?php aos_a(); ?>>
	<?php } else { ?>
		<article id="post-<?php the_ID(); ?>" class="post ms bk" <?php aos_a(); ?>>
	<?php } ?>

			<figure class="thumbnail">
				<?php zm_thumbnail(); ?>
			<?php if (zm_get_option('no_thumbnail_cat')) { ?><span class="cat cat-roll"><?php } else { ?><span class="cat"><?php } ?><?php zm_category(); ?></span>
			</figure>

			<header class="entry-header">
				<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			</header>
			<div class="entry-content">
				<div class="archive-content">
					<?php if (has_excerpt('')){
							echo wp_trim_words( get_the_excerpt(), zm_get_option('word_n'), '...' );
						} else {
							$content = get_the_content();
							$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
							if (zm_get_option('languages_en')) {
								echo begin_strimwidth(strip_tags($content), 0, zm_get_option('words_n'),'...');
							} else {
								echo wp_trim_words( $content, zm_get_option('words_n'), '...' );
							}
						}
					?>
				</div>

				<span class="entry-meta vr">
					<?php begin_related_meta(); ?>
				</span>
				<span class="title-l"></span>
			</div>
			<div class="clear"></div>
		</article>
	<?php } ?>

	<?php if (zm_get_option('related_mode') == 'slider_grid') { ?>
		<div class="r4">
			<div class="related-site">
				<figure class="related-site-img">
					<?php zm_thumbnail(); ?>
				 </figure>
				<div class="related-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
			</div>
		</div>
	<?php } ?>
<?php }

// nav img
function nav_img() { ?>
<div class="nav-section nav-section-img">
	<?php if (!zm_get_option('menu_cat_cover_md') || (zm_get_option("menu_cat_cover_md") == 'menu_cover_id')) { ?>
		<?php
			$args=array( 'include' => zm_get_option('menu_cat_cover_id') );
			$cats = get_categories($args);
			foreach ( $cats as $cat ) {
				query_posts( 'cat=' . $cat->cat_ID );
		?>
		<div class="menu-img bgt">
			 <div class="thumbs-b lazy">
				<a class="thumbs-back sc" rel="external nofollow" href="<?php echo get_category_link($cat->cat_ID); ?>" style="background-image: url(<?php echo cat_cover_url(); ?>) !important;"></a>
			</div>
			<h3><a href="<?php echo get_category_link($cat->cat_ID);?>" rel="bookmark"><?php echo $cat->cat_name; ?></a></h3>
			<div class="clear"></div>
		</div>
		<?php } wp_reset_query(); ?>
	<?php } ?>

	<?php if (zm_get_option('menu_cat_cover_md') == 'menu_post_id') { ?>
		<?php 
			$loop = new WP_Query( array( 'meta_key' => 'menu_post', 'ignore_sticky_posts' => 1 ));
			while ( $loop->have_posts() ) : $loop->the_post();
		?>
		 <div class="menu-img menu-post bgt">
			<?php zm_menu_img(); ?>
			<?php the_title( sprintf( '<h3><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
		</div>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	<?php } ?>

	<?php if (zm_get_option('menu_cat_cover_md') == 'menu_all_cat') { ?>
	<ul class="menu-all-cat bgt">
		<?php
			$args=array(
				'exclude' => zm_get_option('menu_cat_e_id'),
				'hide_empty' => 0
			);
			$cats = get_categories($args);
			foreach ( $cats as $cat ) {
			query_posts( 'cat=' . $cat->cat_ID );
		?>
		<li>
			<a class="bk" href="<?php echo get_category_link($cat->cat_ID);?>" rel="bookmark">
				<?php if (zm_get_option('cat_icon')) { ?>
					<?php $term_id = get_query_var('cat');if (get_option('zm_taxonomy_icon'.$term_id)) { ?><i class="<?php echo zm_taxonomy_icon_code(); ?>"></i><?php } ?>
					<?php $term_id = get_query_var('cat');if (get_option('zm_taxonomy_svg'.$term_id)) { ?><svg class="icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code(); ?>"></use></svg><?php } ?>
				<?php } ?>
				<?php single_cat_title(); ?>
			</a>
		</li>
		<?php } ?>
		<?php wp_reset_query(); ?>
	</ul>
	<?php } ?>
	<div class="clear"></div>
</div>
<?php }

// nav cat
function nav_cat() { ?>
<div class="nav-section ">
	<?php if (!zm_get_option('nav_cat_md') || (zm_get_option("nav_cat_md") == 'nav_cover')) { ?>
		<?php
			$args=array( 'exclude' => zm_get_option('nav_cat_e_id') );
			$cats = get_categories($args);
			foreach ( $cats as $cat ) {
				query_posts( 'cat=' . $cat->cat_ID );
		?>
		<div class="menu-img bgt">
			 <div class="thumbs-b lazy bgt">
				<a class="thumbs-back sc" rel="external nofollow" href="<?php echo get_category_link($cat->cat_ID); ?>" style="background-image: url(<?php echo cat_cover_url(); ?>) !important;"></a>
			</div>
			<h3><a href="<?php echo get_category_link($cat->cat_ID);?>" rel="bookmark"><?php echo $cat->cat_name; ?></a></h3>
			<div class="clear"></div>
		</div>
		<?php } wp_reset_query(); ?>
	<?php } ?>

	<?php if (zm_get_option('nav_cat_md') == 'nav_cat') { ?>
	<ul class="menu-all-cat bgt">
		<?php
			$args=array(
				'exclude' => zm_get_option('nav_cat_e_id'),
				'hide_empty' => 0
			);
			$cats = get_categories($args);
			foreach ( $cats as $cat ) {
			query_posts( 'cat=' . $cat->cat_ID );
		?>
		<li>
			<a class="bk" href="<?php echo get_category_link($cat->cat_ID);?>" rel="bookmark">
				<?php if (zm_get_option('cat_icon')) { ?>
					<?php $term_id = get_query_var('cat');if (get_option('zm_taxonomy_icon'.$term_id)) { ?><i class="<?php echo zm_taxonomy_icon_code(); ?>"></i><?php } ?>
					<?php $term_id = get_query_var('cat');if (get_option('zm_taxonomy_svg'.$term_id)) { ?><svg class="icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code(); ?>"></use></svg><?php } ?>
				<?php } ?>
			<?php single_cat_title(); ?>
			</a>
		</li>
		<?php } ?>
		<?php wp_reset_query(); ?>
	</ul>
	<?php } ?>
	<div class="clear"></div>
</div>
<?php }

function nav_single() { ?>
<?php if (!zm_get_option('post_nav_no')) { ?>
<?php
	if (!zm_get_option('post_nav_mode') || (zm_get_option('post_nav_mode') == 'full_site')) {
		$npm = false;
	} else {
		$npm = true;
	}
 ?>
<?php if (zm_get_option('post_nav_img')) { ?>
<nav class="post-nav-img" <?php aos_a(); ?>>
	<?php 
		global $post;
		$prevPost = get_previous_post( $npm );
		if( $prevPost ) {
			$args = array(
				'posts_per_page' => 1,
				'include' => $prevPost->ID
			);

			$prevPost = get_posts( $args );
			foreach ( $prevPost as $post ) {
				setup_postdata( $post );
				?>
				<div class="nav-img-box post-previous-box ms bk hz">
					<figure class="nav-thumbnail"><?php zm_thumbnail(); ?></figure>
					<a href="<?php the_permalink(); ?>">
						<div class="nav-img post-previous-img">
							<div class="post-nav"><?php _e( '上一篇', 'begin' ); ?></div>
							<div class="nav-img-t"><?php the_title(); ?></div>
						</div>
					</a>
				</div>
				<?php
				wp_reset_postdata();
			}
		} else {
			echo '<div class="no-nav-img nav-img-box post-previous-box ms bk hz">';
			echo '<div class="nav-img post-previous-img">';
			echo '<div class="post-nav">' . sprintf(__( '已是最后', 'begin' )) . '</div>';
			echo '</div>';
			echo '</div>';
		}
		$nextPost = get_next_post( $npm );
		if( $nextPost ) {
			$args = array(
				'posts_per_page' => 1,
				'include' => $nextPost->ID
			);
			$nextPost = get_posts( $args );
			foreach ( $nextPost as $post ) {
				setup_postdata( $post );
				?>
				<div class="nav-img-box post-next-box ms bk">
					<figure class="nav-thumbnail"><?php zm_thumbnail(); ?></figure>
					<a href="<?php the_permalink(); ?>">
						<div class="nav-img post-next-img">
							<div class="post-nav"><?php _e( '下一篇', 'begin' ); ?></div>
							<div class="nav-img-t"><?php the_title(); ?></div>
						</div>
					</a>
				</div>
				<?php
				wp_reset_postdata();
			}
		} else {
			echo '<div class="no-nav-img nav-img-box post-next-box ms bk">';
			echo '<div class="nav-img post-next-img">';
			echo '<div class="post-nav">' . sprintf(__( '已是最新', 'begin' )) . '</div>';
			echo '</div>';
			echo '</div>';
		}
	?>
	<div class="clear"></div>
</nav>
<?php } else { ?>
<nav class="nav-single" <?php aos_a(); ?>>
	<?php
		if (get_previous_post( $npm )) {
			previous_post_link( '%link','<span class="meta-nav meta-previous ms bk"><span class="post-nav"><i class="be be-arrowleft"></i>' . sprintf(__( '上一篇', 'begin' )) . '</span><br/>%title</span>', false ); } else { echo "<span class='meta-nav meta-previous ms bk'><span class='post-nav'><i class='be be-arrowup'></i><br/></span>" . sprintf(__( '已是最后', 'begin' )) . "</span>";
		}
		if (get_next_post( $npm )) {
			next_post_link( '%link', '<span class="meta-nav meta-next ms bk"><span class="post-nav">' . sprintf(__( '下一篇', 'begin' )) . ' <i class="be be-arrowright"></i></span><br/>%title</span>', false ); } else { echo "<span class='meta-nav meta-next ms bk'><span class='post-nav'><i class='be be-arrowup'></i><br/></span>" . sprintf(__( '已是最新', 'begin' )) . "</span>"; 
		}
	?>
	<div class="clear"></div>
</nav>
<?php } ?>
<?php } ?>
<?php }

function type_nav_single() { ?>
<?php if (!zm_get_option('post_nav_no')) { ?>
<nav class="nav-single" <?php aos_a(); ?>>
	<?php
		if (get_previous_post()) { previous_post_link( '%link','<span class="meta-nav meta-previous ms bk"><span class="post-nav"><i class="be be-arrowleft ri"></i>' . sprintf(__( '上一篇', 'begin' )) . '</span><br/>%title</span>' ); } else { echo "<span class='meta-nav meta-previous ms bk'><span class='post-nav'><i class='be be-arrowup'></i><br/></span>" . sprintf(__( '已是最后', 'begin' )) . "</span>"; }
		if (get_next_post()) { next_post_link( '%link', '<span class="meta-nav meta-next ms bk"><span class="post-nav">' . sprintf(__( '下一篇', 'begin' )) . ' <i class="be be-arrowright"></i></span><br/>%title</span>' ); } else { echo "<span class='meta-nav meta-next ms bk'><span class='post-nav'><i class='be be-arrowup'></i><br/></span>" . sprintf(__( '已是最新', 'begin' )) . "</span>"; }
	?>
	<div class="clear"></div>
</nav>
<?php } ?>
<?php }

// random post
function random_post() { ?>
<div class="new_cat">
	<ul>
		<?php
		$cat = get_the_category();
		foreach($cat as $key=>$category){
		    $catid = $category->term_id;
		}
		$args = array( 'orderby' => 'rand', 'showposts' => 5, 'ignore_sticky_posts' => 1 );
		$query_posts = new WP_Query();
		$query_posts->query($args);
		while ($query_posts->have_posts()) : $query_posts->the_post();
		?>
		<li>
			<span class="thumbnail">
				<?php zm_thumbnail(); ?>
			</span>
			<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
			<span class="date"><?php the_time('m/d') ?></span>
			<?php views_span(); ?>
		</li>
		<?php endwhile;?>
		<?php wp_reset_query(); ?>
	</ul>
</div>
<?php }

// child cat cover
function child_cover() { ?>
<?php if ( zm_get_option( 'cat_cover' ) ) { ?>
	<div class="cat-cover-box">
		<?php
			global $cat;
			$cats = get_categories( array(
				'child_of' => 1,
				'parent' => get_category_id( $cat ),
				'hide_empty' => 0
			 ));

			foreach ( $cats as $cat ) {
				query_posts( 'cat=' . $cat->cat_ID );
		?>

			<div class="cat-rec-main cat-rec-<?php echo zm_get_option( 'img_f' ); ?>">
				<a href="<?php echo get_category_link( $cat->cat_ID ); ?>" rel="bookmark">
					<div class="cat-rec-content ms bk" <?php aos_a(); ?>>
						<div class="cat-rec lazy<?php $term_id = get_query_var( 'cat' );if ( get_option('zm_taxonomy_svg'.$term_id ) ) { ?> cat-rec-ico-svg<?php } else { ?> cat-rec-ico-img<?php } ?>">
							<?php if ( !zm_get_option( 'child_cover_ico' ) ) { ?>
								<div class="cat-rec-back" data-src="<?php echo cat_cover_url(); ?>"></div>
							<?php } else { ?>
								<?php $term_id = get_query_var( 'cat' );if ( !get_option('zm_taxonomy_svg'.$term_id ) ) { ?>
									<?php $term_id = get_query_var( 'cat' );if ( get_option('zm_taxonomy_icon'.$term_id ) ) { ?><i class="cat-rec-icon fd <?php echo zm_taxonomy_icon_code(); ?>"></i><?php } ?>
								<?php } else { ?>
									<?php $term_id = get_query_var( 'cat' );if ( get_option('zm_taxonomy_svg'.$term_id ) ) { ?><svg class="cat-rec-svg bgt fd icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code(); ?>"></use></svg><?php } ?>
								<?php } ?>
							<?php } ?>
						</div>
						<h4 class="cat-rec-title bgt"><?php echo $cat->cat_name; ?></h4>
						<?php if ( get_the_archive_description() ) { ?>
							<?php echo the_archive_description( '<div class="cat-rec-des bgt">', '</div>' ); ?>
						<?php } else { ?>
							<div class="cat-rec-des bgt"><?php _e( '暂无描述', 'begin' ); ?></div>
						<?php } ?>
						<div class="rec-adorn-s"></div><div class="rec-adorn-x"></div>
						<div class="clear"></div>
					</div>
				</a>
			</div>
		<?php } ?>
		<?php wp_reset_query(); ?>
		<div class="clear"></div>
	</div>
<?php } ?>
<?php }

// special_single_content
function special_single_content() { ?>
	<?php global $wpdb, $post; ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<?php if ( is_single() ) : ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php else : ?>
		<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('post bk da'); ?>>
		<?php endif; ?>

			<header class="entry-header">
				<?php if ( get_post_meta($post->ID, 'header_img', true) || get_post_meta($post->ID, 'header_bg', true) ) { ?>
					<div class="entry-title-clear"></div>
				<?php } else { ?>
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				<?php } ?>

			</header><!-- .entry-header -->

			<div class="entry-content">
				<div class="single-content">
					<?php the_content(); ?>
				</div>
				<div class="clear"></div>
			</div><!-- .entry-content -->
			<footer class="page-meta-zt">
				<?php begin_page_meta_zt(); ?>
			</footer><!-- .entry-footer -->
			<div class="clear"></div>
		</article><!-- #page -->
	<?php endwhile; ?>
	<?php wp_reset_query(); ?>
<?php }

// 文字截断
function begin_strimwidth($str, $start, $width, $trimmarker ){
	$output = preg_replace('/^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$start.'}((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$width.'}).*/s','\1',$str);
	return $output.$trimmarker;
}

// begin_trim_words()
function begin_trim_words() { ?>
<?php if (has_excerpt('')){
		echo wp_trim_words( get_the_excerpt(), zm_get_option('word_n'), '...' );
	} else {
		$content = get_the_content();
		$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
		if (zm_get_option('languages_en')) {
			echo begin_strimwidth(strip_tags($content), 0, zm_get_option('words_n'),'...');
		} else {
			echo wp_trim_words( $content, zm_get_option('words_n'), '...' );
		}
	}
?>
<?php }

function begin_primary_class() { ?>
	<div id="<?php global $wpdb, $post; if ( get_post_meta($post->ID, 'sidebar_l', true) ) { ?>primary-l<?php } else { ?>primary<?php } ?>" class="content-area<?php global $wpdb, $post; if ( get_post_meta( $post->ID, 'no_sidebar', true ) || ( zm_get_option('single_no_sidebar') ) ) { ?> no-sidebar<?php } ?><?php if (zm_get_option('meta_b')) { ?> meta-b<?php } ?>">
<?php }

function begin_abstract() { ?>
	<?php if ( has_excerpt() ) { ?><span class="abstract<?php global $wpdb, $post; if ( get_post_meta($post->ID, 'no_abstract', true) ) : ?> no_abstract<?php endif; ?>"><fieldset><legend><?php _e( '摘要', 'begin' ); ?></legend><?php the_excerpt() ?><div class="clear"></div></fieldset></span><?php }?>
<?php }

// 正文
function content_support() { ?>
	<?php global $wpdb, $post; if (zm_get_option('all_more') && !get_post_meta($post->ID, 'not_more', true)) { ?><?php all_content(); ?><?php } ?>
	<?php global $wpdb, $post; if ( zm_get_option('begin_today') && !get_post_meta($post->ID, 'no_today', true) ) { ?><?php echo begin_today(); ?><?php } ?>
	<?php if (zm_get_option('copyright_info')) { ?><div class="copyright-post bk dah" ><?php echo zm_get_option('copyright_content'); ?></div><div class="clear"></div><?php } ?>
	<?php begin_link_pages(); ?>
	<?php be_like(); ?>
	<?php if (zm_get_option('single_weixin')) { ?>
		<?php get_template_part( 'template/weixin' ); ?>
	<?php } ?>

	<div class="content-empty"></div>
	<?php get_template_part('ad/ads', 'single-b'); ?>
	<footer class="single-footer">
		<?php begin_single_cat(); ?>
	</footer>
<?php }

// footer links
function links_footer() { ?>
	<div id="links">
		<?php if (zm_get_option('footer_img')) { ?>
			<ul class="links-mode" <?php aos_a(); ?>><?php wp_list_bookmarks('title_li=&before=<li class="lx7" data-aos="fade-up"><span class="link-f link-img sup">&after=</span></li>&categorize=1&show_images=1&orderby=rating&order=DESC&category='.zm_get_option('link_f_cat')); ?></ul>
		<?php } else { ?>
			<?php if (zm_get_option('home_link_ico')) { ?>
				<?php echo begin_home_link_ico(); ?>
			<?php } else { ?>
				<?php wp_list_bookmarks('title_li=&before=<ul class="lx7" data-aos="zoom-in"><li class="link-f link-name sup" data-aos="zoom-in">&after=</li></ul>&categorize=0&show_images=0&orderby=rating&order=DESC&category='.zm_get_option('link_f_cat')); ?>
			<?php } ?>
		<?php } ?>
		<div class="clear"></div>
		<?php if ( zm_get_option('link_url') == '' ) { ?><?php } else { ?><div class="more-link" data-aos="zoom-in"><a href="<?php echo get_permalink( zm_get_option('link_url') ); ?>" target="_blank" title="<?php _e( '更多链接', 'begin' ); ?>"><i class="be be-more"></i></a></div><?php } ?>
	</div>
<?php }

// page links
function links_page() { ?>
	<?php 
		$args = array(
			'before'             => '<li><span class="lx7" data-aos="zoom-in"><span class="link-f">',
			'after'              => '</span></span></li>',
			'title_li'           => '',
			'categorize'         => 1,
			'show_images'        => zm_get_option('links_img_txt'),
			'orderby'            => 'rating',
			'order'              => 'DESC',
			'category_orderby'   => 'description',
			'exclude'            => zm_get_option('link_cat'),
			'title_before'       => '<h3 class="link-cat" data-aos="zoom-in">',
			'title_after'        => '</h3>',
			'category_before'    => '<div class="clear"></div>',
			'category_after'     => '<div class="clear"></div>'
		);
	?>
	<ul class="wp-list<?php if (zm_get_option('links_img_txt')) { ?> links-img<?php } ?>"><?php wp_list_bookmarks($args); ?></ul>
<?php }

function header_title() { ?>
<header class="entry-header<?php if (!zm_get_option('meta_b')) { ?> meta-t<?php } ?><?php if (zm_get_option('title_c')) { ?> entry-header-c<?php } ?>">
<?php }

function nav_ace() { ?>
<?php if (zm_get_option('nav_ace')) { ?> nav-ace<?php } ?>
<?php }

// register-form
function register_form() { ?>
	<form class="zml-register-form" action="<?php echo esc_attr(LoginAjax::$url_register); ?>" method="post">
		<div>
			<div class="zml-status"></div>
			<div class="zml-username zml-ico">
				<?php username_iocn(); ?>
				<label>
					<input type="text" name="user_login" class="input-control dah bk" size="20" tabindex="10" required="required" placeholder="<?php _e( '用户名', 'begin' ); ?>*" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e( '用户名', 'begin' ); ?>*'" />
				</label>
			</div>
			<div class="zml-email zml-ico">
				<?php email_iocn(); ?>
				<label>
					<input type="text" name="user_email" class="input-control dah bk" size="25" tabindex="20" required="required" placeholder="<?php _e( '邮箱', 'begin' ); ?> *" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e( '邮箱', 'begin' ); ?> *'" />
				</label>
			</div>
			<?php do_action('register_form'); ?>
			<?php do_action('zml_register_form'); ?>
			<div class="submit zml-submit-button">
				<input type="submit" name="wp-submit" class="button-primary" value="<?php _e( '注册', 'begin' ); ?>" tabindex="100" />
			</div>
			<input type="hidden" name="login-ajax" value="register" />
			<div class="zml-register-tip"><?php _e( '注册信息通过邮箱发给您', 'begin' ); ?></div>
		</div>
	</form>
<?php }

// submit_info
function be_info_insert( $return = 0 ) {
	$str = submit_info();
	if ( $return ) {
		return $str;
	} else { 
		echo $str;
	}
}
function add_submit_info($content) {
	if( !is_feed() && !is_home() && is_singular() && is_main_query() ) {
		$content .= be_info_insert( 0 );
	}
	return $content;
}
add_filter( 'the_content', 'add_submit_info' );

function submit_info() { ?>
<?php global $wpdb, $post; if ( get_post_meta($post->ID, 'message_a', true) ) : ?>
	<?php 
		$message_a = get_post_meta($post->ID, 'message_a', true);
		$message_b = get_post_meta($post->ID, 'message_b', true);
		$message_c = get_post_meta($post->ID, 'message_c', true);
		$message_d = get_post_meta($post->ID, 'message_e', true);
		$message_e = get_post_meta($post->ID, 'message_e', true);
		$message_f = get_post_meta($post->ID, 'message_f', true);
	?>
	<div class="submit-info-main">
		<div class="submit-info">
			<p><strong><i class="be be-sort"></i><?php echo zm_get_option('info_a'); ?></strong><span><?php echo $message_a; ?></span></p>
			<?php if ( get_post_meta($post->ID, 'message_b', true) ) { ?>
				<p><strong><i class="be be-sort"></i><?php echo zm_get_option('info_b'); ?></strong><span><?php echo $message_b; ?></span></p>
			<?php } ?>
			<?php if ( get_post_meta($post->ID, 'message_c', true) ) { ?>
				<p><strong><i class="be be-sort"></i><?php echo zm_get_option('info_c'); ?></strong><span><?php echo $message_c; ?></span></p>
			<?php } ?>
			<?php if ( get_post_meta($post->ID, 'message_d', true) ) { ?>
				<p><strong><i class="be be-sort"></i><?php echo zm_get_option('info_d'); ?></strong><span><?php echo $message_d; ?></span></p>
			<?php } ?>
			<?php if ( get_post_meta($post->ID, 'message_e', true) ) { ?>
				<p><strong><i class="be be-sort"></i><?php echo zm_get_option('info_e'); ?></strong><span><?php echo $message_e; ?></span></p>
			<?php } ?>
			<?php if ( get_post_meta($post->ID, 'message_f', true) ) { ?>
				<p><strong><i class="be be-sort"></i><?php echo zm_get_option('info_f'); ?></strong><span><?php echo $message_f; ?></span></p>
			<?php } ?>
		</div>
	</div>
<?php endif; ?>
<?php }

// logged_manage
function logged_manage() { ?>
<div class="sidebox">
	<?php if ( zm_get_option('user_back') ) { ?>
		<div class="author-back"><img src="<?php echo zm_get_option('user_back'); ?>" alt="bj"/></div>
	<?php } ?>

	<div class="usericon bk">
		<?php if (current_user_can( 'manage_options' )) { ?>
			<a href="<?php echo admin_url(); ?>" rel="external nofollow" target="_blank" title="<?php _e( '后台管理', 'begin' ); ?>">
		<?php } else { ?>
			<a href="<?php echo admin_url(); ?>" rel="external nofollow">
		<?php } ?>
		<?php 
			global $current_user; wp_get_current_user();
			if (zm_get_option('cache_avatar')):
				echo begin_avatar( $current_user->user_email, 96, '', $current_user->display_name);
			else :
				echo get_avatar( $current_user->user_email, 96, '', $current_user->display_name);
			endif;
		?>
		</a>
	</div>
	<h4>
		<?php global $current_user; wp_get_current_user();
			echo '<a href="' . admin_url() . '" rel="external nofollow">';
			echo '<div class="ml-name">';
			echo '' . $current_user->display_name . "\n";
			echo '</div>';
			echo '</a>';
		?>
	</h4>
	<div class="userinfo">
		<div>
			<?php if (zm_get_option('user_url')) { ?>
				<a class="user-url bk da" href="<?php echo get_permalink( zm_get_option('user_url') ); ?>" target="_blank"><?php _e( '用户中心', 'begin' ); ?></a>
			<?php } ?>
			<a class="bk da" href="<?php echo wp_logout_url('index.php'); ?>"><?php _e( '安全退出', 'begin' ); ?></a>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php }

// Cat Module h3
function cat_module_title() { ?>
	<?php if (zm_get_option('cat_icon')) { ?>
		<?php $term_id = get_query_var('cat');if (get_option('zm_taxonomy_icon'.$term_id)) { ?><i class="t-icon <?php echo zm_taxonomy_icon_code(); ?>"></i><?php } ?>
		<?php $term_id = get_query_var('cat');if (get_option('zm_taxonomy_svg'.$term_id)) { ?><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code(); ?>"></use></svg><?php } ?>
		<?php $term_id = get_query_var('cat'); if (!get_option('zm_taxonomy_icon'.$term_id) && !get_option('zm_taxonomy_svg'.$term_id)) { ?><?php title_i(); ?><?php } ?>
	<?php } else { ?>
		<?php title_i(); ?>
	<?php } ?>
	<?php single_cat_title(); ?><?php more_i(); ?>
<?php }

// nav_weixin
function nav_weixin() { ?>
<div class="nav-weixin-but bgt">
	<div class="nav-weixin-img yy bk"><img src="<?php echo zm_get_option('nav_weixin_img'); ?>" alt="weinxin" /><p>微信</p>
		<span class="arrow-down"></span>
	</div>
	<div class="nav-weixin-i bk da"><i class="be be-weixin"></i></div>
	<div class="nav-weixin da"></div>
</div>
<?php }

// login_but
function login_but() { ?>
	<?php if (zm_get_option('menu_login') && ( !wp_is_mobile() ) ) { ?>
		<?php if ( !is_user_logged_in()){ ?>
			<?php if (!zm_get_option('menu_reg') || !get_option('users_can_register') ) { ?>
				<div class="login-but menu-login-but login-but-only show-layer" data-show-layer="login-layer" role="button"><div class="but-i"><i class="be be-personoutline"></i></div></div>
			<?php } else { ?>
				<div class="login-but menu-login-but"><div class="but-i dah"><i class="be be-personoutline"></i></div>
					<div class="menu-login-box bgt"><div class="arrow-up"></div><div class="menu-login yy bk"><?php login_info(); ?></div></div>
				</div>
			<?php } ?>
		<?php } else { ?>
			<span class="menu-login"><?php login_info(); ?></span>
		<?php } ?>
	<?php } ?>
<?php }

// be_ajax_tabs
function be_ajax_tabs() {
	$display_categories = explode(',',zm_get_option('new_cat_id') ); foreach ($display_categories as $category) {
	query_posts( array( 'post_status' => 'publish', 'post_type' => 'post', 'orderby' => 'date', 'ignore_sticky_posts' => 1, 'cat' => $category) );
		$tab = $_POST['tab'];
		switch ($tab) {
			case $category:
				if ( have_posts() ) :
				while ( have_posts() ) : the_post();
					get_template_part( 'template/content', get_post_format() );
				endwhile;
				echo '<div class="new-more da"><a href="' . get_category_link($category) . '"><i class="be be-arrowright"></i></a></div>';
				wp_reset_query();
				else :
				echo '<article class="post ms bk">' . sprintf(__( '暂无文章', 'begin' )) . '</article>';
				endif;
			break;
		}
	}
	die();
}

add_action('wp_ajax_be_tabs', 'be_ajax_tabs');
add_action('wp_ajax_nopriv_be_tabs', 'be_ajax_tabs');

// be_ajax_tabs_img
function be_ajax_tabs_img() {
	$display_categories = explode(',',zm_get_option('new_cat_id') ); foreach ($display_categories as $category) {
	query_posts( array( 'post_status' => 'publish', 'post_type' => 'post', 'orderby' => 'date', 'ignore_sticky_posts' => 1, 'cat' => $category) );
		$tab = $_POST['tab'];
		switch ($tab) {
			case $category:
				if ( have_posts() ) :
				grid_template_c();
				echo '<div class="new-more-img da"><a href="' . get_category_link($category) . '"><i class="be be-arrowright"></i></a></div>';
				wp_reset_query();
				else :
				echo '<p class="tab-no">' . sprintf(__( '暂无文章', 'begin' )) . '</p>';
				endif;
			break;
		}
	}
	die();
}

add_action('wp_ajax_img_tabs', 'be_ajax_tabs_img');
add_action('wp_ajax_nopriv_img_tabs', 'be_ajax_tabs_img');

// be_ajax_tabs_grid
function be_ajax_tabs_grid() {
	$display_categories = explode(',',zm_get_option('grid_new_cat_id') ); foreach ($display_categories as $category) {
	if (zm_get_option('grid_new_cat_n')) {
		$news_n = '';
	} else {
		$news_n = zm_get_option('grid_cat_news_n');
	}
	query_posts( array( 'posts_per_page' => $news_n, 'post_status' => 'publish', 'post_type' => 'post', 'orderby' => 'date', 'ignore_sticky_posts' => 1, 'cat' => $category) );
		$tab = $_POST['tab'];
		switch ($tab) {
			case $category:
				if ( have_posts() ) :
				while ( have_posts() ) : the_post();
				grid_new();
				endwhile;
				wp_reset_query();
				echo '<div class="clear"></div><div class="grid-cat-more"><a href="' . get_category_link($category) . '" title="' . sprintf(__( '更多', 'begin' )) . '"><i class="be be-more"></i></a></div>';
				else :
				echo '<p class="tab-no">' . sprintf(__( '暂无文章', 'begin' )) . '</p>';
				endif;
			break;
		}
	}
	die();
}

add_action('wp_ajax_grid_tabs', 'be_ajax_tabs_grid');
add_action('wp_ajax_nopriv_grid_tabs', 'be_ajax_tabs_grid');

// cms_ajax_tabs_cat
function cms_ajax_tabs_cat() {
	$display_categories = explode(',',zm_get_option('cms_cat_tab_id') ); foreach ($display_categories as $category) {
		$tab = $_POST['tab'];
		switch ($tab) {
			case $category:
				if (zm_get_option('cms_cat_tab_img') ) { 
					echo '<div class="cat-tab-img-box">';
						query_posts( array( 'showposts' => 4, 'post_status' => 'publish', 'post_type' => 'post', 'orderby' => 'date', 'ignore_sticky_posts' => 1, 'cat' => $category) );
						while ( have_posts() ) : the_post();
						echo '<div class="cat-tab-img">';
						echo '<div class="cat-tab-img-x4">';
						echo '<figure class="tab-thumbnail">';
						zm_thumbnail();
						echo '</figure>';
						echo '</div>';
						echo '</div>';
						endwhile;
						wp_reset_query();
						echo '<div class="clear"></div>';
					echo '</div>';
				}
				echo '<div class="clear"></div>';
				echo '<ul class="cat-site">';
					query_posts( array( 'showposts' => zm_get_option('cms_cat_tab_n'), 'post_status' => 'publish', 'post_type' => 'post', 'orderby' => 'date', 'ignore_sticky_posts' => 1, 'cat' => $category) );
					$s=0;
					if ( have_posts() ) :
					while ( have_posts() ) : the_post();
					$s++;
					the_title( sprintf( '<li class="list-title-date high-'. mt_rand(1, $s) .'"><a class="srm" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></li>' );
					endwhile;
					wp_reset_query();
				echo '</ul>';
				echo '<div class="clear"></div>';
				echo '<div class="grid-cat-more"><a href="' . get_category_link($category) . '" title="' . sprintf(__( '更多', 'begin' )) . '"><i class="be be-more"></i></a></div>';
				else :
				echo '<p class="tab-no">' . sprintf(__( '暂无文章', 'begin' )) . '</p>';
				endif;
			break;
		}
	}
	die();
}

add_action('wp_ajax_cms_tabs', 'cms_ajax_tabs_cat');
add_action('wp_ajax_nopriv_cms_tabs', 'cms_ajax_tabs_cat');

function page_class() {
	global $wpdb, $post;
	if ( zm_get_option('no_copy') && !is_page() && !is_search() && !current_user_can('level_10') && !get_post_meta($post->ID, 'allow_copy', true) ) {
		echo ' copies';
	}
	if ( zm_get_option( 'fresh_no' ) ) {
		echo ' fresh';
	}

	if (zm_get_option('aos_scroll') && !wp_is_mobile() ) {
		echo ' beaos';
	}
}

if ( !zm_get_option('aos_data') ) {
	function aos_a() {}
} else {
	function aos_a() {be_aos_a();}
}

function be_aos_a() { ?>
data-aos=<?php echo zm_get_option('aos_data'); ?>
<?php }

function aos_b() { ?>
data-aos="zoom-in"
<?php }
function aos() { ?>
data-aos=""
<?php }
function aos_c() { ?>
data-aos="fade-zoom-in"
<?php }
function aos_d() { ?>
data-aos="fade-right"
<?php }
function aos_e() { ?>
data-aos="fade-left"
<?php }

function aos_f() { ?>
data-aos="fade-in"
<?php }

// widgets aos
function widgets_data_aos($params) {
	$aos = zm_get_option('aos_data');
	$params[0]['before_widget'] = str_replace('data-aos="', 'data-aos="' . $aos . '', $params[0]['before_widget']);
	return $params;
}

// single tag
function be_tags() { ?>
<?php if (zm_get_option('post_tags') && is_single() ) { ?><div class="single-tag"><?php the_tags( '<ul class="be-tags"><li data-aos="zoom-in">', '</li><li data-aos="zoom-in">', '</li></ul>' ); ?></div><?php } ?>
<?php }

// all sites cat
function all_sites_cat() { ?>
<?php
echo '<ul class="all-site-cat ms da bk" ';
$terms = get_terms("favorites");
$count_posts = wp_count_posts( 'sites' ); 
$count = count($terms);
echo aos_b();
echo '>';
echo '<li class="sites-cat-count">';
echo '<span class="sites-all-cat-ico"></span>';
echo '<span class="sites-all-cat-ico"></span>';
echo sprintf(__( '现有', 'begin' ));
if ( is_tax('favorites') ) {
	$posts = get_queried_object();
	echo $posts->count;
} else {
	echo $published_posts = $count_posts->publish;
}
echo sprintf(__( '个网站入驻', 'begin' ));
echo '<span class="sites-all-cat-ico"></span>';
echo '<span class="sites-all-cat-ico"></span>';
echo '</li>';
if ( $count > 0 ){
	echo '<div class="clear"></div>';
echo '<div class="rec-adorn-s"></div><div class="rec-adorn-x"></div>';
	foreach ( $terms as $term ) {
		echo '<li class="srp"><a href="' . get_term_link( $term ) . '" ><i class="be be-sort"></i>' . $term->name . '</a></li>';
	}
}
echo '</ul>';
echo '<div class="clear"></div>';
?>
<?php }

// favorites
function sites_favorites() { ?>
<?php global $post; ?>
<article <?php post_class('sites-post sup bk'); ?> <?php aos_a(); ?>>
	<?php if ( zm_get_option( 'sites_adorn' ) ) { ?><div class="rec-adorn-s"></div><div class="rec-adorn-x"></div><?php } ?>
	<?php $sites_link = get_post_meta( $post->ID, 'sites_link', true ); ?>
	<?php $sites_url = get_post_meta( $post->ID, 'sites_url', true ); ?>
	<?php $sites_description = get_post_meta( $post->ID, 'sites_description', true ); ?>
	<?php $sites_des = get_post_meta( $post->ID, 'sites_des', true ); ?>
	<?php if ( zm_get_option( 'sites_ico' ) ) { ?>
		<?php if ( get_post_meta( $post->ID, 'sites_url', true )) { ?>
			<a href="<?php echo $sites_url; ?>" target="_blank" rel="external nofollow">
				<div class="sites-ico dah bk load"><img class="sites-img" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" data-original="<?php echo zm_get_option( 'favicon_api' ); ?><?php echo $sites_url; ?>" alt="<?php the_title(); ?>"></div>
			</a>
		<?php } else { ?>
			<a href="<?php echo $sites_link; ?>" target="_blank" rel="external nofollow">
				<div class="sites-ico dah bk load"><img class="sites-img" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" data-original="<?php echo zm_get_option( 'favicon_api' ); ?><?php echo $sites_link; ?>" alt="<?php the_title(); ?>"></div>
			</a>
		<?php } ?>
	<?php } ?>
	<h4 class="sites-title bgt"><a class="bgt" href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
	<div class="sites-excerpt ease bgt">
		<?php 
			if ( get_post_meta( $post->ID, 'sites_description', true ) || get_post_meta( $post->ID, 'sites_des', true ) ) {
				echo $sites_description;
				echo $sites_des;
			} else {
				if ( get_post_meta( $post->ID, 'sites_url', true ) ) {
					echo $sites_url;
				} else {
					echo $sites_link;
				}
			?>
		<?php } ?>

	</div>
	<div class="sites-link-but bgt ease<?php if ( !zm_get_option( 'sites_ico' ) ) { ?> sites-link-but-noico<?php } ?>">
		<div class="sites-link bgt">
			<?php if ( get_post_meta( $post->ID, 'sites_url', true ) ) { ?>
				<a class="bgt" href="<?php echo $sites_url; ?>" target="_blank" rel="external nofollow"><i class="be be-sort ri"></i><?php _e( '访问', 'begin' ); ?></a>
			<?php } else { ?>
				<a class="bgt" href="<?php echo $sites_link; ?>" target="_blank" rel="external nofollow"><i class="be be-sort ri"></i><?php _e( '访问', 'begin' ); ?></a>
			<?php } ?>
		</div>
		<div class="sites-more bgt"><a class="bgt" href="<?php the_permalink(); ?>" rel="external nofollow"><?php _e( '简介', 'begin' ); ?></a></div>
	<div class="clear"></div>
</article>
<?php }

// t-mark
function t_mark_back() {
	if (zm_get_option('inf_back')) {
		return' mark-back-l';
	}
}

function t_mark() {
	global $post;
	$mark = '';
	if ( get_post_meta( $post->ID, 'mark', true ) ) {
		$mark .= '<span class="t-mark'. t_mark_back() .'">';
		$mark .= get_post_meta( $post->ID, 'mark', true );
		$mark .= '</span>';
		return $mark;
	}
}
// list date
function list_date() { ?>
<?php if ( zm_get_option( 'list_date' ) ) { ?>
	<li class="list-date"><?php the_time('m/d'); ?></li>
<?php } ?>
<?php }

// date class
function date_class() {
	$dates = '';
	if ( !zm_get_option( 'list_date' ) ) {
		$dates .= '-date';
		return $dates;
	}
}

// post tag cloud
function post_all_tag_cloud() {
	global $post;
	$number = zm_get_option( 'post_tag_cloud_n' );
	$tag_ids = wp_get_post_tags( $post->ID, array( 'fields' => 'ids' ) );
	if ( $tag_ids ) {
		wp_tag_cloud( array(
			'include'  => $tag_ids,
			'smallest' => 14,
			'largest'  => 14,
			'number'   => $number,
			'unit'     => 'px',
		) );
	}
}

function post_tag_cloud() {
	if (zm_get_option('post_tag_cloud')) {
		echo '<span class="post-tag">';
		post_all_tag_cloud();
		echo '</span>';
	}
}

// reg pages
function reg_pages() { ?>
<?php 
if (zm_get_option('bing_reg')) {
	$imgurl=get_stylesheet_directory_uri() . '/template/bing.php';
} else {
	$imgurl=zm_get_option('reg_img');
}
echo'<style type="text/css">body.custom-background, body{background: url('.$imgurl.') no-repeat;background-position: center center;background-size: cover;width: 100%;background-attachment: fixed;}</style>';
?>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/register.css" />

<div id="primary-reg">
	<main id="main" class="reg-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class("da bk"); ?>>
			<div class="reg-tab">
				<?php if (zm_get_option('reg_sign')) { ?>
					<?php if(!is_user_logged_in()) { ?>
					<?php if ( !get_option('users_can_register') )  { ?>
							<p class="reg-error"><i class="be be-info ri"></i>提示：进入后台→设置→常规→常规选项页面，勾选“任何人都可以注册”。</p>
						<?php } else { ?>
							<div class="reg-sign sign">
								<h3><?php _e( '欢迎注册本站', 'begin' ); ?></h3>
								<div class="zml-register">
									<?php register_form(); ?>
								</div>
								<div class="reg-content">
									<?php the_content(); ?>
								</div>
							</div>
						<?php } ?>
					<?php } else { ?>
						<?php logged_manage(); ?>
					<?php } ?>
				<?php } else { ?>
					<?php echo do_shortcode( '[zml]' ); ?>
				<?php } ?>
			</div>

		</article>
		<?php endwhile; ?>
	</main>
</div>
<?php }

// cat cover
function cat_cover() { ?>
	<div class="cat-rec-box">
	<?php if (zm_get_option( 'cat_tag_cover' ) ) { ?>
		<?php 
			$args = array( 'include' => zm_get_option( 'cat_cover_tag_id' ), 'hide_empty' => 0 );
			$tags = get_tags( $args );
			foreach ( $tags as $tag ) { 
				$tagid = $tag->term_id; 
				query_posts( "tag_id=$tagid" );
		?>

		<div class="cat-rec-main cat-rec-<?php echo zm_get_option( 'cover_img_f' ); ?>">
			<a href="<?php echo get_tag_link( $tagid );?>" rel="bookmark">
			<div class="cat-rec-content ms bk" <?php aos_a(); ?>>
				<div class="cat-rec lazy<?php $term_id = get_query_var( 'cat' );if ( get_option('zm_taxonomy_svg'.$tagid ) ) { ?> cat-rec-svg<?php } else { ?> cat-rec-ico-img<?php } ?>">
					<?php if ( zm_get_option( 'cat_rec_m' ) == 'cat_rec_img' ) { ?>
						<?php if ( zm_get_option( 'cat_cover' ) ) { ?>
							<div class="cat-rec-back" data-src="<?php echo cat_cover_url(); ?>"></div>
						<?php } ?>
					<?php } ?>
					<?php if ( !zm_get_option( 'cat_rec_m' ) || ( zm_get_option( 'cat_rec_m' ) == 'cat_rec_ico' ) ) { ?>
						<?php if ( zm_get_option( 'cat_icon' ) ) { ?>
							<?php $term_id = get_query_var( 'cat' );if ( !get_option('zm_taxonomy_svg'.$tagid ) ) { ?>
								<?php $term_id = get_query_var( 'cat' );if ( get_option('zm_taxonomy_icon'.$tagid ) ) { ?><i class="cat-rec-icon fd <?php echo zm_taxonomy_icon_code(); ?>"></i><?php } ?>
							<?php } else { ?>
								<?php $term_id = get_query_var( 'cat' );if ( get_option('zm_taxonomy_svg'.$tagid ) ) { ?><svg class="cat-rec-icon bgt fd icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code(); ?>"></use></svg><?php } ?>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				</div>
				<h4 class="cat-rec-title bgt"><?php echo $tag->name; ?></h4>
				<?php if ( get_the_archive_description() ) { ?>
					<?php echo the_archive_description( '<div class="cat-rec-des bgt">', '</div>' ); ?>
				<?php } else { ?>
					<div class="cat-rec-des bgt"><p><?php _e( '暂无描述', 'begin' ); ?></p></div>
				<?php } ?>
				<div class="rec-adorn-s"></div><div class="rec-adorn-x"></div>
				<div class="clear"></div>
			</div></a>
		</div>
		<?php } wp_reset_query(); ?>

	<?php } else { ?>
		<?php
			$args = array( 'include' => zm_get_option( 'cat_cover_id' ), 'hide_empty' => 0 );
			$cats = get_categories( $args );
			foreach ( $cats as $cat ) {
				query_posts( 'cat=' . $cat->cat_ID );
		?>
		<div class="cat-rec-main cat-rec-<?php echo zm_get_option( 'cover_img_f' ); ?>">
			<a href="<?php echo get_category_link( $cat->cat_ID ); ?>" rel="bookmark">
			<div class="cat-rec-content ms bk" <?php aos_a(); ?>>
				<div class="cat-rec lazy<?php $term_id = get_query_var( 'cat' );if ( get_option('zm_taxonomy_svg'.$term_id ) ) { ?> cat-rec-ico-svg<?php } else { ?> cat-rec-ico-img<?php } ?>">
					<?php if ( zm_get_option( 'cat_rec_m' ) == 'cat_rec_img' ) { ?>
						<?php if ( zm_get_option( 'cat_cover' ) ) { ?>
							<div class="cat-rec-back" data-src="<?php echo cat_cover_url(); ?>"></div>
						<?php } ?>
					<?php } ?>
					<?php if ( !zm_get_option( 'cat_rec_m' ) || ( zm_get_option( 'cat_rec_m' ) == 'cat_rec_ico' ) ) { ?>
						<?php if ( zm_get_option( 'cat_icon' ) ) { ?>
							<?php $term_id = get_query_var( 'cat' );if ( !get_option('zm_taxonomy_svg'.$term_id ) ) { ?>
								<?php $term_id = get_query_var( 'cat' );if ( get_option('zm_taxonomy_icon'.$term_id ) ) { ?><i class="cat-rec-icon fd <?php echo zm_taxonomy_icon_code(); ?>"></i><?php } ?>
							<?php } else { ?>
								<?php $term_id = get_query_var( 'cat' );if ( get_option('zm_taxonomy_svg'.$term_id ) ) { ?><svg class="cat-rec-svg bgt fd icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code(); ?>"></use></svg><?php } ?>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				</div>
				<h4 class="cat-rec-title bgt"><?php echo $cat->cat_name; ?></h4>
				<?php if ( get_the_archive_description() ) { ?>
					<?php echo the_archive_description( '<div class="cat-rec-des bgt">', '</div>' ); ?>
				<?php } else { ?>
					<div class="cat-rec-des bgt"><?php _e( '暂无描述', 'begin' ); ?></div>
				<?php } ?>
				<div class="rec-adorn-s"></div><div class="rec-adorn-x"></div>
				<div class="clear"></div>
			</div></a>
		</div>
		<?php } wp_reset_query(); ?>
	<?php } ?>
	<div class="clear"></div>
	</div>
<?php }

// header widget
function top_widget() {
	if ( !zm_get_option( 'h_widget_p' ) || ( !wp_is_mobile() ) ) {
		if ( !zm_get_option( 'h_widget_m' ) || ( zm_get_option( 'h_widget_m' ) == 'cat_single_m' ) ) {
			if ( is_category() || is_single() ) {
				get_template_part( '/template/header-widget' );
			}
		}

		if ( zm_get_option( 'h_widget_m' ) == 'cat_m' ) {
			if ( is_category() ) {
				get_template_part( '/template/header-widget' );
			}
		}

		if ( zm_get_option( 'h_widget_m' ) == 'all_m' ) {
			get_template_part( '/template/header-widget' );
		}
	}
}

// down page
function down_page() { ?>
<?php 
	$id = isset( $_GET['id'] ) ? $_GET['id'] : '';
	$id = base64_decode( $id );
	$title = isset( get_post( $id )->post_title ) ? get_post( $id )->post_title : '';

	if ( get_post_meta( $id, 'be_down_name', true ) && get_post_meta( $id, 'down_name', true ) ) {
		$down_name = '<strong>' . sprintf( __( '资源名称', 'begin' ) ) . '：</strong>' . get_post_meta( $id, 'down_name', true );
	} else {
		$down_name = get_post_meta( $id, 'down_name', true );
	}

	if ( get_post_meta( $id, 'be_file_os', true ) && get_post_meta( $id, 'file_os', true ) ) {
		$file_os = '<strong>' . sprintf( __( '应用平台', 'begin' ) ) . '：</strong>' . get_post_meta( $id, 'file_os', true );
	} else {
		$file_os = get_post_meta( $id, 'file_os', true );
	}

	if ( get_post_meta( $id, 'be_file_inf', true ) && get_post_meta( $id, 'file_inf', true ) ) {
		$file_inf = '<strong>' . sprintf( __( '资源版本', 'begin' ) ) . '：</strong>' . get_post_meta( $id, 'file_inf', true );
	} else {
		$file_inf = get_post_meta( $id, 'file_inf', true );
	}

	if ( get_post_meta( $id, 'be_down_size', true ) && get_post_meta( $id, 'down_size', true ) ) {
		$down_size = '<strong>' . sprintf( __( '资源大小', 'begin' ) ) . '：</strong>' . get_post_meta( $id, 'down_size', true );
	} else {
		$down_size = get_post_meta( $id, 'down_size', true );
	}

	$baidu_pan         = get_post_meta( $id, 'baidu_pan', true );
	$baidu_password    = get_post_meta( $id, 'baidu_password', true );
	$baidu_pan         = get_post_meta( $id, 'baidu_pan', true);
	$down_local        = get_post_meta( $id, 'down_local', true );
	$rar_password      = get_post_meta( $id, 'rar_password', true );
	$down_official     = get_post_meta( $id, 'down_official', true );

	$down_img          = get_post_meta( $id, 'down_img', true );
	$baidu_pan_btn     = get_post_meta( $id, 'baidu_pan_btn', true );
	$down_local_btn    = get_post_meta( $id, 'down_local_btn', true );
	$down_official_btn = get_post_meta( $id, 'down_official_btn', true );
	$links_id          = get_post_meta( $id, 'links_id', true );
	$click_count       = get_post_meta( $links_id, '_surl_count', true );
?>
<?php if ( $id ) { ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta http-equiv="Cache-Control" content="no-transform" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<?php if (zm_get_option('no_referrer')) { ?><meta name="referrer" content="no-referrer" /><?php } ?>
<title><?php echo $title;?> | 下载页面</title>
<meta name="keywords" content="<?php echo $title;?>" />
<meta name="description" content="<?php echo $title;?>-下载" />
<link rel="shortcut icon" href="<?php echo zm_get_option( 'favicon' ); ?>">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo zm_get_option( 'apple_icon' ); ?>" />
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/css/down.css" />
<script src="<?php echo get_template_directory_uri(); ?>/js/fancybox.js"></script>
<?php echo zm_get_option( 'ad_t' ); ?>
<?php echo zm_get_option( 'tongji_h' ); ?>
</head>
<body <?php body_class(); ?> ontouchstart="">
<?php wp_body_open(); ?>
<div id="page" class="hfeed site">
	<?php get_template_part( 'template/menu', 'index' ); ?>
	<?php if ( zm_get_option( 'm_nav' ) ) { ?>
		<?php if ( wp_is_mobile() ) { ?><?php get_template_part( 'inc/menu-m' ); ?><?php } ?>
	<?php } ?>
	<nav class="bread">
		<div class="be-bread">
			<?php 
				echo '<span class="seat"></span><a class="crumbs" href="';
				echo home_url('/');
				echo '">';
				echo sprintf( __( '首页', 'begin' ) );
				echo "</a>";
				echo '<i class="be be-arrowright"></i>';
				echo sprintf( __( '文件下载', 'begin' ) );
				echo '<i class="be be-arrowright"></i>';
				echo $title;
				echo '</div>'
			 ?>
		</div>
	</nav>
	<?php get_template_part( 'ad/ads', 'header' ); ?>

<div id="content" class="site-content">
	<div class="down-post bk da">
		<div class="down-main">

			<div class="down-header load">
				<img src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" data-original="<?php echo zm_get_option( 'down_header_img' ); ?>" alt="<?php echo $title; ?>" />
				<h1 class="bgt"><?php echo $title;?></h1>
				<div class="clear"></div>
			</div>

			<div class="down-inf">
				<div class="desc">
					<h3>文件信息</h3>
					<?php if ( $down_name ) { ?><p><?php echo $down_name; ?></p><?php } ?>
					<?php if ( $file_os ) { ?><p><?php echo $file_os; ?></p><?php } ?>
					<?php if ( $file_inf ) { ?><p><?php echo $file_inf; ?></p><?php } ?>
					<?php if ( $down_size ) { ?><p><?php echo $down_size; ?></p><?php } ?>
					<?php if ( $links_id ) { ?><p><strong>下载次数：</strong><?php echo $click_count; ?></p><?php } ?>
				</div>
				<div class="clear"></div>

				<?php if ( zm_get_option( "login_down_key" ) ) { ?>
					<?php if ( is_user_logged_in() ) { ?>
						<div class="down-list-box">
							<div class="down-list-t">下载地址</div>
							<div class="clear"></div>
							<?php if ( $baidu_password ) { ?>
								<?php if ( $baidu_pan ) { ?><div class="down-but"><a href="<?php echo $baidu_pan;?>" onClick="copyUrl2()" target="_blank"><i class="be be-download ri"></i><?php if( $baidu_pan_btn ) { ?><?php echo $baidu_pan_btn; ?><?php } else { ?>网盘下载<?php } ?></a></div><?php } ?>
							<?php } else { ?>
								<?php if ( $baidu_pan ) { ?><div class="down-but"><a href="<?php echo $baidu_pan;?>" target="_blank"><i class="be be-download ri"></i><?php if( $baidu_pan_btn ) { ?><?php echo $baidu_pan_btn; ?><?php } else { ?>网盘下载<?php } ?></a></div><?php } ?>
							<?php } ?>
							<?php if ( $down_official ) { ?><div class="down-but"><a href="<?php echo $down_official;?>" target="_blank"><i class="be be-download ri"></i><?php if( $down_official_btn ) { ?><?php echo $down_official_btn;?><?php } else { ?>官方下载<?php } ?></a></div><?php } ?>
							<?php if ( $down_local ) { ?><div class="down-but"><a href="<?php echo $down_local;?>" target="_blank"><i class="be be-download ri"></i><?php if( $down_local_btn ) { ?><?php echo $down_local_btn; ?><?php } else { ?>本站下载<?php } ?></a></div><?php } ?>
							<div class="clear"></div>
						</div>
					<?php } else { ?>
						<div class="down-list-point"><strong>下载地址：</strong>登录可见</div>
					<?php } ?>
				<?php } else { ?>
					<div class="down-list-box">
						<div class="down-list-t">下载地址</div>
						<div class="clear"></div>
						<?php if ( $baidu_password ){ ?>
							<?php if ( $baidu_pan ) { ?><div class="down-but"><a href="<?php echo $baidu_pan; ?>" onClick="copyUrl2()" target="_blank"><i class="be be-download ri"></i><?php if( $baidu_pan_btn ) { ?><?php echo $baidu_pan_btn; ?><?php } else { ?>网盘下载<?php } ?></a></div><?php } ?>
						<?php } else { ?>
							<?php if ( $baidu_pan ) { ?><div class="down-but"><a href="<?php echo $baidu_pan; ?>" target="_blank"><i class="be be-download ri"></i><?php if ( $baidu_pan_btn ) { ?><?php echo $baidu_pan_btn; ?><?php } else { ?>网盘下载<?php } ?></a></div><?php } ?>
						<?php } ?>
						<?php if ( $down_official) { ?><div class="down-but"><a href="<?php echo $down_official; ?>" target="_blank"><i class="be be-download ri"></i><?php if ( $down_official_btn ) { ?><?php echo $down_official_btn; ?><?php } else { ?>官方下载<?php } ?></a></div><?php } ?>
						<?php if ( $down_local ) { ?><div class="down-but"><a href="<?php echo $down_local; ?>" target="_blank"><i class="be be-download ri"></i><?php if ( $down_local_btn ) { ?><?php echo $down_local_btn; ?><?php } else { ?>本站下载<?php } ?></a></div><?php } ?>
						<div class="clear"></div>
					</div>
				<?php } ?>
				<div class="clear"></div>
				<div class="down-pass">
					<?php if ( $rar_password ) { ?><p>解压密码：<?php echo $rar_password;?></p><?php } ?>
					<?php if ( $baidu_password ) { ?><textarea cols="20" rows="10" id="panpass" class="da"><?php echo $baidu_password; ?></textarea><?php } ?>
					<?php if ( $baidu_password ) { ?><p>网盘密码：<?php echo $baidu_password;?></p><?php } ?>
				</div>
				<div class="clear"></div>
			</div>
			<?php if ( $down_img ) { ?>
				<div class="down-img">
					<h3>演示图片</h3>
					<a class="fancybox" href="<?php echo $down_img; ?>" data-fancybox="gallery"><img src="<?php echo $down_img; ?>" alt="<?php echo $title; ?>" /></a>
				</div>
			<?php } ?>
			<div class="clear"></div>
		</div>
		<?php if ( zm_get_option('ad_down') == '' ) { ?>
		<?php } else { ?>
			<div class="down-tg">
				<?php echo stripslashes( zm_get_option( 'ad_down' ) ); ?>
				<div class="clear"></div>
			</div>
		<?php } ?>

		<div class="down-copyright da">
			<strong>声明：</strong>
			<p><?php echo stripslashes( zm_get_option('down_explain') ); ?></p>
		</div>
	</div>
	<?php remove_footer(); ?>
	<?php if ( $baidu_password ) { ?><script type="text/javascript">function copyUrl2() {var Url2=document.getElementById("panpass");Url2.select();document.execCommand("Copy");alert("网盘密码已复制，可贴粘，点“确定”进入下载页面。");}</script><?php } ?>
	<?php get_footer(); ?>
</div>
<?php } else { ?>
	<?php  wp_die( '<p style="text-align: center;">' . sprintf(__( '出错了', 'begin' ) ) . '</p>' ); ?>
<?php } ?>
<?php }

function remove_footer() { ?>
<?php 
	add_action( 'wp_footer', 'remove_my_action', 1 );
	function remove_my_action() {
		remove_action( 'wp_footer', 'zm_copyright_tips' );
	}

	add_action( 'wp_footer', 'remove_toc', 1 );
	function remove_toc() {
		remove_action( 'wp_footer', 'toc_footer' );
	}

	add_action( 'wp_footer', 'remove_down_file' );
	function remove_down_file() {
		remove_action( 'wp_footer', 'begin_down_file', 99 );
	}
?>
<?php }