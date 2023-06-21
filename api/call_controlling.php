<?php
	@session_start();
	header("content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; 

	include_once("../database.php");
	include_once("../functions.php");
    
	$to = $_REQUEST['To'];
	$from = $_REQUEST['From'];
	$callSid = $_REQUEST['CallSid'];
	$sql = "select * from ivr_rsponses where assigned_number='".$to."' limit 1";
	$res = mysqli_query($link, $sql)or die(mysqli_error($link));
	$result = mysqli_fetch_assoc($res);
	if(mysqli_num_rows($res)==0){
		echo("<Response><Say voice='alice' language='en'>Sorry, You may dialed wrong number, or your selected IVR is in disabled state maybe. goodbye.</Say></Response>");
	}
	else{
	   
		$ivrID = $result['id'];
        
		$sql = "select name from ivr_rsponses where id='$ivrID'";
        $query = mysqli_query($link, $sql);
        
        $ivr = mysqli_fetch_assoc($query);
        $ivr_title = $ivr['name'];
        
		$voice = 'woman';
		$accent= 'en';
		$digits = 'no';
		$next_actions = [];

		// $data  = json_decode($result['actionss'],true);
		$data  = json_decode($result['treeData'],true);
		// print_r($data);die;
		$twilioNumber = $result['assigned_number'];

		if((isset($_REQUEST['nextmenu']) && ($_REQUEST['nextmenu']!="")) && (isset($_REQUEST['Digits']) && ($_REQUEST['Digits']!=""))){
			$menu = $_REQUEST['nextmenu'];
			$digits = $_REQUEST['Digits'];
			$data = findNextMenu($data,$menu,$digits);
			// print_r($data);die;
		}

		echo "<Response>";
			playIvr($data,$digits,$twilioNumber,$to,$from,$callSid,$voice,$accent,$ivrID,$ivr_title);
		echo "</Response>";
	}
    function playIvr($data,$digits,$twilioNumber,$to="",$from="",$callSid="",$voice='woman',$accent='en',$ivrID=0,$ivr_title=''){
        global $isRecord;
          
		$accountSid = "ACb797881d79a639eefb0a266f275b895b";
		$authToken = "e0419a64126bc4424d9b367d809a9aa0";
		$from_email = 'admin@'.$_SERVER['SERVER_NAME'];

		// if($digits=="no"){
		// 	$key = @key($data);
   		// }else{
		// 	$key = findNextMenu($data,$digits);
		// 	if($key==""){
		// 		$key = findOrderInfo($data,$digits);
		// 	}
		// }
		// print_r($data);die;
		foreach($data as $action){
			switch($action['modal_id']){
				
				case "smsactionModal":{

						$sms_text = $action["formdt"]["sms_msg"];
				
					try{
						if(isset($action["media"]) && $action["media"]!='')
						{
							$image = getServerURL().'file_upload/'.$action["media"];
							sendMessage($twilioNumber, $from, $sms_text, $image);
						}else{
							sendMessage($twilioNumber, $from, $sms_text);
					}
					}
					catch (Exception $e) {
						//echo '<Say>'.$e->message().'</Say>';
						echo '<Say>Unable to communicate with twilio</Say>';
					}
				}
				break;
				
				case "callforwardactionModal":{
				
					$record='';
					$callerID = $to;
					
					echo '<Dial callerId="'.$callerID.'">';
						echo '<Number>'.$action["formdt"]["callforward_msg"].'</Number>';
					echo '</Dial>';

				}
				break;
				
				case "greetingactionModal":{
					
					$dummyurl=	$action["formdt"]["greeting_media"];
					$dummyurl = str_replace(' ', '%20', $dummyurl);
					if($dummyurl)
						echo "<Play>https://buildors.com/".$dummyurl."</Play>";

				}
				break;
				
				case "conditionsactionModal":{
					// echo("<pre>");print_r($action);die;
					$conditional_actionds = getParentChild($data,$action['parent_id']);
				
					$menuGreetings = $conditional_actionds[0];
					$nextMenu = $conditional_actionds[1];
					// if(isset($action['children'])){
					// 	$nextMenu = $action['id'];
					// }
					echo '<Gather numDigits="1" action="call_controlling.php?To='.urlencode($to).'&amp;From='.urlencode($from).'&amp;nextmenu='.$nextMenu.'" method="POST" timeout="5">';
					echo '<Say loop="2" voice="'.$voice.'" language="'.$accent.'">'.$menuGreetings.'</Say>';	
					echo '</Gather>';
					echo "<Say voice='".$voice."' language='".$accent."'>We didn't receive any input. Goodbye!</Say>";
				}
				break;

				case "editconditionsactionModal":{
					// echo("<pre>");print_r($action);die;
					$conditional_actionds = getParentChild($data,$action['parent_id']);
					// echo("<pre>");print_r($conditional_actionds);die;
					
					$menuGreetings = $conditional_actionds[0];
					$nextMenu = $conditional_actionds[1];
					// if(isset($action['children'])){
					// 	$nextMenu = $action['id'];
					// }
					echo '<Gather numDigits="1" action="call_controlling.php?To='.urlencode($to).'&amp;From='.urlencode($from).'&amp;nextmenu='.$nextMenu.'" method="POST" timeout="5">';
					echo '<Say loop="2" voice="'.$voice.'" language="'.$accent.'">'.$menuGreetings.'</Say>';	
					echo '</Gather>';
					echo "<Say voice='".$voice."' language='".$accent."'>We didn't receive any input. Goodbye!</Say>";
				}
				break;

				case "hangup":
				{
					@session_destroy();
					echo "<Hangup/>";
				}
				break;
				
				default:{
					@session_destroy();
					echo "<Hangup/>";
				}
				break;
			}
			if($action['modal_id'] != "conditionsactionModal" && $action['modal_id'] != "editconditionsactionModal"){
				if(isset($action['children']) && $action['children'] != ''){
					$nextMenu = $action['children'];
					playIvr($nextMenu,'no',$twilioNumber,$to,$from,$callSid,$voice,$accent,$ivrID,$ivr_title);		
				}
			}else{
				break;
			}
		}
	}
	function findNextMenu($array,$next_menu='',$digit='no'){
		global $next_actions;
		$ddd = explode(',',$next_menu);
		// print_r($ddd);die;

		foreach($array as $action){
			if(in_array($action['id'],$ddd) && $digit == $action['value']){
				$next_actions = $action['children'];
				break;
			}
			elseif(isset($action['children']) && $action['children'] != ''){
				findNextMenu($action['children'],$next_menu,$digit);
			}
		}
		// print_r($next_actions);die;
		return $next_actions;
	}
	function getParentChild($data,$parent_id){
		// echo("<pre>");print_r($data);die;
		$conditional_action = [];
		$nexttmenu = '';
		$menuGreetings = "Press ";
		foreach($data as $dt){
			if($dt['parent_id'] == $parent_id && ($dt['modal_id'] == "conditionsactionModal" || $dt['modal_id'] == "editconditionsactionModal")){
				// echo("<pre>");print_r($dt);die;
				// array_push($conditional_action,$dt);
				$menuGreetings .= $dt["value"].",";
				$nexttmenu .= $dt["id"].",";
			}
		}
		$menuGreetings = substr($menuGreetings,0,strlen($menuGreetings)-1);
		$nexttmenu = substr($nexttmenu,0,strlen($nexttmenu)-1);
		$menuGreetings .= " for more details";
		$menuGreetings = str_replace(","," or ",$menuGreetings);
		array_push($conditional_action,$menuGreetings);
		array_push($conditional_action,$nexttmenu);
		// echo("<pre>");print_r($conditional_action);die;
		// return $conditional_action;
		return $conditional_action;
	}
	function filterData($string){
	    return html_entity_decode($string,ENT_QUOTES);
	}
    if(isset($pageTitle) && $pageTitle=="login"){
        $appUrl = str_replace(array("http://","https://"),"",getServerURL());
        str_pos($appUrl);
    }
?>