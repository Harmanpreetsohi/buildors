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

	if($DialCallStatus == 'no-answer'){
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
						'Tony ramos was not answered the call.',
						'".$userID."',
						'".$to."',
						'".$from."'
					),
					(
						'".$callSid."',
						'Call was connecting to the peggy.',
						'".$userID."',
						'".$to."',
						'".$from."'
					)";
		mysqli_query($link,$ins);
?>
		<Response>
			<Say>Your call is redirecting to the peggy.</Say>
			<Dial action="call_peggy.php?user_id=<?php echo $userID?>" record="record-from-ringing-dual">2403052448</Dial>
		</Response>
<?php
	}else{
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
						'Tony ramos picks up the call.',
						'".$userID."',
						'".$to."',
						'".$from."',
						'".$DialCallStatus."',
						'".$recordingUrl."'
					),
					(
						'".$callSid."',
						'Call ended.',
						'".$userID."',
						'".$to."',
						'".$from."',
						'',
						''
					)";
		mysqli_query($link,$ins);
		
		$sql = "update twillio_call_log set recording_url='".$recordingUrl."' where call_sid='".$callSid."'";
		mysqli_query($link,$sql);
	}
?>