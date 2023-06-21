<?php
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	include_once("database.php");
	include_once("functions.php");
	$to = $_REQUEST['To'];
	$from = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$digits = $_REQUEST["Digits"];
	$clientName = $_REQUEST['client_name'];
	$customerID = $_REQUEST['cid'];
	$projectManagerName = str_replace(".","",$_REQUEST['SpeechResult']);
	$Confidence = $_REQUEST['Confidence'];

	echo '<Response>';
		if(trim($projectManagerName) != ''){
			$sql = "select * from staff where name like '%".$projectManagerName."%'";
			$res = mysqli_query($link,$sql);
			if(mysqli_num_rows($res)){
				$row = mysqli_fetch_assoc($res);
				$projectManagerName = $row['name'];
?>
				<Say>Please hold, your call is connecting to the <?php echo $projectManagerName?>.</Say>
				<Dial action="handle_project_manager.php?client_name=<?php echo $clientName?>&amp;cid=<?php echo $customerID?>">2022941077</Dial>
<?php
			}else{
?>
				<Say>Please hold, your call is connecting to the client support.</Say>
				<Dial action="handle_support.php?client_name=<?php echo $clientName?>&amp;cid=<?php echo $customerID?>">7037724315</Dial>
<?php
			}
		}
		if(($digits != '') && ($digits == '2')){
?>
			<Say>Please hold, your call is connecting to the call client support.</Say>
			<Dial action="handle_support.php?client_name=<?php echo $clientName?>&amp;cid=<?php echo $customerID?>">7037724315</Dial>
<?php
		}
	echo '</Response>';
?>