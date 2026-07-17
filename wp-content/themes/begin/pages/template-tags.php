<?php
/*
Template Name: 标签聚合
*/
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php get_header(); ?>
	<div class="tags-page">
		<div class="tags-area">
			<?php 
				$tags = get_tags( array(
					'orderby' => 'count',
					'order'   => 'DESC'
				));

				if ( $tags ) {
					foreach ( $tags as $tag ) {
						if ( $tag->count > 2 ) {
							echo '<a href="' . get_tag_link( $tag->term_id ) . '" target="_blank">';
							echo '<div class="boxs1">';
							echo '<div class="be-tags-item">';
							echo '<div class="be-tags-name">' . $tag->name . '</div>';
							echo '<div class="be-tags-count">' . $tag->count . '</div>';
							echo '</div>';
							echo '</div>';
							echo '</a>';
						}
					}
				}
			?>
		</div>
	</div>

<script>
	function getRandomColor() {
		const letters = '0123456789ABCDEFabcdef';
		let color = '#';
		for (let i = 0; i < 6; i++) {
			color += letters[Math.floor(Math.random() * 16)];
		}
		return color;
	}
	document.querySelectorAll('.be-tags-count').forEach(box => {
		box.style.color = getRandomColor();
	});
</script>

<?php get_footer(); ?>