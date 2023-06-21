<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	//mail("mirzaahsan42@gmail.com","handle call",print_r($_REQUEST,true));

	$DialCallStatus = $_REQUEST['DialCallStatus'];
	
	if($DialCallStatus == 'no-answer'){
?>
	<Response>
		<Say>Please leave a message at the beep. Press the star key when finished.</Say>
		<Record action="handle_recording.php" method="post" timeout="0" finishOnKey="*"/>
		<Say>I did not receive a recording, goodbye.</Say>
		<Hangup />
	</Response>
<?php
	}
?>