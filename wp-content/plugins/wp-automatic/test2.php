<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://i.instagram.com/api/v1/media/2759881823689758050/comments/?can_support_threading=true&permalink_enabled=false',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'authority: i.instagram.com',
    'sec-ch-ua: " Not;A Brand";v="99", "Google Chrome";v="97", "Chromium";v="97"',
    'x-ig-www-claim: hmac.AR3-365c0myQUxMUooD2u7aSW_B_FyLYH5Hmgx_m28jJveH0',
    'sec-ch-ua-mobile: ?0',
    'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36',
    'accept: */*',
    'x-asbd-id: 198387',
    'sec-ch-ua-platform: "macOS"',
    'x-ig-app-id: 936619743392459',
    'origin: https://www.instagram.com',
    'sec-fetch-site: same-site',
    'sec-fetch-mode: cors',
    'sec-fetch-dest: empty',
    'referer: https://www.instagram.com/',
    'accept-language: en-US,en;q=0.9,ar;q=0.8',
    'Cookie: sessionid=387616934%3AuyXt3L0fwbxYrL%3A9'
  ),
));

$response = curl_exec($curl);

$json = json_decode($response);

print_r($json);

curl_close($curl);
echo $response;
