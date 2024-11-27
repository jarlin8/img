<?php
/*
Template Name: 用户注册
*/
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<?php get_header(); ?>
<?php be_back_img(); ?>
<div id="primary-reg">
	<main id="main" class="be-main reg-page-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
		<article class="reg-page-box ">
			<?php reg_pages(); ?>
		</article>
		<?php endwhile; ?>
	</main>
</div>
<?php get_footer(); ?>