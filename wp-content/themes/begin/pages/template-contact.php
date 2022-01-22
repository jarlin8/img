<?php
/*
Template Name: 联系我们
*/
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>

<div id="primary" class="content-area primary-contact">
	<main id="main" class="site-main" role="main">
	<?php require get_template_directory() . '/inc/contact-form.php'; ?>
		<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class('bk da'); ?>>
			<header class="entry-header"><?php the_title( '<h1 class="entry-title">', '</h1>' ); ?></header>
			<div class="entry-content">
				<div class="single-content">
					<?php the_content(); ?>
					<div class="contact-page">
						<?php if(isset($emailSent) && $emailSent == true) { ?>
							<div class="thanks"><p><?php _e( '您的电子邮件已发送成功！', 'begin' ); ?></p></div>
						<?php } else { ?>
							<?php if(isset($hasError) || isset($captchaError)) { ?>
								<p class="prompt"><?php _e( '出错了！请检查填写的是否正确.', 'begin' ); ?><p>
							<?php } ?>
						<?php } ?>
						<form action="<?php the_permalink(); ?>" id="contactform" method="post">
							<p><label for="contactName"><?php _e( '姓名', 'begin' ); ?>*</label></p>
							<p><?php if($nameError != '') { ?><span class="error"><?php echo $nameError; ?></span><?php } ?></p>
							<p><input type="text" name="contactName" id="contactName" class="da" value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName'];?>" class="required requiredField" /></p>

							<p><label for="email"><?php _e( '邮箱', 'begin' ); ?>*</label></p>
							<p><?php if($emailError != '') { ?><span class="error"><?php echo $emailError; ?></span><?php } ?></p>
							<p><input type="text" name="email" id="email" class="da" value="<?php if(isset($_POST['email']))  echo $_POST['email'];?>" class="required requiredfield email" /></p>

							<p><label for="commentstext"><?php _e( '邮件内容', 'begin' ); ?>*</label></p>
							<p><?php if($commentError != '') { ?><span class="error"><?php echo $commentError; ?></span><?php } ?></p>
							<p><textarea name="comments" id="commentsText" rows="12" cols="15" class="required requiredField da"><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['comments']); } else { echo $_POST['comments']; } } ?></textarea></p>

							<p><input type="submit" id="submitinput" name="submit" class="submit" value="<?php _e( '发送邮件', 'begin' ); ?>"/></p>
							<input type="hidden" name="submitted" id="submitted" value="true" />
						</form>
						<p class="rememberme pretty success">
							<input type="checkbox" id="checked" />
							<label for="rememberme" type="checkbox"/>
								<i class="mdi" data-icon=""></i>
								<?php _e( '必须勾选才能提交', 'begin' ); ?>
							</label>
						</p>
					</div>
				</div>
			</div>
		</article>
		<?php if ( comments_open() || get_comments_number() ) : ?>
			<?php comments_template( '', true ); ?>
		<?php endif; ?>
		<?php endwhile; ?>
	</main>
</div>
<script type="text/javascript">
var checked=document.getElementById("checked")
var register=document.getElementById("submitinput")
register.onclick=function(){
	if(checked.checked==true){
	} else {
		alert("必须勾选才能提交");
		return false
	}
}
</script>
<?php get_footer(); ?>