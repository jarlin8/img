<?php
require( dirname(__FILE__).'/../../../../wp-load.php' );
if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
	header('Allow: POST');
	header('HTTP/1.1 405 Method Not Allowed');
	header('Content-Type: text/plain');
	exit;
}
$appKey=get_option('dmd_tbk_appKey');
$appSecret=get_option('dmd_tbk_appSecret');
$lx = (isset($_POST['lx'])) ? trim(strip_tags($_POST['lx'])) : null;
$action = (isset($_POST['action'])) ? trim(strip_tags($_POST['action'])) : null;
$cxnum = (isset($_POST['num'])) ? trim($_POST['num']) : 20;
if (!$lx || !$action) {
    print_r(json_encode(array('code'=>1,'msg'=>'参数不完整')));
    exit();
}

switch($lx){
    case 'jd':
       getjd_dtk($action,$appKey,$appSecret,$cxnum);
           break;
    case 'tb':
       getdtk($appKey,$appSecret,$action,$apikey,$cxnum);
           break;
    default:
               
           break;
}

//大淘客接口
function getdtk($appKey,$appSecret,$action,$apikey,$cxnum){
	
     switch ($action) {
         case 'rtb':
            $data = makeSign($appKey,$appSecret);
            $data['appKey']=$appKey;
            $data['version']='v1.3.0';
            $data['rankType']=3;
					              
            case 'ssb':
            $data = makeSign($appKey,$appSecret);
            $data['appKey']=$appKey;
            $data['version']='v1.3.0';
            $data['rankType']=1;
                
             break;
             case 'qtb':
             $data = makeSign($appKey,$appSecret);
            $data['appKey']=$appKey;
            $data['version']='v1.3.0';
            $data['rankType']=2;
                
            break;
             case 'zhb':
            $data = makeSign($appKey,$appSecret);
            $data['appKey']=$appKey;
            $data['version']='v1.3.0';
            $data['rankType']=7;

            break;
            case 'search':
                $keyWords = (isset($_POST['keyword'])) ? trim($_POST['keyword']) : null;
				$data = wp_cache_get("dtb_search_".urlencode($keyWords),'daimadogcpc');
				if($data){
					print_r($data);
					exit();
				}				
            dtksearch($appKey,$appSecret,$cxnum,$keyWords);
            break;
    }
	$cahe_data = wp_cache_get("dtb_bd_".$data['rankType'],'daimadogcpc');
	if($cahe_data){
		print_r($cahe_data);
		exit();
	}
	getbddtk($data,$appKey,$appSecret,$cxnum);		
}
//大淘客关键词搜索
function dtksearch($appKey,$appSecret,$cxnum,$keyWords){
    $url="https://openapi.dataoke.com/api/goods/get-dtk-search-goods";
    $data = makeSign($appKey,$appSecret);
    $data['appKey']=$appKey;
    $data['version']='v2.1.2';
    $data['pageSize']=$cxnum;
    $data['keyWords']=$keyWords;
    $data['pageId']=1;
    //拼接请求地址
    $url = $url .'?'. http_build_query($data);
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
      //  var_dump($data);
     $mdata=$data->data->list;
       $n=10;
        if($cxnum){
          $n=$cxnum;  
        }
        if(count($mdata)<$n){
            $n=count($mdata);
        }
        $tgifo=array();
        $html='';
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
        for($i=0;$i<$n;$i++){
          
            $goodsId=$mdata[$i]->goodsId;
            $tg_title=$mdata[$i]->dtitle;
            $tg_des=$mdata[$i]->desc;
            $tg_img=$mdata[$i]->mainPic;
            $tg_originalPrice=$mdata[$i]->originalPrice;
            $tg_actualPrice=$mdata[$i]->actualPrice;
            $tranreturn=tran_dtk($appKey,$appSecret,$goodsId);
            $tg_tpwd=$tranreturn['tg_tpwd'];
            
             $cp=array(
              'goodsId'=>$goodsId,
              'desc'=>$tg_des,
              'tg_tpwd'=>$tg_tpwd,
               'price'=>$tg_originalPrice,
               'actualPrice'=>$tg_actualPrice,
               'name'=>$tg_title,
               'mainPic'=>$tg_img,
               'tgurl'=>$tranreturn['tg_shortUrl']
               );
            array_push($tgifo,$cp);
            
            $html=$html.'<div class="cps-item">
<div class="item-embed">
<div class="tg-img"><img src="'.$tg_img.'" alt="'.$tg_title.'" /></div>
<div class="item-title">'.$tg_title.'</div>
<div class="tg-note">'.$tg_des.'</div>
<div class="bomoto">
<div class="tg-info">
<div class="tg-price"><em>￥</em>'.$tg_actualPrice.'</div>
<div class="old-price">￥'.$tg_originalPrice.'</div>
<div class="tg-tkl">

淘口令：
<div id="tb-'.$goodsId.'-'.$i.'" class="tg-ma">'.$tg_tpwd.'</div>
</div>
</div>
<div class="tg-btn">
<div class="bomm"><a class="cpsbtn cps_copy_btn" style="color: #fff;" data-clipboard-target="#tb-'.$goodsId.'-'.$i.'">复制淘口令</a></div>
<div class="bomm"><a class="cpsbtn lqm" style="color: #fff;" href="'.$tranreturn['tg_shortUrl'].'" target="_blank" rel="nofollow noopener noreferrer">领券购买</a></div>
</div>
</div>
</div>
</div>';
           
        }
    }
         if($tgifo){
			 $data_cache=json_encode(array('code'=>0,'msg'=>'成功','data'=>$html,'times'=>time()));
			 wp_cache_set("dtb_search_".urlencode($keyWords),$data_cache,'daimadogcpc',600);
            print_r($data_cache);
			exit(); 
    }
    print_r(json_encode(array('code'=>1,'msg'=>'大淘客获取失败','data'=>$data)));
    exit();
}
//大淘客榜单请求返回
function getbddtk($odata,$appKey,$appSecret,$cxnum){
    $qurl='https://openapi.dataoke.com/api/goods/get-ranking-list';
    //拼接请求地址
    $url = $qurl .'?'. http_build_query($odata);
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
      $mdata=$data->data;
       $n=10;
        if($cxnum){
          $n=$cxnum;  
        }
        if(count($mdata)<$n){
            $n=count($mdata);
        }
        $tgifo=array();
        $html='';
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
        for($i=0;$i<$n;$i++){
          
            $goodsId=$mdata[$i]->goodsId;
            $tg_title=$mdata[$i]->dtitle;
            $tg_des=$mdata[$i]->desc;
            $tg_img=$mdata[$i]->mainPic;
            $tg_originalPrice=$mdata[$i]->originalPrice;
            $tg_actualPrice=$mdata[$i]->actualPrice;
            $tranreturn=tran_dtk($appKey,$appSecret,$goodsId);
            $tg_tpwd=$tranreturn['tg_tpwd'];
            
             $cp=array(
              'goodsId'=>$goodsId,
              'desc'=>$tg_des,
              'tg_tpwd'=>$tg_tpwd,
               'price'=>$tg_originalPrice,
               'actualPrice'=>$tg_actualPrice,
               'name'=>$tg_title,
               'mainPic'=>$tg_img,
               'tgurl'=>$tranreturn['tg_shortUrl']
               );
            array_push($tgifo,$cp);
            
            $html=$html.'<div class="cps-item">
<div class="item-embed">
<div class="tg-img"><img src="'.$tg_img.'" alt="'.$tg_title.'" /></div>
<div class="item-title">'.$tg_title.'</div>
<div class="tg-note">'.$tg_des.'</div>
<div class="bomoto">
<div class="tg-info">
<div class="tg-price"><em>￥</em>'.$tg_actualPrice.'</div>
<div class="old-price">￥'.$tg_originalPrice.'</div>
<div class="tg-tkl">

淘口令：
<div id="tb-'.$goodsId.'-'.$i.'" class="tg-ma">'.$tg_tpwd.'</div>
</div>
</div>
<div class="tg-btn">
<div class="bomm"><a class="cpsbtn cps_copy_btn" style="color: #fff;" data-clipboard-target="#tb-'.$goodsId.'-'.$i.'">复制淘口令</a></div>
<div class="bomm"><a class="cpsbtn lqm" style="color: #fff;" href="'.$tranreturn['tg_shortUrl'].'" target="_blank" rel="nofollow noopener noreferrer">领券购买</a></div>
</div>
</div>
</div>
</div>';
           
        }
    }
         if($tgifo){
			$data_cache=json_encode(array('code'=>0,'msg'=>'成功','data'=>$html,'times'=>time()));
			wp_cache_set("dtb_bd_".$odata['rankType'],$data_cache,'daimadogcpc',600);
            print_r($data_cache);
			exit(); 
    }
    print_r(json_encode(array('code'=>1,'msg'=>'大淘客获取失败','data'=>$tgifo)));
    exit();
}

