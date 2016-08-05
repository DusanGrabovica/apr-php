<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://pretraga2.apr.gov.rs/ObjedinjenePretrage/Search/SearchResult");

$data = [
	"__RequestVerificationToken" => "O4mF6RpMU/5dAyPkbMqG8mshrzlitMPha8bDLidt4zh8DZWKvswFROcsgGm2ktLNbPwQVj4uOxugFR28pt6CK4xLmRXGTuOBEOTDATU7Ntkei3um485RRL0iwwCls0FKYoxYLbNbeAXo2js+trVCRWjHv8LkvFpESkuddwFBhS8=", 
	"rdbtnSelectInputType" => "poslovnoIme", 
	"SearchByNameString" =>"sbb", 
	"SelectedRegisterId" => 1,
	"X-Requested-With" =>"XMLHttpRequest"
];

$headers = [
	"Cookie: __RequestVerificationToken_L09iamVkaW5qZW5lUHJldHJhZ2U_=vpI/S+qgSqKZzoijRUJy7nP3N8eP6ZLFG+BeX3YSNkUIYLnR4bHAQb/KvQFGiiFmSb1YrwZhpoO/z96xNdvU0aR0fJXstA3/Us6LgNgrLVOUcI03CxKUv/9R1AtwFcxY2C54+29WwJnHR9MHrHexY2IOD7HOgY2oo1amhRjl/+g=; SERVERID=Server2;"
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_ENCODING, '');curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);
curl_close($ch);

echo $server_output; 
