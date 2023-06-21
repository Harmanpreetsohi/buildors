<?php
	header('content-type: text/xml');
	// print_r($_REQUEST);
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	include_once("database.php");
	include_once("functions.php");
	$to = $_REQUEST['To'];
	// $to = str_replace('+', '', $to);
	$from = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$query = mysqli_query($link,"SELECT * FROM `ivr_rsponses` WHERE assigned_number = '".$to."%'");
    if(mysqli_num_rows($query) > 0){
    	// echo "string";
    	$ivr_response = mysqli_fetch_assoc($query);
    }
    if (isset($ivr_response)) {  ?>
    	<Response>
			<Say>Welcome to Karma Constructions</Say>
		</Response>
    <?php } else {?>
<Response>
	<Say>Thanks for your calling system error bye bye...</Say>
</Response>
<?php } ?>