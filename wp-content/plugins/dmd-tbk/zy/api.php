<?php 
require '../../../../wp-load.php';
$dmd_tbk_appKey= get_option('dmd_tbk_appKey');
$dmd_tbk_appSecret= get_option('dmd_tbk_appSecret');
$dmd_jd_unionId= get_option('dmd_jd_unionId');
$appKey=$dmd_tbk_appKey;
$appSecret=$dmd_tbk_appSecret;

$url=(isset($_POST['url'])) ? trim(strip_tags($_POST['url'])) : null;

if(!$url){
	print_r(json_encode(array('error' => 1, 'msg' => '请输入链接地址')));
	exit;
}
$jdint=strpos($url,'jd.com');
if($jdint){
    //京东
   $prama = pathinfo($url);
   $goodsid=$prama['filename'];
   if(!$goodsid){
       	print_r(json_encode(array('error' => 1, 'msg' => '没有找到商品id')));
    	exit;
   }
  
    getjdcp_dtk($appKey,$appSecret,$goodsid,$dmd_jd_unionId);
}
else {
    $pddint=strpos($url,'jinbao.pinduoduo.com');
    if($pddint){
        $cur_q=parse_url($url,PHP_URL_QUERY);
        parse_str($cur_q,$myArray);
        $goodsid=$myArray["s"];
        if(!$goodsid){
        	print_r(json_encode(array('error' => 1, 'msg' => '没有找到商品id')));
        	exit;
        }
        	print_r(json_encode(array('error' => 1, 'msg' => '不再支持拼多多')));
        	exit;
        //getpddcp($appKey,$appSecret,$goodsid,$dmd_pdd_positionId);
        
        
    }else{
            //淘宝
        $cur_q=parse_url($url,PHP_URL_QUERY);
        parse_str($cur_q,$myArray);
        $goodsid=$myArray["id"];
        if(!$goodsid){
        	print_r(json_encode(array('error' => 1, 'msg' => '没有找到商品id')));
        	exit;
        }
        gettbcp($appKey,$appSecret,$goodsid);
    }
}



