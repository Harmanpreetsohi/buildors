<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	//mail("mirzaahsan42@gmail.com","incoming call",print_r($_REQUEST,true));
	
	include_once("functions.php");
	$to = $_REQUEST['To'];
	$from = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$digits = $_REQUEST["Digits"];

	echo '<Response>';
	if($digits == 1){
		sendMessage($to, $from, "https://www.karmaconstructiongroup.com/construction-broker");
?>
		<Say>A link is sent to your number.</Say>
		<Say>Please hold on, your call is connecting to the jordan javier.</Say>
		<Dial action="call_jordan.php">2022941077</Dial>
<?php
	}else if($digits == 2){
?>
		<Say>Pleaes hold on, your call is connecting to the mariya kruseck.</Say>
		<Dial action="call_marlya.php">8454671850</Dial>
<?php
	}else{
		
	}
	echo '</Response>';
?>