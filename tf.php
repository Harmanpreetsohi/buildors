<?php
	
	// header('content-type: text/xml');
	// print_r($_REQUEST);
	function _hasbranch($data,$branch,$step){
		foreach ($data as $key => $value) {
			if ($value->branch_id == $branch && $key >= $step) {
				return true;
			}
		}
		return false;
	}
	if (!isset($_GET['step'])) {
		$_GET['step']=0;
	}
	if (count(explode('_', $_GET['step'])) == 2) {
		$_GET['branch'] = 'branch_'.explode('_', $_GET['step'])[1];
		$_GET['step'] = explode('_', $_GET['step'])[0];
	}
	if (!isset($_GET['branch'])) {
		$_GET['branch'] = '-1';
	}
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	include_once("../database.php");
	include_once("../functions.php");
	$to = $_REQUEST['To'];
	$from = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$sql= "SELECT * FROM `ivr_rsponses` ";
	global $link;
	$query = mysqli_query($link,$sql);
	// $_REQUEST['Digits'] = '1';
    $ivr_response = mysqli_fetch_assoc($query);
    if (isset($ivr_response['id'])) { 
    $action = json_decode($ivr_response['actionss']);

    print_r($action);
	exit();
    
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
				<Redirect method="POST">https://buildors.com/api/ivr_call.php?step=0_<?= ((int)$_REQUEST['Digits']-1) ?></Redirect>
			</Response>
			<?php exit();
		}

	for ($i=$_GET['step']; $i < count($action); $i++) {

		if ($action[$i]->branch_id == $_GET['branch'] && $action[$i]->modal_id == 'smsactionModal') {
			sendMessage($to, $from, $action[$i]->formdt->sms_msg);
			?>
			<Response>
				<Redirect method="POST">https://buildors.com/api/ivr_call.php?step=<?= $i+1 ?><?= (isset($_GET['branch'])) ? '_'.$_GET['branch'] : '' ?></Redirect>
			</Response>
			<?php
			break;
		}

		if ($action[$i]->branch_id == $_GET['branch'] && $action[$i]->modal_id == 'emailactionModal') {
			sendMail('mirzaahsan42@gmail.com', $action[$i]->formdt->email_msg);
			?>
			<Response>
				<Redirect method="POST">https://buildors.com/api/ivr_call.php?step=<?= $i+1 ?><?= (isset($_GET['branch'])) ? '_'.$_GET['branch'] : '' ?></Redirect>
			</Response>
			<?php
			break;
		}
		
		if ($action[$i]->branch_id == $_GET['branch'] && $action[$i]->modal_id == 'messageractionModal') {
			?>
			<Response>
				<Say><?= $action[$i]->formdt->greeting_sms_msg ?></Say>
				<Redirect method="POST">https://buildors.com/api/ivr_call.php?step=<?= $i+1 ?><?= (isset($_GET['branch'])) ? '_'.$_GET['branch'] : '' ?></Redirect>
			</Response>
			<?php
			break;
		}
		if ($action[$i]->branch_id == $_GET['branch'] && $action[$i]->modal_id == 'conditionsactionModal') {
			?>
			<Response>
				<Gather/>
				<Say>No Option Selected Bye Bye</Say>
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