//大淘客转链

function tran_dtk($appKey,$appSecret,$goodsid){
 //----------------转换链接
    $urltran = 'https://openapi.dataoke.com/api/tb-service/get-privilege-link';
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
	$tg_shortUrl=$data->data->shortUrl;
	$tg_tpwd=$data->data->tpwd;
}
return array('tg_couponClickUrl'=>$tg_couponClickUrl,
                'tg_itemUrl'=>$tg_itemUrl,
                'tg_shortUrl'=>$tg_shortUrl,
                'tg_tpwd'=>$tg_tpwd
);
}
//大淘客京东接口入口
function getjd_dtk($action,$appKey,$appSecret,$cxnum){
    switch ($action) {
        case 'dpzk':
            $qurl="https://openapi.dataoke.com/api/dels/jd/column/list-discount-brand";
            $data = makeSign($appKey,$appSecret);
            $data['appKey']=$appKey;
            $data['version']='v1.0.0';
            $data['pageSize']=$cxnum;
             $cahe_data = wp_cache_get("jd_bd_".md5($qurl),'daimadogcpc');
	       if($cahe_data){
	        	print_r($cahe_data);
		        exit();
	        }
            jd_dtk_bds($appKey,$appSecret,$qurl,$data,$cxnum);
            break;
        case 'by9':
            $qurl="https://openapi.dataoke.com/api/dels/jd/column/list-nines";
            $data = makeSign($appKey,$appSecret);
            $data['appKey']=$appKey;
            $data['version']='v1.0.0';
            $data['pageSize']=$cxnum;
            $data['sort']=0;
             $cahe_data = wp_cache_get("jd_bd_".md5($qurl),'daimadogcpc');
	       if($cahe_data){
	        	print_r($cahe_data);
		        exit();
	        }
            jd_dtk_bds($appKey,$appSecret,$qurl,$data,$cxnum);
            break;
        case 'ssb':
            $qurl="https://openapi.dataoke.com/api/dels/jd/column/list-real-ranks";
            $data = makeSign($appKey,$appSecret);
            $data['appKey']=$appKey;
            $data['version']='v1.0.0';
            $data['pageSize']=$cxnum;
             $cahe_data = wp_cache_get("jd_bd_".md5($qurl),'daimadogcpc');
	       if($cahe_data){
	        	print_r($cahe_data);
		        exit();
	        }
            jd_dtk_bds($appKey,$appSecret,$qurl,$data,$cxnum);
            break;
        case 'search':
            $qurl="https://openapi.dataoke.com/api/dels/jd/goods/search";
			$keyword = (isset($_POST['keyword'])) ? trim(strip_tags($_POST['keyword'])) : null;
			if (!$keyword) {
			 print_r(json_encode(array('code'=>1,'msg'=>'没有搜索词')));
			 exit();
			}
			$cahe_data = wp_cache_get("jd_bd_".urlencode($keyword),'daimadogcpc');
			if($cahe_data){
				print_r($cahe_data);
				exit();
			}
            $data = makeSign($appKey,$appSecret);
            $data['appKey']=$appKey;
            $data['version']='v1.0.0';
            $data['pageSize']=$cxnum;
            $data['keyword']=$keyword;
            jd_dtk_search($appKey,$appSecret,$qurl,$data,$cxnum);
            break;
        default:
            $qurl="https://openapi.dataoke.com/api/dels/jd/column/list-real-ranks";  
            $data = makeSign($appKey,$appSecret);
            $data['appKey']=$appKey;
            $data['version']='v1.0.0';
            $data['pageSize']=$cxnum;
            $cahe_data = wp_cache_get("jd_bd_".md5($qurl),'daimadogcpc');
	       if($cahe_data){
	        	print_r($cahe_data);
		        exit();
	        }
            jd_dtk_bds($appKey,$appSecret,$qurl,$data,$cxnum);
            break;
    }
	
    
   
}
//大淘客京东榜单搜索
function jd_dtk_bds($appKey,$appSecret,$qurl,$data,$cxnum){
     //拼接请求地址
    $url = $qurl .'?'. http_build_query($data);
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
    $mdata=$data->data->list;
       $n=10;
        if($cxnum){
          $n=$cxnum;  
        }
        if(count($mdata)<$n){
            $n=count($mdata);
        }
        $tgifo=array();
        $html='';
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
        for($i=0;$i<$n;$i++){
          
            $goodsId=$mdata[$i]->skuId;
            $tg_title=$mdata[$i]->skuName;
            $tg_des=$mdata[$i]->document;
            $tg_img=$mdata[$i]->picMain;
            $tg_originalPrice=$mdata[$i]->originPrice;
            $tg_actualPrice=$mdata[$i]->actualPrice;
            $materiaurl=$mdata[$i]->materialUrl;
            $tg_shortUrl=tran_jd_dtk($appKey,$appSecret,$materiaurl);
            
             $cp=array(
              'goodsId'=>$goodsId,
              'desc'=>$tg_des,
               'price'=>$tg_originalPrice,
               'actualPrice'=>$tg_actualPrice,
               'name'=>$tg_title,
               'mainPic'=>$tg_img,
               'tgurl'=>$tg_shortUrl
               );
            array_push($tgifo,$cp);
            $html=$html.'<div class="cps-item"><div class="item-embed"><div class="tg-img"><img style="width:150px;" src="'.$tg_img.'"></div><div class="item-title">'.$tg_title.'</div><div class="tg-note">'.$tg_des.'</div><div class="bomoto"><div class="tg-info"><div class="tg-price"><em>￥</em>'.$tg_actualPrice.'</div><div class="old-price">￥'.$tg_originalPrice.'</div><div class="bomm" style="float: right;"><a class="cpsbtn lqm" style="color: #fff;" href="'.$tg_shortUrl.'">京东购买</a></div></div></div></div></div>';
           
        }
        

         if($tgifo){
			$data_cache=json_encode(array('code'=>0,'msg'=>'成功','data'=>$html,'times'=>time()));
			wp_cache_set("jd_bd_".md5($qurl),$data_cache,'daimadogcpc',600);
            print_r($data_cache);
			exit(); 
         }
        print_r(json_encode(array('code'=>1,'msg'=>'大淘客京东获取失败','data'=>$tgifo)));
        exit();
    }
}
//大淘客京东搜索
function jd_dtk_search($appKey,$appSecret,$qurl,$data,$cxnum){
     //拼接请求地址
    $url = $qurl .'?'. http_build_query($data);
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
    $mdata=$data->data->list;
       $n=10;
        if($cxnum){
          $n=$cxnum;  
        }
        if(count($mdata)<$n){
            $n=count($mdata);
        }
        $tgifo=array();
        $html='';
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
        for($i=0;$i<$n;$i++){
          
            $goodsId=$mdata[$i]->skuId;
            $tg_title=$mdata[$i]->skuName;
            $tg_des=$mdata[$i]->document;
            $tg_img=$mdata[$i]->imageUrlList[0];
            $tg_originalPrice=$mdata[$i]->lowestCouponPrice;
            $tg_actualPrice=$mdata[$i]->price;
            $materiaurl=$mdata[$i]->materialUrl;
            $tg_shortUrl=tran_jd_dtk($appKey,$appSecret,$materiaurl);
            
             $cp=array(
              'goodsId'=>$goodsId,
              'desc'=>$tg_des,
               'price'=>$tg_originalPrice,
               'actualPrice'=>$tg_actualPrice,
               'name'=>$tg_title,
               'mainPic'=>$tg_img,
               'tgurl'=>$tg_shortUrl
               );
            array_push($tgifo,$cp);
            $html=$html.'<div class="cps-item"><div class="item-embed"><div class="tg-img"><img style="width:150px;" src="'.$tg_img.'"></div><div class="item-title">'.$tg_title.'</div><div class="tg-note">'.$tg_des.'</div><div class="bomoto"><div class="tg-info"><div class="tg-price"><em>￥</em>'.$tg_originalPrice.'</div><div class="old-price">￥'.$tg_actualPrice.'</div><div class="bomm" style="float: right;"><a class="cpsbtn lqm" style="color: #fff;" href="'.$tg_shortUrl.'">京东购买</a></div></div></div></div></div>';
           
        }

         if($tgifo){
			$data_cache=json_encode(array('code'=>0,'msg'=>'成功','data'=>$html,'times'=>time()));
			wp_cache_set("jd_bd_".md5($qurl),$data_cache,'daimadogcpc',600);
            print_r($data_cache);
			exit(); 
         }
        print_r(json_encode(array('code'=>1,'msg'=>'大淘客京东获取失败','data'=>$tgifo)));
        exit();
    }
}

//大淘客京东转链
function tran_jd_dtk($appKey,$appSecret,$content){
    $urltran='https://openapi.dataoke.com/api/dels/jd/kit/promotion-union-convert';
   $dmd_jd_unionId= get_option('dmd_jd_unionId');
    //加密的参数
    $data = makeSign($appKey,$appSecret);
    $data['appKey']=$appKey;
    $data['version']='v1.0.0';
    $data['unionId']=$dmd_jd_unionId;
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
//大淘客签名请求
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