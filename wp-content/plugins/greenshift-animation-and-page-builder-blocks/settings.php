<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('GSPB_GreenShift_Settings')) {

	class GSPB_GreenShift_Settings
	{
		private $allowed_font_ext = [
			'woff2',
			'woff',
			'tiff',
			'ttf',
		];

		public function __construct()
		{
			add_action('admin_menu', array($this, 'greenshift_admin_page'));
			if (!defined('REHUB_ADMIN_DIR')) {
				//Show Reusable blocks column
				add_action('registered_post_type', array($this, 'gspb_template_menu_display'), 10, 2);
				add_filter('manage_wp_block_posts_columns', array($this, 'gspb_template_screen_add_column'));
				add_action('manage_wp_block_posts_custom_column', array($this, 'gspb_template_screen_fill_column'), 1000, 2);
				// Force Block editor for Reusable Blocks even when Classic editor plugin is activated
				add_filter('use_block_editor_for_post', array($this, 'gspb_template_gutenberg_post'), 1000, 2);
				add_filter('use_block_editor_for_post_type', array($this, 'gspb_template_gutenberg_post_type'), 1000, 2);
				//Shortcode output for reusable blocks
				add_shortcode('wp_reusable_render', array($this, 'gspb_template_shortcode_function'));
				//Ajax render action
				add_action('wp_ajax_gspb_el_reusable_load', array($this, 'gspb_el_reusable_load'));
				add_action('wp_ajax_nopriv_gspb_el_reusable_load', array($this, 'gspb_el_reusable_load'));
				//settings fonts actions
				add_action('wp_ajax_gspb_settings_add_font', array($this, 'gspb_settings_add_font'));
			}
		}

		public function greenshift_admin_page()
		{

			$parent_slug = 'greenshift_dashboard';

			add_menu_page(
				'GreenShift',
				'GreenShift',
				'manage_options',
				$parent_slug,
				array($this, 'welcome_page'),
				plugin_dir_url(__FILE__) . 'libs/gspbLogo.svg',
				20
			);

			add_submenu_page(
				$parent_slug,
				esc_html__('Settings', 'greenshift'),
				esc_html__('Settings', 'greenshift'),
				'manage_options',
				'greenshift',
				array($this, 'settings_page')
			);
		}

		public function welcome_page()
		{
		?>
			<div class="wrap gspb_welcome_div_container">
				<style>
					.wrap {
						background: white;
						max-width: 900px;
						margin: 2.5em auto;
						border: 1px solid #dbdde2;
						box-shadow: 0 10px 20px #ececec;
						text-align: center
					}

					.wrap .notice,
					.wrap .error {
						display: none
					}

					.wrap h2 {
						font-size: 1.5em;
						margin-bottom: 1em;
						font-weight: bold
					}

					.gs-introtext {
						font-size: 14px;
						max-width: 500px;
						margin: 0 auto 50px auto
					}

					.gs-intro-video iframe {
						box-shadow: 10px 10px 20px rgb(0 0 0 / 15%);
					}

					.gs-intro-video {
						margin-bottom: 40px
					}

					.wrap h1 {
						text-align: left;
						padding: 15px 20px;
						margin: -1px -1px 0 -1px;
						font-size: 13px;
						font-weight: bold;
						text-transform: uppercase;
						box-shadow: 0 3px 8px rgb(0 0 0 / 5%);
					}

					.wrap .fs-notice {
						margin: 0 25px 25px 25px !important
					}

					.wrap .fs-plugin-title {
						display: none !important
					}

					.gridrows {
						display: grid;
						grid-template-columns: repeat(2, minmax(0, 1fr));
						gap: 20px
					}

					.gridrows div {
						padding: 20px;
						border: 1px solid #e4eff9;
						background: #f3f8ff;
						font-size: 16px
					}

					.gridrows div a {
						text-decoration: none
					}

					.gs-padd {
						padding: 25px
					}
				</style>
				<h1><?php esc_html_e("Getting Started", 'greenshift'); ?></h1>
				<div class="gs-padd">
					<p><img src="<?php echo GREENSHIFT_DIR_URL . 'libs/logo_300.png'; ?>" height="100" width="100" /></p>
					<p class="gs-introtext"><?php esc_html_e("Thank you for using Greenshift. For any bug report, please, contact us ", 'greenshift'); ?> <a href="<?php echo admin_url('admin.php?page=greenshift_dashboard-contact'); ?>"><?php esc_html_e("through the contact form", 'greenshift'); ?></a></p>
					<div class="gs-intro-video"><iframe width="560" height="315" src="https://www.youtube.com/embed/3xbQcQ5LDEc" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
					<div style="text-align:left; padding-top:30px; border-top:1px solid #eee;">
						<h2><?php esc_html_e("More tutorials", 'greenshift'); ?></h2>
						<div class="gridrows">
							<div><a href="https://www.youtube.com/watch?v=hwzSWXvvJXU4" target="_blank"><?php esc_html_e("Row and section Options", 'greenshift'); ?></a></div>
							<div><a href="https://www.youtube.com/watch?v=00ebtAX-a34" target="_blank"><?php esc_html_e("Overview of design options", 'greenshift'); ?></a></div>
							<div><a href="https://www.youtube.com/watch?v=ijo7sBKGPIQ" target="_blank"><?php esc_html_e("In depth overview of unique options", 'greenshift'); ?></a></div>
							<div><a href="https://www.youtube.com/watch?v=5g51fLFtpmc" target="_blank"><?php esc_html_e("How to Add carousels to any block", 'greenshift'); ?></a></div>
							<div><a href="https://www.youtube.com/watch?v=pIz5U5eq2bQ" target="_blank"><?php esc_html_e("How to Use Presets", 'greenshift'); ?></a></div>
							<div><a href="https://youtu.be/Qj5uk7e4vpM" target="_blank"><?php esc_html_e("How to make floating toolbars", 'greenshift'); ?></a></div>
							<div><a href="https://youtu.be/gksGsf1VEBs" target="_blank"><?php esc_html_e("How to improve Query Loop with Query Addon", 'greenshift'); ?></a></div>
						</div>
					</div>
				</div>
			</div>
		<?php
		}

		public function settings_page()
		{

			if (!current_user_can('manage_options')) {
				wp_die('Unauthorized user');
			}

			// Get the active tab from the $_GET param
			$default_tab = null;
			$tab         = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : $default_tab;

		?>
			<div class="wrap">
				<style>
					.wrap {
						background: white;
						max-width: 900px;
						margin: 2.5em auto;
						border: 1px solid #dbdde2;
						box-shadow: 0 10px 20px #ececec;
						text-align: center
					}

					.wrap .notice,
					.wrap .error {
						display: none
					}

					.wrap h2 {
						font-size: 1.5em;
						margin-bottom: 1em;
						font-weight: bold;
						padding: 15px;
						background: #f4f4f4;
					}

					.gs-introtext {
						font-size: 14px;
						max-width: 500px;
						margin: 0 auto 50px auto
					}

					.gs-intro-video iframe {
						box-shadow: 10px 10px 20px rgb(0 0 0 / 15%);
					}

					.gs-intro-video {
						margin-bottom: 40px
					}

					.wrap h1 {
						text-align: left;
						padding: 15px 20px;
						margin: -1px -1px 60px -1px;
						font-size: 13px;
						font-weight: bold;
						text-transform: uppercase;
						box-shadow: 0 3px 8px rgb(0 0 0 / 5%);
					}

					.gs-padd {
						padding: 25px;
						text-align: left;
						background-color: #fbfbfb
					}

					.rtl .gs-padd {
						text-align: right
					}

					.wp-core-ui .button-primary {
						background-color: #2184f9
					}

					.nav-tab-active,
					.nav-tab-active:focus,
					.nav-tab-active:focus:active,
					.nav-tab-active:hover {
						border-bottom: 1px solid #fbfbfb;
						background: #fbfbfb;
					}

					.nav-tab-wrapper {
						padding-left: 20px
					}

					.wrap .fs-notice {
						margin: 0 25px 35px 25px !important
					}

					.wrap .fs-plugin-title {
						display: none !important
					}
				</style>
				<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
				<!-- Here are our tabs -->
				<nav class="nav-tab-wrapper">
					<a href="?page=greenshift" class="nav-tab 
				<?php
				if ($tab === null) :
				?>
					nav-tab-active<?php endif; ?>"> <?php esc_html_e("General", 'greenshift'); ?> </a>
					<a href="?page=greenshift&tab=save_css" class="nav-tab 
				<?php
				if ($tab === 'save_css') :
				?>
					nav-tab-active<?php endif; ?>"><?php esc_html_e("Save CSS", 'greenshift'); ?></a>
					<a href="?page=greenshift&tab=breakpoints" class="nav-tab 
				<?php
				if ($tab === 'breakpoints') :
				?>
					nav-tab-active<?php endif; ?>"><?php esc_html_e("Breakpoints", 'greenshift'); ?></a>
				</nav>

				<div class="tab-content gs-padd">
					<?php
					switch ($tab):
						case 'save_css':
							if (isset($_POST['gspb_save_settings'])) {
								if (!wp_verify_nonce($_POST['gspb_settings_field'], 'gspb_settings_page_action')) {
									esc_html_e("Sorry, your nonce did not verify.", 'greenshift');
									return;
								}
								update_option('gspb_css_save', sanitize_text_field($_POST['gspb_settings_option']));
							}

							$css_tsyle_option = get_option('gspb_css_save');
					?>
							<div class="gspb_settings_form">
								<form method="POST">
									<?php wp_nonce_field('gspb_settings_page_action', 'gspb_settings_field'); ?>
									<table class="form-table">
										<tr>
											<th> <label for="css_system"><?php esc_html_e("Css location", 'greenshift'); ?></label> </th>
											<td>
												<select name="gspb_settings_option">
													<option value="inline" <?php selected($css_tsyle_option, 'inline'); ?>><?php esc_html_e("Inline in Head", 'greenshift'); ?> </option>
													<option value="file" <?php selected($css_tsyle_option, 'file'); ?>> <?php esc_html_e("File system", 'greenshift'); ?> </option>
													<option value="inlineblock" <?php selected($css_tsyle_option, 'inlineblock'); ?>> <?php esc_html_e("Inline in block", 'greenshift'); ?> </option>
												</select>
											</td>
										</tr>
									</table>
									<div style="margin-bottom:15px"><?php esc_html_e("Use Inline in block only if you have some issues with not updating styles of blocks or cache. Once saved as inline in block, styles can be overwritten only when you update post with blocks", 'greenshift'); ?></div>

									<input type="submit" name="gspb_save_settings" value="<?php esc_html_e("Save settings"); ?>" class="button button-primary button-large">
								</form>
							</div>
						<?php
							break;
						case 'breakpoints':
							$global_settings = get_option('gspb_global_settings');

							if (isset($_POST['gspb_save_settings']) && isset($_POST['gspb_settings_field']) && wp_verify_nonce($_POST['gspb_settings_field'], 'gspb_settings_page_action')) {
								$breakpoints = array(
									"mobile" =>  sanitize_text_field($_POST['mobile']),
									"tablet" =>  sanitize_text_field($_POST['tablet']),
									"desktop" =>  sanitize_text_field($_POST['desktop']),
									"row" =>  sanitize_text_field($_POST['row']),
								);
								$global_settings['breakpoints'] = $breakpoints;
								update_option('gspb_global_settings', $global_settings);
							}
						?>
							<form method="POST" class="greenshift_form">
								<?php wp_nonce_field('gspb_settings_page_action', 'gspb_settings_field'); ?>
								<table class="form-table">

									<tr>
										<td> <?php esc_html_e("Mobile", 'greenshift'); ?> </td>
										<td>
											<input name="mobile" type="text" value="<?php if (isset($global_settings['breakpoints']['mobile'])) {
																						echo esc_attr($global_settings['breakpoints']['mobile']);
																					}  ?>" placeholder="576" />
										</td>
									</tr>
									<tr>
										<td> <?php esc_html_e("Tablet", 'greenshift'); ?> </td>
										<td>
											<input name="tablet" type="text" value="<?php if (isset($global_settings['breakpoints']['tablet'])) {
																						echo esc_attr($global_settings['breakpoints']['tablet']);
																					} ?>" placeholder="768" />
										</td>
									</tr>
									<tr>
										<td> <?php esc_html_e("Desktop", 'greenshift'); ?> </td>
										<td>
											<input name="desktop" type="text" value="<?php if (isset($global_settings['breakpoints']['desktop'])) {
																							echo esc_attr($global_settings['breakpoints']['desktop']);
																						} ?>" placeholder="992" />
										</td>
									</tr>
									<tr>
										<td> <?php esc_html_e("Default Row Content Width", 'greenshift'); ?> </td>
										<td>
											<input name="row" type="text" value="<?php if (isset($global_settings['breakpoints']['row'])) {
																						echo esc_attr($global_settings['breakpoints']['row']);
																					} ?>" placeholder="1200" />
										</td>
									</tr>
								</table>
								<input type="submit" name="gspb_save_settings" value="Save" class="button button-primary button-large">
							</form>
						<?php
							break;
						default:
							wp_enqueue_style('gsadminsettings');
							wp_enqueue_script('gsadminsettings');
							if (isset($_POST['gspb_save_settings_general']) && isset($_POST['gspb_settings_field']) && wp_verify_nonce($_POST['gspb_settings_field'], 'gspb_settings_page_action')) {
								$this->gspb_save_general_form($_POST, $_FILES);
							}
						?>
							<h2><?php esc_html_e("General Settings", 'greenshift'); ?></h2>
							<?php esc_html_e("You can assign global presets and other settings in Post edit area when you click on G button in header toolbar", 'greenshift'); ?>
							<h2><?php esc_html_e("Local Font Loader", 'greenshift'); ?></h2>
							<?php esc_html_e("Attention! Local font is global option and it can reduce performance in some cases, please, check", 'greenshift'); ?> <a href="https://greenshiftwp.com/how-to-use-local-fonts-in-greenshift-for-gdpr/" target="_blank"><?php esc_html_e("Documentation", 'greenshift'); ?></a>
							<?php
							$allowed_font_ext = $this->allowed_font_ext;
							require_once GREENSHIFT_DIR_PATH . 'templates/admin/settings_general_form.php'; ?>
					<?php
							break;
					endswitch;
					?>
				</div>
			</div>
		<?php
		}

		// settings fonts
		public function gspb_settings_add_font()
		{
			$i = $_POST['i'];
			$allowed_font_ext = $this->allowed_font_ext;
			ob_start();
			require_once GREENSHIFT_DIR_PATH . 'templates/admin/settings_general_font_item.php';
			$html = ob_get_contents();
			ob_get_clean();
			wp_send_json(['html' => $html]);
		}

		public function gspb_save_general_form($data, $files)
		{
			$global_settings = get_option('gspb_global_settings');

			$fonts_urls = $this->gspb_save_files($files);
			$arr = [];
			for ($i = 0; (int)$data['fonts_count'] > $i; $i++) {
				//$item_arr = ['label' => sanitize_text_field($data['font_specific_style_name'][$i])];
				foreach ($this->allowed_font_ext as $ext) {
					$item_arr[$ext] = !empty($fonts_urls[$i][$ext]) ? $fonts_urls[$i][$ext] : sanitize_text_field($data[$ext][$i]);
				}
				$arr[sanitize_text_field($data['font_family_name'][$i])] = $item_arr;
			}
			$new_localfont = json_encode($arr);
			$global_settings['localfont'] = $new_localfont;

			$localfontcss = '';
			if(!empty($arr)){
				foreach ($arr as $i=>$value) {
					$localfontcss .= '@font-face {';
						$localfontcss .= 'font-family: "'.$i.'";';
						$localfontcss .= 'src: ';
						if(!empty($value['woff2'])){
							$localfontcss .= 'url('.$value["woff2"].') format("woff2"), ';
						}
						if(!empty($value['woff'])){
							$localfontcss .= 'url('.$value["woff"].') format("woff"), ';
						}
						if(!empty($value['ttf'])){
							$localfontcss .= 'url('.$value["ttf"].') format("ttf"), ';
						}
						if(!empty($value['tiff'])){
							$localfontcss .= 'url('.$value["tiff"].') format("tiff"), ';
						}
						$localfontcss .= ';';
					$localfontcss .= 'font-display: swap;}';
				}
				$localfontcss = str_replace(', ;', ';', $localfontcss);
				$global_settings['localfontcss'] = $localfontcss;

				$gs_global_css = (!empty($global_settings['globalcss'])) ? $global_settings['globalcss'] : '';
				$upload_dir = wp_upload_dir();
		
				require_once ABSPATH . 'wp-admin/includes/file.php';
				global $wp_filesystem;
				$dir = trailingslashit($upload_dir['basedir']) . 'GreenShift/'; // Set storage directory path
			
				WP_Filesystem(); // WP file system
			
				if (!$wp_filesystem->is_dir($dir)) {
					$wp_filesystem->mkdir($dir);
				}
			
				$gspb_css_filename = 'globalstyle.css';
		
				$gs_global_css = str_replace('!important', '', $gs_global_css);
			
				if (!$wp_filesystem->put_contents($dir . $gspb_css_filename, $gs_global_css.$localfontcss)) {
					throw new Exception(__('CSS not saved due the permission!!!', 'greenshift'));
				}
			}
			update_option('gspb_global_settings', $global_settings);
		}

		public function gspb_save_files($files)
		{
			$result = [];
			$upload = wp_upload_dir();
			$upload_dir = $upload['basedir'] . '/GreenShift/fonts';
			$upload_url = $upload['baseurl'] . '/GreenShift/fonts';

			foreach (array_keys($files) as $filename) {
				foreach ($files[$filename]["error"] as $key => $error) {
					if ($error == UPLOAD_ERR_OK) {
						$tmp_name = $files[$filename]["tmp_name"][$key];
						$name = basename($files[$filename]["name"][$key]);
						$ext = pathinfo($name, PATHINFO_EXTENSION);
						$font_dir = $upload_dir . '/font_' . ($key + 1) . '/' . $ext;

						$this->gspb_rm_rec($font_dir); //clean up dir before download

						if (!wp_mkdir_p($font_dir)) {
							return false;
						}

						if (move_uploaded_file($tmp_name, "$font_dir/$name")) {
							$result[$key][$ext] = $upload_url . '/font_' . ($key + 1) . '/' . $ext . '/' . $name;
						}
					}
				}
			}

			return $result;
		}

		public function gspb_rm_rec($path)
		{
			if (is_file($path)) return unlink($path);
			if (is_dir($path)) {
				foreach (scandir($path) as $p) if (($p != '.') && ($p != '..'))
					$this->gspb_rm_rec($path . '/' . $p);
				return rmdir($path);
			}
			return false;
		}

		//Function to display Reusable section in menu
		function gspb_template_menu_display($type, $args)
		{
			if ('wp_block' !== $type) {
				return;
			}
			$args->show_in_menu = true;
			$args->_builtin = false;
			$args->labels->name = esc_html__('Block template', 'greenshift');
			$args->labels->menu_name = esc_html__('Reusable templates', 'greenshift');
			$args->menu_icon = 'dashicons-screenoptions';
			$args->menu_position = 58;
		}

		//Columns in Reusable section
		function gspb_template_screen_add_column($columns)
		{
			$columns = array(
				'cb' => '<input type="checkbox" />',
				'title' => esc_html__('Block title', 'greenshift'),
				'gs-reusable-preview' => esc_html__('Usage', 'greenshift'),
			);
			return $columns;
		}

		//Render function for Columns in Reusable Sections
		function gspb_template_screen_fill_column($column, $ID)
		{
			global $post;
			switch ($column) {

				case 'gs-reusable-preview':

					echo '<p><input type="text" style="width:350px" value="[wp_reusable_render id=\'' . $ID . '\']" readonly=""></p>';
					echo '<p>' . esc_html__('If you use template inside other dynamic ajax blocks', 'greenshift') . '<br><input type="text" style="width:350px" value="[wp_reusable_render inlinestyle=1 id=\'' . $ID . '\']" readonly="">';
					echo '<p>' . esc_html__('Shortcode for Ajax render:', 'greenshift') . '<br><input type="text" style="width:350px" value="[wp_reusable_render ajax=1 height=100px id=\'' . $ID . '\']" readonly="">';
					echo '<p>' . esc_html__('Hover trigger:', 'greenshift') . ' <code>gs-el-onhover load-block-' . $ID . '</code>';
					echo '<p>' . esc_html__('Click trigger:', 'greenshift') . ' <code>gs-el-onclick load-block-' . $ID . '</code>';
					echo '<p>' . esc_html__('On view trigger:', 'greenshift') . ' <code>gs-el-onview load-block-' . $ID . '</code>';
					break;

				default:
					break;
			}
		}

		//Render shortcode function

		function gspb_template_shortcode_function($atts)
		{
			extract(shortcode_atts(
				array(
					'id' => '',
					'ajax' => '',
					'height' => '',
					'inlinestyle' => ''
				),
				$atts
			));
			if (!isset($id) || empty($id)) {
				return '';
			}
			if (!is_numeric($id)) {
				$postget = get_page_by_path($id, OBJECT, array('wp_block'));
				$id = $postget->ID;
			}
			if (!empty($ajax)) {
				wp_enqueue_style('wp-block-library');
				wp_enqueue_style('gspreloadercss');
				wp_enqueue_script('gselajaxloader');
				$scriptvars = array(
					'reusablenonce' => wp_create_nonce('gsreusable'),
					'ajax_url' => admin_url('admin-ajax.php', 'relative'),
				);
				wp_localize_script('gselajaxloader', 'gsreusablevars', $scriptvars);
				$content = '<div class="gs-ajax-load-block gs-ajax-load-block-' . $id . '"></div>';

				$content_post = get_post($id);
				$contentpost = $content_post->post_content;
				$style = '';
				if (has_blocks($contentpost)) {
					$blocks = parse_blocks($contentpost);
					$style .= '<style scoped>';
					$style .= gspb_get_inline_styles_blocks($blocks);
					$style .= '</style>';
				}
				if (!empty($height)) {
					$content = '<div style="min-height:' . $height . '">' . $content . $style . '</div>';
				} else {
					$content = '<div>' . $content . $style . '</div>';
				}
			} else {
				$content_post = get_post($id);
				$content = $content_post->post_content;
				$style = '';
				if ($inlinestyle) {
					if (has_blocks($content)) {
						$blocks = parse_blocks($content);
						$style .= '<style scoped>';
						$style .= gspb_get_inline_styles_blocks($blocks);
						$style .= '</style>';
					}
				}
				$content = do_blocks($content);
				$content = do_shortcode($content);
				$content = preg_replace('%<p>&nbsp;\s*</p>%', '', $content);
				$content = preg_replace('/^(?:<br\s*\/?>\s*)+/', '', $content);
				$content = $content . $style;
			}
			return $content;
		}

		//Load reusable Ajax function
		function gspb_el_reusable_load()
		{
			check_ajax_referer('gsreusable', 'security');
			$post_id = intval($_POST['post_id']);
			$content_post = get_post($post_id);
			$content = $content_post->post_content;
			$content = apply_filters('the_content', $content);
			$content = str_replace('strokewidth', 'stroke-width', $content);
			$content = str_replace('strokedasharray', 'stroke-dasharray', $content);
			$content = str_replace('stopcolor', 'stop-color', $content);
			$content = str_replace('loading="lazy"', '', $content);
			if ($content) {
				wp_send_json_success($content);
			} else {
				wp_send_json_success('fail');
			}
			wp_die();
		}

		//Show gutenberg editor on reusable section even if Classic editor plugins enabled
		function gspb_template_gutenberg_post($use_block_editor, $post)
		{
			if (empty($post->ID)) return $use_block_editor;
			if ('wp_block' === get_post_type($post->ID)) return true;
			return $use_block_editor;
		}
		function gspb_template_gutenberg_post_type($use_block_editor, $post_type)
		{
			if ('wp_block' === $post_type) return true;
			return $use_block_editor;
		}
	}
}

