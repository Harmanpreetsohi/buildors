<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	include_once("database.php");
	include_once("functions.php");

	$to = $_REQUEST['To'];
	$from = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$digits = $_REQUEST["Digits"];
	$designation = $_REQUEST['SpeechResult'];
	$Confidence = $_REQUEST['Confidence'];
	$customerID = $_REQUEST['cid'];
	
	$sql = "update customers set designation='".$designation."' where id='".$customerID."'";
	mysqli_query($link,$sql);

	echo '<Response>';
?>
		<Say>Welcome <?php echo $designation;?></Say>
		<Gather action="booking_apt.php?cid=<?php echo $customerID?>" input="dtmf" timeout="3" numDigits="1">
			<Say>Press 1 to book an appointment for a consultation or 2 for a specific department.</Say>
			<Pause length="3"/>
			<Say>Press 1 to book an appointment for a consultation or 2 for a specific department.</Say>
			<Pause length="3"/>
			<Say>Press 1 to book an appointment for a consultation or 2 for a specific department.</Say>
		</Gather>
		<Say>We didn't receive any input. Goodbye!</Say>
		<Hangeup />
<?php
	echo '</Response>';
?>