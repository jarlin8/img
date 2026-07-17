<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<meta http-equiv="Cache-Control" content="no-transform" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<title><?php bloginfo( 'name' ); ?><?php $description = get_bloginfo( 'description', 'display' ); if ( $description ) : ?><?php if ( zm_get_option( 'blog_info' ) ) { ?><?php connector(); ?><?php bloginfo( 'description' ); ?><?php } ?><?php endif; ?></title>
<meta name="description" content="<?php echo zm_get_option( 'description' ); ?>" />
<meta name="keywords" content="<?php echo zm_get_option( 'keyword' ); ?>" />
<?php do_action( 'favicon_ico' ); ?>
<?php wp_head(); ?>
</head>
<body style="background: url('<?php echo zm_get_option( 'be_maintain_img' ); ?>') no-repeat fixed center / cover !important;">
<div class="be-maintain">
    <div class="be-maintain-area">
        <h1><?php echo zm_get_option( 'be_maintain_text' ); ?></h1>
    </div>
    <div class="bewh">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
</body>
</html>