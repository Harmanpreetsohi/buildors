<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
$_REQUEST['To'] = '+15855222765';
	$_REQUEST['From'] = '+15855222765';
	$_REQUEST['CallSid'] = '+15855222765';
	$f = fopen('ivr_log.txt', 'a+');


	fwrite($f, "\n\n\nHEllo\n\n\n");

	fwrite($f, "\n\n\n".$_REQUEST['To']."\n\n\n");

	fwrite($f, json_encode($_REQUEST));

	function _hasbranch($data,$branch,$step){
		foreach ($data as $key => $value) {
			if ($value->branch_id == $branch && $key >= $step) {
				return true;
			}
		}
		return false;
	}



	function get_response($step,$branch){
		global $action;
		
		for ($i=$step; $i < count($action); $i++) { 
			if ($action[$i]->branch_id == $branch){
				switch ($action[$i]->modal_id) {
					case 'callforwardactionModal':
						return '<Dial> '. $action[$i]->formdt->callforward_msg .' </Dial>';
						break;
					
					case 'smsactionModal':
						return '<Message>'.$action[$i]->formdt->sms_msg.'</Message>';
						break;
					
					case 'emailactionModal':
						sendPHPMailerEmail('subject','mirzaahsan42@gmail.com','info@buildors.com', $action[$i]->formdt->email_msg,'Builders');
						// sendPHPMailerEmail('subject','umarzaki1000@gmail.com','info@buildors.com', $action[$i]->formdt->email_msg,'Builders');
						return '<Redirect method="POST">https://buildors.com/api/ivr_call.php?step='. ($i+1) .'</Redirect>';
						break;
					
					case 'messageractionModal':
						return '<Say>'. $action[$i]->formdt->greeting_sms_msg .'</Say> <Redirect method="POST">https://buildors.com/api/ivr_call.php?step='. ($i+1) .'</Redirect>';
						break;
				}
			}
		}

		return '';
	}





	// header('content-type: text/xml');
	// print_r($_REQUEST);
	if (!isset($_GET['step'])) {
		$_GET['step']=0;
	}
	if (count(explode('ppp', $_GET['step'])) == 2) {
		$_GET['branch'] = explode('ppp', $_GET['step'])[1];
		$_GET['step'] = explode('ppp', $_GET['step'])[0];
	}
	if (!isset($_GET['branch'])) {
		$_GET['branch'] = '-1';
	}
	if ($_GET['branch'] == 'branch_-1') {
		$_GET['branch'] = '-1';
	}

	
	echo '<?xml version="1.0" encoding="UTF-8"?>';

	
	include_once("../database.php");
	include_once("../functions.php");
	$to = $_REQUEST['To'];
	$from = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$sql= "SELECT * FROM `ivr_rsponses` WHERE assigned_number = '".$to."'";
	global $link;
	$query = mysqli_query($link,$sql);
	// fwrite($f, "\n\n\nnum=".mysqli_num_rows($query)."\n\n\n");
    
    if (mysqli_num_rows($query) > 0) { 
    $ivr_response = mysqli_fetch_assoc($query); 
    $action = json_decode(str_replace("\n", "", $ivr_response['actionss']));

    
    if (!isset($action[$_GET['step']])) {
			?>
			<Response>
				<Say>Bye Bye</Say>
			</Response>
			<?php
			exit();
		}

	if (!_hasbranch($action,$_GET['branch'],$_GET['step'])) {
			?>
			<Response>
				<Say>Bye Bye</Say>
			</Response>
			<?php
			exit();
		}	

	if (isset($_REQUEST['Digits'])) {
			?>
			<Response>
				<Redirect method="POST">https://buildors.com/api/ivr_call.php?step=0pppbranch_<?= $_REQUEST['Digits']-1 ?></Redirect>
			</Response>
			<?php exit();
		}

	for ($i=$_GET['step']; $i < count($action); $i++) {

		if ($action[$i]->branch_id == $_GET['branch'] && $action[$i]->modal_id == 'callforwardactionModal') {
			?>
			<Response>
				<Dial> <?= $action[$i]->formdt->callforward_msg ?> </Dial>
			</Response>
			<?php
			break;
		}


		if ($action[$i]->branch_id == $_GET['branch'] && $action[$i]->modal_id == 'smsactionModal') {
			sendMessage($to, $from, $action[$i]->formdt->sms_msg);
			?>
			<Response>
				<Redirect method="POST">https://buildors.com/api/ivr_call.php?step=<?= $i+1 ?><?= (isset($_GET['branch'])) ? 'ppp'.$_GET['branch'] : '' ?></Redirect>
			</Response>
			<?php
			break;
		}

		if ($action[$i]->branch_id == $_GET['branch'] && $action[$i]->modal_id == 'emailactionModal') {
			// sendEmail('mirzaahsan42@gmail.com', $action[$i]->formdt->email_msg);
			// sendEmail('subject','mirzaahsan42@gmail.com', 'info@buildors.com','msg','buildors');
			sendPHPMailerEmail('subject','mirzaahsan42@gmail.com','info@buildors.com', $action[$i]->formdt->email_msg,'Builders');
			// sendPHPMailerEmail('subject','umarzaki1000@gmail.com','info@buildors.com', $action[$i]->formdt->email_msg,'Builders');
			?>
			<Response>
				<Redirect method="POST">https://buildors.com/api/ivr_call.php?step=<?= $i+1 ?><?= (isset($_GET['branch'])) ? 'ppp'.$_GET['branch'] : '' ?></Redirect>
			</Response>
			<?php
			break;
		}
		
		if ($action[$i]->branch_id == $_GET['branch'] && $action[$i]->modal_id == 'messageractionModal') {
			?>
			<Response>
				<Say><?= $action[$i]->formdt->greeting_sms_msg ?></Say>
				<Redirect method="POST">https://buildors.com/api/ivr_call.php?step=<?= $i+1 ?><?= (isset($_GET['branch'])) ? 'ppp'.$_GET['branch'] : '' ?></Redirect>
			</Response>
			<?php
			break;
		}
		if ($action[$i]->branch_id == $_GET['branch'] && $action[$i]->modal_id == 'conditionsactionModal') {
			?>
			<Response>
				<Gather input="dtmf" timeout="3" numDigits="1" />
				<?= get_response(0,'elsebranch_0') ?>
			</Response>
			<?php 
			break;
		}
	 ?>
    <?php  } } else {?>
			<Response>
				<Say>Thanks for your calling system error bye bye...</Say>
			</Response>
			<?php }
// print_r($action);
 ?>