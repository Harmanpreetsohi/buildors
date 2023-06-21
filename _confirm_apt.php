<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	//mail("mirzaahsan42@gmail.com","incoming call",print_r($_REQUEST,true));
	
	$to = $_REQUEST['To'];
	$from = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];

	echo '<Response>';
?>
		<Gather action="handle_apt.php" input="dtmf" timeout="3" numDigits="1">
			<Say>Please press 1 to book your appointment.</Say>
			<Pause length="2"/>
			<Say>Please press 1 to book your appointment.</Say>
			<Pause length="2"/>
			<Say>Please press 1 to book your appointment.</Say>
		</Gather>
		<Say>We didn't receive any input. Goodbye!</Say>
<?php
	echo '</Response>';
?>