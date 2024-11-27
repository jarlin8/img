<?php
class BEGIN_Front {
	public function __construct() {
		if ( !is_admin() ) {
			add_filter( 'the_content', array( $this, 'begin_share_content' ), 100 );
			add_action( 'wp_head', array( $this, 'begin_share_head' ), 50 );
		}
		add_action( 'wp_ajax_beshare_ajax', array( $this,'begin_ajax_handler' ) );
		add_action( 'wp_ajax_nopriv_beshare_ajax', array($this,'begin_ajax_handler') );
	}

	public function begin_ajax_handler() {
		switch ( $_POST['do'] ) {
			case 'like':
				$post_id = intval( $_REQUEST['post_id'] );
				if( !$post_id ) {
					break;
				}
				$like = get_post_meta( $post_id,'zm_like',true );
				if( $like ) {
					$like = intval( $like );
				} else {
					$like = 0;
				}
				$like++;
				update_post_meta( $post_id,'zm_like', $like );
				echo $like;
				break;
			case 'zmy_share_poster':
				self::zmy_share_post_handler();
				break;
		}
		exit();
	}

	function begin_share_head() {
		if ( is_singular() ) {
			$ajax_url = admin_url( 'admin-ajax.php' );
			$curr_uid = wp_get_current_user()->ID;
			$post_id = get_the_ID();
			$beshare_opt = '';
			$beshare_opt .= 'var beshare_opt="|'. urlencode( get_template_directory_uri() ). '|'. $curr_uid .'|'. urlencode( $ajax_url ).'|'. $post_id .'";';
			wp_add_inline_script( 'social-share', $beshare_opt ,'before' );
		}
	}

	public function begin_share_content( $content ) {
		if ( is_singular() ) {
			$content .= $this->donateHtml();
		}
		return $content;
	}

	// 按钮
	private function donateHtml() {
		$post_id = get_the_ID();

		$like = get_post_meta( $post_id,'zm_like',true );
		$like = $like ? intval( $like ) : 0;
		$like = $like > 999 ? intval( $like / 1000 ) . 'K+' : $like;

		$tab_html = '';
		$cont_html = '';
		$inline_script = '';

		if (zm_get_option('shar_donate')) {
			$tab_html .= '<div class="share-tab-nav-item item-alipay current da"><i class="cx cx-alipay"></i><span class="bgt">支付宝</span></div>';
			$tab_html .= '<div class="share-tab-nav-item item-weixin da"><i class="cx cx-weixin"></i><span class="bgt">微信</span></div>';
			$cont_html .= '<div class="share-tab-cont current"><div class="give-qr"><img src="' . zm_get_option('qr_b') . '" alt="支付宝二维码"></div><p>支付宝扫描二维码打赏作者</p></div>';
			$cont_html .= '<div class="share-tab-cont"><div class="give-qr"><img src="' . zm_get_option('qr_a') . '" alt="微信二维码"></div><p>微信扫描二维码打赏作者</p></div>';
			$inline_script .= 'var zmy_beshare_donate_html=\'<div class="tab-navs">'.$tab_html.'</div><div class="share-tab-conts">'.$cont_html.'</div>\';';
		}

		$img_url = $this->zmy_first_image();
		if (zm_get_option('poster_default_img')) {
			$opt_def_img = zm_get_option( 'poster_default_img' );
			$def_cover = zm_get_option( 'poster_default_img' );
		} else {
			$opt_def_img = zm_get_option( 'reg_img' );
			$def_cover = zm_get_option( 'reg_img' );
		}

		$share_cover = $img_url ? $img_url['src'] : ( $opt_def_img ? $opt_def_img : $def_cover );
		
		$zmy_beshare_html = '';

		$zmy_beshare_html = '<div class="zmy-share-list" data-cover="' . $share_cover . '">';
		$zmy_beshare_html .= '<a class="share-logo ico-weixin bk" data-cmd="weixin" title="分享到微信" rel="external nofollow"></a>';
		$zmy_beshare_html .= '<a class="share-logo ico-weibo bk" data-cmd="weibo" title="分享到微博" rel="external nofollow"></a>';
		$zmy_beshare_html .= '<a class="share-logo ico-qzone bk" data-cmd="qzone" title="分享到QQ空间" rel="external nofollow"></a>';
		$zmy_beshare_html .= '<a class="share-logo ico-qq bk" data-cmd="qq" title="分享到QQ" rel="external nofollow"></a>';

		$inline_script .= 'var zmy_beshare_html=\''.$zmy_beshare_html.'\';';
		wp_add_inline_script( 'social-share', $inline_script,'before' );
	}

