<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	include_once("database.php");
	include_once("functions.php");

	$to = $_REQUEST['To'];
	$from = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$digits = $_REQUEST["Digits"];
	$lastName = $_REQUEST['SpeechResult'];
	$Confidence = $_REQUEST['Confidence'];
	$customerID = $_REQUEST['cid'];
	
	$sql = "update customers set last_name='".$lastName."' where id='".$customerID."'";
	$res = mysqli_query($link,$sql);

	echo '<Response>';
?>
		<Gather action="save_number.php?cid=<?php echo $customerID?>" input="dtmf" timeout="3" numDigits="10">
			<Say>please enter your phone number.</Say>
			<Pause length="3"/>
			<Say>please enter your phone number.</Say>
		</Gather>
		<Say>We didn't receive any input. Goodbye!</Say>
		<Hangeup />
<?php
	echo '</Response>';
?>