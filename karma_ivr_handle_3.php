<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	include_once("database.php");
	include_once("functions.php");
	$to 	 = $_REQUEST['To'];
	$from    = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$digits  = $_REQUEST["Digits"];
	$userID  = $_REQUEST['user_id'];
	$recordingUrl = $_REQUEST['RecordingUrl'];
	$DialCallStatus = $_REQUEST['DialCallStatus'];

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
						'SMS Sent: thanks for your interest in working with us. please fill out the document below and email to hr@karmacgroupo.com.<br>https://drive.google.com/file/d/lqsA8qXpkd9YUwue5kDbvgZ9rBkQT0bTE/view?usp=sharing',
						'".$userID."',
						'".$to."',
						'".$from."'
					)";
		mysqli_query($link,$ins);
		
		$message = 'thanks for your interest in working with us. please fill out the document below and email to hr@karmacgroupo.com.<br>https://drive.google.com/file/d/lqsA8qXpkd9YUwue5kDbvgZ9rBkQT0bTE/view?usp=sharing';
		sendMessage($to, $from, $message);
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
						'SMS Sent: you can also text us what type of work do you do and in which states you want to work in, please also text us your license info, pictures of work, and insurance information.',
						'".$userID."',
						'".$to."',
						'".$from."'
					)";
		mysqli_query($link,$ins);
		$message = 'you can also text us what type of work do you do and in which states you want to work in, please also text us your license info, pictures of work, and insurance information.';
		sendMessage($to, $from, $message);
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
						'Call was connecting to the Tony ramos.',
						'".$userID."',
						'".$to."',
						'".$from."'
					)";
		mysqli_query($link,$ins);
?>		
		<Say>Please hold, your call is connecting to the tony ramos.</Say>
		<Dial action="handle_tony_ramos_call.php?user_id=<?php echo $userID?>" method="post">7712010715</Dial>
<?php
	}
	echo "</Response>";
?>