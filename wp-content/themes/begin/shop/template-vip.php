<?php 
/*
Template Name: 会员中心
Author: 知更鸟
Author URI: https://zmingcx.com/
Version: 1.0
Description: 与ErphpDown13.3+配套使用，仅支持Begin主题。
*/
session_start();
if(!is_user_logged_in()){
	if ( zm_get_option( 'logout_to' ) ) {
		wp_redirect( zm_get_option('logout_to' ) );
	} else {
		wp_redirect( home_url() );
	}
	exit;
}
if ( is_plugin_active( 'erphpdown/erphpdown.php' ) ) {
global $wpdb, $erphpdown_version;
$user_info=wp_get_current_user();

$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';

$erphp_life_days    = get_option('erphp_life_days');
$erphp_year_days    = get_option('erphp_year_days');
$erphp_quarter_days = get_option('erphp_quarter_days');
$erphp_month_days  = get_option('erphp_month_days');
$erphp_day_days  = get_option('erphp_day_days');

function mobantu_paging($type,$paged,$max_page) {
	if ( $max_page <= 1 ) return; 
	if ( empty( $paged ) ) $paged = 1;

	echo '<div class="epd-pagination" style="float:left"><ul>';
	echo "<li><a class=extend href='?pd=$type&pp=1'>首页</a></li>";
	if($paged > 1){
		echo '<li class="prev-page"><a href="?pd='.$type.'&pp='.($paged-1).'">上页</a></li>';
	}
	if ( $paged > 2 ) echo "<li><span class='paging-number'> ... </span></li>";
	for( $i = $paged - 1; $i <= $paged + 3; $i++ ) { 
		if ( $i > 0 && $i <= $max_page ) 
		{
			if($i == $paged) 
				print "<li class=\"active\"><span>{$i}</span></li>";
			else
				print "<li><a href='?pd=$type&pp={$i}'><span>{$i}</span></a></li>";
		}
	}
	if ( $paged < $max_page - 3 ) echo "<li><span class='paging-number'> ... </span></li>";
	if($paged < $max_page){
		echo '<li class="next-page"><a href="?pd='.$type.'&pp='.($paged+1).'">下页</a></li>';
	}
	echo "<li><a class=extend href='?pd=$type&pp=$max_page'>尾页</a></li>";
	echo '</ul></div>';
}


if(isset($_POST['ice_alipay'])){
	$fee=get_option("ice_ali_money_site");
	$fee=isset($fee) ?$fee :100;
	
	$ice_ali_money_site = get_user_meta($user_info->ID,'ice_ali_money_site',true);
	if($ice_ali_money_site != '' && ($ice_ali_money_site || $ice_ali_money_site == 0)){
		$fee = $ice_ali_money_site;
	}

	$erphp_aff_money = get_option('erphp_aff_money');
	$okMoney = erphpGetUserOkMoney();
	if($erphp_aff_money){
		$okMoney = erphpGetUserOkAff();
	}

	$ice_alipay = $wpdb->escape($_POST['ice_alipay']);
	$ice_name   = $wpdb->escape($_POST['ice_name']);
	$ice_money  = isset($_POST['ice_money']) && is_numeric($_POST['ice_money']) ?$_POST['ice_money'] :0;
	$ice_money = $wpdb->escape($ice_money);
	if($ice_money<get_option('ice_ali_money_limit'))
	{
		$epdalert = '<div class="alert"><p>提现金额至少得满'.get_option('ice_ali_money_limit').get_option('ice_name_alipay').'</p></div>';
	}
	elseif(empty($ice_name) || empty($ice_alipay))
	{
		$epdalert = '<div class="alert"><p>请输入支付宝帐号和姓名</p></div>';
	}
	elseif($ice_money > $okMoney)
	{
		$epdalert = '<div class="alert"><p>提现金额大于可提现金额'.$okMoney.'</p></div>';
	}
	else
	{

		$sql="insert into ".$wpdb->iceget."(ice_money,ice_user_id,ice_time,ice_success,ice_success_time,ice_note,ice_name,ice_alipay)values
			('".$ice_money."','".$user_info->ID."','".date("Y-m-d H:i:s")."',0,'".date("Y-m-d H:i:s")."','','$ice_name','$ice_alipay')";
		if($wpdb->query($sql))
		{
			if($erphp_aff_money){
				addUserAffXiaoFei($user_info->ID, $ice_money);
			}else{
				addUserMoney($user_info->ID, '-'.$ice_money);
			}
		$epdalert = '<div class="alert"><p>申请成功，等待管理员处理！</p></div>';
		}
		else
		{
		$epdalert = '<div class="alert"><p>系统错误，请稍后重试！</p></div>';
		}
	}
}

if(isset($_POST['erphp-pay-vip'])){
	$paytype=intval($_POST['paytype']);
	$usertype = intval($_POST['userType']);

	if($paytype==1)
	{
		$url=constant("erphpdown")."payment/alipay.php?ice_type=".$usertype;
	}
	elseif($paytype==5)
	{
		$url=constant("erphpdown")."payment/f2fpay.php?ice_type=".$usertype;
	}
	elseif($paytype==4)
	{
		if(erphpdown_is_weixin() && get_option('ice_weixin_app')){
			$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.get_option('ice_weixin_appid').'&redirect_uri='.urlencode(constant("erphpdown")).'payment%2Fweixin.php%3Fice_type%3D'.$usertype.'&response_type=code&scope=snsapi_base&state=STATE&connect_redirect=1#wechat_redirect';
		}else{
			$url=constant("erphpdown")."payment/weixin.php?ice_type=".$usertype;
		}
	}
	elseif($paytype==7)
	{
		$url=constant("erphpdown")."payment/paypy.php?ice_type=".$usertype;
	}
	elseif($paytype==8)
	{
		$url=constant("erphpdown")."payment/paypy.php?ice_type=".$usertype."&type=alipay";
	}
	elseif($paytype==2)
	{
		$url=constant("erphpdown")."payment/paypal.php?ice_type=".$usertype;
	}
    elseif($paytype==18)
	{
		$url=constant("erphpdown")."payment/xhpay3.php?ice_type=".$usertype."&type=2";
	}
	elseif($paytype==17)
	{
		$url=constant("erphpdown")."payment/xhpay3.php?ice_type=".$usertype."&type=1";
	}elseif($paytype==19)
	{
		$url=constant("erphpdown")."payment/payjs.php?ice_type=".$usertype;
	}elseif($paytype==20)
	{
		$url=constant("erphpdown")."payment/payjs.php?ice_type=".$usertype."&type=alipay";
	}
    elseif($paytype==13)
    {
        $url=constant("erphpdown")."payment/codepay.php?ice_type=".$usertype."&type=1";
    }elseif($paytype==14)
    {
        $url=constant("erphpdown")."payment/codepay.php?ice_type=".$usertype."&type=3";
    }elseif($paytype==15)
    {
        $url=constant("erphpdown")."payment/codepay.php?ice_type=".$usertype."&type=2";
    }
    elseif($paytype==21)
	{
		$url=constant("erphpdown")."payment/epay.php?ice_type=".$usertype."&type=alipay";
	}elseif($paytype==22)
	{
		$url=constant("erphpdown")."payment/epay.php?ice_type=".$usertype."&type=wxpay";
	}elseif($paytype==23)
	{
		$url=constant("erphpdown")."payment/epay.php?ice_type=".$usertype."&type=qqpay";
	}elseif($paytype==31)
	{
		$url=constant("erphpdown")."payment/vpay.php?ice_type=".$usertype."&type=2";
	}elseif($paytype==32)
	{
		$url=constant("erphpdown")."payment/vpay.php?ice_type=".$usertype;
	}
	echo "<script>location.href='".$url."'</script>";
	exit;
}

if(isset($_POST['paytype'])){
	$paytype=intval($_POST['paytype']);
	$doo = 1;
	
	if(isset($_POST['paytype']) && $paytype==1)
	{
		$url=constant("erphpdown")."payment/alipay.php?ice_money=".esc_sql($_POST['ice_money']);
	}
	elseif(isset($_POST['paytype']) && $paytype==5)
	{
		$url=constant("erphpdown")."payment/f2fpay.php?ice_money=".esc_sql($_POST['ice_money']);
	}
	elseif(isset($_POST['paytype']) && $paytype==4)
	{
		if(erphpdown_is_weixin() && get_option('ice_weixin_app')){
			$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.get_option('ice_weixin_appid').'&redirect_uri='.urlencode(constant("erphpdown")).'payment%2Fweixin.php%3Fice_money%3D'.esc_sql($_POST['ice_money']).'&response_type=code&scope=snsapi_base&state=STATE&connect_redirect=1#wechat_redirect';
		}else{
			$url=constant("erphpdown")."payment/weixin.php?ice_money=".esc_sql($_POST['ice_money']);
		}
	}
	elseif(isset($_POST['paytype']) && $paytype==7)
	{
		$url=constant("erphpdown")."payment/paypy.php?ice_money=".esc_sql($_POST['ice_money']);
	}
	elseif(isset($_POST['paytype']) && $paytype==8)
	{
		$url=constant("erphpdown")."payment/paypy.php?ice_money=".esc_sql($_POST['ice_money'])."&type=alipay";
	}
	elseif(isset($_POST['paytype']) && $paytype==2)
	{
		$url=constant("erphpdown")."payment/paypal.php?ice_money=".esc_sql($_POST['ice_money']);
	}
    elseif(isset($_POST['paytype']) && $paytype==18)
	{
		$url=constant("erphpdown")."payment/xhpay3.php?ice_money=".esc_sql($_POST['ice_money'])."&type=2";
	}
	elseif(isset($_POST['paytype']) && $paytype==17)
	{
		$url=constant("erphpdown")."payment/xhpay3.php?ice_money=".esc_sql($_POST['ice_money'])."&type=1";
	}elseif(isset($_POST['paytype']) && $paytype==19)
	{
		$url=constant("erphpdown")."payment/payjs.php?ice_money=".esc_sql($_POST['ice_money']);
	}elseif(isset($_POST['paytype']) && $paytype==20)
	{
		$url=constant("erphpdown")."payment/payjs.php?ice_money=".esc_sql($_POST['ice_money'])."&type=alipay";
	}
    elseif(isset($_POST['paytype']) && $paytype==13)
    {
        $url=constant("erphpdown")."payment/codepay.php?ice_money=".esc_sql($_POST['ice_money'])."&type=1";
    }elseif(isset($_POST['paytype']) && $paytype==14)
    {
        $url=constant("erphpdown")."payment/codepay.php?ice_money=".esc_sql($_POST['ice_money'])."&type=3";
    }elseif(isset($_POST['paytype']) && $paytype==15)
    {
        $url=constant("erphpdown")."payment/codepay.php?ice_money=".esc_sql($_POST['ice_money'])."&type=2";
    }
    elseif(isset($_POST['paytype']) && $paytype==21)
	{
		$url=constant("erphpdown")."payment/epay.php?ice_money=".esc_sql($_POST['ice_money'])."&type=alipay";
	}elseif(isset($_POST['paytype']) && $paytype==22)
	{
		$url=constant("erphpdown")."payment/epay.php?ice_money=".esc_sql($_POST['ice_money'])."&type=wxpay";
	}elseif(isset($_POST['paytype']) && $paytype==23)
	{
		$url=constant("erphpdown")."payment/epay.php?ice_money=".esc_sql($_POST['ice_money'])."&type=qqpay";
	}elseif($paytype==31)
	{
		$url=constant("erphpdown")."payment/vpay.php?ice_money=".esc_sql($_POST['ice_money'])."&type=2";
	}elseif($paytype==32)
	{
		$url=constant("erphpdown")."payment/vpay.php?ice_money=".esc_sql($_POST['ice_money']);
	}
	else{
		
	}
	if($doo) echo "<script>location.href='".$url."'</script>";
	exit;
}

if(isset($_POST['userType'])){
	$userType=isset($_POST['userType']) && is_numeric($_POST['userType']) ?intval($_POST['userType']) :0;
	if($userType >5 && $userType < 11){
		$okMoney=erphpGetUserOkMoney();
		$priceArr=array('6'=>'ciphp_day_price','7'=>'ciphp_month_price','8'=>'ciphp_quarter_price','9'=>'ciphp_year_price','10'=>'ciphp_life_price');
		$priceType=$priceArr[$userType];
		$price=get_option($priceType);
		$oldUserType = getUsreMemberTypeById($user_info->ID);
		$vip_update_pay = get_option('vip_update_pay');
		if($vip_update_pay){
			$erphp_quarter_price = get_option('ciphp_quarter_price');
			$erphp_month_price  = get_option('ciphp_month_price');
			$erphp_day_price  = get_option('ciphp_day_price');
			$erphp_year_price    = get_option('ciphp_year_price');
			$erphp_life_price  = get_option('ciphp_life_price');

			if($userType == 7){
				if($oldUserType == 6){
             		$price = $erphp_month_price - $erphp_day_price;
             	}
			}elseif($userType == 8){
				if($oldUserType == 6){
             		$price = $erphp_quarter_price - $erphp_day_price;
             	}elseif($oldUserType == 7){
             		$price = $erphp_quarter_price - $erphp_month_price;
             	}
			}elseif($userType == 9){
				if($oldUserType == 6){
             		$price = $erphp_year_price - $erphp_day_price;
             	}elseif($oldUserType == 7){
             		$price = $erphp_year_price - $erphp_month_price;
             	}elseif($oldUserType == 8){
             		$price = $erphp_year_price - $erphp_quarter_price;
             	}
			}elseif($userType == 10){
				if($oldUserType == 6){
             		$price = $erphp_life_price - $erphp_day_price;
             	}elseif($oldUserType == 7){
             		$price = $erphp_life_price - $erphp_month_price;
             	}elseif($oldUserType == 8){
             		$price = $erphp_life_price - $erphp_quarter_price;
             	}elseif($oldUserType == 9){
             		$price = $erphp_life_price - $erphp_year_price;
             	}
			}
		}

		if(!$price){
			echo "<script>alert('此类型的会员价格错误，请稍候重试！');</script>";
		}elseif($okMoney < $price){
			echo "<script>alert('当前可用余额不足完成此次交易！');</script>";
		}elseif($okMoney >=$price){
			if(erphpSetUserMoneyXiaoFei($price)){
				if(userPayMemberSetData($userType)){
					addVipLog($price, $userType);
					
					$EPD = new EPD();
					$EPD->doAff($price, $user_info->ID);
				}else{
					echo "<script>alert('系统发生错误，请联系管理员！');</script>";
				}
			}else{
				echo "<script>alert('系统发生错误，请稍候重试！');</script>";
			}
		}else{
			echo "<script>alert('未定义的操作！');</script>";
		}
	}else{
		echo "<script>alert('会员类型错误！');</script>";
	}
}

if(isset($_POST['action']) && $_POST['action'] == 'card'){
	$cardnum = $wpdb->escape($_POST['epdcardnum']);
	$cardpass = $wpdb->escape($_POST['epdcardpass']);
	$result = checkDoCardResult($cardnum,$cardpass);
	if($result == '5'){
		echo "<script>alert('充值卡不存在！');</script>";
	}elseif($result == '0'){
		echo "<script>alert('充值卡已被使用！');</script>";
	}elseif($result == '2'){
		echo "<script>alert('充值卡密码错误！');</script>";
	}elseif($result == '1'){
		echo "<script>alert('充值成功！');</script>";
	}else{
		echo "<script>alert('系统错误，请稍后重试！');</script>";
	}
}elseif(isset($_POST['action']) && $_POST['action'] == 'vipcard'){
	$cardnum = $wpdb->escape($_POST['epdcardnum']);
	$result = checkDoVipCardResult($cardnum);
	if($result == '3'){
		echo "<script>alert('充值卡不存在！');</script>";
	}elseif($result == '0'){
		echo "<script>alert('充值卡已被使用！');</script>";
	}elseif($result == '2'){
		echo "<script>alert('充值卡已过期！');</script>";
	}elseif($result == '1'){
		echo "<script>alert('升级成功！');</script>";
	}else{
		echo "<script>alert('系统错误，请稍后重试！');</script>";
	}
}elseif(isset($_POST['action']) && $_POST['action'] == 'mycredto'){
	$epdmycrednum = $wpdb->escape($_POST['epdmycrednum']);
	if(is_numeric($epdmycrednum) && $epdmycrednum > 0 && get_option('erphp_mycred') == 'yes'){
		if(floatval(mycred_get_users_cred( $user_info->ID )) < floatval($epdmycrednum*get_option('erphp_to_mycred'))){
			$mycred_core = get_option('mycred_pref_core');
			echo "<script>alert('mycred剩余".$mycred_core['name']['plural']."不足！');</script>";
		}
		else
		{
			mycred_add( '兑换', $user_info->ID, '-'.$epdmycrednum*get_option('erphp_to_mycred'), '兑换扣除%plural%!', date("Y-m-d H:i:s") );
			$money = $epdmycrednum;
			if(addUserMoney($user_info->ID, $money))
			{
				$sql="INSERT INTO $wpdb->icemoney (ice_money,ice_num,ice_user_id,ice_time,ice_success,ice_note,ice_success_time,ice_alipay)
				VALUES ('$money','".date("ymdhis").mt_rand(10000,99999)."','".$user_info->ID."','".date("Y-m-d H:i:s")."',1,'4','".date("Y-m-d H:i:s")."','')";
				$wpdb->query($sql);
				echo "<script>alert('兑换成功！');</script>";
			}
			else
			{
				echo "<script>alert('兑换失败！');</script>";
			}
		}
	}
}

get_header(); ?>

<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/user-center.css" />
<section class="container">
	<div class="pagewrapper clearfix n-mode" id="profile" >
		<header class="pageheader clearfix">
			<div class="ed-page-title">个人中心</div>
			<?php 
				if(get_option('ice_ali_money_checkin')){
					if(erphpdown_check_checkin($user_info->ID)){
						echo '<a href="javascript:;" class="usercheck active"><i class="ep ep-tixianchuli"></i>已签到</a>';
					}else{
						echo '<a href="javascript:;" class="usercheck erphp-checkin"><i class="ep ep-tixianchuli"></i>签到</a>';
					}
				}
			?>
		</header>
		<aside class="pagesidebar" style="top:44px;">
			<ul class="pagesider-menu">
				<li <?php if((isset($_GET["pd"]) && $_GET["pd"]=='info') || !isset($_GET["pd"])){?>class="active"<?php }?> ><a href="?pd=info"><i class="cx cx-haibao"></i><span class="pdt">我的信息</span></a></li>
				<li <?php if(isset($_GET["pd"]) && $_GET["pd"]=='recharge'){?>class="active"<?php }?> ><a class="ico-k" href="?pd=recharge" ><i class="ep ep-yue"></i><span class="pdt">我的余额</span></a></li>
				<li <?php if(isset($_GET["pd"]) && $_GET["pd"]=='vip'){?>class="active"<?php }?> ><a class="ico-k" href="?pd=vip"><i class="cx cx-svip"></i><span class="pdt">我的会员</span></a></li>
				<li <?php if(isset($_GET["pd"]) && $_GET["pd"]=='cart'){?>class="active"<?php }?> ><a href="?pd=cart"><i class="ep ep-blogs"></i><span class="pdt">下载记录</span></a></li>
				<?php if(function_exists('erphpad_install')){?>
				<li class="<?php if(isset($_GET["pd"]) && $_GET['pd'] == 'ad') echo 'active';?>"><a href="?pd=ad"><i class="ep ep-guanggaoguanli"></i><span class="pdt">我的广告</span></a></li>
				<?php }?>
				<?php if(function_exists('erphpdown_tuan_install')){?>
				<li class="<?php if(isset($_GET["pd"]) && $_GET['pd'] == 'tuan') echo 'active';?>"><a href="?pd=tuan"><i class="ep ep-shouye"></i><span class="pdt">我的拼团</span></a></li>
				<?php }?>
				<li <?php if(isset($_GET["pd"]) && $_GET["pd"]=='ref'){?>class="active"<?php }?> ><a href="?pd=ref"><i class="ep ep-tongzhituiguang"></i><span class="pdt">推广记录</span></a></li>
				<li <?php if(isset($_GET["pd"]) && ($_GET["pd"]=='outmo' || $_GET["pd"]=='tixian')){?>class="active"<?php }?> ><a href="?pd=outmo"><i class="ep ep-tixian"></i><span class="pdt">申请提现</span></a></li>
				<!-- be -->
				<li <?php if(isset($_GET["pd"]) && $_GET["pd"]=='post'){?>class="active"<?php }?> ><a href="?pd=post"><i class="be be-file"></i><span class="pdt">我的文章</span></a></li>
				<li <?php if(isset($_GET["pd"]) && $_GET["pd"]=='comment'){?>class="active"<?php }?> ><a href="?pd=comment"><i class="be be-speechbubble"></i><span class="pdt">我的评论</span></a></li>
				<?php if (zm_get_option('shar_favorite')) { ?>
					<li <?php if(isset($_GET["pd"]) && $_GET["pd"]=='favorite'){?>class="active"<?php }?> ><a href="?pd=favorite"><i class="be be-favoriteoutline"></i><span class="pdt">我的收藏</span></a></li>
				<?php } ?>
				<?php if ( !zm_get_option('tou_url') == '' ) { ?>
					<li><a href="<?php echo get_permalink( zm_get_option('tou_url') ); ?>" target="_blank"><i class="be be-editor"></i><span class="pdt">我要投稿</span></a></li>
				<?php } ?>
				<!-- be end -->
				<!-- 
				<li><a href="<?php echo wp_logout_url(home_url());?>"><i class="be be-businesscard"></i><span class="pdt">安全退出</span></a></li>
				-->
			</ul>
			<div class="pd-menu-switch fd">
				<div class="menu-display"><i class="be be-sidebar-off"></i></div>
				<div class="menu-hide"><i class="be be-sidebar-on"></i></div>
			</div>
		</aside>
		<div class="pagecontent profile-content">
			<?php if( (isset($_GET["pd"]) && $_GET["pd"]=='info') || !isset($_GET["pd"])){?>
			<div id="infocenter">

			<div class="my-user">
				<?php my_inf(); ?>
				<?php my_data(); ?>
				<h2 class="profile-title">我的余额</h2>
				<?php epd_assets_vip(); ?>
				<div class="be-topup-submit"><a class="topup-btn" href="<?php echo zm_get_option( 'be_rec_but_url' ); ?>">立即充值</a></div>
				<div class="form-clear-m"></div>
				<?php get_template_part( 'inc/my-data' ); ?>
			</div>

			<?php }elseif(isset($_GET["pd"]) && $_GET["pd"]=='recharge'){
				$totallists = $wpdb->get_var("SELECT count(*) FROM $wpdb->icemoney WHERE ice_success=1 and ice_user_id=".$user_info->ID);
				$ice_perpage = 10;
				$pages = ceil($totallists / $ice_perpage);
				$page=isset($_GET['pp']) ?intval($_GET['pp']) :1;
				$offset = $ice_perpage*($page-1);
				$lists = $wpdb->get_results("SELECT * FROM $wpdb->icemoney where ice_success=1 and ice_user_id=".$user_info->ID." order by ice_time DESC limit $offset,$ice_perpage");
				
				?>

				<!-- 资产 -->
				<h2 class="profile-title">我的余额</h2>
				<?php epd_assets_vip(); ?>
				<div class="be-topup-submit"><a class="topup-btn" href="<?php echo zm_get_option( 'be_vip_but_url' ); ?>">订购会员</a></div>
				<div class="form-clear-p"></div>
				<!-- 充值 -->
				<div class="profile-box">
	                <form action="" method="post" onSubmit="return checkFm();">
	                        <h2 class="profile-title ed-title">在线充值</h2>
	                        <table class="form-table">
	                            <tr>
	                				<td>
	                				<input type="text" class="profile-input" id="ice_money" name="ice_money" required="required"/>充值金额，1元 = <?php echo get_option('ice_proportion_alipay').' '.get_option('ice_name_alipay')?>
	                				</td>
	                            </tr>
	                            <tr>
									<td>
									<h4 class="epd-paytype-title">支付方式</h4>
									<div class="clear"></div>
	                                <?php 
				            		$erphpdown_recharge_order = get_option('erphpdown_recharge_order');
				            		if($erphpdown_recharge_order){
				            			$erphpdown_recharge_order_arr = explode(',', str_replace('，', ',', trim($erphpdown_recharge_order)));
				            			$pi = 0;
				            			foreach ($erphpdown_recharge_order_arr as $payment) {
				            				if($pi == 0) $checked = ' checked'; else $checked = '';
				            				switch ($payment) {
				            					case 'alipay':
				            						echo '<input type="radio" id="paytype1"'.$checked.' class="paytype" name="paytype" value="1" /><label for="paytype1" class="payment-label payment-alipay-label">支付宝</label>';
				            						break;
				            					case 'wxpay':
				            						echo '<input type="radio" id="paytype4" class="paytype"'.$checked.' name="paytype" value="4" /><label for="paytype4" class="payment-label payment-wxpay-label">微信</label>';
				            						break;
				            					case 'f2fpay':
				            						echo '<input type="radio" id="paytype5" class="paytype"'.$checked.' name="paytype" value="5" /><label for="paytype5" class="payment-label payment-alipay-label">支付宝</label>';
				            						break;
				            					case 'paypal':
				            						echo '<input type="radio" id="paytype2" class="paytype"'.$checked.' name="paytype" value="2" /><label for="paytype2" class="payment-label payment-paypal-label">PayPal</label> (美元汇率:'.get_option('ice_payapl_api_rmb').')';
				            						break;
				            					case 'paypy-wx':
				            						echo '<input type="radio" id="paytype7" class="paytype" name="paytype" value="7"'.$checked.' /><label for="paytype7" class="payment-label payment-wxpay-label">微信</label>';
				            						break;
				            					case 'paypy-ali':
				            						echo '<input type="radio" id="paytype8" class="paytype" name="paytype" value="8"'.$checked.' /><label for="paytype8" class="payment-label payment-alipay-label">支付宝</label>';
				            						break;
				            					case 'payjs-wx':
				            						echo '<input type="radio" id="paytype19" class="paytype" name="paytype" value="19"'.$checked.' /><label for="paytype19" class="payment-label payment-wxpay-label">微信</label>';
				            						break;
				            					case 'payjs-ali':
				            						echo '<input type="radio" id="paytype20" class="paytype" name="paytype" value="20"'.$checked.' /><label for="paytype20" class="payment-label payment-alipay-label">支付宝</label>';
				            						break;
				            					case 'xhpay-wx':
				            						echo '<input type="radio" id="paytype18" class="paytype" name="paytype" value="18"'.$checked.' /><label for="paytype18" class="payment-label payment-wxpay-label">微信</label>';
				            						break;
				            					case 'xhpay-ali':
				            						echo '<input type="radio" id="paytype17" class="paytype" name="paytype" value="17"'.$checked.' /><label for="paytype17" class="payment-label payment-alipay-label">支付宝</label>';
				            						break;
				            					case 'codepay-wx':
				            						echo '<input type="radio" id="paytype14" class="paytype" name="paytype" value="14"'.$checked.' /><label for="paytype14" class="payment-label payment-wxpay-label">微信</label>';
				            						break;
				            					case 'codepay-ali':
				            						echo '<input type="radio" id="paytype13" class="paytype" name="paytype" value="13"'.$checked.' /><label for="paytype13" class="payment-label payment-alipay-label">支付宝</label>';
				            						break;
				            					case 'codepay-qq':
				            						echo '<input type="radio" id="paytype15" class="paytype" name="paytype" value="15"'.$checked.' /><label for="paytype15" class="payment-label payment-qqpay-label">QQ钱包</label>';
				            						break;
				            					case 'epay-wx':
				            						echo '<input type="radio" id="paytype22" class="paytype" name="paytype" value="22"'.$checked.' /><label for="paytype22" class="payment-label payment-wxpay-label">微信</label>';
				            						break;
				            					case 'epay-ali':
				            						echo '<input type="radio" id="paytype21" class="paytype" name="paytype" value="21"'.$checked.' /><label for="paytype21" class="payment-label payment-alipay-label">支付宝</label>';
				            						break;
				            					case 'epay-qq':
				            						echo '<input type="radio" id="paytype23" class="paytype" name="paytype" value="23"'.$checked.' /><label for="paytype23" class="payment-label payment-qqpay-label">QQ钱包</label>';
				            						break;
				            					case 'vpay-wx':
				            						echo '<input type="radio" id="paytype32" class="paytype" name="paytype" value="32"'.$checked.' /><label for="paytype32" class="payment-label payment-wxpay-label">微信</label>';
				            						break;
				            					case 'vpay-ali':
				            						echo '<input type="radio" id="paytype31" class="paytype" name="paytype" value="31"'.$checked.' /><label for="paytype31" class="payment-label payment-alipay-label">支付宝</label>';
				            						break;
				            					default:
				            						break;
				            				}
				            				$pi ++;
				            			}
				            		}else{
				            		?>
		                                <?php if(get_option('ice_payapl_api_uid')){?> 
		                                <input type="radio" id="paytype2" class="paytype" checked name="paytype" value="2" />PayPal(美元汇率:<?php echo get_option('ice_payapl_api_rmb')?>)&nbsp;
		                                <?php }?>
		                                <?php if(get_option('ice_weixin_mchid')){?> 
		                                <input type="radio" id="paytype4" class="paytype" checked name="paytype" value="4" /><label for="paytype4" class="ed-type"><i class="cx cx-weixin"></i>微信</label>
		                                <?php }?>
		                                <?php if(get_option('ice_ali_partner') || get_option('ice_ali_app_id')){?> 
		                                <input type="radio" id="paytype1" class="paytype" checked name="paytype" value="1" /><label for="paytype1" class="ed-type"><i class="cx cx-alipay"></i>支付宝</label>
		                                <?php }?>
		                                <?php if(get_option('erphpdown_f2fpay_id')){?> 
										<input type="radio" id="paytype5" class="paytype" checked name="paytype" value="5" /><label for="paytype5" class="ed-type"><i class="cx cx-alipay"></i>支付宝</label>
										<?php }?>
										<?php if(get_option('erphpdown_payjs_appid')){?>  
											<?php if(!get_option('erphpdown_payjs_alipay')){?><input type="radio" id="paytype20" class="paytype" name="paytype" value="20" checked /><label for="paytype20" class="ed-type"><i class="cx cx-alipay"></i>支付宝</label><?php }?>
											<?php if(!get_option('erphpdown_payjs_wxpay')){?><input type="radio" id="paytype19" class="paytype" name="paytype" value="19" checked /><label for="paytype19" class="ed-type"><i class="cx cx-weixin"></i>微信</label><?php }?>
										<?php }?>
										<?php if(get_option('erphpdown_xhpay_appid31')){?> 
											<input type="radio" id="paytype18" class="paytype" name="paytype" value="18" checked /><label for="paytype18" class="ed-type"><i class="cx cx-weixin"></i>微信</label>
										<?php }?>
										<?php if(get_option('erphpdown_xhpay_appid32')){?> 
											<input type="radio" id="paytype17" class="paytype" name="paytype" value="17" checked /><label for="paytype17" class="ed-type"><i class="cx cx-alipay"></i>支付宝</label>
										<?php }?>
						                <?php if(get_option('erphpdown_codepay_appid')){?> 
						                	<?php if(!get_option('erphpdown_codepay_alipay')){?><input type="radio" id="paytype13" class="paytype" name="paytype" value="13" checked /><label for="paytype13" class="ed-type"><i class="cx cx-alipay"></i>支付宝</label><?php }?>
						                	<?php if(!get_option('erphpdown_codepay_wxpay')){?><input type="radio" id="paytype14" class="paytype" name="paytype" value="14" /><label for="paytype14" class="ed-type"><i class="cx cx-weixin"></i>微信</label><?php }?>
						                	<?php if(!get_option('erphpdown_codepay_qqpay')){?><input type="radio" id="paytype15" class="paytype" name="paytype" value="15" /><label for="paytype15" class="ed-type"><i class="be be-qq"></i>QQ钱包</label><?php }?> 
						                <?php }?>
						                <?php if(get_option('erphpdown_paypy_key')){?> 
											<?php if(!get_option('erphpdown_paypy_wxpay')){?><input type="radio" id="paytype7" class="paytype" name="paytype" value="7" checked /><label for="paytype7" class="ed-type"><i class="cx cx-weixin"></i>微信</label><?php }?>
											<?php if(!get_option('erphpdown_paypy_alipay')){?><input type="radio" id="paytype8" class="paytype" name="paytype" value="8" checked/><label for="paytype8" class="ed-type"><i class="cx cx-alipay"></i>支付宝</label><?php }?>
										<?php }?> 
										<?php if(get_option('erphpdown_epay_id')){?>
											<?php if(!get_option('erphpdown_epay_alipay')){?><input type="radio" id="paytype21" class="paytype" name="paytype" value="21" checked /><label for="paytype21" class="ed-type"><i class="cx cx-alipay"></i>支付宝</label><?php }?>
											<?php if(!get_option('erphpdown_epay_qqpay')){?><input type="radio" id="paytype23" class="paytype" name="paytype" value="23" checked/><label for="paytype23" class="ed-type"><i class="be be-qq"></i>QQ钱包</label><?php }?>
											<?php if(!get_option('erphpdown_epay_wxpay')){?><input type="radio" id="paytype22" class="paytype" name="paytype" value="22" checked/><label for="paytype22" class="ed-type"><i class="cx cx-weixin"></i>微信</label><?php }?>
										<?php }?>
										<?php if(get_option('erphpdown_vpay_key')){?>
											<?php if(!get_option('erphpdown_vpay_alipay')){?><input type="radio" id="paytype31" class="paytype" name="paytype" value="31" checked /><label for="paytype31" class="ed-type"><i class="cx cx-alipay"></i>支付宝</label><?php }?>
											<?php if(!get_option('erphpdown_vpay_wxpay')){?><input type="radio" id="paytype32" class="paytype" name="paytype" value="32" checked/><label for="paytype32" class="ed-type"><i class="cx cx-weixin"></i>微信</label><?php }?>
										<?php }?>
		                            <?php }?>
	                                </td>
	                            </tr>
	                     <tr>
	                        <td><p class="submit">
	                            <input type="submit" name="Submit" value="立即充值" class="profile-btn"/>
	                            </p>
	                        </td>
	                
	                        </tr> 
	                        
	                        </table>
	                
	                </form>
	                </div>

					<div class="form-clear"></div>

	                <?php if(function_exists("checkDoCardResult")){?>
	                <div class="profile-box">
	                	<form action="" method="post">
	                        <h2 class="profile-title ed-title">充值卡充值</h2>
	                        <table class="form-table">
	                        	<tr>
	                            	<td>1 元 = <?php echo get_option('ice_proportion_alipay').' '.get_option('ice_name_alipay')?></td>
	                            </tr>
	                            <tr>
	                				<td>
	                				充值卡号<input type="text" id="epdcardnum" name="epdcardnum"  required="required" />
	                				</td>
	                            </tr>
	                            <tr>
	                				<td>
	                				充值卡密<input type="text" id="epdcardpass" name="epdcardpass"  required="required"/>
	                				</td>
	                            </tr>
	                            
	                    <tr>
	                        <td><p class="submit">
	                        <input type="hidden" name="action" value="card">
	                            <input type="submit" value="立即充值" class="profile-btn" />
	                            </p>
	                        </td>
	                
	                        </tr> 
	                        
	                        </table>
	                
	                	</form>
	                </div>
	                <?php }?>

	                <?php if(function_exists("checkDoVipCardResult")){?>
	                <div class="profile-box">
	                	<form action="" method="post">
	                        <h2 class="profile-title ed-title">充值卡</h2>
	                        <table class="form-table">
	                            <tr>
	                				<td>
	                				卡号<input type="text" id="epdcardnum" name="epdcardnum"  required="required" />
	                				</td>
	                            </tr>
	                            
	                    <tr>
	                        <td><p class="submit">
	                        <input type="hidden" name="action" value="vipcard">
	                            <input type="submit" value="立即充值" class="profile-btn" />
	                            </p>
	                        </td>
	                
	                        </tr> 
	                        
	                        </table>
	                
	                	</form>
	                </div>
	                <?php }?>

	                <?php if(plugin_check_cred() && get_option('erphp_mycred') == 'yes'){
	                	$mycred_core = get_option('mycred_pref_core');
	                	?>

					<div class="form-clear"></div>

	                <div class="profile-box">
		                <form action="" method="post" onSubmit="return checkFm3();">
							<h2 class="profile-title ed-title"><?php echo $mycred_core['name']['plural'];?>兑换<?php echo get_option('ice_name_alipay')?></h2>
		                	<table class="form-table">
		                            <tr>
		                				<td>
		                				<input type="text" id="epdmycrednum" name="epdmycrednum" class="profile-input" required="required" placeholder="需要兑换的<?php echo get_option('ice_name_alipay')?>数" />（请输入一个整数，<?php echo get_option('erphp_to_mycred').$mycred_core['name']['plural'];?> = 1<?php echo get_option('ice_name_alipay')?>）
		                				</td>
		                            </tr>
		                     <tr>
		                        <td><p class="submit">
		                            <input type="submit" name="Submit" value="兑换" class="profile-btn" onClick="return confirm('确认兑换?');"/>
		                            <input type="hidden" name="action" value="mycredto">
		                            </p>
		                        </td>
		                
		                        </tr> 
		                        
		                        </table>
		                </form>
					</div>
					<?php }?>
				<!-- 充值结束 -->

				<div class="be-epd-user">
					<h2 class="profile-title ed-title">充值记录</h2>
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th>金额</th>
								<th>方式</th>
								<th>时间</th>
							</tr>
						</thead>
						<tbody>
							<?php
								if($lists) {
									foreach($lists as $value)
									{
										echo "<tr>\n";
										echo "<td>$value->ice_money</td>\n";
										if(intval($value->ice_note)==0)
										{
											echo "<td>在线充值</td>\n";
										}elseif(intval($value->ice_note)==1)
										{
											echo "<td>后台充值</td>\n";
										}
										elseif(intval($value->ice_note)==2)
										{
											echo "<td>转账收</td>\n";
										}
										elseif(intval($value->ice_note)==3)
										{
											echo "<td>转账付</td>\n";
										}elseif(intval($value->ice_note)==4)
										{
											echo "<td>积分兑换</td>\n";
										}elseif(intval($value->ice_note)==6)
										{
											echo "<td>充值卡</td>\n";
										}else{
											echo "<td>未知</td>\n";
										}
										
										echo "<td>$value->ice_time</td>";
										echo "</tr>";
									}
								}
								else
								{
									echo '<tr width=100%><td colspan="3" align="center"><center><strong>没有记录！</strong></center></td></tr>';
								}
							?>
						</tbody>
					</table>
					<?php mobantu_paging('recharge',$page,$pages);?>
				</div>


			<?php }elseif(isset($_GET["pd"]) && $_GET["pd"]=='vip'){
				$totallists = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->vip where ice_user_id=".$user_info->ID);
				$ice_perpage = 10;
				$pages = ceil($totallists / $ice_perpage);
				$page=isset($_GET['pp']) ?intval($_GET['pp']) :1;
				$offset = $ice_perpage*($page-1);
				$lists = $wpdb->get_results("SELECT * FROM $wpdb->vip where ice_user_id=".$user_info->ID." order by ice_time DESC limit $offset,$ice_perpage");
				
				?>
				<!-- 购买VIP -->
				<!-- 资产 -->
				<?php 
					$user_Info   = wp_get_current_user();
					$userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$user_Info->ID);
					if(!$userMoney){
						$okMoney=0;
					}else{
						$okMoney=$userMoney->ice_have_money - $userMoney->ice_get_money;
					}
				?>

				<h2 class="profile-title">我的余额</h2>
				<div class="profile-assets<?php if ( class_exists( 'myCRED_Core' ) && get_option( 'erphp_mycred' ) == 'yes' ) { ?> profile-assets-cred<?php }?>">


					<!-- VIP信息 -->
					<div class="be-assets-box">
						<div class="be-assets-main be-assets-main-d ms<?php $userTypeId=getUsreMemberType(); if ( $userTypeId == 0 ) { ?> be-assets-main-d-f<?php } ?>">
						<div class="be-assets-caption">您目前为</div>
						<div class="be-assets-count be-assets-count-vip">
							<?php 
								$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
								$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
								$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
								$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
								$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';

								$erphp_year_price    = get_option('ciphp_year_price');
								$erphp_quarter_price = get_option('ciphp_quarter_price');
								$erphp_month_price  = get_option('ciphp_month_price');
								$erphp_life_price  = get_option('ciphp_life_price');
								$erphp_day_price  = get_option('ciphp_day_price');

								$vip_update_pay = 0;
								if(get_option('vip_update_pay')){
									$vip_update_pay = 1;
									global $current_user;
								}

								$userTypeId=getUsreMemberType();
								if($userTypeId==6) {
									echo "".$erphp_day_name;
								} elseif($userTypeId==7) {
									echo "".$erphp_month_name;
								} elseif ($userTypeId==8) {
									echo "".$erphp_quarter_name;
								} elseif ($userTypeId==9) {
									echo "".$erphp_year_name;
				                } elseif ($userTypeId==10) {
									echo "".$erphp_life_name;
								} else  { ?>
									普通用户
									<div class="be-assets-inf be-assets-vip-inf">无下载查看收费内容权限</div>
									</div></div>
								<?php }
							?>
						<?php if ( ! $userTypeId == 0 ) { ?></div><?php } ?>
						<?php if ( ! $userTypeId == 0 ) { ?>
							<div class="be-assets-inf be-assets-vip-inf">
								<?php if ( $userTypeId == 10 ) { ?>
									<?php echo (($userTypeId>0 && $userTypeId<10) || ($userTypeId == 10 && !get_option('erphp_life_days'))) ? '<span class="be-expire-date bgt">有效期至</span>永久':'';?>
									<!-- <?php echo (($userTypeId>0 && $userTypeId<10) || ($userTypeId == 10 && get_option('erphp_life_days'))) ? '<span class="be-expire-date bgt">有效期至</span>永久':'';?> -->
								<?php } else { ?>
									<?php echo (($userTypeId>0 && $userTypeId<10) || ($userTypeId == 10 && get_option('erphp_life_days'))) ? '<span class="be-expire-date bgt">有效期至</span>'.getUsreMemberTypeEndTime() :'';?>
								<?php } ?>
							</div>
						</div>
						<?php } ?>
						<!-- VIP信息结束 -->
					</div>

					<div class="be-assets-box">
						<div class="be-assets-main be-assets-main-a ms">
							<div class="be-assets-caption">当前余额
								<div class="be-assets-count"><?php echo sprintf("%.2f",$okMoney); ?><div class="be-assets-inf"><?php echo get_option('ice_name_alipay');?></div></div>
							</div>
						</div>
					</div>

					<div class="be-assets-box">
						<div class="be-assets-main be-assets-main-b ms">
							<div class="be-assets-caption">累计消费</div>
							<div class="be-assets-count"><?php echo $userMoney?sprintf("%.2f",$userMoney->ice_get_money):0; ?><div class="be-assets-inf"><?php echo get_option('ice_name_alipay');?></div></div>
						</div>
					</div>

					<?php if ( class_exists( 'myCRED_Core' ) && get_option( 'erphp_mycred' ) == 'yes' ) { ?>
						<div class="be-assets-box">
							<div class="be-assets-main be-assets-main-c ms">
								<div class="be-assets-caption">您目前有</div>
								<div class="be-assets-count"><?php echo mycred_get_users_cred( $user_Info->ID ); ?>
									<div class="be-assets-inf">
										<?php $mycred_core = get_option('mycred_pref_core'); ?>
										<?php echo $mycred_core['name']['plural']; ?>
									</div>
								</div>
							</div>
						</div>
					<?php }?>
					<!-- 资产结束 -->
				</div>

				<div class="be-topup-submit"><a class="topup-btn" href="<?php echo zm_get_option( 'be_rec_but_url' ); ?>">立即充值</a></div>
			<div class="clear"></div>
		<div class="form-clear-m"></div>
		<div class="profile-box ed-vip-box">
			<h2 class="profile-title ed-title">订购会员</h2>
			<div class="clear"></div>
			<h3>选择类型</h3>
	
			<form action="" method="post">
				<table class="form-table ed-vip-table <?php if ( ! $erphp_month_price ){ ?> no-month<?php }?>">
					<tr>
						<td>
							<div class="ed-select fr">
								<!-- 终身VIP -->
								<?php if($erphp_life_price){?>
									<div class="userType10 fl eup">
										<label for="userType10">
											<input type="radio" id="userType10" name="userType" value="10" checked />
										</label>
										<span class="vipbtn">
											<span class="vip-img vip10-img">
												<?php if ( zm_get_option( 'vip10img' ) ) { ?>
													<img src="<?php echo zm_get_option( 'vip10img' ); ?>" alt="vip">
												<?php } else { ?>
													<img src="<?php echo vip_img(); ?>" alt="vip">
												<?php } ?>
											</span>
											<span class="vip-brand"><?php echo $erphp_life_name;?></span>
											<span class="vip-term bgt"><?php if($erphp_life_days) echo '永久';?></span>
											<span class="vip-price bgt">
												<?php
													if($vip_update_pay){
														if($userTypeId == 6 && $erphp_day_price){
															echo '<del>'.$erphp_life_price.'</del>';
															echo $erphp_life_price - $erphp_day_price;
														}elseif($userTypeId == 7 && $erphp_month_price){
															echo '<del>'.$erphp_life_price.'</del>';
															echo $erphp_life_price - $erphp_month_price;
														}elseif($userTypeId == 8 && $erphp_quarter_price){
															echo '<del>'.$erphp_life_price.'</del>';
															echo $erphp_life_price - $erphp_quarter_price;
														}elseif($userTypeId == 9 && $erphp_year_price){
															echo '<del>'.$erphp_life_price.'</del>';
															echo $erphp_life_price - $erphp_year_price;
														}else{
															echo $erphp_life_price;
														}
													}else{
														echo $erphp_life_price;
													}
												?>
											</span>
											<span class="vip-money bgt"><?php echo get_option('ice_name_alipay'); ?></span>
											<span class="vipbtn-submit">
												<input type="submit" name="Submit" value="立即订购" class="profile-btn bk dah" onClick="return confirm('确认订购成为<?php echo $erphp_life_name;?>？');"/>
											</span>
											<span class="vip-dns bgt">全站资源免费下载</span>
										</span>
									</div>
								<?php }?>
								<!-- 包年VIP -->
								<?php if($erphp_year_price){?>
									<div class="userType9 fl eup">
										<label for="userType9">
											<input type="radio" id="userType9" name="userType" value="9" checked/>
										</label>
										<span class="vipbtn">
											<span class="vip-img vip9-img">
												<?php if ( zm_get_option( 'vip9img' ) ) { ?>
													<img src="<?php echo zm_get_option( 'vip9img' ); ?>" alt="vip">
												<?php } else { ?>
													<img src="<?php echo vip_img(); ?>" alt="vip">
												<?php } ?>
											</span>
											<span class="vip-brand"><?php echo $erphp_year_name;?></span>
											<span class="vip-term bgt"><?php if($erphp_year_days) echo ''.$erphp_year_days.'个月'; ?></span>
											<span class="vip-price bgt">
												<?php
													if($vip_update_pay){
														if($userTypeId == 6 && $erphp_day_price){
															echo '<del>'.$erphp_year_price.'</del>';
															echo $erphp_year_price - $erphp_day_price;
														}elseif($userTypeId == 7 && $erphp_month_price){
															echo '<del>'.$erphp_year_price.'</del>';
															echo $erphp_year_price - $erphp_month_price;
														}elseif($userTypeId == 8 && $erphp_quarter_price){
															echo '<del>'.$erphp_year_price.'</del>';
															echo $erphp_year_price - $erphp_quarter_price;
														}else{
															echo $erphp_year_price;
														}
													}else{
														echo $erphp_year_price;
													}
												?>
											</span>
											<span class="vip-money bgt"><?php echo get_option('ice_name_alipay'); ?></span>
											<span class="vipbtn-submit">
												<input type="submit" name="Submit" value="立即订购" class="profile-btn bk dah" onClick="return confirm('确认订购成为<?php echo $erphp_year_name;?>？');"/>
											</span>
											<span class="vip-dns bgt">部分资源免费下载</span>
										</span>
									</div>
								<?php }?>

								<!-- 包季VIP -->
								<?php if($erphp_quarter_price){?>
									<div class="userType8 fl eup">
										<label for="userType8">
											<input type="radio" id="userType8" name="userType" value="8" checked/>
										</label>
										<span class="vipbtn">
											<span class="vip-img vip8-img">
												<?php if ( zm_get_option( 'vip8img' ) ) { ?>
													<img src="<?php echo zm_get_option( 'vip8img' ); ?>" alt="vip">
												<?php } else { ?>
													<img src="<?php echo vip_img(); ?>" alt="vip">
												<?php } ?>
											</span>
											<span class="vip-brand"><?php echo $erphp_quarter_name;?></span>
											<span class="vip-term bgt"><?php if($erphp_quarter_days) echo ''.$erphp_quarter_days.'个月'; ?></span>
											<span class="vip-price bgt">
												<?php
													if($vip_update_pay){
														if($userTypeId == 6 && $erphp_day_price){
															echo '<del>'.$erphp_quarter_price.'</del>';
															echo $erphp_quarter_price - $erphp_day_price;
														}elseif($userTypeId == 7 && $erphp_month_price){
															echo '<del>'.$erphp_quarter_price.'</del>';
															echo $erphp_quarter_price - $erphp_month_price;
														}else{
															echo $erphp_quarter_price;
														}
													}else{
														echo $erphp_quarter_price;
													}
												?>
											</span>
											<span class="vip-money bgt"><?php echo get_option('ice_name_alipay'); ?></span>
											<span class="vipbtn-submit">
												<input type="submit" name="Submit" value="立即订购" class="profile-btn bk dah" onClick="return confirm('确认订购成为<?php echo $erphp_quarter_name;?>？');"/>
											</span>
											<span class="vip-dns bgt">部分资源免费下载</span>
										</span>
									</div>
								<?php }?>

								<!-- 包月VIP -->
								<?php if($erphp_month_price){?>
									<div class="userType7 fl eup">
										<label for="userType7">
											<input type="radio" id="userType7" name="userType" value="7" checked/>
										</label>
										<span class="vipbtn">
											<span class="vip-img vip7-img">
												<?php if ( zm_get_option( 'vip7img' ) ) { ?>
													<img src="<?php echo zm_get_option( 'vip7img' ); ?>" alt="vip">
												<?php } else { ?>
													<img src="<?php echo vip_img(); ?>" alt="vip">
												<?php } ?>
											</span>
											<span class="vip-brand"><?php echo $erphp_month_name;?></span>
											<span class="vip-term bgt"><?php if($erphp_month_days) echo ''.$erphp_month_days.'天'; ?></span>
											<span class="vip-price bgt">
												<?php
													if($vip_update_pay){
														if($userTypeId == 6 && $erphp_day_price){
															echo '<del>'.$erphp_month_price.'</del>';
															echo $erphp_month_price - $erphp_day_price;
														}else{
															echo $erphp_month_price;
														}
													}else{
														echo $erphp_month_price;
													}
												?>
											</span>
											<span class="vip-money bgt"><?php echo get_option('ice_name_alipay'); ?></span>
											<span class="vipbtn-submit">
												<input type="submit" name="Submit" value="立即订购" class="profile-btn bk dah" onClick="return confirm('确认订购成为<?php echo $erphp_month_name;?>？');"/>
											</span>
											<span class="vip-dns bgt">部分资源免费下载</span>
										</span>
									</div>
								<?php }?>

								<!-- 体验VIP -->
								<?php if($erphp_day_price){?>
									<div class="userType6 fl eup">
										<label for="userType6">
											<input type="radio" id="userType6" name="userType" value="6" checked/>
										</label>
										<span class="vipbtn">
											<span class="vip-img vip6-img">
												<img src="<?php echo vip_img(); ?>" alt="vip">
											</span>
											<span class="vip-brand"><?php echo $erphp_day_name;?></span>
											<span class="vip-term bgt"><?php if($erphp_day_days) echo '&nbsp;'.$erphp_day_days.'天'; ?></span>
											<span class="vip-price bgt">
												<?php echo $erphp_day_price;?>
											</span>
											<span class="vip-money bgt"><?php echo get_option('ice_name_alipay'); ?></span>
											<span class="vipbtn-submit">
												<input type="submit" name="Submit" value="立即订购" class="profile-btn bk dah" onClick="return confirm('确认订购成为<?php echo $erphp_day_name;?>？');"/>
											</span>
											<span class="vip-dns bgt">部分资源免费下载</span>
										</span>
									</div>
								<?php }?>
								<div class="clear"></div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<p class="submit">
								<input type="submit" name="Submit" value="立即订购" class="profile-btn" onClick="return confirm('确认订购成为会员？');"/>
							</p>
						</td>
					</tr>
				</table>
			</form>

			<div class="form-clear"></div>

			<h2 class="profile-title ed-title">在线支付订购会员</h2>
			<div class="clear"></div>
			<h3>选择类型</h3>
			<form action="" method="post">
				<table class="form-table ed-vip-table <?php if ( ! $erphp_month_price ){ ?> no-month<?php }?>">
					<tr>
						<td>
							<div class="ed-select fr">
								<!-- 终身VIP -->
								<?php if($erphp_life_price){?>
									<div class="userType10 fl eup">
										<label for="uservipType10">
											<input type="radio" id="uservipType10" name="userType" value="10" checked />
										</label>
										<span class="vipbtn">
											<span class="vip-img vip10-img">
												<?php if ( zm_get_option( 'vip10img' ) ) { ?>
													<img src="<?php echo zm_get_option( 'vip10img' ); ?>" alt="vip">
												<?php } else { ?>
													<img src="<?php echo vip_img(); ?>" alt="vip">
												<?php } ?>
											</span>
											<span class="vip-brand"><?php echo $erphp_life_name;?></span>
											<span class="vip-term bgt"><?php if($erphp_life_days) echo '永久';?></span>
											<span class="vip-price bgt">
												<?php
													if($vip_update_pay){
														if($userTypeId == 6 && $erphp_day_price){
															echo '<del>'.$erphp_life_price.'</del>';
															echo $erphp_life_price - $erphp_day_price;
														}elseif($userTypeId == 7 && $erphp_month_price){
															echo '<del>'.$erphp_life_price.'</del>';
															echo $erphp_life_price - $erphp_month_price;
														}elseif($userTypeId == 8 && $erphp_quarter_price){
															echo '<del>'.$erphp_life_price.'</del>';
															echo $erphp_life_price - $erphp_quarter_price;
														}elseif($userTypeId == 9 && $erphp_year_price){
															echo '<del>'.$erphp_life_price.'</del>';
															echo $erphp_life_price - $erphp_year_price;
														}else{
															echo $erphp_life_price;
														}
													}else{
														echo $erphp_life_price;
													}
												?>
											</span>
											<span class="vip-money bgt"><?php echo get_option('ice_name_alipay'); ?></span>
											<span class="vipbtn-submit">
												<input type="submit" value="立即订购" class="profile-btn" name="erphp-pay-vip">
											</span>
											<span class="vip-dns bgt">全站资源免费下载</span>
										</span>
									</div>
								<?php }?>
								<!-- 包年VIP -->
								<?php if($erphp_year_price){?>
									<div class="userType9 fl eup">
										<label for="uservipType9">
											<input type="radio" id="uservipType9" name="userType" value="9" checked/>
										</label>
										<span class="vipbtn">
											<span class="vip-img vip9-img">
												<?php if ( zm_get_option( 'vip9img' ) ) { ?>
													<img src="<?php echo zm_get_option( 'vip9img' ); ?>" alt="vip">
												<?php } else { ?>
													<img src="<?php echo vip_img(); ?>" alt="vip">
												<?php } ?>
											</span>
											<span class="vip-brand"><?php echo $erphp_year_name;?></span>
											<span class="vip-term bgt"><?php if($erphp_year_days) echo ''.$erphp_year_days.'个月'; ?></span>
											<span class="vip-price bgt">
												<?php
													if($vip_update_pay){
														if($userTypeId == 6 && $erphp_day_price){
															echo '<del>'.$erphp_year_price.'</del>';
															echo $erphp_year_price - $erphp_day_price;
														}elseif($userTypeId == 7 && $erphp_month_price){
															echo '<del>'.$erphp_year_price.'</del>';
															echo $erphp_year_price - $erphp_month_price;
														}elseif($userTypeId == 8 && $erphp_quarter_price){
															echo '<del>'.$erphp_year_price.'</del>';
															echo $erphp_year_price - $erphp_quarter_price;
														}else{
															echo $erphp_year_price;
														}
													}else{
														echo $erphp_year_price;
													}
												?>
											</span>
											<span class="vip-money bgt"><?php echo get_option('ice_name_alipay'); ?></span>
											<span class="vipbtn-submit">
												<input type="submit" value="立即订购" class="profile-btn" name="erphp-pay-vip">
											</span>
											<span class="vip-dns bgt">部分资源免费下载</span>
										</span>
									</div>
								<?php }?>

								<!-- 包季VIP -->
								<?php if($erphp_quarter_price){?>
									<div class="userType8 fl eup">
										<label for="uservipType8">
											<input type="radio" id="uservipType8" name="userType" value="8" checked/>
										</label>
										<span class="vipbtn">
											<span class="vip-img vip8-img">
												<?php if ( zm_get_option( 'vip8img' ) ) { ?>
													<img src="<?php echo zm_get_option( 'vip8img' ); ?>" alt="vip">
												<?php } else { ?>
													<img src="<?php echo vip_img(); ?>" alt="vip">
												<?php } ?>
											</span>
											<span class="vip-brand"><?php echo $erphp_quarter_name;?></span>
											<span class="vip-term bgt"><?php if($erphp_quarter_days) echo ''.$erphp_quarter_days.'个月'; ?></span>
											<span class="vip-price bgt">
												<?php
													if($vip_update_pay){
														if($userTypeId == 6 && $erphp_day_price){
															echo '<del>'.$erphp_quarter_price.'</del>';
															echo $erphp_quarter_price - $erphp_day_price;
														}elseif($userTypeId == 7 && $erphp_month_price){
															echo '<del>'.$erphp_quarter_price.'</del>';
															echo $erphp_quarter_price - $erphp_month_price;
														}else{
															echo $erphp_quarter_price;
														}
													}else{
														echo $erphp_quarter_price;
													}
												?>
											</span>
											<span class="vip-money bgt"><?php echo get_option('ice_name_alipay'); ?></span>
											<span class="vipbtn-submit">
												<input type="submit" value="立即订购" class="profile-btn" name="erphp-pay-vip">
											</span>
											<span class="vip-dns bgt">部分资源免费下载</span>
										</span>
									</div>
								<?php }?>

								<!-- 包月VIP -->
								<?php if($erphp_month_price){?>
									<div class="userType7 fl eup">
										<label for="uservipType7">
											<input type="radio" id="uservipType7" name="userType" value="7" checked/>
										</label>
										<span class="vipbtn">
											<span class="vip-img vip7-img">
												<?php if ( zm_get_option( 'vip7img' ) ) { ?>
													<img src="<?php echo zm_get_option( 'vip7img' ); ?>" alt="vip">
												<?php } else { ?>
													<img src="<?php echo vip_img(); ?>" alt="vip">
												<?php } ?>
											</span>
											<span class="vip-brand"><?php echo $erphp_month_name;?></span>
											<span class="vip-term bgt"><?php if($erphp_month_days) echo ''.$erphp_month_days.'天'; ?></span>
											<span class="vip-price bgt">
												<?php
													if($vip_update_pay){
														if($userTypeId == 6 && $erphp_day_price){
															echo '<del>'.$erphp_month_price.'</del>';
															echo $erphp_month_price - $erphp_day_price;
														}else{
															echo $erphp_month_price;
														}
													}else{
														echo $erphp_month_price;
													}
												?>
											</span>
											<span class="vip-money bgt"><?php echo get_option('ice_name_alipay'); ?></span>
											<span class="vipbtn-submit">
												<input type="submit" value="立即订购" class="profile-btn" name="erphp-pay-vip">
											</span>
											<span class="vip-dns bgt">部分资源免费下载</span>
										</span>
									</div>
								<?php }?>

								<!-- 体验VIP -->
								<?php if($erphp_day_price){?>
									<div class="userType6 fl eup">
										<label for="uservipType6">
											<input type="radio" id="uservipType6" name="userType" value="6" checked/>
										</label>
										<span class="vipbtn">
											<span class="vip-img vip6-img">
												<img src="<?php echo vip_img(); ?>" alt="vip">
											</span>
											<span class="vip-brand"><?php echo $erphp_day_name;?></span>
											<span class="vip-term bgt"><?php if($erphp_day_days) echo '&nbsp;'.$erphp_day_days.'天'; ?></span>
											<span class="vip-price bgt">
												<?php echo $erphp_day_price;?>
											</span>
											<span class="vip-money bgt"><?php echo get_option('ice_name_alipay'); ?></span>
											<span class="vipbtn-submit">
												<input type="submit" value="立即订购" class="profile-btn" name="erphp-pay-vip">
											</span>
											<span class="vip-dns bgt">部分资源免费下载</span>
										</span>
									</div>
								<?php }?>
								<div class="clear"></div>
							</div>
						</td>
					</tr>

					<tr>
						<td>
							<h4 class="epd-paytype-title">支付方式</h4>
							<div class="clear"></div>
							<?php if(get_option('ice_payapl_api_uid')){?> 
							<input type="radio" id="paytype2" class="paytype" checked name="paytype" value="2" />PayPal(美元汇率:<?php echo get_option('ice_payapl_api_rmb')?>)&nbsp;
							 <?php }?>
							<?php if(get_option('ice_weixin_mchid')){?> 
							<input type="radio" id="paytype4" class="paytype" checked name="paytype" value="4" /><label for="paytype4" class="ed-type"><i class="cx cx-weixin"></i>微信</label>
							<?php }?>
							<?php if(get_option('ice_ali_partner') || get_option('ice_ali_app_id')){?> 
							<input type="radio" id="paytype1" class="paytype" checked name="paytype" value="1" /><label for="paytype1" class="ed-type"><i class="cx cx-alipay"></i>支付宝</label>
							<?php }?>
							<?php if(get_option('erphpdown_f2fpay_id')){?> 
							<input type="radio" id="paytype5" class="paytype" checked name="paytype" value="5" /><label for="paytype5" class="ed-type"><i class="cx cx-alipay"></i>支付宝</label>
							<?php }?>
							<?php if(get_option('erphpdown_payjs_appid')){?> 
								<?php if(!get_option('erphpdown_payjs_alipay')){?><input type="radio" id="paytype20" class="paytype" name="paytype" value="20" checked /><label for="paytype20" class="ed-type"><i class="cx cx-alipay"></i>支付宝</label><?php }?>
								<?php if(!get_option('erphpdown_payjs_wxpay')){?><input type="radio" id="paytype19" class="paytype" name="paytype" value="19" checked /><label for="paytype19" class="ed-type"><i class="cx cx-weixin"></i>微信</label><?php }?>
							<?php }?>
							<?php if(get_option('erphpdown_xhpay_appid31')){?> 
								<input type="radio" id="paytype18" class="paytype" name="paytype" value="18" checked /><label for="paytype18" class="ed-type"><i class="cx cx-weixin"></i>微信</label>
							<?php }?>
							<?php if(get_option('erphpdown_xhpay_appid32')){?> 
								<input type="radio" id="paytype17" class="paytype" name="paytype" value="17" checked /><label for="paytype17" class="ed-type"><i class="cx cx-alipay"></i>支付宝</label>
							<?php }?>
							<?php if(get_option('erphpdown_codepay_appid')){?> 
								<?php if(!get_option('erphpdown_codepay_alipay')){?><input type="radio" id="paytype13" class="paytype" name="paytype" value="13" checked /><label for="paytype13" class="ed-type"><i class="cx cx-alipay"></i>支付宝</label><?php }?>
								<?php if(!get_option('erphpdown_codepay_wxpay')){?><input type="radio" id="paytype14" class="paytype" name="paytype" value="14" /><label for="paytype14" class="ed-type"><i class="cx cx-weixin"></i>微信</label><?php }?>
								<?php if(!get_option('erphpdown_codepay_qqpay')){?><input type="radio" id="paytype15" class="paytype" name="paytype" value="15" /><label for="paytype15" class="ed-type"><i class="be be-qq"></i>QQ钱包</label><?php }?>
							<?php }?>
							<?php if(get_option('erphpdown_paypy_key')){?> 
								<?php if(!get_option('erphpdown_paypy_alipay')){?><input type="radio" id="paytype8" class="paytype" name="paytype" value="8" checked/><label for="paytype8" class="ed-type"><i class="cx cx-alipay"></i>支付宝</label><?php }?>
								<?php if(!get_option('erphpdown_paypy_wxpay')){?><input type="radio" id="paytype7" class="paytype" name="paytype" value="7" checked /><label for="paytype7" class="ed-type"><i class="cx cx-weixin"></i>微信</label><?php }?>
							<?php }?> 
							<?php if(get_option('erphpdown_epay_id')){?>
								<?php if(!get_option('erphpdown_epay_alipay')){?><input type="radio" id="paytype21" class="paytype" name="paytype" value="21" checked /><label for="paytype21" class="ed-type"><i class="cx cx-alipay"></i>支付宝</label><?php }?>
								<?php if(!get_option('erphpdown_epay_qqpay')){?><input type="radio" id="paytype23" class="paytype" name="paytype" value="23" checked/><label for="paytype23" class="ed-type"><i class="be be-qq"></i>QQ钱包</label><?php }?>
								<?php if(!get_option('erphpdown_epay_wxpay')){?><input type="radio" id="paytype22" class="paytype" name="paytype" value="22" checked /><label for="paytype22" class="ed-type"><i class="cx cx-weixin"></i>微信</label><?php }?>
							<?php }?>
							<?php if(get_option('erphpdown_vpay_key')){?>
								<?php if(!get_option('erphpdown_vpay_alipay')){?><input type="radio" id="paytype31" class="paytype" name="paytype" value="31" checked /><label for="paytype31" class="ed-type"><i class="cx cx-alipay"></i>支付宝</label><?php }?>
								<?php if(!get_option('erphpdown_vpay_wxpay')){?><input type="radio" id="paytype32" class="paytype" name="paytype" value="32" checked /><label for="paytype32" class="ed-type"><i class="cx cx-weixin"></i>微信</label><?php }?>
							<?php }?>
						</td>
					</tr>
					<tr>
						<td>
							<p class="submit">
								<input type="submit" value="立即订购" class="profile-btn" name="erphp-pay-vip">
							</p>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<!-- 购买VIP结束 -->

				<div class="be-epd-user">
					<h2 class="profile-title ed-title">订购会员记录</h2>
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th>会员类型</th>
								<th>价格</th>
								<th>时间</th>
							</tr>
						</thead>
						<tbody>
							<?php
								if($lists) {
									foreach($lists as $value)
									{
										if($value->ice_user_type == 6) $typeName = $erphp_day_name;
										else {$typeName=$value->ice_user_type==7 ?$erphp_month_name :($value->ice_user_type==8 ?$erphp_quarter_name : ($value->ice_user_type==10 ?$erphp_life_name : $erphp_year_name));}
										echo "<tr>\n";
										echo "<td>$typeName</td>\n";
										echo "<td>$value->ice_price</td>\n";
										echo "<td>$value->ice_time</td>\n";
										echo "</tr>";
									}
								}
								else
								{
									echo '<tr width=100%><td colspan="3" align="center"><center><strong>没有记录！</strong></center></td></tr>';
								}
							?>
						</tbody>
					</table>
					<?php mobantu_paging('vip',$page,$pages);?>
				</div>

			<?php }elseif($_GET["pd"]=='tuan'){
				$totallists = $wpdb->get_var("SELECT count(ice_id) FROM $wpdb->tuanorder WHERE ice_user_id=".$user_info->ID." and ice_status>0");
				$perpage = 10;
				$pages = ceil($totallists / $perpage);
				$page=isset($_GET['pp']) ?intval($_GET['pp']) :1;
				$offset = $perpage*($page-1);
				$lists = $wpdb->get_results("SELECT * FROM $wpdb->tuanorder where ice_user_id=".$user_info->ID." and ice_status>0 order by ice_time DESC limit $offset,$perpage");
			?>
			<div class="be-epd-user">
				<table class="table table-striped table-hover user-orders">
		          	  <thead>
		              	  <tr>
		          			<th style="text-align: left;">商品名称</th>
		          			<th class="pc">订单号</th>
		                    <th>价格</th>
		                    <th>时间</th>
		                    <th>进度</th>
		                    <th>状态</th>
		                  </tr>
		              </thead>
		              <tbody>
		              <?php foreach($lists as $value){?>
		            	  <tr>
		            	  	<td style="text-align: left;"><a target="_blank" href="<?php echo get_permalink($value->ice_post);?>"><?php echo get_post($value->ice_post)->post_title;?></a></td>
		                  	<td class="pc"><?php echo $value->ice_num;?></td>
		                  	<td><?php echo $value->ice_price;?></td>
		                  	<td><?php echo $value->ice_time;?></td>
		                  	<td><?php echo get_erphpdown_tuan_percent($value->ice_post,$value->ice_tuan_num);?>%</td>
		                  	<td><?php echo $value->ice_status == 1?'进行中':'已完成';?></td>
		                  </tr>
				      <?php }?>
		              </tbody>
		          </table>
	        </div>
	          <?php mobantu_paging('tuan',$page,$pages);?>
			<?php }elseif($_GET["pd"]=='ad'){
				global $erphpad_table;
		  	    $totallists = $wpdb->get_var("SELECT count(id) FROM $erphpad_table WHERE user_id=".$user_info->ID." and order_status=1");
				$perpage = 10;
				$pages = ceil($totallists / $perpage);
				$page=isset($_GET['pp']) ?intval($_GET['pp']) :1;
				$offset = $perpage*($page-1);
				$lists = $wpdb->get_results("SELECT * FROM $erphpad_table where user_id=".$user_info->ID." and order_status=1 order by order_time DESC limit $offset,$perpage");
			?>
			<div class="be-epd-user">
				<table class="table table-striped table-hover user-orders">
		          	  <thead>
		              	  <tr>
		          			<th width="15%">广告位</th>
		          			<th width="10%" class="pc">金额</th>
		                    <th width="20%">生效时间</th>
		                    <th width="10%">周期</th>
		                    <th width="10%">状态</th>
		                    <th width="15%">说明</th>
		                    <th width="20%">操作</th>
		                  </tr>
		              </thead>
		              <tbody>
		              <?php if($lists){foreach($lists as $value){?>
		            	  <tr>
		                  	<td><?php echo erphpad_get_pos_name($value->pos_id);?></td>
		                  	<td class="pc"><?php echo $value->order_price;?></td>
		                  	<td><?php echo $value->order_time;?></td>
		                  	<td><?php echo $value->order_cycle;?>天</td>
		                  	<td><?php echo $value->order_status == 1?'正常':'过期';?></td>
		                  	<td><?php echo erphpad_get_pos($value->pos_id)->pos_tips;?></td>
		                  	<td><a href="javascript:;" data-id="<?php echo $value->id;?>" class="erphpad-edit-loader">修改广告</a></td>
		                  </tr>
				      <?php }}?>
		              </tbody>
		          </table>
	        </div>
	          <?php mobantu_paging('ad',$page,$pages);?>
	          	<?php if(function_exists('erphpad_install')){?>
		        <form id="uploadad" action="<?php echo ERPHPAD_URL.'/action/ad.php';?>" method="post" enctype="multipart/form-data" style="display:none;">
		            <input type="file" id="adimage" name="adimage" accept="image/png, image/jpeg, image/gif">
		        </form>
		    	<?php }?>


			<?php }elseif(isset($_GET["pd"]) && $_GET["pd"]=='ref'){
				$totallists = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users WHERE father_id=".$user_info->ID);
				$ice_perpage = 10;
				$pages = ceil($totallists / $ice_perpage);
				$page=isset($_GET['pp']) ?intval($_GET['pp']) :1;
				$offset = $ice_perpage*($page-1);
				$lists = $wpdb->get_results("SELECT ID,user_login,user_registered FROM $wpdb->users where father_id=".$user_info->ID." limit $offset,$ice_perpage");
				?>
				<h2 class="profile-title ed-title-b">推广注册</h2>
				<div class="alert">推广链接&nbsp;&nbsp;&nbsp;&nbsp;<?php echo esc_url( home_url( '/?aff=' ) ).$user_info->ID; ?></div>
				<div class="be-epd-user">
						<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th>用户ID</th>
								<th>注册时间</th>
								<th>消费额</th>
							</tr>
						</thead>
						<tbody>
							<?php
								if($lists) {
									foreach($lists as $value)
									{
										echo "<tr>\n";
										echo "<td>$value->user_login</td>\n";
										echo "<td>$value->user_registered</td>";
										echo "<td>".erphpGetUserAllXiaofei($value->ID)."</td>";
										echo "</tr>";
									}
								}
								else
								{
									echo '<tr width=100%><td colspan="3" align="center"><center><strong>没有记录！</strong></center></td></tr>';
								}
							?>
						</tbody>
					</table>
					<?php mobantu_paging('ref',$page,$pages);?>

			<?php
				$totallists = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->vip where ice_user_id in (select ID from $wpdb->users where father_id=".$user_info->ID.")");
				$ice_perpage = 10;
				$pages = ceil($totallists / $ice_perpage);
				$page=isset($_GET['pp']) ?intval($_GET['pp']) :1;
				$offset = $ice_perpage*($page-1);
				$lists = $wpdb->get_results("SELECT * FROM $wpdb->vip where ice_user_id in (select ID from $wpdb->users where father_id=".$user_info->ID.") order by ice_time DESC limit $offset,$ice_perpage");
			?>
				<div class="be-epd-user">
					<h2 class="profile-title ed-title">推广会员</h2>
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th>用户ID</th>
								<th>VIP类型</th>
								<th>价格</th>
								<th>交易时间</th>
							</tr>
						</thead>
						<tbody>
							<?php
								if($lists) {
									foreach($lists as $value)
									{
										if($value->ice_user_type == 6) $typeName = $erphp_day_name;
										else {$typeName=$value->ice_user_type==7 ?$erphp_month_name :($value->ice_user_type==8 ?$erphp_quarter_name : ($value->ice_user_type==10 ?$erphp_life_name : $erphp_year_name));}
										echo "<tr>\n";
										echo "<td>".get_the_author_meta( 'user_login', $value->ice_user_id )."</td>\n";
										echo "<td>$typeName</td>\n";
										echo "<td>$value->ice_price</td>\n";
										echo "<td>$value->ice_time</td>\n";
										echo "</tr>";
									}
								}
								else
								{
									echo '<tr width=100%><td colspan="4" align="center"><center><strong>没有记录！</strong></center></td></tr>';
								}
							?>
						</tbody>
					</table>
					<?php mobantu_paging('ref',$page,$pages);?>
				</div>
			<?php }elseif(isset($_GET["pd"]) && $_GET["pd"]=='outmo'){
				$totallists = $wpdb->get_var("SELECT count(*) FROM $wpdb->iceget WHERE ice_user_id=".$user_info->ID);
				$ice_perpage = 10;
				$pages = ceil($totallists / $ice_perpage);
				$page=isset($_GET['pp']) ?intval($_GET['pp']) :1;
				$offset = $ice_perpage*($page-1);
				$lists = $wpdb->get_results("SELECT * FROM $wpdb->iceget where ice_user_id=".$user_info->ID." order by ice_time DESC limit $offset,$ice_perpage");
				?>
				<h2 class="profile-title ed-title-b">提现记录</h2>
				<div class="alert"><a href="?pd=tixian"><i class="ep ep-tixian"></i>&nbsp;&nbsp;申请提现&nbsp;&nbsp;<i class="be be-fastforward"></i></a></div>
				<div class="clear"></div>
				<div class="be-epd-user">
		            	<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th>申请金额</th>
								<th>申请时间</th>
								<th>到账金额</th>
								<th>状态</th>
							</tr>
						</thead>
						<tbody>
							<?php
								if($lists) {
									foreach($lists as $value)
									{
										$result=$value->ice_success==1?'已支付':'--';
										echo "<tr>\n";
										echo "<td>$value->ice_money</td>\n";
										echo "<td>$value->ice_time</td>\n";
										echo "<td>".sprintf("%.2f",(((100-get_option("ice_ali_money_site"))*$value->ice_money)/100))."</td>\n";
										echo "<td>$result</td>\n";
										echo "</tr>";
									}
								}
								else
								{
									echo '<tr><td colspan="4" align="center"><center><strong>没有记录！</strong></center></td></tr>';
								}
							?>
						</tbody>
					</table>
					<?php mobantu_paging('outmo',$page,$pages);?>
				</div>
			<?php }elseif(isset($_GET["pd"]) && $_GET["pd"]=='tixian'){
			?>
				<div class="profile-form be-epd-user">
					<?php ed_cash_application(); ?>
				</div>
			
			 <?php } elseif ( isset($_GET["pd"] ) && $_GET["pd"]=='post') { ?>
			
			<div id="my-post">
				<h4><?php _e( '我的文章', 'begin' ); ?><span class="m-number"><?php echo count_user_posts( $current_user->ID, array( 'post', 'bulletin', 'picture', 'video', 'tao' ), false ); ?><span></h4>
				<div class="my-user"><?php my_post(); ?></div>
			</div>

			 <?php } elseif ( isset($_GET["pd"] ) && $_GET["pd"]=='comment') { ?>
			
			<div id="my-comment">
				<h4><?php _e( '我的评论', 'begin' ); ?><span class="m-number"><?php echo $comments = get_comments( array( 'user_id' => $current_user->ID, 'count' => true ) ); ?><span></h4>
				<div class="my-user"><?php my_comment(); ?></div>
			</div>

			 <?php } elseif ( isset($_GET["pd"] ) && $_GET["pd"]=='favorite') { ?>
			
			<div id="my-favorite">
				<h4><?php _e( '我的收藏', 'begin' ); ?></h4>
					<div class="my-user"><?php my_favorite(); ?></div>
				<div class="clear"></div>
			</div>

			<?php }elseif(isset($_GET["pd"]) && $_GET["pd"]=='cart'){
			$total_trade   = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->icealipay WHERE ice_success>0 and ice_user_id=".$user_info->ID);
			$ice_perpage = 10;
			$pages = ceil($total_trade / $ice_perpage);
			$page=isset($_GET['pp']) ?intval($_GET['pp']) :1;
			$offset = $ice_perpage*($page-1);
			$list = $wpdb->get_results("SELECT * FROM $wpdb->icealipay where ice_success=1 and ice_user_id=$user_info->ID order by ice_time DESC limit $offset,$ice_perpage");
			?>

			<div id="downlist" class="be-epd-user">
				<h2 class="profile-title ed-title-b">下载清单</h2>
				<table class="table table-hover table-striped ed-down-list">
					<thead>
						<tr>
							<th>订单号</th>
							<th >商品名称</th>
							<th>价格</th>
							<th>交易时间</th>
							<th>下载</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($list) {
								foreach($list as $value)
								{
									echo "<tr>\n";
									echo "<td>$value->ice_num</td>";
									echo "<td><a target=_blank href=".get_permalink($value->ice_post).">$value->ice_title</a></td>\n";
									echo "<td>$value->ice_price</td>\n";
									echo "<td>$value->ice_time</td>\n";
									echo "<td><a href='".get_bloginfo('wpurl').'/wp-content/plugins/erphpdown/download.php?url='.$value->ice_url."' target='_blank'><i class='dashicons dashicons-cloud-saved'></i>下载文件</a></td>\n";
									echo "</tr>";
								}
							}
							else
							{
								echo '<tr width=100%><td colspan="5" align="center"><center><strong>没有订单</strong></center></td></tr>';
							}
						?>
					</tbody>
				</table>
                <?php mobantu_paging('cart',$page,$pages);?>
			</div>
			<?php } ?>
		</div>
	</div>
