<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	include_once("database.php");
	include_once("functions.php");
	logErrors(json_encode($_REQUEST));

	$DialCallStatus = $_REQUEST['DialCallStatus'];
	

	if($DialCallStatus == 'no-answer'){
?>
		<Response>
			<Say>Your call is redirecting to the jordan javier.</Say>
			<Dial action="call_jordan.php" record="record-from-ringing-dual">2022941077</Dial>
		</Response>
<?php
	}else{
		$sql = "update twillio_call_log set recording_url='".$_REQUEST['RecordingUrl']."' where call_sid='".$_REQUEST['CallSid']."'";
		mysqli_query($link,$sql);
	}
?>