<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

//////////////////////////////////////////////////////////////////
// Render animation for dynamic blocks
//////////////////////////////////////////////////////////////////

if (!function_exists('gspb_AnimationRenderProps')) {
	function gspb_AnimationRenderProps($animation = '')
	{
		if ($animation) {
			$animeprops = array();

			if (!empty($animation['usegsap'])) {

				$animeprops['data-gsapinit'] = 1;
				$animeprops['data-from'] = "yes";

				if (!empty($animation['delay'])) {
					$animeprops['data-delay'] = floatval($animation['delay']) / 1000;
				}
				if (!empty($animation['duration'])) {
					$animeprops['data-duration'] = floatval($animation['duration']) / 1000;
				}
				if (!empty($animation['ease'])) {
					$animeprops['data-ease'] = $animation['ease'];
				}
				if (!empty($animation['x'])) {
					$animeprops['data-x'] = $animation['x'];
				}
				if (!empty($animation['y'])) {
					$animeprops['data-y'] = $animation['y'];
				}
				if (!empty($animation['z'])) {
					$animeprops['data-z'] = $animation['z'];
				}
				if (!empty($animation['rx'])) {
					$animeprops['data-rx'] = $animation['rx'];
				}
				if (!empty($animation['ry'])) {
					$animeprops['data-ry'] = $animation['ry'];
				}
				if (!empty($animation['r'])) {
					$animeprops['data-r'] = $animation['r'];
				}
				if (!empty($animation['s'])) {
					$animeprops['data-s'] = $animation['s'];
				}
				if (!empty($animation['o'])) {
					$animeprops['data-o'] = $animation['o'];
				}
				if (!empty($animation['origin'])) {
					$animeprops['data-origin'] = $animation['origin'];
				}
				if (!empty($animation['text'])) {
					if (!empty($animation['texttype'])) {
						$animeprops['data-text'] = $animation['texttype'];
					} else {
						$animeprops['data-text'] = 'words';
					}
					if (!empty($animation['textdelay'])) {
						$animeprops['data-stdelay'] = $animation['textdelay'];
					}
					if (!empty($animation['textrandom'])) {
						$animeprops['data-strandom'] = "yes";
					}
				} else if (!empty($animation['stagger'])) {
					if (!empty($animation['staggerdelay'])) {
						$animeprops['data-stdelay'] = $animation['staggerdelay'];
					}
					if (!empty($animation['staggerrandom'])) {
						$animeprops['data-strandom'] = "yes";
					}
					$animeprops['data-stchild'] = "yes";
				}
				if (!empty($animation['o']) && ($animation['o'] == 1 || $animation['o'] == 0)) {
					$animeprops['data-prehidden'] = 1;
				}
				if (!empty($animation['onload'])) {
					$animeprops['data-triggertype'] = "load";
				}
			} else if (!empty($animation['type'])) {

				$animeprops['data-aos'] = $animation['type'];

				if (!empty($animation['delay'])) {
					$animeprops['data-aos-delay'] = $animation['delay'];
				}
				if (!empty($animation['easing'])) {
					$animeprops['data-aos-easing'] = $animation['easing'];
				}
				if (!empty($animation['duration'])) {
					$animeprops['data-aos-duration'] = $animation['duration'];
				}
				if (!empty($animation['anchor'])) {
					$anchor = str_replace(' ', '-', $animation['anchor']);
					$animeprops['data-aos-anchor-placement'] = $anchor;
				}
				if (!empty($animation['onlyonce'])) {
					$animeprops['data-aos-once'] = true;
				}
			} else {
				return false;
			}
			$out = '';
			foreach ($animeprops as $key => $value) {
				$out .= ' ' . $key . '="' . $value . '"';
			}
			return $out;
		}
		return false;
	}
}


//////////////////////////////////////////////////////////////////
// Smooth Mouse
//////////////////////////////////////////////////////////////////

