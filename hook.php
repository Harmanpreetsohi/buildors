<?php

include_once("database.php");
include_once("functions.php");
require __DIR__ . '/vendor/autoload.php';
include 'clsss/class-fb.php';


$options = array(
		'cluster' => 'ap3',
		'useTLS' => true
	);
$pusher = new Pusher\Pusher(
	'65561534463c91979b12',
	'7109415b0f1625b3f958',
	'1535046',
	$options
);

$post = file_get_contents('php://input');

$post = json_decode($post,true);

$fb = new FBChat('EABXfZCfLPDE4BAHWirU9eyNCVBJpIN7a6qIEJmImQom2PNpZCx8RrSMpWPxZBrE2oVcTwXcW5SGGCu3Vv8tH2WCtaqiLLnw958IHrrS1aXOqbQYfPCCZAg0aAdPKNmV2NGjtxTVGsZCnjTZBbxWptb2hyaaM9teDBILSxyPutJerq4xyVj13nsVc9jDEtmkfbdIr5kNk6QLAZDZD');

$post = $fb->receive_msg($post);

global $link;

extract($post);

if  (isset($sender) && isset($recipient) && isset($timestamp) && isset($mid) && isset($from_name) && isset($to_name)) {
	$sql = "INSERT INTO `fb_msgs`(`id`, `sender`, `recipient`, `timestamp`, `mid`, `text`, `from_name`, `to_name`) VALUES (NULL,'$sender','$recipient','$timestamp','$mid','$text','$from_name','$to_name')";
		mysqli_query($link,$sql);

	$post['timestamp'] = date("d M, H:i a",((int)round($post['timestamp']/1000)));

	$pusher->trigger('my-channel', 'new-incoming-sms', $post);

	
	// $text = "Hey i am Solar Daddy How may i help you?";
	// $result = $fb->send_msg($sender,$text);
	// $result = json_decode($result,true);
	
	// if (isset($result['message_id'])) {
	// 	$mid = $result['message_id'];
	// 	$sql = "INSERT INTO `fb_msgs`(`id`, `sender`, `recipient`, `timestamp`, `mid`, `text`, `from_name`, `to_name`) VALUES (NULL,'$recipient','$sender','".strtotime('now')."','$mid','$text','$to_name','$from_name')";
	// 	mysqli_query($link,$sql);
	// }

}

