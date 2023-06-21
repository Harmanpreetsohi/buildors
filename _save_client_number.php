<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	//mail("mirzaahsan42@gmail.com","Saving number",print_r($_REQUEST,true));
	
	$to = $_REQUEST['To'];
	$from = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$phoneNumber = strlen($_REQUEST['Digits']);
	
	echo '<Response>';
	if($phoneNumber != 10){
?>
		<Say>Your provided phone number is not correct, please provide 10 digits phone number without country code.</Say>
		<Say>Your provided phone number have <?php echo $phoneNumber?> digits.</Say>
		<Redirect method="POST">https://aawebconsultants.com/ivr/handle_apt.php</Redirect>
<?php
	}else{
?>
		<Say>Thanks for providing your number, you will receive appointment link on this phone number soon.</Say>
<?php
	}
	echo '</Response>';
?>  