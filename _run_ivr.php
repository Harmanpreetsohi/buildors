<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	//mail("mirzaahsan42@gmail.com","in digits",print_r($_REQUEST,true));
	include_once("database.php");
	include_once("functions.php");
	$to = $_REQUEST['To'];
	$from = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	
	echo '<Response>';
		$sql = "select id,name from customers where (cell='".removeCountryCode($from)."') or (cell='".$from."')";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
?>
			<Gather action="old.php?cid=<?php echo $row['id']?>&amp;client_name=<?php echo $row['name']?>" input="speech dtmf" timeout="3" numDigits="1">
				<Say>Thanks for calling karma construction group., Hi <?php echo $row['name']?></Say>
				<Say>to speak your sales project manager, say project manager name or To speak client support, press 2.</Say>
				<Pause length="3" />
				<Say>to speak your sales project manager, say project manager name or To speak client support, press 2.</Say>
				<Pause length="3" />
				<Say>to speak your sales project manager, say project manager name or To speak client support, press 2.</Say>
			</Gather>
			<Say>We didn't receive any input. Goodbye!</Say>
			<Hangeup />
<?php
		}
		else{
?>
			
			<Gather action="listen_name.php" input="speech" timeout="3">
				<Say>You look like a new caller, please state your first name.</Say>
				<Pause length="3" />
				<Say>You look like a new caller, please state your first name.</Say>
			</Gather>
			<!--
			<Gather action="new.php" input="dtmf" timeout="3" numDigits="1">
				<Say>If you are a new client, press 1, vendor or installer, press 2, if you are seeking career oppertunities press 3, if this is regarding billing process, press 4, if this is an emergency press 5 now.</Say>
				<Pause length="3" />
				<Say>If you are a new client, press 1, vendor or installer, press 2, if you are seeking career oppertunities press 3, if this is regarding billing process, press 4, if this is an emergency press 5 now.</Say>
				<Pause length="3" />
				<Say>If you are a new client, press 1, vendor or installer, press 2, if you are seeking career oppertunities press 3, if this is regarding billing process, press 4, if this is an emergency press 5 now.</Say>
			</Gather>
			-->
			<Say>We didn't receive any input. Goodbye!</Say>
			<Hangeup />
<?php
		}
	echo '</Response>';
?>