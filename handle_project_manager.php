<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	include_once("database.php");
	include_once("functions.php");
	$to = $_REQUEST['To'];
	$from = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$digits = $_REQUEST["Digits"];
	$clientName = $_REQUEST['client_name'];
	$customerID = $_REQUEST['cid'];

	echo '<Response>';
	if($DialCallStatus == 'no-answer'){
		$message = "Your client ".$clientName." called requesting to speak you. the call was transferred to client support.";
		sendMessage($to, $from, $message);
?>
		<Say>Please hold, your call is connecting to the support agent.</Say>
		<Dial action="handle_support.php?cid=<?php echo $customerID?>&amp;client_name=<?php echo $clientName?>">7037724315</Dial>
<?php
	}else{
?>
		<Say>thanks for calling, goodbye.</Say>
		<Hangup />
<?php
	}
	echo '</Response>';
?>