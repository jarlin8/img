<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 下载模板
function down_page() { ?>
<?php 
	$id = isset( $_GET['id'] ) ? $_GET['id'] : '';
	$id = base64_decode( $id );
	$title = isset( get_post( $id )->post_title ) ? get_post( $id )->post_title : '';

	if ( get_post_meta( $id, 'be_down_name', true ) && get_post_meta( $id, 'down_name', true ) ) {
		$down_name = '<strong>' . sprintf( __( '资源名称', 'begin' ) ) . '：</strong>' . get_post_meta( $id, 'down_name', true );
	} else {
		$down_name = get_post_meta( $id, 'down_name', true );
	}

	if ( get_post_meta( $id, 'be_file_os', true ) && get_post_meta( $id, 'file_os', true ) ) {
		$file_os = '<strong>' . sprintf( __( '应用平台', 'begin' ) ) . '：</strong>' . get_post_meta( $id, 'file_os', true );
	} else {
		$file_os = get_post_meta( $id, 'file_os', true );
	}

	if ( get_post_meta( $id, 'be_file_inf', true ) && get_post_meta( $id, 'file_inf', true ) ) {
		$file_inf = '<strong>' . sprintf( __( '资源版本', 'begin' ) ) . '：</strong>' . get_post_meta( $id, 'file_inf', true );
	} else {
		$file_inf = get_post_meta( $id, 'file_inf', true );
	}

	if ( get_post_meta( $id, 'be_down_size', true ) && get_post_meta( $id, 'down_size', true ) ) {
		$down_size = '<strong>' . sprintf( __( '资源大小', 'begin' ) ) . '：</strong>' . get_post_meta( $id, 'down_size', true );
	} else {
		$down_size = get_post_meta( $id, 'down_size', true );
	}

	$baidu_pan         = get_post_meta( $id, 'baidu_pan', true );
	$baidu_password    = get_post_meta( $id, 'baidu_password', true );
	$baidu_pan         = get_post_meta( $id, 'baidu_pan', true);
	$down_local        = get_post_meta( $id, 'down_local', true );
	$rar_password      = get_post_meta( $id, 'rar_password', true );
	$down_official     = get_post_meta( $id, 'down_official', true );

	$down_img          = get_post_meta( $id, 'down_img', true );
	$baidu_pan_btn     = get_post_meta( $id, 'baidu_pan_btn', true );
	$down_local_btn    = get_post_meta( $id, 'down_local_btn', true );
	$down_official_btn = get_post_meta( $id, 'down_official_btn', true );
	$links_id          = get_post_meta( $id, 'links_id', true );
	$click_count       = get_post_meta( $links_id, 'surl_count', true );
?>
<?php if ( $id ) { ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0<?php if ( zm_get_option( 'mobile_viewport' ) ) { ?>, maximum-scale=1.0, maximum-scale=0.0, user-scalable=no<?php } ?>">
<meta http-equiv="Cache-Control" content="no-transform" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<title><?php echo $title;?> | 下载页面</title>
<meta name="keywords" content="<?php echo $title;?>" />
<meta name="description" content="<?php echo $title;?>-下载" />
<link rel="shortcut icon" href="<?php echo zm_get_option( 'favicon' ); ?>">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo zm_get_option( 'apple_icon' ); ?>" />
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/css/down.css" />
<script src="<?php echo get_template_directory_uri(); ?>/js/fancybox.js"></script>
<?php echo zm_get_option( 'ad_t' ); ?>
<?php echo zm_get_option( 'tongji_h' ); ?>
</head>
<body <?php body_class(); ?> ontouchstart="">
<?php wp_body_open(); ?>
<div id="page" class="hfeed site">
	<?php get_template_part( 'template/menu', 'index' ); ?>
	<nav class="bread">
		<div class="be-bread">
			<?php 
				echo '<span class="seat"></span><a class="crumbs" href="';
				echo home_url('/');
				echo '">';
				echo sprintf( __( '首页', 'begin' ) );
				echo "</a>";
				echo '<i class="be be-arrowright"></i>';
				echo sprintf( __( '文件下载', 'begin' ) );
				echo '<i class="be be-arrowright"></i>';
				echo $title;
				echo '</div>'
			 ?>
		</div>
	</nav>
	<?php get_template_part( 'ad/ads', 'header' ); ?>

<div id="content" class="site-content">
	<div class="down-post">
		<div class="down-main">

			<div class="down-header">
				<img src="<?php echo cx_get_option( 'down_header_img' ); ?>" alt="<?php echo $title; ?>">
				<h1><?php echo $title;?></h1>
				<div class="clear"></div>
			</div>

			<div class="down-inf">
				<div class="desc">
					<h3><?php _e( '文件信息', 'begin' ); ?></h3>
					<?php if ( $down_name ) { ?><p><?php echo $down_name; ?></p><?php } ?>
					<?php if ( $file_os ) { ?><p><?php echo $file_os; ?></p><?php } ?>
					<?php if ( $file_inf ) { ?><p><?php echo $file_inf; ?></p><?php } ?>
					<?php if ( $down_size ) { ?><p><?php echo $down_size; ?></p><?php } ?>
					<?php if ( $links_id ) { ?><p><strong><?php _e( '下载次数', 'begin' ); ?>：</strong><?php echo $click_count; ?></p><?php } ?>
				</div>
				<div class="clear"></div>

				<?php if ( cx_get_option( "login_down_key" ) ) { ?>
					<?php if ( is_user_logged_in() ) { ?>
						<div class="down-list-box">
							<div class="down-list-t"><?php _e( '下载地址', 'begin' ); ?></div>
							<div class="clear"></div>
							<?php if ( $baidu_password ) { ?>
								<?php if ( $baidu_pan ) { ?><div class="down-but"><a href="<?php echo $baidu_pan;?>" onClick="copyUrl2()" target="_blank"><div class="btnico"><i class="be be-clouddownload"></i></div><div class="btntxt"><?php if ( $baidu_pan_btn ) { ?><?php echo $baidu_pan_btn; ?><?php } else { ?><?php _e( '网盘下载', 'begin' ); ?><?php } ?></div></a></div><?php } ?>
							<?php } else { ?>
								<?php if ( $baidu_pan ) { ?><div class="down-but"><a href="<?php echo $baidu_pan;?>" target="_blank"><div class="btnico"><i class="be be-clouddownload"></i></div><div class="btntxt"><?php if ( $baidu_pan_btn ) { ?><?php echo $baidu_pan_btn; ?><?php } else { ?><?php _e( '网盘下载', 'begin' ); ?><?php } ?></div></a></div><?php } ?>
							<?php } ?>
							<?php if ( $down_official ) { ?><div class="down-but"><a href="<?php echo $down_official;?>" target="_blank"><div class="btnico"><i class="be be-clouddownload"></i></div><div class="btntxt"><?php if ( $down_official_btn ) { ?><?php echo $down_official_btn;?><?php } else { ?><?php _e( '官方下载', 'begin' ); ?><?php } ?></div></a></div><?php } ?>
							<?php if ( $down_local ) { ?><div class="down-but"><a href="<?php echo $down_local;?>" target="_blank"><div class="btnico"><i class="be be-clouddownload"></i></div><div class="btntxt"><?php if ( $down_local_btn ) { ?><?php echo $down_local_btn; ?><?php } else { ?><?php _e( '本站下载', 'begin' ); ?><?php } ?></div></a></div><?php } ?>
							<div class="clear"></div>
						</div>
					<?php } else { ?>
						<div class="down-list-point"><strong><?php _e( '下载地址', 'begin' ); ?>：</strong><?php _e( '登录可见', 'begin' ); ?></div>
					<?php } ?>
				<?php } else { ?>
					<div class="down-list-box">
						<div class="down-list-t"><?php _e( '下载地址', 'begin' ); ?></div>
						<div class="clear"></div>
						<?php if ( $baidu_password ){ ?>
							<?php if ( $baidu_pan ) { ?><div class="down-but"><a href="<?php echo $baidu_pan; ?>" onClick="copyUrl2()" target="_blank"><div class="btnico"><i class="be be-clouddownload"></i></div><div class="btntxt"><?php if ( $baidu_pan_btn ) { ?><?php echo $baidu_pan_btn; ?><?php } else { ?><?php _e( '网盘下载', 'begin' ); ?><?php } ?></div></a></div><?php } ?>
						<?php } else { ?>
							<?php if ( $baidu_pan ) { ?><div class="down-but"><a href="<?php echo $baidu_pan; ?>" target="_blank"><div class="btnico"><i class="be be-clouddownload"></i></div><div class="btntxt"><?php if ( $baidu_pan_btn ) { ?><?php echo $baidu_pan_btn; ?><?php } else { ?><?php _e( '网盘下载', 'begin' ); ?><?php } ?></div></a></div><?php } ?>
						<?php } ?>
						<?php if ( $down_official) { ?><div class="down-but"><a href="<?php echo $down_official; ?>" target="_blank"><div class="btnico"><i class="be be-clouddownload"></i></div><div class="btntxt"><?php if ( $down_official_btn ) { ?><?php echo $down_official_btn; ?><?php } else { ?><?php _e( '官方下载', 'begin' ); ?><?php } ?></div></a></div><?php } ?>
						<?php if ( $down_local ) { ?><div class="down-but"><a href="<?php echo $down_local; ?>" target="_blank"><div class="btnico"><i class="be be-clouddownload"></i></div><div class="btntxt"><?php if ( $down_local_btn ) { ?><?php echo $down_local_btn; ?><?php } else { ?><?php _e( '本站下载', 'begin' ); ?><?php } ?></div></a></div><?php } ?>
						<div class="clear"></div>
					</div>
				<?php } ?>
				<div class="clear"></div>
				<div class="down-pass">
					<?php if ( $rar_password ) { ?><p><?php _e( '解压密码', 'begin' ); ?>：<?php echo $rar_password;?></p><?php } ?>
					<?php if ( $baidu_password ) { ?><textarea cols="20" rows="10" id="panpass"><?php echo $baidu_password; ?></textarea><?php } ?>
					<?php if ( $baidu_password ) { ?><p><?php _e( '网盘密码', 'begin' ); ?>：<?php echo $baidu_password;?></p><?php } ?>
				</div>
				<div class="clear"></div>
			</div>
			<?php if ( $down_img ) { ?>
				<div class="down-img">
					<h3><?php _e( '演示图片', 'begin' ); ?></h3>
					<a class="fancybox" href="<?php echo $down_img; ?>" data-fancybox="gallery"><img src="<?php echo $down_img; ?>" alt="<?php echo $title; ?>"></a>
				</div>
			<?php } ?>
			<div class="clear"></div>
		</div>
		<?php if ( zm_get_option('ad_down') == '' ) { ?>
		<?php } else { ?>
			<div class="down-tg">
				<?php echo stripslashes( zm_get_option( 'ad_down' ) ); ?>
				<div class="clear"></div>
			</div>
		<?php } ?>

		<div class="down-copyright">
			<strong>声明：</strong>
			<p><?php echo stripslashes( cx_get_option('down_explain') ); ?></p>
		</div>
	</div>
	<?php remove_footer(); ?>
	<?php if ( $baidu_password ) { ?><script type="text/javascript">function copyUrl2() {var Url2=document.getElementById("panpass");Url2.select();document.execCommand("Copy");alert("网盘密码已复制，可贴粘，点“确定”进入下载页面。");}</script><?php } ?>
	<?php get_footer(); ?>
</div>
<?php } else { ?>
	<?php wp_die( '<p style="text-align: center;">' . sprintf(__( '出错了', 'begin' ) ) . '</p>' ); ?>
<?php } ?>
<?php }