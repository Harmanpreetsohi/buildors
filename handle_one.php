<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	include_once("database.php");
	include_once("functions.php");
	$to = $_REQUEST['To'];
	$from = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$digits = $_REQUEST["Digits"];
	
	echo '<Response>';
		if($digits == '1'){
			$bookingLink = "https://www.karmaconstructiongroup.com/booking-calendar/residential-construction-dmv?referral=service_list_widget";
			sendMessage($to, $from, $bookingLink);
?>
			<Say>Booking link is sent to your number, goodbye.</Say>
			<Hangup />
<?php
		}else if($digits == '2'){
?>
			<Say>Please wait, your call is connecting to a live support agent.</Say>
			<Dial action="end_call.php">7037724315</Dial>
<?php
		}
	echo '</Response>';
?>