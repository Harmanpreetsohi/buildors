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
	if($digits != ""){
		if(strlen($digits) != 10){
?>			
			<Say>Provided phone number is not correct, please provide 10 digits phone number without entering country code.</Say>
			<Redirect method="POST"><?php echo getServerURL()?>/save_number.php</Redirect>
<?php
		}
		else{
			$sql = "update customers set cell='".$digits."' where id='".$customerID."'";
			mysqli_query($link,$sql);
?>
			<Gather action="new.php" input="dtmf" timeout="3" numDigits="1">
				<Say>If you are a new client, press 1, vendor or installer, press 2, if you are seeking career oppertunities press 3, if this is regarding billing process, press 4, if this is an emergency press 5 now.</Say>
				<Pause length="3" />
				<Say>If you are a new client, press 1, vendor or installer, press 2, if you are seeking career oppertunities press 3, if this is regarding billing process, press 4, if this is an emergency press 5 now.</Say>
				<Pause length="3" />
				<Say>If you are a new client, press 1, vendor or installer, press 2, if you are seeking career oppertunities press 3, if this is regarding billing process, press 4, if this is an emergency press 5 now.</Say>
			</Gather>
<?php
		}
	}else{
?>
		<Gather action="save_number.php?cid=<?php echo $customerID?>" input="dtmf" timeout="3" numDigits="10">
			<Say>please enter your phone number.</Say>
			<Pause length="3"/>
			<Say>please enter your phone number.</Say>
		</Gather>
		<Say>We didn't receive any input. Goodbye!</Say>
		<Hangeup />
<?php
	}
	echo '</Response>';
?>