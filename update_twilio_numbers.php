<?php
	include_once("database.php");
	$twilio_sid   = 'ACb797881d79a639eefb0a266f275b895b';
	$twilio_token = 'e0419a64126bc4424d9b367d809a9aa0';
	$smsURL       = getServerURL().'/incoming_sms.php';
	$callURL      = getServerURL().'/run_ivr.php';

	$url = 'https://api.twilio.com/2010-04-01/Accounts/ACb797881d79a639eefb0a266f275b895b/IncomingPhoneNumbers.json';
	$allNumbers = getCurl($url);
	$allNumbers = json_decode($allNumbers,true);

	for($j=0; $j < count($allNumbers["incoming_phone_numbers"]); $j++){
		$phoneSid = $allNumbers["incoming_phone_numbers"][$j]['sid'];
		$data = array("VoiceUrl" => $callURL, "SmsUrl" => $smsURL);

		$url = "https://$twilio_sid:$twilio_token@api.twilio.com/2010-04-01/Accounts/$twilio_sid/IncomingPhoneNumbers/".$phoneSid.".json";
		$numbers = json_decode(postCurl($url,$data),true);
		//print_r($numbers);
		
		$sid = $numbers["sid"];
		$twilioNumber = $numbers['phone_number'];
		
		echo $sql = "insert into twilio_numbers (phone_number,sid,user_id) values ('".$twilioNumber."','".$sid."','1')";
		$res = mysqli_query($link,$sql);
		echo "<br>";
	}
	
	function getCurl($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HTTPGET);
		curl_setopt($ch, CURLOPT_USERPWD, 'ACb797881d79a639eefb0a266f275b895b'.':'.'e0419a64126bc4424d9b367d809a9aa0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20110814 Firefox/6.0');
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	function postCurl($url,$data){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20110814 Firefox/6.0');
		$d = curl_exec($ch);
		curl_close($ch);
		return $d;
	}
	function getServerURL(){
		$protocol = ( ((!empty($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] !== 'off')) || ($_SERVER['SERVER_PORT'] == 443) ) ? "https://" : "http://";
		$domainName = $_SERVER['HTTP_HOST'];
		$filePath   = $_SERVER['REQUEST_URI'];
		$fullUrl = $protocol.$domainName.$filePath;
		$installURL = substr($fullUrl,0,strrpos($fullUrl,'/'));
		return $installURL;
	}
?>