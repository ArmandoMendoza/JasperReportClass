<?php
//// 1 GET
$request = curl_init("http://192.168.0.145:8080/jasperserver/rest/resource/reports/samples/AllAccounts");
curl_setopt($request, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($request, CURLOPT_USERPWD, "jasperadmin:jasperadmin");
curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($request);
curl_close($request);
$descriptor = $response;

//// 2 PUT
$request = curl_init("http://192.168.0.145:8080/jasperserver/rest/report/reports/samples/AllAccounts");
curl_setopt($request, CURLOPT_POST, 1);
curl_setopt($request, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($request, CURLOPT_USERPWD, "jasperadmin:jasperadmin");
curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($request, CURLOPT_HEADER, 1);
curl_setopt($request, CURLOPT_POSTFIELDS, $descriptor);
curl_setopt($request, CURLOPT_CUSTOMREQUEST, "PUT");

$response = curl_exec($request);
$header_size = curl_getinfo($request, CURLINFO_HEADER_SIZE);

$result = array();
$result['header'] = substr($response, 0, $header_size);
$result['body'] = substr( $response, $header_size );
preg_match('/^Set-Cookie: (.*?);/m', $result['header'], $cookie_content);
curl_close($request);
$cookie = $cookie_content[1];
$xml = simplexml_load_string($result['body']);

$xml->uuid;

/// 3 GET

$request = curl_init("http://192.168.0.145:8080/jasperserver/rest/report/".$xml->uuid."?file=report");
// $headers = array('Content-Type' => 'text/xml', 'Cookie'=> $cookie);
// curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
curl_setopt($request, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($request, CURLOPT_USERPWD, "jasperadmin:jasperadmin");
curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($request, CURLOPT_COOKIE, $cookie.';$Path=/jasperserver');
curl_setopt($request, CURLOPT_HTTPGET, 1);
curl_setopt($request, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($request, CURLOPT_VERBOSE, true);
$response = curl_exec($request);
curl_close($request);
echo $response;