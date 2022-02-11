<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class be_user_avatars {
	private $user_id_being_edited;
	public function __construct() {
		add_action( 'show_user_profile', array( $this, 'edit_user_profile' ) );
		add_action( 'edit_user_profile', array( $this, 'edit_user_profile' ) );
		add_action( 'personal_options_update', array( $this, 'edit_user_profile_update' ) );
		add_action( 'edit_user_profile_update', array( $this, 'edit_user_profile_update' ) );
		if ( !is_admin() ) {
			if (zm_get_option('cache_avatar')) {
				add_filter( 'begin_avatar', array( $this, 'get_avatar' ), 10, 5 );
			} else {
				add_filter( 'get_avatar', array( $this, 'get_avatar' ), 10, 5 );
			}
		} else {
			add_filter( 'get_avatar', array( $this, 'get_avatar' ), 10, 5 );
		}
		add_filter( 'avatar_defaults', array( $this, 'avatar_defaults' ) );
	}

	public function get_avatar( $avatar, $id_or_email, $size = 96, $default = '', $alt = false ) {
		if ( is_numeric( $id_or_email ) )
			$user_id = (int) $id_or_email;
		elseif ( is_string( $id_or_email ) && ( $user = get_user_by( 'email', $id_or_email ) ) )
			$user_id = $user->ID;
		elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) )
			$user_id = (int) $id_or_email->user_id;

		if ( empty( $user_id ) )
			return $avatar;

		$local_avatars = get_user_meta( $user_id, 'be_user_avatar', true );

		if ( empty( $local_avatars ) || empty( $local_avatars['full'] ) )
			return $avatar;

		$size = (int) $size;

		if ( empty( $alt ) )
			$alt = get_the_author_meta( 'display_name', $user_id );

		if ( empty( $local_avatars[$size] ) ) {
			$upload_path      = wp_upload_dir();
			$avatar_full_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $local_avatars['full'] );
			$image            = wp_get_image_editor( $avatar_full_path );
			$image_sized      = null;

			if ( ! is_wp_error( $image ) ) {
				$image->resize( $size, $size, true );
				$image_sized = $image->save();
			}

			if ( empty( $image_sized ) || is_wp_error( $image_sized ) ) {
				$local_avatars[ $size ] = $local_avatars['full'];
			} else {
				$local_avatars[ $size ] = str_replace( $upload_path['basedir'], $upload_path['baseurl'], $image_sized['path'] );
			}

			update_user_meta( $user_id, 'be_user_avatar', $local_avatars );

		} elseif ( substr( $local_avatars[$size], 0, 4 ) != 'http' ) {
			$local_avatars[$size] = home_url( $local_avatars[$size] );
		}

		if ( is_ssl() ) {
			$local_avatars[ $size ] = str_replace( 'http:', 'https:', $local_avatars[ $size ] );
		}

		$author_class = is_author( $user_id ) ? ' current-author' : '' ;
		$avatar       = "<img alt='" . esc_attr( $alt ) . "' src='" . $local_avatars[$size] . "' class='avatar avatar-{$size}{$author_class} photo' height='{$size}' width='{$size}' />";

		return apply_filters( 'be_user_avatar', $avatar, $user_id );
	}

	public function edit_user_profile( $profileuser ) {
		?>
		<?php if ( is_admin()) { ?>
			<h2><?php _e( '头像', 'begin' ); ?></h2>

			<table class="form-table">
				<tr>
					<th><label for="be-user-avatar"><?php esc_html_e( '上传头像', 'begin' ); ?></label></th>
					<td style="width: 50px;" valign="top">
						<?php echo get_avatar( $profileuser->ID ); ?>
					</td>

					<td>
						<?php
							if ( zm_get_option('all_local_avatars') || current_user_can( 'upload_files' ) ) {
							wp_nonce_field( 'be_user_avatar_nonce', '_be_user_avatar_nonce', false );
							echo '<input type="file" name="be-user-avatar" id="be-local-avatar" /><br />';

							if ( empty( $profileuser->be_user_avatar ) ) {
								echo '<span class="description">' . sprintf(__( '选择一张图片', 'begin' )) . '</span>';
							} else {
								echo '<input type="checkbox" name="be-user-avatar-erase" id="be-user-avatar-erase" value="1" /><label for="be-user-avatar-erase">' . sprintf(__( '删除本地头像', 'begin' )) . '</label><br />';
								echo '<span class="description">' . sprintf(__( '上传新头像或删除本地头像', 'begin' )) . '</span>';
							}

						} else {
							if ( empty( $profileuser->be_user_avatar ) ) {
								echo '<span class="description">' . sprintf(__( '未设置本地头像，在Gravatar.com上设置您的头像', 'begin' )) . '</span>';
							} else {
								echo '<span class="description"><a href="https://cn.gravatar.com/" target="_blank" rel="external nofollow">' . sprintf(__( '申请设置头像', 'begin' )) . '</a></span>';
							}
						}
						?>
					</td>
				</tr>
			</table>

		<?php } else { ?>

			<?php
				if ( zm_get_option('all_local_avatars') || current_user_can( 'upload_files' ) ) {
					wp_nonce_field( 'be_user_avatar_nonce', '_be_user_avatar_nonce', false );
						if ( empty( $profileuser->be_user_avatar ) ) {
							echo '<p><label for="be-local-avatar" class="update-avatar dah bk bet-btn"> ' . sprintf(__( '上传头像', 'begin' )). '</label><input type="text" name="userdefinedFile" id="userfile" class="bky" value="' . sprintf(__( '未选择文件', 'begin' )). '"><input type="file" name="be-user-avatar" id="be-local-avatar" style="display: none;" /></p>';
						} else {
							echo '<p><label for="be-local-avatar" class="update-avatar dah bk bet-btn"> ' . sprintf(__( '更换头像', 'begin' )). '</label><input type="text" name="userdefinedFile" id="userfile" class="bky" value="' . sprintf(__( '未选择文件', 'begin' )). '"><input type="file" name="be-user-avatar" id="be-local-avatar" style="display: none;" /></p>';
						}
						if ( empty( $profileuser->be_user_avatar ) ) {
							echo '<p class="no-avatar">' . sprintf(__( '未设置本地头像', 'begin' )). '</p>';
						} else {
							echo '<div class="rememberme pretty success">';
							echo '<input type="checkbox" name="be-user-avatar-erase" id="be-user-avatar-erase" value="1">';
							echo '<label for="be-user-avatar-erase" class="del-avatars" type="checkbox"/><i class="mdi" data-icon=""></i>' . sprintf(__( '删除本地头像', 'begin' )). '</label>';
							echo '</div>';
						}
				} else {
					echo '<span class="apply-box">';
					if ( empty( $profileuser->be_user_avatar ) ) {
						echo '<span class="apply-url">' . sprintf(__( '未设置本地头像，在Gravatar.com上设置您的头像', 'begin' )) . '</span>';
					} else {
						echo '<span class="apply-url"><a href="https://cn.gravatar.com/" target="_blank" rel="external nofollow">' . sprintf(__( '申请全球通用头像', 'begin' )) . '</a></span>';
					}
					echo '</span>';
				}
			?>
		<?php } ?>

		<script type="text/javascript">var form = document.getElementById('your-profile');form.encoding = 'multipart/form-data';form.setAttribute('enctype', 'multipart/form-data');</script>
		<?php
	}

	public function edit_user_profile_update( $user_id ) {
		if ( ! isset( $_POST['_be_user_avatar_nonce'] ) || ! wp_verify_nonce( $_POST['_be_user_avatar_nonce'], 'be_user_avatar_nonce' ) )
			return;

		if ( ! empty( $_FILES['be-user-avatar']['name'] ) ) {
			$mimes = array(
				'jpg|jpeg|jpe' => 'image/jpeg',
				'gif'          => 'image/gif',
				'png'          => 'image/png',
			);

			if ( ! function_exists( 'wp_handle_upload' ) )
				require_once ABSPATH . 'wp-admin/includes/file.php';

			$this->avatar_delete( $user_id );
			if ( strstr( $_FILES['be-user-avatar']['name'], '.php' ) )
				//wp_die( '' . sprintf(__( '', 'begin' )) . '');
				die('' . sprintf(__( '出于安全原因，不能上传该类型文件', 'begin' )) . '') ;
			$this->user_id_being_edited = $user_id; 
			$avatar = wp_handle_upload( $_FILES['be-user-avatar'], array( 'mimes' => $mimes, 'test_form' => false, 'unique_filename_callback' => array( $this, 'unique_filename_callback' ) ) );

			if ( empty( $avatar['file'] ) ) {  
				switch ( $avatar['error'] ) {
				case '' . sprintf(__( '文件类型不符合安全准则', 'begin' )) . '';
					add_action( 'user_profile_update_errors', function( $error = 'avatar_error' ){;
						sprintf(__( '请上传头像的有效图像文件', 'begin' ));
					} );
					break;
				default :
					add_action( 'user_profile_update_errors', function( $error = 'avatar_error' ){
						"<strong>".sprintf(__( '上传头像时出错', 'begin' ))."</strong> ". esc_attr( $avatar['error'] );
					} );
				}
				return;
			}
			update_user_meta( $user_id, 'be_user_avatar', array( 'full' => $avatar['url'] ) );

		} elseif ( ! empty( $_POST['be-user-avatar-erase'] ) ) {
			$this->avatar_delete( $user_id );
		}
	}

	public function avatar_defaults( $avatar_defaults ) {
		remove_action( 'get_avatar', array( $this, 'get_avatar' ) );
		return $avatar_defaults;
	}

	public function avatar_delete( $user_id ) {
		$old_avatars = get_user_meta( $user_id, 'be_user_avatar', true );
		$upload_path = wp_upload_dir();

		if ( is_array( $old_avatars ) ) {
			foreach ( $old_avatars as $old_avatar ) {
				$old_avatar_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $old_avatar );
				@unlink( $old_avatar_path );
			}
		}

		delete_user_meta( $user_id, 'be_user_avatar' );
	}

	public function unique_filename_callback( $dir, $name, $ext ) {
		$user = get_user_by( 'id', (int) $this->user_id_being_edited );
		$name = $base_name = sanitize_file_name( $user->display_name . '_avatar' );
		$number = 1;

		while ( file_exists( $dir . "/$name$ext" ) ) {
			$name = $base_name . '_' . $number;
			$number++;
		}
		return $name . $ext;
	}
}