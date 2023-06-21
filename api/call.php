<?php

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");

$f = fopen('call.txt', 'a+');


fwrite($f, "\n\n\nHEllo\n\n\n");

fwrite($f, json_encode($_REQUEST));

fclose($f);

if (isset($_REQUEST['hub_challenge'])) {
	echo $_REQUEST['hub_challenge'];
}

print_r($_POST);