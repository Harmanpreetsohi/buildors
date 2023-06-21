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
?>
		<Say>Your call is not answered, will update you soon, goodbye.</Say>
		<Hangup />
<?php
	}else{
?>
		<Say>thanks for calling, goodbye.</Say>
		<Hangup />
<?php
	}
	echo '</Response>';
?>