add_filter('block_editor_settings_all', 'gspb_generate_anchor_headings', 10, 2);

function gspb_generate_anchor_headings($settings, $block_editor_context)
{
	$settings['generateAnchors'] = true;
	return $settings;
}

function gspb_get_inline_styles_blocks($blocks)
{
	$inlinestyle = '';
	foreach ($blocks as $block) {
		if (!empty($block['attrs']['inlineCssStyles'])) {
			$dynamic_style = $block['attrs']['inlineCssStyles'];
			$dynamic_style = gspb_get_final_css($dynamic_style);
			$dynamic_style = gspb_quick_minify_css($dynamic_style);
			$dynamic_style = htmlspecialchars_decode($dynamic_style);
			$inlinestyle .= $dynamic_style;
		}
		gspb_greenShift_block_script_assets('', $block);
		if (function_exists('greenShiftGsap_block_script_assets')) {
			greenShiftGsap_block_script_assets('', $block);
		}
		if (!empty($block['innerBlocks'])) {
			$blocks = $block['innerBlocks'];
			$inlinestyle .= gspb_get_inline_styles_blocks($blocks);
		}
	}
	return $inlinestyle;
}

//////////////////////////////////////////////////////////////////
// File Manager
//////////////////////////////////////////////////////////////////

if (!function_exists('greenshift_download_file_localy')) {
	function  greenshift_download_file_localy($file_uri, $save_dir, $file_name, $file_ext = null, $check_type = '')
	{
		$file_path = trailingslashit($save_dir) . $file_name;
		if (file_exists($file_path)) {
			return $file_name;
		}
		$args = array(
			'timeout' => 30,
			'httpversion' => '1.1',
			'user-agent'  => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36',
			'sslverify'   => true,
		);

		$response = wp_remote_get($file_uri, $args);

		if (is_wp_error($response) || (int) wp_remote_retrieve_response_code($response) !== 200) {
			return false;
		}

		if ($file_ext === null) {
			$headers = wp_remote_retrieve_headers($response);
			if (empty($headers['content-type'])) return false;

			$types = array_search($headers['content-type'], wp_get_mime_types());

			if (!$types) return false;

			$exts = explode('|', $types);
			$file_ext = $exts[0];
			$file_name .= '.' . $file_ext;
		}

		$file_name = wp_unique_filename($save_dir, $file_name);

		if ($check_type) {
			$filetype = wp_check_filetype($file_name, null);
			if (substr($filetype['type'], 0, 5) != $check_type)
				return false;
		}

		$image_string = wp_remote_retrieve_body($response);
		if (!file_put_contents($file_path, $image_string))
			return false;

		return $file_name;
	}
}

