<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	//mail("mirzaahsan42@gmail.com","virtual online",print_r($_REQUEST,true));
	include_once("database.php");
	include_once("functions.php");
	$to = $_REQUEST['To'];
	$from = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$digits = $_REQUEST['Digits'];
	$cid = $_REQUEST['cid'];

	echo '<Response>';
		if($digits == 1){
			$sql = "update appointments set
						virtual_online='1'
					where
						id='".$cid."'";
			mysqli_query($link,$sql);
?>
			<Say>You have successfully booked a virtual online appointment, thanks.</Say>
			<Hangeup />
<?php
		}else if($digits == 2){
			sendMessage($to, $from, "https://www.karmaconstructiongroup.com/booking-calendar/residential-construction-dmv?referral=service_list_widget");
			
			$sql = "update appointments set
						onsite='1'
					where
						id='".$cid."'";
			mysqli_query($link,$sql);
?>
			<Say>A consultation link is sent to your number, thanks.</Say>
			<Hangeup />
<?php
		}else{
?>
			<Gather action="virtual_online.php" input="dtmf" timeout="3" numDigits="1">
				<Say>You pressed wrong option, Press 1 for a virtual online appointment, or press 2 for an onsite consultation text link to phone number.</Say>
				<Pause length="2"/>
				<Say>Press 1 for a virtual online appointment, or press 2 for an onsite consultation text link to phone number.</Say>
			</Gather>
			<Say>We didn't receive any input. Goodbye!</Say>
			<Hangeup />
<?php
		}
	echo '</Response>';
?>