<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	include_once("database.php");
	include_once("functions.php");
	$to = $_REQUEST['To'];
	$from = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$digits = $_REQUEST["Digits"];
	$sql = "insert into customers (cell) values ('".$from."')";
	$res = mysqli_query($link,$sql);

	echo '<Response>';
		if($digits == '1'){
?>
			<Gather action="handle_one.php" input="dtmf" timeout="3" numDigits="1">
				<Say>for an automatic booking link sent to this number, press 1, for a live agent press 2.</Say>
				<Pause length="3" />
				<Say>for an automatic booking link sent to this number, press 1, for a live agent press 2.</Say>
				<Pause length="3" />
				<Say>for an automatic booking link sent to this number, press 1, for a live agent press 2.</Say>
			</Gather>
<?php
		}else if($digits == '2'){
?>
			<Gather action="handle_two.php" input="dtmf" timeout="3" numDigits="1">
				<Say>For an application link sent to this number, press 1. for an H R agent, press 2.</Say>
				<Pause length="3" />
				<Say>For an application link sent to this number, press 1. for an H R agent, press 2.</Say>
				<Pause length="3" />
				<Say>For an application link sent to this number, press 1. for an H R agent, press 2.</Say>
			</Gather>
			<Hangup />
<?php
		}else if($digits == '3'){
			
		}else if($digits == '4'){
			
		}else if($digits == '5'){
			
		}
	echo '</Response>';
?>