if (!function_exists('greenshift_save_file_localy')) {
	function greenshift_save_file_localy($file_uri, $img_title = '', $check_type = '')
	{
		$newfilename = basename($file_uri);
		$ext = pathinfo(basename($file_uri), PATHINFO_EXTENSION);

		$ext = ($ext) ? $ext : null;

		if (empty($newfilename)) {
			$newfilename = preg_replace('/[^a-zA-Z0-9\-]/', '', $newfilename);
			$newfilename = strtolower($newfilename);
		}

		$uploads = wp_upload_dir();

		if ($newfilename = greenshift_download_file_localy($file_uri, $uploads['path'], $newfilename, $ext, $check_type)) {
			return $newfilename;
		} else {
			return false;
		}
	}
}

if (!function_exists('greenshift_replace_ext_images')) {
	function greenshift_replace_ext_images($content)
	{
		$pattern = '#https?://[^/\s]+/\S+\.(jpg|png|gif|webp|svg|jpeg)#';
		$content = json_decode($content, true);
		$result = preg_replace_callback($pattern, function ($match) {
			if (is_array($match)) {
				$url = $match[0];
				if ($url) {
					$urlnew = greenshift_save_file_localy($url);
					$uploads = wp_upload_dir();
					$image = trailingslashit($uploads['url']) . $urlnew;
					return $image;
				}
			}
		}, $content);
		$result = json_encode($result);
		return $result;
	}
}

?>