</section>

<script type="text/javascript">
	function checkFm(){
		if(document.getElementById("ice_money").value=="")
		{
			alert('请输入金额');
			return false;
		}
	}

	function checkFm2(){
		if(document.getElementById("epdcardnum").value=="")
		{
			alert('请输入金额');
			return false;
		}
	}

	function checkFm3(){
		if(document.getElementById("epdmycrednum").value=="")
		{
			alert('请输入兑换的金额');
			return false;
		}
	}

	function strlen(str){
		var len = 0;
		for (var i=0; i<str.length; i++){
			var c = str.charCodeAt(i);
			if ((c >= 0x0001 && c <= 0x007e) || (0xff60<=c && c<=0xff9f)) {
				len++;
			}else {
				len+=2;
			}
		} 
		return len;
	}

	if ( window.history.replaceState ) {
		window.history.replaceState( null, null, window.location.href );
	}

	jQuery(document).ready(function($){
		$(".ed-select label").on("mouseover touchend", function(){
			$(this).siblings("span").addClass("active");
			$(this).parent().siblings("div").find("span").removeClass("active");
		});

		$(".userType6").mouseover(function() {
			$("#uservipType6").prop("checked",true);
		});

		$(".userType7").mouseover(function() {
			$("#uservipType7").prop("checked",true);
		});

		$(".userType8").mouseover(function() {
			$("#uservipType8").prop("checked",true);
		});

		$(".userType9").mouseover(function() {
			$("#uservipType9").prop("checked",true);
		});

		$(".userType10").mouseover(function() {
			$("#uservipType10").prop("checked",true);
		});

		$(".userType6").mouseover(function() {
			$("#userType6").prop("checked",true);
		});

		$(".userType7").mouseover(function() {
			$("#userType7").prop("checked",true);
		});

		$(".userType8").mouseover(function() {
			$("#userType8").prop("checked",true);
		});

		$(".userType9").mouseover(function() {
			$("#userType9").prop("checked",true);
		});

		$(".userType10").mouseover(function() {
			$("#userType10").prop("checked",true);
		});

		$('.menu-hide').click(function() {
			$('.pagewrapper').removeClass("n-mode");
			$.cookie('BE_menu', 'off');
		});

		$('.menu-display').click(function() {
			$('.pagewrapper').addClass("n-mode");
			$('.pagewrapper').addClass("m-mode");
			$.cookie('BE_menu', 'on');
		});

		var cookie_menu_on = $.cookie('BE_menu');
		if( cookie_menu_on == 'off' ) { 
			$('.pagewrapper').removeClass("n-mode");
		} else {
			$('.pagewrapper').addClass("n-mode");
		}

		function response() {
			if ( $(window).width() < 520 ) {
				$('.pagewrapper').removeClass("n-mode");
			} else {
				$('.pagewrapper').addClass("n-mode");
			}
		}

		$(window).resize(function() {
			if ( $(window).width() < 520 ) {
				response();
			}
		});
	});
</script>
<?php get_footer(); ?>
<?php } else { ?>
<p style="color: #bb9998;font-size: 1.6rem;text-align: center;margin: 5% 0 0 0;"> 请安装 ErphpDown插件 </p>
<?php } ?>