<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	include_once("database.php");
	include_once("functions.php");
	logErrors(json_encode($_REQUEST));

	$DialCallStatus = $_REQUEST['DialCallStatus'];
	
	if($DialCallStatus == 'no-answer'){
		$message = 'it looks like everybody is busy, please leave a voicemail or respond to the text and will get back to you.';
		sendMessage($to, $from, $message);
		$message = 'thanks for inquiring about employment opportuniities, what type of work do you do and in which state? please also text us your license info, pictures of your work and insurance information.';
		sendMessage($to, $from, $message);
?>
		<Response>
			<Hangup />
		</Response>
<?php
	}else{
		$sql = "update twillio_call_log set recording_url='".$_REQUEST['RecordingUrl']."' where call_sid='".$_REQUEST['CallSid']."'";
		mysqli_query($link,$sql);
	}
?>