<?php
defined('ABSPATH') || exit;
if( ! class_exists('Be_image_sizes_resizer') ) {
	class Be_image_sizes_resizer{
		private $detector = false;
		private $be_dir = '';

		function __construct(){
			$this->be_dir = apply_filters( 'be_dir_path', $this->get_be_dir() );

			$this->check_be_dir();

			add_action( 'admin_menu', array( $this, 'admin_menu_item' ) );
			add_filter( 'media_row_actions', array( $this, 'media_row_action' ), 10, 2 );
			add_action( 'delete_attachment', array( $this, 'delete_attachment_be_images' ) );
			add_action( 'switch_blog', array( $this, 'blog_switched' ) );
		}

		// 多站点
		function blog_switched(){
			$this->be_dir = '';
			$this->be_dir = apply_filters( 'be_dir_path', $this->get_be_dir() );
		}

		function get_be_dir( $path = '' ) {
			if( empty( $this->be_dir ) ) {
				$wp_upload_dir = wp_upload_dir();
				return $wp_upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'thumbnail' . ( $path !== '' ? DIRECTORY_SEPARATOR . $path : '' );
			}else{
				return $this->be_dir . ( $path !== '' ? DIRECTORY_SEPARATOR . $path : '' );
			}
		}

		function check_be_dir(){
			if( ! is_dir( $this->be_dir ) ) {
				wp_mkdir_p( $this->be_dir );
			}
		}

		function be_dir_writable() {
			return is_dir( $this->be_dir ) && wp_is_writable( $this->be_dir );
		}

		function delete_all_be_images() {
			if( ! function_exists( 'WP_Filesystem' ) ) return false;
			WP_Filesystem();
			global $wp_filesystem;
			if( $wp_filesystem->rmdir( $this->get_be_dir(), true ) ){
				$this->check_be_dir();
				return true;
			}
			return false;
		}

		function delete_attachment_be_images( $attachment_id = 0 ) {
			if( ! function_exists( 'WP_Filesystem' ) ) return false;
			WP_Filesystem();
			global $wp_filesystem;
			return $wp_filesystem->rmdir( $this->get_be_dir( $attachment_id ), true );
		}

		function admin_menu_item() {
			add_management_page(
				'缩略图缓存',
				'<span class="bem"></span>缩略图',
				'manage_options',
				'crop-thumb',
				array( $this, 'options_page' )
			);
		}

		function options_page() {
			if( isset( $_POST['be_nonce'] ) && wp_verify_nonce( $_POST['be_nonce'], 'delete_all_be_images' ) ) {
				$this->delete_all_be_images();
				echo '<div class="updated"><p>所有缩略图缓存已删除！</p></div>';
			}elseif( isset( $_GET['delete-be-image'], $_GET['ids'], $_GET['be_nonce'] ) && wp_verify_nonce( $_GET['be_nonce'], 'delete_be_image' ) ) {
				$ids = array_map( 'intval', array_map( 'trim', explode( ',', sanitize_key( $_GET['ids'] ) ) ) );
				if( ! empty( $ids ) ) {
					foreach( $ids as $id ) {
						$this->delete_attachment_be_images( $id );
					}
					echo '<div class="updated"><p>此媒体文件的缩略图缓存已删除！</p></div>';
				}
			} ?>

			<div class="wrap">
				<h2 class="bezm-settings">缩略图缓存</h2>

				<div class="card be-area-box">
					<p>用于清理生成的缩略图缓存，会再次生成。</p>
					<h4>缩略图缓存目录</h4>
					<p><code><?php echo esc_html( $this->get_be_dir() ) ?></code></p>
					<?php if( $this->be_dir_writable() ): ?>
						<p style="color:#7AD03A">可写入</p>
					<?php else: ?>
						<p style="color:#A00">不可写入，请确保文件夹存在并有写入权限!</p>
					<?php endif ?>
				</div>

				<form method="post" action="">
					<div class="submit">
						<?php wp_nonce_field( 'delete_all_be_images', 'be_nonce' ) ?>
						<input class="button button-primary" value="删除所有" type="submit">
					</div>
				</form>
			</div>
		<?php
		}

		function media_row_action( $actions, $post ){
			if( in_array( $post->post_mime_type, array( 'image/jpeg', 'image/png', 'image/webp' ) ) ){
				$url = wp_nonce_url( admin_url( 'tools.php?page=crop-thumb&delete-be-image&ids=' . $post->ID ), 'delete_be_image', 'be_nonce' );
				$actions['be-image-delete'] = '<a href="' . esc_url( $url ) . '" title="删除此图片缩略图缓存">删除缩略缓存</a>';
			}
			return $actions;
		}

		function get_be_file_name( $file_name, $width, $height, $crop ){
			$file_name_only = pathinfo( $file_name, PATHINFO_FILENAME );
			$file_extension = strtolower( pathinfo( $file_name, PATHINFO_EXTENSION ) );
			$crop_extension = '';
			if( $crop === true || $crop === 1 ){
				$crop_extension = '-c';
			}elseif( is_array( $crop ) ){
				if( is_numeric( $crop[0] ) ){
					$crop_extension = '-f' . round( floatval( $crop[0] ) * 100 ) . '_' . round( floatval( $crop[1] ) * 100 );
				}else{
					$crop_extension = '-' . implode( '', array_map( function( $position ){
						return $position[0];
					}, $crop ) );
				}
			}
			return $file_name_only . '-' . intval( $width ) . 'x' . intval( $height ) . $crop_extension . '.' . $file_extension;
		}

		function get_be_path( $absolute_path = '' ){
			$wp_upload_dir = wp_upload_dir();
			$path = $wp_upload_dir['baseurl'] . str_replace( $wp_upload_dir['basedir'], '', $absolute_path );
			return str_replace( DIRECTORY_SEPARATOR, '/', $path );
		}

		function get_attachment_image_src( $attachment_id = 0, $size = '', $crop = null ){
			if( $attachment_id < 1 || empty( $size ) ){
				return array();
			}

			if( $size == 'full' || ! in_array( get_post_mime_type( $attachment_id ), array( 'image/jpeg', 'image/png', 'image/webp' ) ) ){
				$default_attachment = wp_get_attachment_image_src( $attachment_id, 'full' );
				if( is_array( $default_attachment ) ){
					return array_combine( array( 'src', 'width', 'height', 'resized' ), $default_attachment );
				}
				return array();
			}

			$image = wp_get_attachment_metadata( $attachment_id );
			if( $image ){
				switch( gettype( $size ) ){
					case 'array':
						$width = $size[0];
						$height = $size[1];
						break;
					default:
						return array();
				}

				if( intval( $width ) === 0 || intval( $height ) === 0 ){
					$crop = false;
				}

				$be_dir = $this->get_be_dir( $attachment_id );
				$be_file_path = $be_dir . DIRECTORY_SEPARATOR . $this->get_be_file_name( basename( $image['file'] ), $width, $height, $crop );

				if( file_exists( $be_file_path ) ){
					$image_size = getimagesize( $be_file_path );
					if( ! empty( $image_size ) ){
						return array(
							'src' => $this->get_be_path( $be_file_path ),
							'width' => $image_size[0],
							'height' => $image_size[1],
						);
					}else{
						return array();
					}
				}

				$this->check_be_dir();

				$image_editor = wp_get_image_editor( get_attached_file( $attachment_id ) );
				if( ! is_wp_error( $image_editor ) ){
					if( is_array( $crop ) && is_numeric( $crop[0] ) ){
						$original_sizes = $image_editor->get_size();

						$dst_w = intval( $size[0] );
						$dst_h = intval( $size[1] );
						$focal_x = floatval( $crop[0] );
						$focal_y = floatval( $crop[1] );

						if( ! $dst_w ) $dst_w = $original_sizes['width'];
						if( ! $dst_h ) $dst_h = $original_sizes['height'];

						$src_w = $original_sizes['width'];
						$src_h = $original_sizes['height'];

						if( $original_sizes['width'] / $original_sizes['height'] > $dst_w / $dst_h ){
							$src_w = round( $original_sizes['height'] * ( $dst_w / $dst_h ) );
						}else{
							$src_h = round( $original_sizes['width'] * ( $dst_h / $dst_w ) );
						}

						$src_x = $original_sizes['width'] * $focal_x - $src_w * $focal_x;
						if( $src_x + $src_w > $original_sizes['width'] ){
							$src_x += $original_sizes['width'] - $src_w - $src_x;
						}
						if( $src_x < 0 ){
							$src_x = 0;
						}
						$src_x = round( $src_x );

						$src_y = $original_sizes['height'] * $focal_y - $src_h * $focal_y;
						if( $src_y + $src_h > $original_sizes['height'] ){
							$src_y += $original_sizes['height'] - $src_h - $src_y;
						}
						if( $src_y < 0 ){
							$src_y = 0;
						}
						$src_y = round( $src_y );

						$image_editor->crop( $src_x, $src_y, $src_w, $src_h, $dst_w, $dst_h );
					}else{
						$image_editor->resize( $width, $height, $crop );
					}
					$image_editor->save( $be_file_path );

					do_action( 'be_image_created', $attachment_id, $be_file_path );

					$image_dimensions = $image_editor->get_size();
					return array(
						'src' => $this->get_be_path( $be_file_path ),
						'width' => $image_dimensions['width'],
						'height' => $image_dimensions['height'],
					);
				}
			}
			return array();
		}

		function get_attachment_image( $attachment_id = 0, $size = '', $crop = null, $attr = array() ){
			$html = '';
			$image = $this->get_attachment_image_src( $attachment_id, $size, $crop );
			if( $image ){
				$hwstring = image_hwstring( $image['width'], $image['height'] );
				$size_class = $size;
				if( is_array( $size_class ) ){
					$size_class = join( 'x', $size );
				}
				$attachment = get_post( $attachment_id );
				$default_attr = array(
					'src' => $image['src'],
				);

				$attr = wp_parse_args( $attr, $default_attr );

				$attr = array_map( 'esc_attr', $attr );
				foreach( $attr as $name => $value ){
					$html .= $value;
				}
			}
			return $html;
		}
	}
	new Be_image_sizes_resizer();
}

