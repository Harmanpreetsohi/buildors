<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	include_once("database.php");
	include_once("functions.php");
	$to = $_REQUEST['To'];
	$from = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$CallerCity = $_REQUEST['CallerCity'];
	$CallerCountry = $_REQUEST['CallerCountry'];
	$CallerState = $_REQUEST['CallerState'];

	$userID = getNumberOwner($to);
	$sql = "insert into twillio_call_log
				(
					to_number,
					number,
					call_sid,
					mode,
					user_id,
					CallerCity,
					CallerCountry,
					CallerState
				)
			values
				(
					'".$to."',
					'".$from."',
					'".$callSid."',
					'in',
					'".$userID."',
					'".$CallerCity."',
					'".$CallerCountry."',
					'".$CallerState."'
				)";
	mysqli_query($link,$sql);
	
	$ins = "insert into call_tracking
				(
					call_sid,
					action,
					user_id,
					to_number,
					from_number
				)
			values
				(
					'".$callSid."',
					'Welcome to karma construction group, If you are a new client and have question regarding your estimate, press 1. a new vendor or need permitting services, press 2. if you are seeking carrier opportunities, press 3. if this is regarding billing, press 4. if this is any emergency, press 5 now.',
					'".$userID."',
					'".$to."',
					'".$from."'
				)";
	mysqli_query($link,$ins);


	
?>
<Response>
	<Gather action="https://buildors.com/karma_ivr_main_menu.php?user_id=<?php echo $userID?>" input="dtmf" numDigits="1" timeout="3" method="post">
		<Say>Welcome to karma construction group, If you are a new client and have question regarding your estimate, press 1. a new vendor or need permitting services, press 2. if you are seeking carrier opportunities, press 3. if this is regarding billing, press 4. if this is any emergency, press 5 now.</Say>
		<Pause length="3"/>
		<Say>Welcome to karma construction group, If you are a new client and have question regarding your estimate, press 1. a new vendor or need permitting services, press 2. if you are seeking carrier opportunities, press 3. if this is regarding billing, press 4. if this is any emergency, press 5 now.</Say>
		<Pause length="3"/>
		<Say>Welcome to karma construction group, If you are a new client and have question regarding your estimate, press 1. a new vendor or need permitting services, press 2. if you are seeking carrier opportunities, press 3. if this is regarding billing, press 4. if this is any emergency, press 5 now.</Say>
		<Pause length="3"/>
	</Gather>
	<?php
		/*
		sleep(5);
		//$jsonData = '{"RecordingStatusCallback":"https://buildors.com/server.php?cmd=recording_callback_status"}';
		$jsonData = array(
			"RecordingStatusCallback" => urldecode("https://buildors.com/server.php?cmd=recording_callback_status")
		);	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://api.twilio.com/2010-04-01/Accounts/ACb797881d79a639eefb0a266f275b895b/Calls/'.$callSid.'/Recordings.json');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
		curl_setopt($ch, CURLOPT_USERPWD, 'ACb797881d79a639eefb0a266f275b895b'.':'.'e0419a64126bc4424d9b367d809a9aa0');
		$result = curl_exec($ch);
		if(curl_errno($ch)){
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
		*/
		//logErrors("run_ivr: ".$result);
	?>
	<Say>We didn't receive any input. Goodbye!</Say>
	<Hangup />
</Response>