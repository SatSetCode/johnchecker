<?php
set_time_limit(0); error_reporting(0); $email = $_GET['email'];

if(!isset($_GET['email']) || !filter_var($_GET['email'], FILTER_VALIDATE_EMAIL) || $_GET['created_by'] != "JohnSetiawan"){
exit(json_encode([ "email" => $email, "status"=> "unknown" ]));
}


$ch = curl_init('https://appleid.apple.com/account');
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_ENCODING, '');
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_HEADERFUNCTION,
function($request, $header) use (&$headers){
$len = strlen($header);
$header = explode(':', $header, 2);
if (count($header) < 2) 
return $len;
$headers[strtolower(trim($header[0]))][] = trim($header[1]);
return $len;
}
);

$ex = curl_exec($ch);
preg_match_all('/^Set-Cookie:\s*([^\r\n]*)/mi', $ex, $ms);
$cookies = array();
foreach ($ms[1] as $m) {
list($name, $value) = explode('=', $m, 2);
$cookies[$name] = $value;
}
$se_explode = $cookies['aidsp'];
$sessionid_explode = explode(";", $se_explode);



$headers2 = [
'Accept: application/json, text/javascript, */*; q=0.01',
'Content-Type: application/json',
'scnt: '.$headers["scnt"][0] ,
'X-Apple-Api-Key: cbf64fd6843ee630b463f358ea0b707b',
'X-Apple-ID-Session-Id: '.$sessionid_explode[0]
];

$ch = curl_init('https://appleid.apple.com/account/validation/appleid');
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"emailAddress":"'.$_GET['email'].'"}');
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_ENCODING, '');
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers2);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$jsonResult = json_decode(curl_exec($ch) , true);


if(isset($jsonResult['used']) and $jsonResult['used'] === true):
$msg = [ "email" => $email, "status"=> "registered" ];

elseif(isset($jsonResult['used']) and $jsonResult['used'] === false):
$msg = [ "email" => $email, "status"=> "unregistered" ];

else:
$msg = [ "email" => $email, "status"=> "unknown" ];

/*  */ endif; /*  */

exit(json_encode($msg));



?>