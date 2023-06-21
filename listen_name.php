<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	include_once("database.php");
	include_once("functions.php");

	$to = $_REQUEST['To'];
	$from = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$digits = $_REQUEST["Digits"];
	$name = $_REQUEST['SpeechResult'];
	$Confidence = $_REQUEST['Confidence'];
	$customerID = $_REQUEST['cid'];
	
	$sql = "insert into customers (name) values ('".$name."')";
	$res = mysqli_query($link,$sql);
	$customerID = mysqli_insert_id($link);

	echo '<Response>';
?>
		<Say>Welcome <?php echo $name;?></Say>
		<Gather action="listen_last_name.php?cid=<?php echo $customerID?>" input="speech" timeout="3">
			<Say>Please state your last name.</Say>
			<Pause length="3"/>
			<Say>Please state your last name.</Say>
		</Gather>
		<Say>We didn't receive any input. Goodbye!</Say>
		<Hangeup />
<?php
	echo '</Response>';
?>