	// 图片
	public function zmy_first_image() {
		global $post;
		$first_img = array();
		if(has_post_thumbnail() && get_the_post_thumbnail( get_the_ID() )!='') {
			$first_img['src'] = wp_get_attachment_image_src( get_post_thumbnail_id( $post ), 'post-thumbnail' )[0];
			return $first_img;
		}

		if( preg_match_all( '#<img[^>]+>#is', $post->post_content, $match ) ) {
			$match_frist = $match[0][0];
			if( $match_frist ) :
				preg_match( '#src=[\'"]([^\'"]+)[\'"]#', $match_frist, $src );
				preg_match( '#width=[\'"]([^\'"]+)[\'"]#', $match_frist, $width );
				preg_match( '#height=[\'"]([^\'"]+)[\'"]#', $match_frist, $height );

				$first_img['src'] = $src ? $src[1] : '';
				$first_img['width'] = $width ? $width[1] : '';
				$first_img['height'] = $height ? $height[1] : '';
			endif;
		} else {
			$first_img = 0;
		}
		return $first_img;
	}

	// 海报
	public function zmy_share_post_handler() {
		global $post;
		if( isset( $_POST['id'] ) && $_POST['id'] && $post = get_post( $_POST['id'] ) ) {
			setup_postdata( $post );
			$img_url = $this->zmy_first_image();
			$customposter = get_post_meta($post->ID, 'poster_img', true);

			if (zm_get_option('poster_default_img')) {
				$opt_def_img = zm_get_option( 'poster_default_img' );
				$def_cover = zm_get_option( 'poster_default_img' );
			} else {
				$opt_def_img = zm_get_option( 'reg_img' );
				$def_cover = zm_get_option( 'reg_img' );
			}

			if (get_post_meta($post->ID, 'poster_img', true)) {
				$share_head = $customposter;
			} else {
				$share_head = $img_url ? $img_url['src'] : ( !empty($opt_def_img) ? $opt_def_img : $def_cover );
			}

			if (zm_get_option('poster_logo')) {
				$share_logo = zm_get_option( 'poster_logo' );
			} else {
				$share_logo = zm_get_option( 'logo_small_b' );
			}

			if (zm_get_option('poster_site_name')) {
				$site_name = zm_get_option( 'poster_site_name' );
			} else {
				$site_name = get_bloginfo( 'name' );
			}

			if (zm_get_option('poster_site_tagline')) {
				$site_tagline = zm_get_option( 'poster_site_tagline' );
			} else {
				$site_tagline = get_bloginfo( 'description' );
			}
			if (has_excerpt('')){
				if (zm_get_option('languages_en')) {
					$excerpt = wp_trim_words( get_the_excerpt(), '22', '...' );
				} else {
					$excerpt = wp_trim_words( get_the_excerpt(), '56', '...' );
				}

			} else {
				$content = get_the_content();
				$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));

				if (zm_get_option('languages_en')) {
					$excerpt = wp_trim_words( $content, '22', '...' );
				} else {
					$excerpt = wp_trim_words( $content, '56', '...' );
				}
			}

			if (zm_get_option('languages_en')) {
				$titles = wp_trim_words( get_the_title(), 10 );
			} else {
				$titles = wp_trim_words( get_the_title(), 34 );
			}

			$category = get_the_category();

