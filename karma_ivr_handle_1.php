<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	include_once("database.php");
	include_once("functions.php");
	$to 	 = $_REQUEST['To'];
	$from    = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$digits  = $_REQUEST["Digits"];
	$userID  = $_REQUEST['user_id'];

	echo "<Response>";

	if($digits == '1'){
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
						'Caller pressed 1',
						'".$userID."',
						'".$to."',
						'".$from."'
					),
					(
						'".$callSid."',
						'SMS sent: https://www.karmaconstructiongroup.com/book-online-dmv',
						'".$userID."',
						'".$to."',
						'".$from."'
					)";
		mysqli_query($link,$ins);
		$message = 'https://www.karmaconstructiongroup.com/book-online-dmv';
		sendMessage($to, $from, $message);
		echo "<Say>Booking link is sent to your number, goodbye.</Say>";
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
						'Call ended',
						'".$userID."',
						'".$to."',
						'".$from."'
					)";
		mysqli_query($link,$ins);
	}
	else if($digits == '2'){
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
						'Caller pressed 2',
						'".$userID."',
						'".$to."',
						'".$from."'
					),
					(
						'".$callSid."',
						'SMS sent: https://www.karmaconstructiongroup.com/book-online-raleigh',
						'".$userID."',
						'".$to."',
						'".$from."'
					)";
		mysqli_query($link,$ins);
		$message = 'https://www.karmaconstructiongroup.com/book-online-raleigh';
		sendMessage($to, $from, $message);
		echo "<Say>Booking link is sent to your number, goodbye.</Say>";
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
						'Call ended',
						'".$userID."',
						'".$to."',
						'".$from."'
					)";
		mysqli_query($link,$ins);
	}
	else if($digits == '3'){
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
						'Caller pressed 3',
						'".$userID."',
						'".$to."',
						'".$from."'
					),
					(
						'".$callSid."',
						'SMS sent: https://www.karmaconstructiongroup.com/book-online-miami',
						'".$userID."',
						'".$to."',
						'".$from."'
					)";
		mysqli_query($link,$ins);
		$message = 'https://www.karmaconstructiongroup.com/book-online-miami';
		sendMessage($to, $from, $message);
		echo "<Say>Booking link is sent to your number, goodbye.</Say>";
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
						'Call ended',
						'".$userID."',
						'".$to."',
						'".$from."'
					)";
		mysqli_query($link,$ins);
	}
	else if($digits == '9'){
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
						'Caller pressed 9',
						'".$userID."',
						'".$to."',
						'".$from."'
					),
					(
						'".$callSid."',
						'Call was connecting to the kat power.',
						'".$userID."',
						'".$to."',
						'".$from."'
					)";
		mysqli_query($link,$ins);
?>
		<Say>Please hold, your call is connecting to the kat power.</Say>
		<Dial action="karma_ivr_handle_call.php?user_id=<?php echo $userID?>" method="post">7037724315</Dial>
<?php
	}	
	echo "</Response>";
?>