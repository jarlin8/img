<?php
// 生成
class BECaptchaCode {
	function generateCode($characters) {
		$possible = 'bcdfghjkmnpqrstvwxyz';
		$code = '';
		$i = 2;
		while ($i < $characters) {
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}
}