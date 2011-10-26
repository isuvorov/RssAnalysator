<?php

$login = 'suvorov.igor.90@gmail.com';
$pass = 'Dog-27-goD';
$labels = array('RusFreelance', 'EngFreelance');
$label = $labels[0];
$userId = '13436249930847868446';
$coninuation = '';



$lts = file_get_contents('ts.txt');
$ts = time();
file_put_contents('ts.txt', $ts);

$outputJson = 1 ? 'api/0/stream/contents' : 'atom';
$authUrl = 'https://www.google.com/accounts/ClientLogin';
$syncUrl = "http://www.google.ru/reader/{$outputJson}/user/{$userId}/label/{$label}?r=o&n=999999&ot={$lts}&output=json&c={$continuation}";
//$syncUrl = "https://www.google.ru/reader/api/0/stream/contents/user/{$userId}/label/{$label}?r=n&c=CMXP58ya1asC&n=40&ck={$ts}";
//https://www.google.com/reader/api/0/stream/contents/user/13436249930847868446/label/RusFreelance
$ts = time();//'1317954968709';
$syncFile = "rss_{$label}_{$ts}.json";

//$getTagsUrl = 'http://www.google.com/reader/api/0/tag/list?output=json';
 
// https://www.google.com/reader/api/0/stream/contents/user/13436249930847868446/label/RusFreelance?r=n&c=CMXP58ya1asC&n=4000&ck=1317954968709
 
 
$loginData = array();
$loginData['service'] = 'reader';
$loginData['Email'] = $login;
$loginData['Passwd'] = $pass;
$loginData['accountType'] = 'GOOGLE';
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $authUrl);
curl_setopt($ch, CURLOPT_USERAGENT, $agent);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($loginData));
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
$data = curl_exec($ch);


$authData = array();
foreach (explode("\n",$data) as $str) {
	if (!empty($str)) {
			$data = explode("=", $str);
			$authData[$data[0]] = $data[1];
	}
}
$key = $authData['Auth'];
//$key = 'DQAAAMMAAAAdWGaiqkvvfIFFF05IS8IWPYDNz7OBNE_VK8u9X8pIQFZ5hOU6ortynisTTCIAnWD9pX4-IeqI-kRM9n4V9eTuFQ8FwG4Zso1yp7EXxjuuTAJrCscqanSXPoLsi_nVgcjrElKUZ85IZE2MM5rYyAlkGAIyKk6vJus3qC4TZTUKITUY9P38CxanWgyiS74cMV4RGj1GYNzhWe1c-bh13rb-3nIsf8JzuxpxdmH7qEi_Pow2rgElDlh9BhbHFif19d0R4jK45QxsI_jbKWd15ZIG';

$headers = array("Authorization:GoogleLogin auth={$key}");
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
curl_setopt($ch, CURLOPT_URL, $syncUrl);
$json = curl_exec($ch);
$obj = json_decode($json);
$empty = count($obj->items) == 0;
echo count($obj->items);
if(!$empty)
	file_put_contents($syncFile, $json);

?>