<?php
/*
Template Name: 用户注册
*/
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<?php get_header(); ?>
<div id="primary-reg">
	<main id="main" class="be-main reg-page-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
			<article class="reg-page-box"></article>
			<?php if ( is_user_logged_in() ) { ?>
				<article class="reg-page-box ">
					<div class="reg-main reg-main-bg fd">
						<?php logged_manage(); ?>
					</div>
				</article>
			<?php } else { ?>
				<?php if ( has_shortcode_in_post( get_the_ID(), 'beregister' ) ) { ?>
					<?php the_content(); ?>
				<?php } else { ?>
					<?php be_reg_ajax_js(); ?>
				<?php } ?>
			<?php } ?>
		<?php endwhile; ?>
	</main>
</div>

<?php be_back_img(); ?>
<?php get_footer(); ?>