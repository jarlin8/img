<?php
/*
Template Name: 友情链接
*/

if ( ! defined( 'ABSPATH' ) ) exit;
if (zm_get_option('add_link')) {
	if( isset($_POST['begin_form']) && $_POST['begin_form'] == 'send'){
		global $wpdb;

		$link_name = isset( $_POST['begin_name'] ) ? trim(htmlspecialchars($_POST['begin_name'], ENT_QUOTES)) : '';
		$link_url = isset( $_POST['begin_url'] ) ? trim(htmlspecialchars($_POST['begin_url'], ENT_QUOTES)) : '';
		$link_description = isset( $_POST['begin_description'] ) ? trim(htmlspecialchars($_POST['begin_description'], ENT_QUOTES)) : '';
		$link_notes = isset( $_POST['link_notes'] ) ? trim(htmlspecialchars($_POST['link_notes'], ENT_QUOTES)) : '';
		$link_target = "_blank";
		$link_visible = "N";

		if ( empty($link_name) || mb_strlen($link_name) > 20 ){
			wp_die('连接名称必须填写，且长度不得超过30字<a href="'.get_permalink( zm_get_option('link_url') ).' "><p class="link-return">重写</p></a>');
		}

		if ( empty($link_description) || mb_strlen($link_description) > 100 ){
			wp_die('网站描述必须填写，且长度不得超过100字<a href="'.get_permalink( zm_get_option('link_url') ).' "><p class="link-return">重写</p></a>');
		}

		if ( empty($link_notes)){
			wp_die('QQ必须填写<a href="'.get_permalink( zm_get_option('link_url') ).' "><p class="link-return">重写</p></a>');
		}

		if ( empty($link_url) || strlen($link_url) > 60 || !preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $link_url)){
			wp_die('链接地址必须填写<a href="'.get_permalink( zm_get_option('link_url') ).' "><p class="link-return">重写</p></a>');
		}

		$lkname = $link_name.' — 待审核';
		$lk_name = $wpdb->get_row("select * from $wpdb->links  where link_name ='$lkname'");
		if ($lk_name){
			wp_die('链接名称已经存在请勿重复申请！！！<a href="'.get_permalink( zm_get_option('link_url') ).' "><p class="link-return">重写</p></a>');
		}

		$lk_url =  $wpdb->get_row("select * from $wpdb->links  where link_url ='$link_url'");
		if ($lk_url){
			wp_die('链接已经存在请勿重复申请！！！<a href="'.get_permalink( zm_get_option('link_url') ).' "><p class="link-return">重写</p></a>');
		}

		$sql_link = $wpdb->insert(
		$wpdb->links,
			array(
				'link_name' => $link_name.' — 待审核',
				'link_url' => $link_url,
				'link_target' => $link_target,
				'link_description' => $link_description,
				'link_notes' => $link_notes,
				'link_visible' => $link_visible
			)
		);

		$result = $wpdb->get_results($sql_link);
		wp_die('提交成功，等待站长审核中！<a href="'.get_permalink( zm_get_option('link_url') ).' "><p class="link-return">返回</p></a>');
	}
}
?>

<?php get_header(); ?>
<div id="content-links" class="content-area">
	<main id="main" class="link-area">
		<?php while ( have_posts() ) : the_post(); ?>
			<article class="link-page">
				<div class="link-content">
					<?php 
					if (!zm_get_option('links_model') || (zm_get_option("links_model") == 'links_ico')) {
						echo begin_get_link_items();
					}
					if (zm_get_option('links_model') == 'links_default') {
						echo links_page();
					}
					?>
				</div>
			</article>
			<div class="clear"></div>
			<article id="post-<?php the_ID(); ?>" <?php post_class('ms bk da'); ?>>
				<?php if ( get_post_meta($post->ID, 'header_img', true) || get_post_meta($post->ID, 'header_bg', true) ) { ?>
				<?php } else { ?>
					<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header>
				<?php } ?>
				<div class="entry-content">
					<div class="single-content">
						<?php the_content(); ?>
						<?php if (zm_get_option('add_link')) { ?>
						<div class="add-link" <?php aos_a(); ?>>
							<form method="post" class="add-link-form" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
								<p class="add-link-label">
									<label for="begin_name"><i class="be be-personoutline"></i>链接名称 *</label>
									<input type="text" size="40" value="" class="form-control da bk" id="begin_name" name="begin_name" required="required" />
								</p>
								<p class="add-link-label">
									<label for="begin_url"><i class="be be-anchor"></i>链接地址 *</label>
									<input type="text" size="40" value="" class="form-control da bk" id="begin_url" name="begin_url" required="required" />
								</p>

								<p class="add-link-label">
									<label for="link_notes"><i class="be be-qq"></i>QQ *</label>
									<input type="text" size="40" value="" class="form-control da bk" id="link_notes" name="link_notes" required="required" />
								</p>

								<p class="add-link-label">
									<label for="begin_description"><i class="be be-editor"></i>网站描述 *</label>
									<textarea id="begin_description" class="form-control da bk" name="begin_description" rows="2" tabindex="1" required="required" ></textarea>
								</p>
								<p class="add-link-label">
									<input type="hidden" value="send" name="begin_form" />
									<button type="submit" class="add-link-btn da bk">提交申请</button>
								</p>
							</form>
						</div>
						<?php } ?>
						<?php edit_post_link('<i class="be be-editor"></i>', '<div class="page-edit-link edit-link">', '</div>' ); ?>
					</div>
					<div class="clear"></div>
				</div>
			</article>

		<?php endwhile; ?>
		<div class="clear"></div>
		<?php if ( comments_open() || get_comments_number() ) : ?>
			<?php comments_template( '', true ); ?>
		<?php endif; ?>
	</main>
</div>
<?php get_footer(); ?>