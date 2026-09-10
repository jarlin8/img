<?php
/*
Template Name: 作者墙
*/
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>

<main id="main" class="be-main author-content" role="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" class="post-item post ms" <?php aos_a(); ?>>
			<?php if ( get_post_meta( get_the_ID(), 'header_img', true ) || get_post_meta( get_the_ID(), 'header_bg', true ) ) { ?>
			<?php } else { ?>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>
			<?php } ?>
			<div class="entry-content">
				<div class="single-content">
					<?php the_content(); ?>
					<?php edit_post_link('<i class="be be-editor"></i>', '<div class="page-edit-link edit-link">', '</div>' ); ?>
					<div class="clear"></div>
				</div>
			</div>
		</article>
	<?php endwhile; ?>

	<article id="picture" class="author-page">
		<?php 
			$authors = get_users(
				array(
					'orderby'  => 'post_count',
					'order'    => 'DESC'
				)
			);

			foreach ( $authors as $author ){
				if ( count_user_posts( $author->ID ) > 0 ){
					echo '<div class="cx5" ';
					echo aos_a();
					echo '>';
					echo '<div class="boxs1">';
					echo '<div class="author-all">';
					echo '<div class="author-bgs">';
					if ( get_the_author_meta( 'userimg', $author->ID) ) {
						echo '<img src="';
						echo the_author_meta( "userimg", $author->ID );
						echo '">';
					}
					echo '</div>';
					echo '<a class="author-img load" href="'. get_author_posts_url( $author->ID ) .'" rel="external nofollow" target="_blank">';
						if (zm_get_option( 'cache_avatar' ) ) {
							echo begin_avatar( $author->user_email, $size = 96, '', $author->display_name );
						} else {
							be_avatar_authors();
						}
					echo '</a>';
					echo '<div class="author-name"><a href="'. get_author_posts_url( $author->ID ) .'" rel="external nofollow" target="_blank">'. $author->display_name .'</a></div>';
					echo '<div class="author-des">'; 
					echo the_author_meta( 'description', $author->ID );
					echo '</div>';
					echo '<div class="author-user-role">';
					if ( be_check_user_role( array( 'administrator' ), $author->ID ) ) {
						echo '<div class="role1">' . __( '管理员', 'begin' ) . '</div>';
					}
					elseif( be_check_user_role( array( 'editor'), $author->ID ) ) {
						echo '<div class="role2">' . __( '本站编辑', 'begin' ) .'</div>';
					}
					elseif( be_check_user_role( array( 'author'), $author->ID ) ) {
						echo '<div class="role3">' . __( '专栏作者', 'begin' ) . '</div>';
					}
					elseif( be_check_user_role( array( 'contributor'), $author->ID ) ) {
						echo '<div class="role4">' . __( '自由撰稿人', 'begin' ) . '</div>';
					}
					elseif( be_check_user_role( array( 'vip_roles'), $author->ID ) ) {
						echo '<div class="role-vip">' . zm_get_option( 'roles_name' ) . '</div>';
					} else {
						echo '<div class="role-none">' . __( '天外来客', 'begin' ) . '</div>';
					}
					echo '</div>';
					echo '<div class="author-info-box">';
					echo '<a href="'. get_author_posts_url( $author->ID ) .'" rel="external nofollow" target="_blank">';
					echo '<div class="author-post-count"><span>'. sprintf(__( '文章', 'begin' )) .'</span>'. count_user_posts( $author->ID ) .'</div>';
					echo '<div class="author-comments-count"><span>'. sprintf(__( '评论', 'begin' )) .'</span>'. $comments = get_comments( array( 'user_id' => $author->ID, 'count' => true ) ) .'</div>';
					echo '</a>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
				}
			}
		?>
		<div class="clear"></div>
	</article>
</main>

<?php get_footer(); ?>