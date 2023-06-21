<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	include_once("database.php");
	include_once("functions.php");
	$to 	 = $_REQUEST['To'];
	$from    = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$digits  = $_REQUEST["Digits"];
	$userID  = $_REQUEST['user_id'];

	echo "<Response>";

	if($digits == '1'){
		$ins = "insert into call_tracking
					(
						call_sid,
						action,
						user_id,
						to_number,
						from_number
					)
				values
					(
						'".$callSid."',
						'Caller pressed 1',
						'".$userID."',
						'".$to."',
						'".$from."'
					),
					(
						'".$callSid."',
						'For an automatic first available appointment booking link sent to this phone number please press 1 for the DMV area, 2 for north carolina and three for florida. if you like to speak with someone to setup an appointment, press 9 if you have questions regarding an estimate.',
						'".$userID."',
						'".$to."',
						'".$from."'
					)";
		mysqli_query($link,$ins);
?>
		<Gather action="karma_ivr_handle_1.php?user_id=<?php echo $userID?>" input="dtmf" numDigits="1" timeout="3" method="post">
			<Say>For an automatic first available appointment booking link sent to this phone number please press 1 for the DMV area, 2 for north carolina and three for florida. if you like to speak with someone to setup an appointment, press 9 if you have questions regarding an estimate.</Say>
			<Pause length="3"/>
			<Say>For an automatic first available appointment booking link sent to this phone number please press 1 for the DMV area, 2 for north carolina and three for florida. if you like to speak with someone to setup an appointment, press 9 if you have questions regarding an estimate.</Say>
			<Pause length="3"/>
			<Say>For an automatic first available appointment booking link sent to this phone number please press 1 for the DMV area, 2 for north carolina and three for florida. if you like to speak with someone to setup an appointment, press 9 if you have questions regarding an estimate.</Say>
		</Gather>
		<Say>We didn't receive any input. Goodbye!</Say>
		<Hangup />
<?php
	}
	else if($digits == '2'){
		$ins = "insert into call_tracking
					(
						call_sid,
						action,
						user_id,
						to_number,
						from_number
					)
				values
					(
						'".$callSid."',
						'Caller pressed 2',
						'".$userID."',
						'".$to."',
						'".$from."'
					),
					(
						'".$callSid."',
						'Call was connecting to the tony ramos.',
						'".$userID."',
						'".$to."',
						'".$from."'
					)";
		mysqli_query($link,$ins);
?>
		<Say>Please hold, your call is connecting to the tony ramos.</Say>
		<Dial action="handle_tony_ramos_call.php?user_id=<?php echo $userID?>" record="record-from-ringing-dual" method="post">7712010715</Dial>
<?php
	}
	else if($digits == '3'){
		$ins = "insert into call_tracking
					(
						call_sid,
						action,
						user_id,
						to_number,
						from_number
					)
				values
					(
						'".$callSid."',
						'Caller pressed 3',
						'".$userID."',
						'".$to."',
						'".$from."'
					),
					(
						'".$callSid."',
						'for the quickest answer, a hiring application form to be sent to this number, please press 1. please press 2 to talk to a hiring person.',
						'".$userID."',
						'".$to."',
						'".$from."'
					)";
		mysqli_query($link,$ins);
?>
		<Gather action="karma_ivr_handle_3.php?user_id=<?php echo $userID?>" input="dtmf" numDigits="1" timeout="3" method="post">
			<Say>for the quickest answer, a hiring application form to be sent to this number, please press 1. please press 2 to talk to a hiring person.</Say>
			<Pause length="3"/>
			<Say>for the quickest answer, a hiring application form to be sent to this number, please press 1. please press 2 to talk to a hiring person.</Say>
			<Pause length="3"/>
			<Say>for the quickest answer, a hiring application form to be sent to this number, please press 1. please press 2 to talk to a hiring person.</Say>
		</Gather>
		<Say>We didn't receive any input. Goodbye!</Say>
		<Hangup />
<?php
	}
	else if($digits == '4'){
		$ins = "insert into call_tracking
					(
						call_sid,
						action,
						user_id,
						to_number,
						from_number
					)
				values
					(
						'".$callSid."',
						'Caller pressed 4',
						'".$userID."',
						'".$to."',
						'".$from."'
					),
					(
						'".$callSid."',
						'Call was connecting to the peggy.',
						'".$userID."',
						'".$to."',
						'".$from."'
					)";
		mysqli_query($link,$ins);
?>		
		<Say>Your call is redirecting to the peggy.</Say>
		<Dial action="call_peggy.php?user_id=<?php echo $userID?>" record="record-from-ringing-dual">2403052448</Dial>
<?php
	}
	else if($digits == '5'){
		$ins = "insert into call_tracking
					(
						call_sid,
						action,
						user_id,
						to_number,
						from_number
					)
				values
					(
						'".$callSid."',
						'Caller pressed 5',
						'".$userID."',
						'".$to."',
						'".$from."'
					),
					(
						'".$callSid."',
						'Call was connecting to the kat power.',
						'".$userID."',
						'".$to."',
						'".$from."'
					)";
		mysqli_query($link,$ins);
?>
		<Say>Please hold, your call is connecting to the kat power.</Say>
		<Dial action="karma_ivr_handle_call.php?user_id=<?php echo $userID?>" method="post">7037724315</Dial>
<?php
	}
	echo "</Response>";
?>