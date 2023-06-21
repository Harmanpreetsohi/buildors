<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	include_once("database.php");
	include_once("functions.php");

	$to = $_REQUEST['To'];
	$from = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$digits = $_REQUEST['Digits'];
	$customerID = $_REQUEST['cid'];
		
	echo '<Response>';
	
	if($digits == 1){ // sending appointment link
		$text = "https://www.karmaconstructiongroup.com/booking-calendar/residential-construction-dmv?referral=service_list_widget";
		sendMessage($to, $from, $text);
?>
		<Say>Thank you, goodbye.</Say>
<?php
	}
	else if($digits == "*"){ // connecting to support
?>
		<Say>Please hold, your call is connecting to a live agent.</Say>
		<Dial>7037724315</Dial>
<?php
	}
	else{
?>
		<!--
		<Gather action="booking_online.php" input="dtmf" timeout="3" numDigits="1">
			<Say>You pressed wrong option, Please press 1 to book online or press 2 for talk to a live agent.</Say>
			<Pause length="2"/>
			<Say>Please press 1 to book online or press 2 for talk to a live agent.</Say>
		</Gather>
		<Say>We didn't receive any input. Goodbye!</Say>
		<Hangeup />
		-->
<?php
	}
	echo '</Response>';
?>