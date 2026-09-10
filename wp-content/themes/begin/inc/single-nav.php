<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 上下篇
function nav_single() { ?>
<?php if (!zm_get_option('post_nav_no')) { ?>
<?php
	if (!zm_get_option('post_nav_mode') || (zm_get_option('post_nav_mode') == 'full_site') || ( zm_get_option( 'related_img' ) == 'related_inside' )) {
		$npm = false;
	} else {
		$npm = true;
	}
 ?>
<?php if (zm_get_option('post_nav_img')) { ?>
<nav class="post-nav-img betip" <?php aos_a(); ?>>
	<?php 
		global $post;
		$prevPost = get_previous_post( $npm );
		if ( $prevPost ) {
			$args = array(
				'posts_per_page' => 1,
				'include' => $prevPost->ID
			);

			$prevPost = get_posts( $args );
			foreach ( $prevPost as $post ) {
				setup_postdata( $post );
				?>
				<div class="nav-img-box post-previous-box ms">
					<figure class="nav-thumbnail"><?php echo zm_thumbnail(); ?></figure>
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
			echo '<div class="no-nav-img nav-img-box post-previous-box ms">';
			echo '<div class="nav-img post-previous-img">';
			echo '<div class="post-nav">' . sprintf(__( '已是最后', 'begin' )) . '</div>';
			echo '</div>';
			echo '</div>';
		}
		$nextPost = get_next_post( $npm );
		if ( $nextPost ) {
			$args = array(
				'posts_per_page' => 1,
				'include' => $nextPost->ID
			);
			$nextPost = get_posts( $args );
			foreach ( $nextPost as $post ) {
				setup_postdata( $post );
				?>
				<div class="nav-img-box post-next-box ms">
					<figure class="nav-thumbnail"><?php echo zm_thumbnail(); ?></figure>
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
			echo '<div class="no-nav-img nav-img-box post-next-box ms">';
			echo '<div class="nav-img post-next-img">';
			echo '<div class="post-nav">' . sprintf(__( '已是最新', 'begin' )) . '</div>';
			echo '</div>';
			echo '</div>';
		}
	?>
	<?php be_help( $text = '主题选项 → 文章设置 → 上下篇文章链接' ); ?>
	<div class="clear"></div>
</nav>
<?php } else { ?>
<nav class="nav-single betip" <?php aos_a(); ?>>
	<?php
		if ( get_previous_post( $npm ) ) {
			previous_post_link( '%link','<span class="meta-nav meta-previous ms"><span class="post-nav"><i class="be be-arrowleft"></i>' . sprintf( __( '上一篇', 'begin' ) ) . '</span><br/>%title</span>', $npm );
		} else {
			echo "<span class='meta-nav meta-previous ms'><span class='post-nav'><i class='be be-arrowup'></i><br/></span>" . sprintf( __( '已是最后', 'begin' ) ) . "</span>";
		}
		if ( get_next_post( $npm ) ) {
			next_post_link( '%link', '<span class="meta-nav meta-next ms"><span class="post-nav">' . sprintf(__( '下一篇', 'begin' )) . ' <i class="be be-arrowright"></i></span><br/>%title</span>', $npm );
		} else {
			echo "<span class='meta-nav meta-next ms'><span class='post-nav'><i class='be be-arrowup'></i><br/></span>" . sprintf( __( '已是最新', 'begin' ) ) . "</span>"; 
		}
	?>
	<?php be_help( $text = '主题选项 → 文章设置 → 上下篇文章链接' ); ?>
	<div class="clear"></div>
</nav>
<?php } ?>
<?php } ?>
<?php }

function type_nav_single() { ?>
<?php if (!zm_get_option('post_nav_no')) { ?>
<nav class="nav-single betip" <?php aos_a(); ?>>
	<?php
		if (get_previous_post()) { previous_post_link( '%link','<span class="meta-nav meta-previous ms"><span class="post-nav"><i class="be be-arrowleft ri"></i>' . sprintf(__( '上一篇', 'begin' )) . '</span><br/>%title</span>' ); } else { echo "<span class='meta-nav meta-previous ms'><span class='post-nav'><i class='be be-arrowup'></i><br/></span>" . sprintf(__( '已是最后', 'begin' )) . "</span>"; }
		if (get_next_post()) { next_post_link( '%link', '<span class="meta-nav meta-next ms"><span class="post-nav">' . sprintf(__( '下一篇', 'begin' )) . ' <i class="be be-arrowright"></i></span><br/>%title</span>' ); } else { echo "<span class='meta-nav meta-next ms'><span class='post-nav'><i class='be be-arrowup'></i><br/></span>" . sprintf(__( '已是最新', 'begin' )) . "</span>"; }
	?>
	<?php be_help( $text = '主题选项 → 文章设置 → 上下篇文章链接' ); ?>
	<div class="clear"></div>
</nav>
<?php } ?>
<?php }

// novel nav
function novel_nav() { ?>
	<nav class="novel-nav-single">
		<?php
			if ( get_previous_post( true ) ) {
				previous_post_link( '%link','<span class="novel-nav meta-previous"><span class="post-nav"></span>%title</span>', true );
			} else {
				echo "<span class='novel-nav meta-previous'><span class='post-nav'></span>" . sprintf( __( '已是最后', 'begin' ) ) . "</span>";
			}
			if ( get_next_post( true ) ) {
				next_post_link( '%link', '<span class="novel-nav meta-next">%title<span class="post-nav"></span></span>', true );
			} else {
				echo "<span class='novel-nav meta-next'><span class='post-nav'>" . sprintf( __( '已是最新', 'begin' ) ) . "</span>"; 
			}
		?>
	</nav>
<?php }