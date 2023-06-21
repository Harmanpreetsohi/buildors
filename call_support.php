<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	//mail("mirzaahsan42@gmail.com","handle call",print_r($_REQUEST,true));

	$DialCallStatus = $_REQUEST['DialCallStatus'];
	
	if($DialCallStatus == 'no-answer'){
?>
		<Response>
			<Say>Your call is redirecting to the Peggy jorgenson</Say>
			<Dial action="call_peggy.php">2403052448</Dial>
		</Response>
<?php
	}
?>