<?php
define("BECRCC_KEY_FOR_RC4", be_get_cartpauj_url());

function be_get_cartpauj_url() {
	return (string)$_SERVER["DOCUMENT_ROOT"].substr(uniqid(''), 0, 3);
}

function be_str_encrypt($str) {
	$mystr = BECRCCRC4($str, BECRCC_KEY_FOR_RC4);
	$mystr = rawurlencode(base64_encode($mystr));
	return $mystr;
}

function be_str_decrypt($str) {
	$mystr = base64_decode(rawurldecode($str));
	$mystr =  BECRCCRC4($mystr, BECRCC_KEY_FOR_RC4);
	return $mystr;
}

function BECRCCRC4($data, $key) {
	$x=0; $j=0; $a=0; $temp=""; $Zcrypt="";
	for ($i=0; $i<=255; $i++) {
		$counter[$i] = "";
	}

	$pwd = $key;
	$pwd_length = strlen($pwd);
	for ($i = 0; $i < 255; $i++) { 
		@$key[$i] = ord(substr($pwd, ($i % $pwd_length)+1, 1));
		$counter[$i] = $i; 
	}

	for ($i = 0; $i < 255; $i++) {
		$x = ($x + $counter[$i] + $key[$i]) % 256;
		$temp_swap = $counter[$i]; 
		$counter[$i] = $counter[$x];
		$counter[$x] = $temp_swap;
	}

	for ($i = 0; $i < strlen($data); $i++) {
		$a = ($a + 1) % 256; 
		$j = ($j + $counter[$a]) % 256;
		$temp = $counter[$a]; 
		$counter[$a] = $counter[$j];
		$counter[$j] = $temp;
		$k = $counter[((intval($counter[$a]) + intval($counter[$j])) % 256)];
		$Zcipher = ord(substr($data, $i, 1)) ^ intval($k);
		$Zcrypt .= chr($Zcipher);
	}
	return $Zcrypt;
}