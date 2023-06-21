<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	include_once("database.php");
	include_once("functions.php");

	$to 	 = $_REQUEST['To'];
	$from    = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$digits  = $_REQUEST["Digits"];
	$userID  = $_REQUEST['user_id'];
	$recordingUrl = $_REQUEST['RecordingUrl'];
	$DialCallStatus = $_REQUEST['DialCallStatus'];	

	echo '<Response>';

	if(($DialCallStatus != '') && ($DialCallStatus == 'no-answer')){
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
						'Kat power was not answered the call.',
						'".$userID."',
						'".$to."',
						'".$from."'
					),
					(
						'".$callSid."',
						'Call was connecting to the craig.',
						'".$userID."',
						'".$to."',
						'".$from."'
					)";
		mysqli_query($link,$ins);
?>
		<Say>Kat power is not available, redirecting you to the Craig.</Say>
		<Dial action="handle_call_craig.php?user_id=<?php echo $userID?>">2406874276</Dial>
<?php
	}
	else{
		$ins = "insert into call_tracking
					(
						call_sid,
						action,
						user_id,
						to_number,
						from_number,
						final_call_status,
						recording_url
					)
				values
					(
						'".$callSid."',
						'Call status was ".$DialCallStatus."',
						'".$userID."',
						'".$to."',
						'".$from."',
						'".$DialCallStatus."',
						'".$recordingUrl."'
					)";
		mysqli_query($link,$ins);
		echo "<Say>Thanks for your call, goodbye!</Say>";
	}
	echo '</Response>';
?>