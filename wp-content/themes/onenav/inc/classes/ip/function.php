<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2022-12-06 20:32:29
 * @LastEditors: iowen
 * @LastEditTime: 2023-02-06 21:57:37
 * @FilePath: \onenav\inc\classes\ip\function.php
 * @Description: 
 */

define("IP_DATABASE_ROOT_DIR", wp_upload_dir()['basedir'].'/ip_data');

require dirname(__DIR__) . '/ip/IpParser/IpParserInterface.php';

require dirname(__DIR__) . '/ip/IpLocation.php';

require dirname(__DIR__) . '/ip/IpParser/QQwry.php';
require dirname(__DIR__) . '/ip/IpParser/IpV6wry.php';
require dirname(__DIR__) . '/ip/IpParser/Ip2Region.php';

require dirname(__DIR__) . '/ip/StringParser.php';
