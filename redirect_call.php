<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	include_once("database.php");
	include_once("functions.php");
	$to = $_REQUEST['To'];
	$from = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$digits = $_REQUEST["Digits"];
	$DialCallStatus = $_REQUEST['DialCallStatus'];
	$clientName = $_REQUEST['client_name'];
	$redirectTo = $_REQUEST['redirect_to'];
	
	echo '<Response>';
		if($redirectTo == 'support'){
			if($DialCallStatus == 'no-answer'){
				$message = "and text project manager, your client ".$clientName." called requesting to speak you, the call was transferred to client support.";
				sendMessage($to, $from, $message);
?>				
				<Say>Please hold, your call is connecting to the support agent.</Say>
				<Dial action="redirect_call.php?redirect_to=management">7037724315</Dial>	
<?php
			}else{
?>			
				<Say>Please hold, your call is connecting to the support agent.</Say>
				<Dial action="redirect_call.php?redirect_to=management">7037724315</Dial>
<?php
			}
		}else if($redirectTo == 'management'){
?>
			<Say>Please hold, your call is connecting to the management.</Say>
			<Dial action="redirect_call.php?redirect_to=end_call">2022941077</Dial>
<?php
		}else if($redirectTo == 'end_call'){
?>
			<Say>Thanks for your calling, goodbye.</Say>
			<Hangup />
<?php
		}else if($redirectTo == 'project_manager'){
?>
			<Say>Please hold, your call is connecting to the project manager.</Say>
			<Dial action="redirect_call.php?redirect_to=support">2022941077</Dial>
<?php
		}
	echo '</Response>';
?>