function gettbcp($appKey,$appSecret,$goodsid){
    //输出产品信息
    $tg_title;
    $tg_des;
    $tg_img;
    $tg_originalPrice;
    $tg_actualPrice;
    $tg_commissionRate;
    $tg_discounts;
    $tg_couponLink;
    $tg_couponClickUrl;
    $tg_itemUrl;
    $tg_shortUrl;
    $tg_tpwd;
    //----------------转换链接
    $urltran = 'https://openapi.dataoke.com/api/tb-service/get-privilege-link';
    
    //加密的参数
    $data = makeSign($appKey,$appSecret);
    $data['appKey']=$appKey;
    $data['version']='v1.3.1';
    $data['goodsId']=$goodsid;
    //拼接请求地址
    $url = $urltran .'?'. http_build_query($data);
    $output=sendrquest($url);
    $data=json_decode($output);
    
    if($data->code==-1){
    	print_r(json_encode(array('error' => 1, 'msg' => $data->msg)));
    	exit;
    }
    if($data->code==1){
    	print_r(json_encode(array('error' => 1, 'msg' => $data->msg)));
    	exit;
    }
    if($data->code==0){
    	$tg_couponClickUrl=$data->data->couponClickUrl;
    	$tg_itemUrl=$data->data->itemUrl;
    	if(get_option('dmd_tbk_link')){
    	  $tg_shortUrl=$data->data->couponClickUrl;  
    	}else{
    	  $tg_shortUrl=$data->data->shortUrl;
    	}
    	
    	$tg_tpwd=$data->data->tpwd;
    }
    $data=null;
    //---------------链接转换完毕
    //--------------获取商品详情
    $spinfo = 'https://openapi.dataoke.com/api/goods/get-goods-details';
    
    $data = makeSign($appKey,$appSecret);
    $data['appKey']=$appKey;
    $data['version']=' v1.2.3';
    $data['goodsId']=$goodsid;
    //拼接请求地址
    $url = $spinfo .'?'. http_build_query($data);
    $output=sendrquest($url);
    
    $data=json_decode($output);
    if($data->code==-1){
    	print_r(json_encode(array('error' => 1, 'msg' => $data->msg)));
    	exit;
    }
    if($data->code==1){
    	print_r(json_encode(array('error' => 1, 'msg' => $data->msg)));
    	exit;
    }
    if($data->code==0){
    	$tg_title=$data->data->title;
    	$tg_des=$data->data->desc;
    	$tg_img=$data->data->mainPic;
    	$tg_originalPrice=$data->data->originalPrice;
    	$tg_actualPrice=$data->data->actualPrice;
    	$tg_discounts=$data->data->discounts;
    	$tg_commissionRate=$data->data->commissionRate;
    	$tg_couponLink=$data->data->couponLink;
    }
    print_r(json_encode(array('error' => 0,'lx'=>'tb', 'msg' =>array(
    	'tg_goodsid'=>$goodsid,
    	'tg_couponClickUrl'=>$tg_couponClickUrl,
    	'tg_itemUrl'=>$tg_itemUrl,
    	'tg_shortUrl'=>$tg_shortUrl,
    	'tg_tpwd'=>$tg_tpwd,
    	'tg_title'=>$tg_title,
    	'tg_des'=>$tg_des,
    	'tg_img'=>$tg_img,
    	'tg_originalPrice'=>$tg_originalPrice,
    	'tg_actualPrice'=>$tg_actualPrice,
    	'tg_discounts'=>$tg_discounts,
    	'tg_commissionRate'=>$tg_commissionRate,
    	'tg_couponLink'=>$tg_couponLink
    ))));
	exit;
}
//京东商品查询
function getjdcp_dtk($appKey,$appSecret,$goodsid,$dmd_jd_unionId){
    $urltran='https://openapi.dataoke.com/api/dels/jd/goods/search';
   
    //加密的参数
    $data = makeSign($appKey,$appSecret);
    $data['appKey']=$appKey;
    $data['version']='v1.3.1';
    $data['skuIds']=$goodsid;
    //拼接请求地址
    $url = $urltran .'?'. http_build_query($data);
    $output=sendrquest($url);
    $data=json_decode($output);
    
    if($data->code==-1){
    	print_r(json_encode(array('error' => 1, 'msg' => $data->msg)));
    	exit;
    }
    if($data->code==1){
    	print_r(json_encode(array('error' => 1, 'msg' => $data->msg)));
    	exit;
    }
    if($data->code==0){
        $data=$data->data->list[0];
        $goods_id=$data->skuId;
        $goods_name=$data->skuName;
        $materiaurl=$data->materialUrl;
        $goods_desc=$data->document;
        $price=$data->price;
        $price_after=$data->lowestPrice;
        $picurl=$data->whiteImage;
        $couponurl=$data->couponurl;
        
        $mtgurl= jdtg_dtk($appKey,$appSecret,$materiaurl,$dmd_jd_unionId);
        
        print_r(json_encode(array('error' => 0,'lx'=>'jd', 'msg' =>array(
    	'tg_goodsid'=>$goods_id,
    	'tg_couponClickUrl'=>$tg_couponClickUrl,
    	'tg_itemUrl'=>$tg_itemUrl,
    	'tg_shortUrl'=>$mtgurl,
    	'tg_tpwd'=>$tg_tpwd,
    	'tg_title'=>$goods_name,
    	'tg_des'=>$goods_desc,
    	'tg_img'=>$picurl,
    	'tg_originalPrice'=>$price,
    	'tg_actualPrice'=>$price_after,
    	'tg_discounts'=>$tg_discounts,
    	'tg_commissionRate'=>$tg_commissionRate,
    	'tg_couponLink'=>$tg_couponLink
    ))));
	exit;
}

}


//大淘客京东转链
function jdtg_dtk($appKey,$appSecret,$content,$unionId){
    $urltran='https://openapi.dataoke.com/api/dels/jd/kit/promotion-union-convert';
   
    //加密的参数
    $data = makeSign($appKey,$appSecret);
    $data['appKey']=$appKey;
    $data['version']='v1.0.0';
    $data['unionId']=$unionId;
    $data['materialId']=$content;
    //拼接请求地址
    $url = $urltran .'?'. http_build_query($data);
    $output=sendrquest($url);
    $data=json_decode($output);
    $jdtgurl='';
    if($data->code==-1){
    	print_r(json_encode(array('error' => 11, 'msg' => $data->msg)));
    	exit;
    }
    if($data->code==1){
    	print_r(json_encode(array('error' => 11, 'msg' => $data->msg)));
    	exit;
    }
    if($data->code==0){
        $jdtgurl=$data->data->shortUrl;
    }
    return $jdtgurl;
}

	
function makeSign($appKey, $appSecret)
{
    $num = str_pad(mt_rand(0, 999999), 6, "0", STR_PAD_BOTH);
    $params=[
    'appKey'=>$appKey,
    'timer'=>microtime(),
    'nonce'=>$num,
    'key'=>$appSecret
    ];
    ksort($params);
    $str=urldecode( http_build_query($params));
    $params['signRan']=strtoupper(md5($str));
    return $params;
}

function sendrquest($url){
	//执行请求获取数据
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_TIMEOUT,10);
curl_setopt($ch, CURLOPT_HEADER, 0);
$output = curl_exec($ch);
curl_close($ch);
return $output;
}

?>