<?php
if ( ! defined( 'ABSPATH' ) ) exit;
add_action( 'wp_ajax_ajax_search', 'ajax_search' );
add_action( 'wp_ajax_nopriv_ajax_search', 'ajax_search' );
function ajax_search() { ?>
<?php if ( !zm_get_option( 'search_the') || ( zm_get_option( "search_the" ) == 'search_list' ) ) { ?>
<!-- list -->
	<?php 
		$the_query = new WP_Query( 
			array(
				'posts_per_page' => zm_get_option( 'ajax_search_n' ),
				'post_status'    => 'publish',
				's'              => esc_attr( $_POST['keyword'] ),
				'post_type'      => array( 'post', 'picture', 'video', 'show', 'bulletin', 'tao' )
			)
		);
	?>
	<?php if ( $the_query->have_posts() ) : ?>
		<div class="wp-ajax-search-result"><i class="be be-search"></i><?php _e( '搜索结果', 'begin' ); ?></div>
		<ul class="ajax-search-box ajax-search-result">
			<?php while( $the_query->have_posts() ): $the_query->the_post(); ?>
				<?php $a = esc_attr( $_POST['keyword'] ); ?>
				<?php $search = get_the_title(); ?>
				<?php if ( stripos("/{$search}/", $a) !== false) { ?>
					<?php the_title( sprintf( '<li class="entry-title"><span class="be-menu-custom-title-ico"></span><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></li>' ); ?>
				<?php } ?>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
			<div class="clear"></div>
		</ul>
	<?php else : ?>
		<div class="wp-ajax-search-not fd da"><i class="be be-loader"></i><?php _e( '无匹配文章', 'begin' ); ?></div>
	<?php endif; ?>
	<?php die(); ?>
<?php } ?>

<?php if ( zm_get_option( 'search_the' ) == 'search_img' ){ ?>
<!-- img -->
	<?php 
		$the_query = new WP_Query( 
			array(
				'posts_per_page' => zm_get_option( 'ajax_search_n' ),
				'post_status'    => 'publish',
				's'              => esc_attr( $_POST['keyword'] ),
				'post_type'      => array( 'post', 'picture', 'video', 'show', 'bulletin', 'tao' )
			)
		);
	?>
	<?php if ( $the_query->have_posts() ) : ?>
		<section id="picture" class="picture-area content-area ajax-search-result grid-cat-<?php echo zm_get_option('img_f'); ?>">
			<div class="wp-ajax-search-result"><i class="be be-loader"></i><?php _e( '搜索结果', 'begin' ); ?></div>
			<main id="main" class="site-main" role="main">
				<?php while( $the_query->have_posts() ): $the_query->the_post(); ?>
					<?php $a = esc_attr( $_POST['keyword'] ); ?>
					<?php $search = get_the_title(); ?>
					<?php if ( stripos("/{$search}/", $a) !== false ) { ?>
						<article class="picture">
							<div class="picture-box">
								<figure class="picture-img">
									<?php if (zm_get_option('hide_box')) { ?>
										<a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><div class="hide-box"></div></a>
										<a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><div class="hide-excerpt"><?php if ( has_excerpt('') ){ echo wp_trim_words( get_the_excerpt(), 62, '...' ); } else { echo wp_trim_words( get_the_content(), 72, '...' ); } ?></div></a>
									<?php } ?>
									<?php zm_thumbnail(); ?>
								</figure>
								<?php the_title( sprintf( '<h2 class="grid-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					 			<div class="clear"></div>
							</div>
						</article>
					<?php } ?>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</main>
		</section>
	<?php else : ?>
		<div class="wp-ajax-search-not fd da"><i class="be be-loader"></i><?php _e( '无匹配文章', 'begin' ); ?></div>
	<?php endif; ?>
	<?php die(); ?>
<?php } ?>

<?php if ( zm_get_option( 'search_the' ) == 'search_normal' ){ ?>
<!-- normal -->
	<?php 
		$the_query = new WP_Query( 
			array(
				'posts_per_page' => zm_get_option( 'ajax_search_n' ),
				'post_status'    => 'publish',
				's'              => esc_attr( $_POST['keyword'] ),
				'post_type'      => array( 'post', 'picture', 'video', 'show', 'bulletin', 'tao' )
			)
		);
	?>
	<?php if ( $the_query->have_posts() ) : ?>
		<section id="primary" class="content-area cms-news-grid-container grid-ajax-search ajax-search-result">
			<div class="wp-ajax-search-result"><i class="be be-loader"></i><?php _e( '搜索结果', 'begin' ); ?></div>
			<?php while( $the_query->have_posts() ): $the_query->the_post(); ?>
				<?php $a = esc_attr( $_POST['keyword'] ); ?>
				<?php $search = get_the_title(); ?>
				<?php if ( stripos("/{$search}/", $a) !== false ) { ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'gl post' ); ?>>
						<?php get_template_part( 'template/new' ); ?>

						<figure class="thumbnail">
							<?php zm_thumbnail(); ?>
						</figure>

						<header class="entry-header">
							<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						</header>

						<div class="entry-content">
							<span class="entry-meta lbm">
								<?php begin_entry_meta(); ?>
							</span>
							<div class="clear"></div>
						</div>
					</article>
				<?php } ?>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
			<div class="clear"></div>
		</section>
	<?php else : ?>
		<div class="wp-ajax-search-not fd da"><i class="be be-loader"></i><?php _e( '无匹配文章', 'begin' ); ?></div>
	<?php endif; ?>
	<?php die(); ?>
<?php } ?>
<?php }

add_action( 'wp_ajax_ajax_fetch' , 'ajax_fetch' );
add_action( 'wp_ajax_nopriv_ajax_fetch','ajax_fetch' );
function ajax_fetch() { ?>
<?php 
	$the_query = new WP_Query( 
		array(
			'posts_per_page' => zm_get_option( 'ajax_search_n' ),
			'post_status'    => 'publish',
			's'              => esc_attr( $_POST['keyword'] ),
			'post_type'      => array( 'post', 'picture', 'video', 'show', 'bulletin', 'tao' )
		)
	);
?>
<?php if ( $the_query->have_posts() ) : ?>
	<ul class="search-widget-list">
		<?php while( $the_query->have_posts() ): $the_query->the_post(); ?>
			<?php $a = esc_attr( $_POST['keyword'] ); ?>
			<?php $search = get_the_title(); ?>
			<?php if ( stripos("/{$search}/", $a) !== false) { ?>
				<?php the_title( sprintf( '<li class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></li>' ); ?>
			<?php } ?>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	</ul>
<?php else : ?>
	<div class="ajax-search-not fd da"><i class="be be-loader"></i><?php _e( '无匹配文章', 'begin' ); ?></div>
<?php endif; ?>
<?php die(); ?>
<?php }