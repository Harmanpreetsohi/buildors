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
		$message = "Client ".$clientName." requested to speak with Project Manager and no one has answered.";
		sendMessage($to, $from, $message);
?>
		<Say>Please hold, your call is connecting management.</Say>
		<Dial action="handle_management.php?cid=<?php echo $customerID?>&amp;client_name=<?php echo $clientName?>">7037724315</Dial>
<?php
	}else{
?>
		<Say>Please hold, your call is connecting management.</Say>
		<Dial action="handle_management.php?cid=<?php echo $customerID?>&amp;client_name=<?php echo $clientName?>">7037724315</Dial>
<?php
	}
	echo '</Response>';
?>