add_action('wp_footer', 'greenshift_additional__footer_elements');
function greenshift_additional__footer_elements()
{
	if (defined('GREENSHIFTGSAP_DIR_URL')) {
		$sitesettings = get_option('gspb_global_settings');
		if (!empty($sitesettings['sitesettings']['mousefollow'])) {
			$color = !empty($sitesettings['sitesettings']['mousecolor']) ? $sitesettings['sitesettings']['mousecolor'] : '#2184f9';
			echo '<div class="gsmouseball"></div><div class="gsmouseballsmall"></div><style scoped>.gsmouseball{width:33px;height:33px;position:fixed;top:0;left:0;z-index:99999;border:1px solid ' . esc_attr($color) . ';border-radius:50%;pointer-events:none;opacity:0}.gsmouseballsmall{width:4px;height:4px;position:fixed;top:0;left:0;background:' . esc_attr($color) . ';border-radius:50%;pointer-events:none;opacity:0; z-index:99999}</style>';
			wp_enqueue_script('gsap-mousefollow-init');
		}
	}
}

//////////////////////////////////////////////////////////////////
// Render icon for dynamic blocks
//////////////////////////////////////////////////////////////////

function greenshift_render_icon_module($attribute, $size = 20)
{

	$type = !empty($attribute['type']) ? $attribute['type'] : '';
	$icon = !empty($attribute['icon']) ? $attribute['icon'] : '';

	if ($type == 'image') {
		return '<img src="' . $icon['image']['url'] . '" alt="Image" width="' . $size . 'px" height="' . $size . 'px" />';
	} else if ($type == 'svg') {
		//return $icon['svg']; disable direct load as it's unsafe for dynamic fields
		return false;
	} else if ($type == 'font') {
		$font = str_replace('rhicon rhi-', '', $icon['font']);
		$pathicon = '';
		$widthicon = '1024';
		$iconfontsaved = get_transient('gspb-dynamic-icons-render');

		if (empty($iconfontsaved[$font])) {
			$icons = GREENSHIFT_DIR_PATH . 'libs/iconpicker/selection.json';
			$iconsfile = file_get_contents($icons); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			$iconsdecode = json_decode($iconsfile, true);
			$iconsarray = [];
			foreach ($iconsdecode['icons'] as $key => $value) {
				$name = $value['properties']['name'];
				$path = $value['icon']['paths'];
				$width = !empty($value['icon']['width']) ? $value['icon']['width'] : '';
				if ($width) {
					$iconsarray[$name]['width'] = $width;
				}
				$iconsarray[$name]['path'] = $path;
			}

			if (is_array($iconsarray[$font])) {
				foreach ($iconsarray[$font]['path'] as $key => $value) {
					$pathicon .= '<path d="' . $value . '" />';
				}
				if (!empty($iconsarray[$font]['width'])) {
					$widthicon = $iconsarray[$font]['width'];
				}
			}
			if (empty($iconfontsaved)) $iconfontsaved = [];
			$iconfontsaved[$font]['path'] = $pathicon;
			$iconfontsaved[$font]['width'] = $widthicon;
			set_transient('gspb-dynamic-icons-render', $iconfontsaved, 180 * DAY_IN_SECONDS);
		}

		return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 ' . $iconfontsaved[$font]['width'] . ' 1024" xmlns="http://www.w3.org/2000/svg">' . $iconfontsaved[$font]['path'] . '</svg>';
	}
}

//////////////////////////////////////////////////////////////////
// Disable Lazy load on image
//////////////////////////////////////////////////////////////////

add_filter('wp_img_tag_add_loading_attr', 'gspb_skip_lazy_load', 10, 3);
remove_filter('admin_head', 'wp_check_widget_editor_deps');

function gspb_skip_lazy_load($value, $image, $context)
{
	if (strpos($image, 'no-lazyload') !== false) $value = 'eager';
	return $value;
}
