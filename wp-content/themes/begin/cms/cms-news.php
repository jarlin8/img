<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'news' ) ) { ?>
<div class="cms-news">
	<?php 
		if ( ! be_get_option( 'news_model' ) || ( be_get_option( "news_model" ) == 'news_grid' ) ) {
			require get_template_directory() . '/cms/cms-news-grid.php';
		}
		if ( be_get_option( 'news_model' ) == 'news_normal' ) {
			require get_template_directory() . '/cms/cms-news-normal.php';
		}

		if ( be_get_option( 'news_model' ) == 'news_card' ) {
			require get_template_directory() . '/cms/cms-news-card.php';
		}
	?>
</div>
<?php } ?>