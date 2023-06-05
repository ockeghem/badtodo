<?php
$url = filter_input(INPUT_GET, "url");
//  ini_set("openssl.cafile", "/etc/ssl/certs/ca-certificates.crt");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 3);
$output = curl_exec($ch);
curl_close($ch);
if ($output === false) {
  header("HTTP/1.1 404 Not Found");
  exit;
}
echo $output;
