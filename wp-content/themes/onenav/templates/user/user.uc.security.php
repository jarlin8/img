<?php
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */
?>
<?php get_header(); ?>


<?php 
global $current_user; 
$bind_type = io_get_option('bind_type');
?>
    <div class="user-bg" style="background-image: url(<?php echo io_get_user_cover($current_user->ID ,"full") ?>)">
    </div>
    <div id="content" class="container user-area my-4">
        <div class="row">
            <div class="sidebar col-md-3 user-menu">
            <?php load_template(get_theme_file_path('/templates/user/user.menu.php')); ?>
            </div>
            <div id="user" class="col-md-9">
                <div class="author-meta-r d-none mb-5 d-md-block">
                    <div class="h2 text-white mb-3"><?php echo $current_user->display_name; ?>
                        <small class="text-xs"><span class="badge badge-outline-primary mt-2">
                            <?php echo io_get_user_cap_string($current_user->ID) ?>
                        </span></small>
                    </div>
                    <div class="text-white text-sm"><?php echo get_user_desc($current_user->ID); ?></div>
                </div> 
                <div class="card">
                <div class="card-body">
                    <?php echo io_bind_oauth_html( $current_user->ID ) ?>
                    <div class="text-lg pb-3 border-bottom border-light border-2w mb-3 mt-5"><?php _e('绑定邮箱','i_theme') ?></div>  
                    <form id="io-mail-bind" method="post">
                        <input type="hidden" name="action" value="mail_bind">
                        <input type="hidden" name="bind_type" class="bind_type" value="mail">
                        <div class="form-group row">
                            <label for="mm_mail" class="col-sm-3 col-md-2 col-form-label"><?php _e('邮箱','i_theme') ?></label>
                            <div class="col-sm-9 col-md-10">
                                <input type="text" class="form-control mm_mail" name="mm_mail" value="<?php echo esc_attr( $current_user->user_email ) ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="mm_token" class="col-sm-3 col-md-2 col-form-label"><?php _e('验证码','i_theme') ?></label>
                            <div class="col-auto">
                                <input type="text" class="form-control" id="mm_token" name="mm_token" required>
                            </div>
                            <a href="javascript:;" class="btn_token col-form-label text-sm" data-form="#io-mail-bind"><?php _e('发送验证码','i_theme') ?></a>
                        </div>
                        <?php wp_nonce_field('mail_bind','mail_nonce'); ?>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-2 col-form-label"></label> 
                            <div class="col-sm-9 col-md-10">
                                <button type="submit" class="submit btn btn-warning"><?php _e('确定','i_theme') ?></button>
                            </div>
                        </div>
                    </form> 
                    <?php if(io_get_option('bind_phone',false)): ?>
                    <div class="text-lg pb-3 border-bottom border-light border-2w mb-3 mt-5"><?php _e('绑定手机号','i_theme') ?></div>  
                    <form id="io-phone-bind" method="post">
                        <input type="hidden" name="action" value="mail_bind">
                        <input type="hidden" name="bind_type" class="bind_type" value="phone">
                        <div class="form-group row">
                            <label for="mm_mail" class="col-sm-3 col-md-2 col-form-label"><?php _e('手机号','i_theme') ?></label>
                            <div class="col-sm-9 col-md-10">
                                <input type="text" class="form-control mm_mail" name="mm_mail" value="<?php echo io_get_user_phone($current_user->ID) ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="mm_token" class="col-sm-3 col-md-2 col-form-label"><?php _e('验证码','i_theme') ?></label>
                            <div class="col-auto">
                                <input type="text" class="form-control" id="mm_token" name="mm_token" required>
                            </div>
                            <a href="javascript:;" class="btn_token col-form-label text-sm" data-form="#io-phone-bind"><?php _e('发送验证码','i_theme') ?></a>
                        </div>
                        <?php wp_nonce_field('mail_bind','mail_nonce'); ?>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-2 col-form-label"></label> 
                            <div class="col-sm-9 col-md-10">
                                <button type="submit" class="submit btn btn-warning"><?php _e('确定','i_theme') ?></button>
                            </div>
                        </div>
                    </form> 
                    <?php endif; ?>
                    <div class="text-lg pb-3 border-bottom border-light border-2w mb-3 mt-5"><?php _e('修改密码','i_theme') ?></div>  
                    <form id="io-dopassword" method="post">
                        <input type="hidden" name="action" value="change_safe_info">
                        <div class="form-group row">
                            <label for="mm_pass_new" class="col-sm-3 col-md-2 col-form-label"><?php _e('新密码','i_theme') ?></label>
                            <div class="col-sm-9 col-md-10">
                                <input type="password" class="form-control" id="mm_pass_new" name="mm_pass_new" aria-describedby="passHelpBlock" required>
                                <small id="passHelpBlock" class="form-text text-muted">
                                <?php _e('如果您想修改您的密码，请在此输入新密码，否则请留空。','i_theme') ?>
                                </small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="mm_pass_new2" class="col-sm-3 col-md-2 col-form-label"><?php _e('重复密码','i_theme') ?></label>
                            <div class="col-sm-9 col-md-10">
                                <input type="password" class="form-control" id="mm_pass_new2" name="mm_pass_new2" aria-describedby="pass2HelpBlock" required>
                                <small id="pass2HelpBlock" class="form-text text-muted">
                                <?php _e('再输入一遍新密码。提示：您的密码最好至少包含8个字符。为了保证密码强度，使用大小写字母、数字和符号（例如! " ? $ % ^ & ）','i_theme') ?>
                                </small>
                            </div>
                        </div>
                        <?php wp_nonce_field('change_safe_info','change_safe'); ?>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-2 col-form-label"></label> 
                            <div class="col-sm-9 col-md-10">
                                <button type="submit" class="submit btn btn-warning"><?php _e('保存密码','i_theme') ?></button>
                            </div>
                        </div>
                    </form> 
                </div>
                </div>
            </div>
        </div>
	</div> 
