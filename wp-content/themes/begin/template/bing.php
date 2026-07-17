<?php
$date         = mt_rand( 0, 7 );
$json_content = file_get_contents( 'https://cn.bing.com/HPImageArchive.aspx?format=js&idx=' . $date . '&n=1&mkt=zh-CN' );
$json_content = json_decode( $json_content, true );
$imgurl       = 'https://cn.bing.com' . $json_content['images'][0]['url'];
header( "Location: $imgurl" );