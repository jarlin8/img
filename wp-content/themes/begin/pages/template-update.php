<?php
/*
Template Name: 文章更新
*/
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php get_header(); ?>

<?php
function up_archives_list() { ?>
	<div id="archives-box">
		<?php
			$args = array(
				'posts_per_page'      => -1,
				'cat'                 => cx_get_option('cat_up_n'),
				'year'                => cx_get_option('year_n'),
				'monthnum'            => cx_get_option('mon_n'),
				'ignore_sticky_posts' => 1
			);

			$the_query = new WP_Query( $args );
		?>

		<?php $year = 0; $mon = 0; ?>
		<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<?php $year_tmp = get_the_time( 'Y' ); ?>
			<?php $mon_tmp = get_the_time( 'm' ); ?>
			<?php $y=$year; $m=$mon; ?>

			<?php if ( $mon != $mon_tmp && $mon > 0 ) { ?>
				</ul>
			<?php } ?>

			<?php if ( $year != $year_tmp && $year > 0 ) { ?>
				</ul>
			<?php } ?>

			<?php if ( $year != $year_tmp ) { ?>
				<?php $year = $year_tmp; ?>
				<h3 class="beyear"><?php echo $year; ?>年</h3>
				<ul class="mon_list">
			<?php } ?>

			<?php if ( $mon != $mon_tmp ) { ?>
				<?php $mon = $mon_tmp; ?>
				<span class="bemon"><?php echo $mon; ?>月</span>
				<span class="year-m"><?php echo $year; ?>年</span>
				<div class="clear"></div>
				<ul class="mon-list">
			<?php } ?>

			<li class="day-box">
				<span class="day-w"><time datetime="<?php echo get_the_date('Y-m-d'); ?> <?php echo get_the_time('H:i:s'); ?>"><span class="days"><?php echo get_the_time('d'); ?></span><span class="week-d">日<br /><?php echo get_the_time('l'); ?></span></time></span>
				<a href="<?php echo get_permalink(); ?>" target="_blank"><?php echo get_the_title(); ?></a>
			</li>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
		</ul></ul>
	</div>
<?php } ?>

<div class="up-area">
	<main id="main" class="be-main site-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>">
				<header class="archives-header">
					<h1 class="archives-title"><?php the_title(); ?></h1>
		
						<div class="single-content">
							<?php the_content(); ?>
						</div>
	
					<ul class="archives-meta">
						<li>今日更新：
							<?php today_renew(); ?>
						</li>
						<li>本周更新：
							<?php week_renew(); ?>
						</li>
						<div class="clear"></div>
					</ul>
				</header>
			</article>
		<?php endwhile;?>
		<div class="up-archives">
			<ul class="mon-list ms future-post">
				<span class="future-t"><i class="be be-file"></i>即将发表</span>
				<ul class="day-list">
					<?php
					$my_query = new WP_Query( array ( 'post_status' => 'future','cat' => '','order' => 'ASC','showposts' => 5,'ignore_sticky_posts' => '1'));
					if ($my_query->have_posts()) {
						while ($my_query->have_posts()) : $my_query->the_post();
							$do_not_duplicate = $post->ID;
							echo '<li>';
							the_title();
							echo '</li>';
						endwhile; wp_reset_postdata();
					} else {
						echo '<li>暂无，敬请期待！</li>';
					}
					?>
				</ul>
			</ul>

			<?php up_archives_list(); ?>
		</div>
	</main>
</div>
<?php get_footer(); ?>