<script type="text/javascript">
    (function($){ 
        $('#io-dopassword').on('submit',function(){  
            if( $("#mm_pass_new").val()!="" && $("#mm_pass_new").val().length<8 ){
                alert("<?php _e('密码长度至少8位','i_theme') ?>");
                return false;
            };
            if($("#mm_pass_new").val()!=$("#mm_pass_new2").val()){
                alert("<?php _e('两次输入密码不相同','i_theme') ?>");
                return false;
            };
            var t = $(this);
            t.find('.submit').text("<?php _e('保存中...','i_theme') ?>").attr("disabled",true);
            $.ajax({
                url: theme.ajaxurl, 
                data : $(this).serialize(),
                type: 'POST',
                dataType: 'json',
            })
            .done(function(response) {  
                t.find('.submit').text("<?php _e('保存密码','i_theme') ?>").removeAttr("disabled");
                countdown = 0;
                showAlert(response); 
            })
            .fail(function() {  
                t.find('.submit').text("<?php _e('保存密码','i_theme') ?>").removeAttr("disabled");
                showAlert(JSON.parse('{"status":4,"msg":"<?php _e('网络错误！','i_theme') ?>"}'));
            });
            return false;
        });
        $('.btn_token').on('click',function(){  
            var t = $(this);
            var p = t.closest(t.data('form'));
            var mail = p.find('.mm_mail');
            var type = p.find('.bind_type');
            t.text("<?php _e('稍等...','i_theme') ?>").addClass("disabled");
            mail.attr("readonly","readonly");
            $.ajax({
                url: theme.ajaxurl, 
                data : "action=get_email_token&mm_mail="+mail.val()+"&bind_type="+type.val(),
                type: 'POST',
                dataType: 'json',
            })
            .done(function(response) {  
                if(response.status == 1){
                    settime(t,mail);
                }else{
                    mail.removeAttr("readonly");
                    t.text("<?php _e('发送验证码','i_theme') ?>").removeClass("disabled");
                }
                showAlert(response); 
            })
            .fail(function() { 
                mail.removeAttr("readonly"); 
                t.text("<?php _e('发送验证码','i_theme') ?>").removeClass("disabled");
                showAlert(JSON.parse('{"status":4,"msg":"<?php _e('网络错误！','i_theme') ?>"}'));
            });
            return false;
        }); 
        $('.unbound-open-id').on('click',function(){ 
            var t = $(this);
            ioConfirm("<div><p class='text-xl text-center mb-3'><?php _e('你确定要解除绑定？','i_theme') ?></p><?php _e('绑定前请先验证邮箱并设置密码，否则会造成账号丢失！','i_theme') ?><br><br><?php _e('是否继续？','i_theme') ?></div>",function(result){
                if(result){
                    $.ajax({
                        url: theme.ajaxurl, 
                        data : t.data(),
                        type: 'POST',
                        dataType: 'json',
                    })
                    .done(function(response) {  
                        if(response.status == 1){
                            location.reload();
                        }
                        showAlert(response); 
                    })
                    .fail(function() {  
                        showAlert(JSON.parse('{"status":4,"msg":"<?php _e('网络错误！','i_theme') ?>"}'));
                    });
                }else{
                    console.log( '取消操作！');
                }
            }); 
        }); 
        $('#io-mail-bind,#io-phone-bind').on('submit',function(){  
            var t = $(this);
            var mail = t.find('.mm_mail');
            t.find('.submit').text("<?php _e('保存中...','i_theme') ?>").attr("disabled",true);
            $.ajax({
                url: theme.ajaxurl, 
                data : $(this).serialize(),
                type: 'POST',
                dataType: 'json',
            })
            .done(function(response) {  
                if(response.status == 1){
                    $('.email-status').remove();
                    mail.removeAttr("readonly");
                }
                t.find('.submit').text("<?php _e('确定','i_theme') ?>").removeAttr("disabled");
                countdown = 0;
                showAlert(response); 
            })
            .fail(function() {  
                t.find('.submit').text("<?php _e('确定','i_theme') ?>").removeAttr("disabled");
                showAlert(JSON.parse('{"status":4,"msg":"<?php _e('网络错误！','i_theme') ?>"}'));
            });
            return false;
        });
        var timer;
        var countdown=60;
        function settime(t,mail) { 
            if (countdown == 0) {
                t.html("<?php _e('重新发送','i_theme') ?>").removeClass("disabled"); 
                countdown = 60;
                clearTimeout(timer);
                mail.removeAttr("readonly");
                return;
            } else {
                t.html(countdown+"<?php _e('秒后重新发送','i_theme') ?>");
                countdown--; 
            };
            timer=setTimeout(function() { 
                settime(t,mail) 
            },1000) 
        }
    })(jQuery);
</script> 
<?php get_footer(); ?>