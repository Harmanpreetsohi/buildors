<?php
	//phpinfo();

	// include_once("functions.php");
	// $sid = "ACb797881d79a639eefb0a266f275b895b";
	// $token = "e0419a64126bc4424d9b367d809a9aa0";
	// // listing all number from twilio account

	// $ch = curl_init();
	// curl_setopt($ch, CURLOPT_URL, "https://api.twilio.com/2010-04-01/Accounts/".$sid."/IncomingPhoneNumbers.json?PageSize=20");
	// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	// curl_setopt($ch, CURLOPT_USERPWD, $sid. ':' . $token);
	// $result = json_decode(curl_exec($ch),true);
	// if(curl_errno($ch)){
	// 	echo 'Error:' . curl_error($ch);
	// }
	// curl_close($ch);
	// echo "<pre>";
	// print_r($result);
	// echo "</pre>";

	//echo encodePassword("Temp@12345");
	/*
	// update an app
	$data = array(
		//"VoiceUrl" => getServerURL()."/incoming_call.php",
		"VoiceUrl" => "https://buildors.com/incoming_call.php",
		"FriendlyName" => "buildors_dialer"
	);
	//echo json_encode($data);
	//print_r($data);
	//die();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.twilio.com/2010-04-01/Accounts/".$sid."/Applications/AP7ec325c1dbc2464eca1b6efacf8f3028.json");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_USERPWD, $sid . ':' . $token);
	$headers = array();
	$headers[] = 'Content-Type: application/x-www-form-urlencoded';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = json_decode(curl_exec($ch),true);
	if(curl_errno($ch)){
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	echo "<pre>";
	print_r($result);
	*/

	/*
	// deleting apps
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.twilio.com/2010-04-01/Accounts/".$sid."/Applications/APf9c4a3f816c666a64af340787e6d0e8d.json");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
	curl_setopt($ch, CURLOPT_USERPWD, $sid . ':' . $token);
	$result = json_decode(curl_exec($ch),true);
	if(curl_errno($ch)){
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	echo "<pre>";
	print_r($result);
	*/

	/*
	// creating app
	$fields = array(
		"VoiceMethod" => "post",
		"VoiceUrl" => getServerURL()."/incoming_call.php",
		"FriendlyName" => "aaweb-buildors-dialer"
	);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.twilio.com/2010-04-01/Accounts/'.$sid.'/Applications.json');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	curl_setopt($ch, CURLOPT_USERPWD, $sid . ":" . $token);
	$headers = array();
	$headers[] = 'Content-Type: application/x-www-form-urlencoded';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = json_decode(curl_exec($ch),true);
	if(curl_errno($ch)){
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	echo "<pre>";
	print_r($result);
	*/
	
	// listing apps
	/*
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.twilio.com/2010-04-01/Accounts/".$sid."/Applications.json?PageSize=20");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_USERPWD, $sid . ':' . $token);
	$result = json_decode(curl_exec($ch),true);
	if(curl_errno($ch)){
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	echo "<pre>";
	print_r($result);
	*/

	/*
	require_once 'browser_call/autoload.php';
	use Twilio\Jwt\ClientToken;

	$capability = new ClientToken($accountSid, $authToken);
	$capability->allowClientOutgoing($appSID);
	*/
	chdir('../');
	// exec('rm public_html.zip');
	// exec('zip -r public_html.zip "public_html"');
	exec('mv public_html.zip public_html/public_html.zip');
	$a = scandir('.');
	print_r($a);exit();
// Get real path for our folder
$rootPath = realpath('public_html');

// Initialize archive object
$zip = new ZipArchive();
$zip->open(date('d_m_Y').'vendor.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file)
{
    // Skip directories (they would be added automatically)
    if (!$file->isDir())
    {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        // Add current file to archive
        $zip->addFile($filePath, $relativePath);
    }
}

// Zip archive will be created only after closing object
$zip->close();