<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	
	include_once("functions.php");
	$to = $_REQUEST['To'];
	$from = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$call_msg = $_REQUEST["call_msg"];

	echo '<Response>';
	if($call_msg){
		echo '<Say>'.$call_msg.'</Say>';
	}
	echo '</Response>';
?>