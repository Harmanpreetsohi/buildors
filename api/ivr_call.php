<?php
	if (isset($_GET['debug'])) {
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
		$_REQUEST['To'] = '+12027987663';
		$_REQUEST['From'] = '+12027987663';
		$_REQUEST['CallSid'] = '+12027987663';
	}


	if (!isset($_GET['step'])) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://api.twilio.com/2010-04-01/Accounts/ACb797881d79a639eefb0a266f275b895b/Calls/'.$_REQUEST['CallSid'].'/Recordings.json');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "RecordingStatusCallback=https://buildors.com/api/recording.php&RecordingStatusCallbackEvent=in-progress completed absent");
		curl_setopt($ch, CURLOPT_USERPWD, 'ACb797881d79a639eefb0a266f275b895b' . ':' . 'e0419a64126bc4424d9b367d809a9aa0');

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
	}



	$f = fopen('ivr_log.txt', 'a+');


	fwrite($f, "\n\n\nHEllo\n\n\n");

	fwrite($f, "\n\n\n".$_REQUEST['To']."\n\n\n");

	if (isset($result)) {
		fwrite($f, $result);
	}


	fwrite($f, json_encode($_REQUEST));

	function _hasbranch($data,$branch,$step){
		foreach ($data as $key => $value) {
			if (!isset($value->menu)) {
				$value->menu = 1;
			}
			if ($value->branch_id == $branch && $value->menu == $_GET['menu'] && $key >= $step) {
				return true;
			}
		}
		return false;
	}



	function get_response($step,$branch){
		global $action;
		
		for ($i=$step; $i < count($action); $i++) { 
			if (!isset($action[$i]->menu)) {
				$action[$i]->menu = 1;
			}
			if ($action[$i]->branch_id == $branch && $action[$i]->menu == $_GET['menu']){
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




	if (!isset($_GET['debug'])) {
	header('content-type: text/xml');
	}
	// print_r($_REQUEST);
	
	if (!isset($_GET['step'])) {
		$_GET['step']=0;
	}
	$input = $_GET['step'];
	if (count(explode('ppp', $input)) > 1) {
		$_GET['branch'] = explode('ppp', $input)[1];
		$_GET['step'] = explode('ppp', $input)[0];
	}
	
	if (!isset($_GET['branch'])) {
		$_GET['branch'] = '-1';
	}
	if ($_GET['branch'] == 'branch_-1') {
		$_GET['branch'] = '-1';
	}

	if (isset(explode('ppp', $input)[2])) {
		$_GET['menu'] = explode('ppp', $input)[2];
	}

	if (!isset($_GET['menu'])) {
		$_GET['menu'] = 1;
	}


	if (isset($_GET['debug'])) { print_r(explode('ppp', $input)); print_r([$_GET['step'],$_GET['branch'],$_GET['menu']]); }


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
				<Redirect method="POST">https://buildors.com/api/ivr_call.php?step=0pppbranch_<?= $_REQUEST['Digits']-1 ?>ppp<?= ++$_GET['menu'] ?></Redirect>
			</Response>
			<?php exit();
		}

	for ($i=$_GET['step']; $i < count($action); $i++) {

		if (!isset($action[$i]->menu)) {
			$action[$i]->menu = 1;
		}

		if ($action[$i]->branch_id == $_GET['branch'] && $action[$i]->menu == $_GET['menu'] && $action[$i]->modal_id == 'callactionModal') {
			?>
			<Response>
				<Say>Please leave a message at the beep. Press the star key when finished.</Say>
				<Record action="handle_recording.php" method="post" timeout="0" finishOnKey="*"/>
				<Say>I did not receive a recording, goodbye.</Say>
				<Hangup />
			</Response>
			<?php
			break;
		}


		if ($action[$i]->branch_id == $_GET['branch'] && $action[$i]->menu == $_GET['menu'] && $action[$i]->modal_id == 'callforwardactionModal') {
			?>
			<Response>
				<Dial> <?= $action[$i]->formdt->callforward_msg ?> </Dial>
				<Redirect method="POST">https://buildors.com/api/ivr_call.php?step=<?= $i+1 ?><?= (isset($_GET['branch'])) ? 'ppp'.$_GET['branch'] : '' ?><?= (isset($_GET['menu'])) ? 'ppp'.$_GET['menu'] : '' ?></Redirect>
			</Response>
			<?php
			break;
		}


		if ($action[$i]->branch_id == $_GET['branch'] && $action[$i]->menu == $_GET['menu'] &&  $action[$i]->modal_id == 'smsactionModal') {
			// sendMessage($to, $from, $action[$i]->formdt->sms_msg);
			?>
			<Response>
				<Message>
			        <?= $action[$i]->formdt->sms_msg ?>
			    </Message>
				<Redirect method="POST">https://buildors.com/api/ivr_call.php?step=<?= $i+1 ?><?= (isset($_GET['branch'])) ? 'ppp'.$_GET['branch'] : '' ?><?= (isset($_GET['menu'])) ? 'ppp'.$_GET['menu'] : '' ?></Redirect>
			</Response>
			<?php
			break;
		}

		if ($action[$i]->branch_id == $_GET['branch'] && $action[$i]->menu == $_GET['menu'] &&  $action[$i]->modal_id == 'emailactionModal') {
			// sendEmail('mirzaahsan42@gmail.com', $action[$i]->formdt->email_msg);
			// sendEmail('subject','mirzaahsan42@gmail.com', 'info@buildors.com','msg','buildors');
			sendPHPMailerEmail('subject','mirzaahsan42@gmail.com','info@buildors.com', $action[$i]->formdt->email_msg,'Builders');
			// sendPHPMailerEmail('subject','umarzaki1000@gmail.com','info@buildors.com', $action[$i]->formdt->email_msg,'Builders');
			?>
			<Response>
				<Redirect method="POST">https://buildors.com/api/ivr_call.php?step=<?= $i+1 ?><?= (isset($_GET['branch'])) ? 'ppp'.$_GET['branch'] : '' ?><?= (isset($_GET['menu'])) ? 'ppp'.$_GET['menu'] : '' ?></Redirect>
			</Response>
			<?php
			break;
		}
		
		if ($action[$i]->branch_id == $_GET['branch'] && $action[$i]->menu == $_GET['menu'] &&  $action[$i]->modal_id == 'messageractionModal') {
			?>
			<Response>
				<Say><?= $action[$i]->formdt->greeting_sms_msg ?></Say>
				<Redirect method="POST">https://buildors.com/api/ivr_call.php?step=<?= $i+1 ?><?= (isset($_GET['branch'])) ? 'ppp'.$_GET['branch'] : '' ?><?= (isset($_GET['menu'])) ? 'ppp'.$_GET['menu'] : '' ?></Redirect>
			</Response>
			<?php
			break;
		}
		if ($action[$i]->branch_id == $_GET['branch'] && $action[$i]->menu == $_GET['menu'] &&  $action[$i]->modal_id == 'conditionsactionModal') {
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