			$warn = sprintf(__( '长按识别二维码查看详细内容', 'begin' ));
			$res = array(
				'head' => $this->zmy_image_to_base64( $share_head ),
				'logo' => $this->zmy_image_to_base64( $share_logo ),
				'ico_cat' => '✪',
				'post_cat' => $category[0]->cat_name,
				'title' => $titles,
				'excerpt' => $excerpt,
				'warn' => $warn,
				'get_name' => $site_name,
				'web_home' => $site_tagline,
				'day' => get_the_time( 'd', $post_id ),
				'year' => get_the_time( 'm / Y', $post_id )
			);
			wp_reset_postdata();
			echo wp_json_encode($res);
			exit;
		}
	}

	public function zmy_image_to_base64( $image ){
		$site_domain = parse_url(get_bloginfo('url'), PHP_URL_HOST);
		$img_domain = parse_url($image, PHP_URL_HOST);
		if ( $img_domain != $site_domain ) {
			$http_options = array(
				'httpversion' => '1.0',
				'timeout' => 20,
				'redirection' => 20,
				'sslverify' => FALSE,
				'user-agent' => 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; MALC)'
			);
			if( preg_match('/^\/\//i', $image ) ) $image = 'http:' . $image;
			$get = wp_remote_get( $image, $http_options );
			if ( !is_wp_error($get) && 200 === $get ['response'] ['code'] ) {
				$img_base64 = 'data:' . $get['headers']['content-type'] . ';base64,' . base64_encode( $get ['body'] );
				return $img_base64;
			}
		}
		$image = preg_replace( '/^(http:|https:)/i', '', $image );
		return $image;
	}
}

new BEGIN_Front();

// [begin_share]
add_shortcode( 'begin_share','begin_share_social' );
function begin_share_social() {
	return share_poster();
}

function share_poster() { ?>
<div class="sharing-box" data-aos="zoom-in">
	<?php if (zm_get_option('shar_donate')) { ?><a class="zmy-btn-beshare zmy-btn-donate use-beshare-donate-btn bk dah" rel="external nofollow" data-hover="<?php _e( '打赏', 'begin' ); ?>"><div class="arrow-share"></div></a><?php } ?>
	<?php if (zm_get_option('shar_like')) { ?><a class="zmy-btn-beshare zmy-btn-like use-beshare-like-btn bk dah" data-count="<?php global $wpdb, $post; $zm_like = get_post_meta($post->ID, 'zm_like', true);{echo $zm_like;}?>" rel="external nofollow">
		<span class="sharetip bz like-number">
			<?php 
				global $wpdb, $post;
				if( get_post_meta($post->ID,'zm_like',true) ){
					echo '';
					echo get_post_meta($post->ID,'zm_like',true);
				} else {
					echo sprintf(__( '点赞', 'begin' )) ;
				}
			?>
		</span>
		<div class="triangle-down"></div>
	</a><?php } ?>
	<?php if ( function_exists( 'keep_button' ) ) like_button(); ?>
	<?php if (zm_get_option('shar_share')) { ?><a class="zmy-btn-beshare zmy-btn-share use-beshare-social-btn bk dah" rel="external nofollow" data-hover="<?php _e( '分享', 'begin' ); ?>"><div class="arrow-share"></div></a><?php } ?>
	<?php if (zm_get_option('shar_link')) { ?>
		<span class="post-link"><?php echo get_permalink(); ?></span>
		<?php if (zm_get_option('like_left')) { ?>
			<?php if ( wp_is_mobile() ) { ?>
				<span class="zmy-btn-link-b copytip"></span>
			<?php } ?>
			<a class="tooltip zmy-btn-beshare zmy-btn-link zmy-btn-link-b use-beshare-link-btn bk dah" rel="external nofollow" onclick="myFunction()" onmouseout="outFunc()"><span class="sharetip bz copytip"><?php _e( '复制链接', 'begin' ); ?></span></a>
			<a class="tooltip zmy-btn-beshare zmy-btn-link zmy-btn-link-l use-beshare-link-btn bk dah" rel="external nofollow" onclick="myFunction()" onmouseout="outFunc()"><span class="sharetip bz copytipl"><?php _e( '复制链接', 'begin' ); ?></span></a>
		<?php } else { ?>
			<span class="zmy-btn-link-l copytip"></span>
			<span class="zmy-btn-link-l copytipl"></span>
			<a class="tooltip zmy-btn-beshare zmy-btn-link zmy-btn-link-b use-beshare-link-btn bk dah" rel="external nofollow" onclick="myFunction()" onmouseout="outFunc()"><span class="sharetip bz copytip"><?php _e( '复制链接', 'begin' ); ?></span></a>
		<?php } ?>
	<?php } ?>
	<?php if (zm_get_option('shar_poster')) { ?><a class="zmy-btn-beshare zmy-share-poster use-beshare-poster-btn bk dah" rel="external nofollow" data-hover="<?php _e( '海报', 'begin' ); ?>"><div class="arrow-share"></div></a><?php } ?>
</div>
<?php }