function be_get_image_url( $attachment_id = 0, $size = '', $crop = null ){
	return ( new Be_image_sizes_resizer )->get_attachment_image( $attachment_id, $size, $crop );
}

// 预加载
if( ! class_exists( 'Be_Resize' ) ) {
	class Be_Exception extends Exception {}

	class Be_Resize {

		static private $instance = null;
		public $throwOnError = false;
		private function __construct() {}
		private function __clone() {}
		static public function getInstance() {
			if( self::$instance == null ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		public function process( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = false ) {
			try {
				if (!$url)
					throw new Be_Exception('$url parameter is required');
				if (!$width)
					throw new Be_Exception('$width parameter is required');

				if ( true === $upscale ) add_filter( 'image_resize_dimensions', array($this, 'be_upscale'), 10, 6 );

				$upload_info = wp_upload_dir();
				$upload_dir = $upload_info['basedir'];
				$upload_url = $upload_info['baseurl'];

				$http_prefix = "http://";
				$https_prefix = "https://";
				$relative_prefix = "//";

				if(!strncmp($url,$https_prefix,strlen($https_prefix))){
					$upload_url = str_replace($http_prefix,$https_prefix,$upload_url);
				}
				elseif(!strncmp($url,$http_prefix,strlen($http_prefix))){
					$upload_url = str_replace($https_prefix,$http_prefix,$upload_url);
				}
				elseif(!strncmp($url,$relative_prefix,strlen($relative_prefix))){
					$upload_url = str_replace(array( 0 => "$http_prefix", 1 => "$https_prefix"),$relative_prefix,$upload_url);
				}

				if ( false === strpos( $url, $upload_url ) )
					throw new Be_Exception('Image must be local: ' . $url);

				$rel_path = str_replace( $upload_url, '', $url );
				$img_path = $upload_dir . $rel_path;


				if ( ! file_exists( $img_path ) or ! getimagesize( $img_path ) )
					throw new Be_Exception('Image file does not exist (or is not an image): ' . $img_path);

				$info = pathinfo( $img_path );
				$ext = $info['extension'];
				list( $orig_w, $orig_h ) = getimagesize( $img_path );

				$dims = image_resize_dimensions( $orig_w, $orig_h, $width, $height, $crop );
				$dst_w = $dims[4];
				$dst_h = $dims[5];

				if ( ! $dims || ( ( ( null === $height && $orig_w == $width ) xor ( null === $width && $orig_h == $height ) ) xor ( $height == $orig_h && $width == $orig_w ) ) ) {
					$img_url = $url;
					$dst_w = $orig_w;
					$dst_h = $orig_h;
				} else {
					$suffix = "{$dst_w}x{$dst_h}";
					$dst_rel_path = str_replace( '.' . $ext, '', $rel_path );
					$destfilename = "{$upload_dir}{$dst_rel_path}-{$suffix}.{$ext}";

					if ( ! $dims || ( true == $crop && false == $upscale && ( $dst_w < $width || $dst_h < $height ) ) ) {
						throw new Be_Exception('Unable to resize image because image_resize_dimensions() failed');
					}

					elseif ( file_exists( $destfilename ) && getimagesize( $destfilename ) ) {
					    $img_url = "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}";
					} else {

						$editor = wp_get_image_editor( $img_path );

						if ( is_wp_error( $editor ) || is_wp_error( $editor->resize( $width, $height, $crop ) ) ) {
							throw new Be_Exception('Unable to get WP_Image_Editor: ' . $editor->get_error_message() . ' (is GD or ImageMagick installed?)');
						}

						$resized_file = $editor->save();

						if ( ! is_wp_error( $resized_file ) ) {
							$resized_rel_path = str_replace( $upload_dir, '', $resized_file['path'] );
							$img_url = $upload_url . $resized_rel_path;
						} else {
							throw new Be_Exception('Unable to save resized image file: ' . $editor->get_error_message());
						}
					}
				}

				if ( true === $upscale ) remove_filter( 'image_resize_dimensions', array( $this, 'be_upscale' ) );

				if ( $single ) {
					$image = $img_url;
				} else {
					$image = array (
						0 => $img_url,
						1 => $dst_w,
						2 => $dst_h
					);
				}

				return $image;
			}
			catch (Be_Exception $ex) {
				error_log('Be_Resize.process() error: ' . $ex->getMessage());

				if ($this->throwOnError) {
					throw $ex;
				} else {
					return false;
				}
			}
		}

		function be_upscale( $default, $orig_w, $orig_h, $dest_w, $dest_h, $crop ) {
			if ( ! $crop ) return null;

				$aspect_ratio = $orig_w / $orig_h;
				$new_w = $dest_w;
				$new_h = $dest_h;

				if ( ! $new_w ) {
					$new_w = intval( $new_h * $aspect_ratio );
				}

				if ( ! $new_h ) {
					$new_h = intval( $new_w / $aspect_ratio );
				}

				$size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );

				$crop_w = round( $new_w / $size_ratio );
				$crop_h = round( $new_h / $size_ratio );

				$s_x = floor( ( $orig_w - $crop_w ) / 2 );
				$s_y = floor( ( $orig_h - $crop_h ) / 2 );

				return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
		}
	}
}

if ( !function_exists( 'be_resize' ) ) {
	function be_resize( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = false ) {
		$be_resize = Be_Resize::getInstance();
		return $be_resize->process( $url, $width, $height, $crop, $single, $upscale );
	}
}