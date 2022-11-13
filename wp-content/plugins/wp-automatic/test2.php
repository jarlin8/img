<?php

$curl = curl_init();

curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://i.instagram.com/api/v1/feed/user/36846237222/?count=12&max_id=2922653525437157832_36846237222',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(
				'authority: i.instagram.com',
				'accept: */*',
				'accept-language: en-US,en;q=0.9,ar;q=0.8',
				'origin: https://www.instagram.com',
				'referer: https://www.instagram.com/',
				'sec-ch-ua: "Chromium";v="106", "Google Chrome";v="106", "Not;A=Brand";v="99"',
				'sec-ch-ua-mobile: ?0',
				'sec-ch-ua-platform: "macOS"',
				'sec-fetch-dest: empty',
				'sec-fetch-mode: cors',
				'sec-fetch-site: same-site',
				'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36',
				'x-asbd-id: 198387',
				'x-csrftoken: 9NNVLTPlK7Axm3jCNdYh8EeLRYmormAU',
				'x-ig-app-id: 936619743392459',
				'x-ig-www-claim: hmac.AR3-365c0myQUxMUooD2u7aSW_B_FyLYH5Hmgx_m28jJvWOq',
				'x-instagram-ajax: 1006400988'
				
		),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
