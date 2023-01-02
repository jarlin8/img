<?php
$current_user = wp_get_current_user();
$post = false;
$post_id = -1;
$featured_img_html = '';
if (isset($_GET['bet_id']) && isset($_GET['bet_action']) && $_GET['bet_action'] == 'edit') {
	$post_id = $_GET['bet_id'];
	$p = get_post($post_id, 'ARRAY_A');
	if ($p['post_author'] != $current_user->ID) return __("您没有权限编辑这篇文章。", 'begin');
	$category = get_the_category($post_id);
	$tags = wp_get_post_tags($post_id, array('fields' => 'names'));
	$featured_img = get_post_thumbnail_id($post_id);
	$featured_img_html = (!empty($featured_img)) ? wp_get_attachment_image($featured_img, array(200, 200)) : '';
	$post = array(
		'title'            => $p['post_title'],
		'content'          => $p['post_content'],
		'about_the_author' => get_post_meta($post_id, 'about_the_author', true)
	);
	if (isset($category[0]) && is_array($category))
		$post['category'] = $category[0]->cat_ID;
	if (isset($tags) && is_array($tags))
		$post['tags'] = implode(', ', $tags);
}
?>

<div id="bet-new-post" class="bet-tougao">
	<form id="bet-submission-form">
		<?php if (!zm_get_option('tougao_mode') || (zm_get_option('tougao_mode') == 'post_mode')) { ?>
			<label for="bet-post-title"><?php _e( '标题', 'begin' ); ?></label>
			<input type="text" name="post_title" id="bet-post-title" class="bk dah" value="<?php echo ($post) ? $post['title'] : ''; ?>">
			<?php
				wp_editor('', 'bet-post-content', $settings = array('textarea_name' => 'post_content', 'editor_class' => 'toug dah', 'textarea_rows' => 15, 'media_buttons' => 1));
				wp_nonce_field('betnonce_action', 'betnonce');
			?>
			<?php if (zm_get_option('thumbnail_required')) { ?>
				<div id="bet-featured-image">
					<div id="bet-featured-image-container"><?php echo $featured_img_html; ?></div>
					<a id="bet-featured-image-link" class="bk" href="#"><?php _e( '特色图像', 'begin' ); ?></a>
					<input type="hidden" id="bet-featured-image-id" value="<?php echo (!empty($featured_img)) ? $featured_img : '-1'; ?>"/>
				</div>
			<?php } ?>

			<div class="tou-cat bet-category" <?php aos_a(); ?>>
				<label for="bet-category"><?php _e( '分类', 'begin' ); ?></label>
				<?php 
					$notcat = explode(',',zm_get_option('not_front_cat'));
					wp_dropdown_categories(array('id' => 'bet-category', 'class' => 'bk da s-veil', 'hide_empty' => 0, 'name' => 'post_category', 'orderby' => 'name', 'selected' => 0, 'hierarchical' => true,  'exclude' => $notcat));
				?>
			</div>

			<div class="tou-cat bet-tags">
				<label for="bet-tags"><?php _e( '标签', 'begin' ); ?></label>
				<input type="text" name="post_tags" id="bet-tags" class="bk da dah" value="<?php echo ($post) ? $post['tags'] : ' '; ?>">
			</div>
		<?php } ?>

		<?php if (zm_get_option('tougao_mode') == 'info_mode') { ?>
			<div class="info-submit">
				<p class="p-info">
					<label for="bet-post-title"><?php echo zm_get_option('info_a'); ?><i class="be be-star"></i></label>
					<input type="text" name="post_title" id="bet-post-title" class="bk info-type dah" value="<?php echo ($post) ? $post['title'] : ''; ?>">
				</p>

				<?php if (zm_get_option('info_b')) { ?>
				<p class="p-info">
					<label for="bet-info-b"></i><?php echo zm_get_option('info_b'); ?></label>
					<input type="text" name="info_b" id="bet-info-b" class="bk info-type dah" value="">
				</p>
				<?php } ?>

				<?php if (zm_get_option('info_c')) { ?>
				<p class="p-info">
					<label for="bet-info-c" class="info-c"><?php echo zm_get_option('info_c'); ?></label>
					<select class="of-input s-veil" name="info_c" id="bet-info-c" class="info-type dah">
						<option value=""><?php echo zm_get_option('s_info_a'); ?></option>
						<?php if (zm_get_option('s_info_b')) { ?>
							<option value="<?php echo zm_get_option('s_info_b'); ?>"><?php echo zm_get_option('s_info_b'); ?></option>
						<?php } ?>
						<?php if (zm_get_option('s_info_c')) { ?>
							<option value="<?php echo zm_get_option('s_info_c'); ?>"><?php echo zm_get_option('s_info_c'); ?></option>
						<?php } ?>
						<?php if (zm_get_option('s_info_d')) { ?>
							<option value="<?php echo zm_get_option('s_info_d'); ?>"><?php echo zm_get_option('s_info_d'); ?></option>
						<?php } ?>
						<?php if (zm_get_option('s_info_e')) { ?>
							<option value="<?php echo zm_get_option('s_info_e'); ?>"><?php echo zm_get_option('s_info_e'); ?></option>
						<?php } ?>
						<?php if (zm_get_option('s_info_f')) { ?>
							<option value="<?php echo zm_get_option('s_info_f'); ?>"><?php echo zm_get_option('s_info_f'); ?></option>
						<?php } ?>
					</select>
				</p>
				<?php } ?>
				<div class="clear"></div>
				<?php if (zm_get_option('info_d')) { ?>
				<p class="p-info">
					<label for="bet-info-d"><?php echo zm_get_option('info_d'); ?></label>
					<input type="text" name="info_d" id="bet-info-d" class="bk info-type dah" value="">
				</p>
				<?php } ?>

				<?php if (zm_get_option('info_e')) { ?>
				<p class="p-info">
					<label for="bet-info-e"><?php echo zm_get_option('info_e'); ?></label>
					<input type="text" name="info_e" id="bet-info-e" class="bk info-type dah" value="">
				</p>
				<?php } ?>

				<?php if (zm_get_option('info_f')) { ?>
				<p class="p-info">
					<label for="bet-info-f"><?php echo zm_get_option('info_f'); ?></label>
					<input type="text" name="info_f" id="bet-info-f" class="bk info-type dah" value="">
				</p>
				<?php } ?>

				<label for="bet-post-conten"><?php _e( '备注', 'begin' ); ?></label><br />
				<?php
					$toolbar1 = apply_filters( 'info_tinymce_toolbar1', 'bold,' . 'bullist,numlist,' . 'link,unlink,' . 'image,code,'. 'spellchecker,fullscreen,dwqaCodeEmbed,' );
					wp_editor('', 'bet-post-content', $settings = array(
						'textarea_name' => 'post_content',
						'editor_class' => 'toug dah',
						'media_buttons' => 0, 
						'textarea_rows' => 3,
						'tinymce' => array( 'toolbar1' => $toolbar1,'toolbar2'   => '' ),
						'quicktags' => true
					) );
					wp_nonce_field('betnonce_action', 'betnonce');
				?>

				<?php if (zm_get_option('thumbnail_required')) { ?>
					<div id="bet-featured-image">
						<div id="bet-featured-image-container"><?php echo $featured_img_html; ?></div>
						<a id="bet-featured-image-link" class="bk" href="#"><?php _e( '特色图像', 'begin' ); ?></a>
						<input type="hidden" id="bet-featured-image-id" value="<?php echo (!empty($featured_img)) ? $featured_img : '-1'; ?>"/>
					</div>
				<?php } ?>

				<div class="tou-cat bet-category">
					<label for="bet-category"><?php _e( '分类', 'begin' ); ?></label>
					<?php 
						$notcat = explode( ',',zm_get_option( 'not_front_cat' ) );
						wp_dropdown_categories( array (
							'id' => 'bet-category',
							'taxonomy' => array('category', 'taobao', 'gallery', 'videos', 'products', 'notice'),
							'class' => 'bk',
							'hide_empty' => 0,
							'name' => 'post_category',
							'orderby' => 'name',
							'selected' => 0,
							'hierarchical' => true,
							'exclude' => $notcat
						) );
					?>
				</div>

				<div class="tou-cat bet-tags">
					<label for="bet-tags"><?php _e( '标签', 'begin' ); ?></label>
					<input type="text" name="post_tags" id="bet-tags" class="bk da" value="<?php echo ($post) ? $post['tags'] : ' '; ?>">
				</div>
			</div>
		<?php } ?>

		<input type="hidden" name="post_id" id="bet-post-id" value="<?php echo $post_id ?>">
		<button type="button" id="bet-submit-post" class="active-btn bet-btn dah"><?php _e( '提 交', 'begin' ); ?></button>
	</form>
	<div class="clear"></div>
	<div id="bet-message" class="warning"></div>
</div>
<script type="text/javascript">function renovates(){ document.location.reload();}</script>