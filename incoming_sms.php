<?php
	include_once("database.php");
	include_once("functions.php");
	require __DIR__ . '/vendor/autoload.php';
	//logErrors(json_encode($_REQUEST));

	$SmsSid = $_REQUEST['SmsSid'];
	$Body   = $_REQUEST['Body'];
	$To		= removeCountryCode($_REQUEST['To']);
	$From	= removeCountryCode($_REQUEST['From']);
	$Media  = $_REQUEST['MediaUrl0'];
	$mediaInfo = explode("/",$_REQUEST['MediaContentType0']);
	$mediaContentType = $mediaInfo[0];
	$mediaExtension   = $mediaInfo[1];
	$customerInfo = getCustomerInfoByNumber($From);
	
	$sqlCheck = "select user_id from twilio_numbers where phone_number='+1".$To."'";
	$resCheck = mysqli_query($link,$sqlCheck);
	if(mysqli_num_rows($resCheck)){
		$rowCheck = mysqli_fetch_assoc($resCheck);
		$userID = $rowCheck['user_id'];
	}

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
	if(trim($Media)==''){
		$Media = 'no';
	}

	if(strtolower($Body) == 'stop'){
		mysqli_query($link,"update customers set status='2' where cell='".$From."'");
		$sql = "insert into conversations
					(
						customer_number,
						user_id,
						to_number,
						from_number,
						is_sent,
						sms_sid,
						message,
						direction,
						media
					)
				values
					(
						'".$To."',
						'".$userID."',
						'".$To."',
						'".$From."',
						'true',
						'".$SmsSid."',
						'".DBin($Body)."',
						'in',
						'".$Media."'
					)";
		mysqli_query($link,$sql);
		die();
	}
	if(strtolower($Body) == 'start'){
		mysqli_query($link,"update customers set status='1' where cell='".$From."'");
		$sql = "insert into conversations
					(
						customer_number,
						user_id,
						to_number,
						from_number,
						is_sent,
						sms_sid,
						message,
						direction,
						media
					)
				values
					(
						'".$To."',
						'".$userID."',
						'".$To."',
						'".$From."',
						'true',
						'".$SmsSid."',
						'".DBin($Body)."',
						'in',
						'".$Media."'
					)";
		mysqli_query($link,$sql);
		die();
	}

	$sel = "select id from conversations where customer_number='".$From."' limit 1";
	$exe = mysqli_query($link,$sel);
	if(mysqli_num_rows($exe)==0){
		$data['message'] = $Body;
		$data['to_number'] = $To;
		$data['from_number'] = $From;
		$data['from_media'] = $Media;
		$data['first_name'] = $customerInfo['first_name'];
		$data['last_name']  = $customerInfo['last_name'];
		$data['event_type'] = "new-number";
		$pusher->trigger('my-channel', 'new-incoming-number', $data);
	}
	else{
		$data['message'] = $Body;
		$data['to_number'] = $To;
		$data['from_number'] = $From;
		$data['from_media'] = $Media;
		$data['first_name'] = $customerInfo['first_name'];
		$data['last_name']  = $customerInfo['last_name'];
		$data['event_type'] = "new-sms";
		$pusher->trigger('my-channel', 'new-incoming-sms', $data);
	}

	$sql = "insert into conversations
				(
					customer_number,
					user_id,
					to_number,
					from_number,
					is_sent,
					sms_sid,
					message,
					direction,
					media,
					media_content_type,
					media_extension
				)
			values
				(
					'".$From."',
					'".$userID."',
					'".$To."',
					'".$From."',
					'true',
					'".$SmsSid."',
					'".DBin($Body)."',
					'in',
					'".$Media."',
					'".$mediaContentType."',
					'".$mediaExtension."'
				)";
	mysqli_query($link,$sql)or die(mysqli_error($link));
?>