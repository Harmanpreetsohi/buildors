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
?>
		<Say>You will receive a payment link on your number.</Say>
		<Say>Pleaes hold on, your call is connecting to the peggy jorgenson.</Say>
		<Dial action="handle_call_3.php">2403052448</Dial>
<?php
	}else if($digits == 2){
?>
		<Say>Pleaes hold on, your call is connecting to the peggy jorgenson.</Say>
		<Dial action="handle_call_3.php">2403052448</Dial>
<?php
	}else{
		
	}
	echo '</Response>';
?>