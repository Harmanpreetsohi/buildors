<?php
	include_once("database.php");
	include_once("functions.php");
	$sid   = 'ACb797881d79a639eefb0a266f275b895b';
	$token = 'e0419a64126bc4424d9b367d809a9aa0';
	date_default_timezone_set("US/Eastern");
	//die("die");
	
	if($_REQUEST['limit']!='')
		$sql = "select * from queued_msgs order by id asc limit ".$_REQUEST['limit'];
	else
		$sql = "select * from queued_msgs order by id asc limit 240";
	$res = mysqli_query($link,$sql);
	if(mysqli_num_rows($res)){
		$isSent = 'false';
		$smsSid = '';
		while($row = mysqli_fetch_assoc($res)){
			$to = $row['to_number'];
			$from = $row['from_number'];
			$message = $row['message'];
			$media = $row['media'];
			$userID = $row['user_id'];
			$url  = "https://api.twilio.com/2010-04-01/Accounts/".$sid."/Messages.json";
			$data = array (
				'From' => $from,
				'To' => $to,
				'Body' => DBout($message)
			);
			if(trim($media)!=''){$data["MediaUrl"] = $media;}
			$post = http_build_query($data);
			$x = curl_init($url);
			curl_setopt($x, CURLOPT_POST, true);
			curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($x, CURLOPT_USERPWD, $sid.":".$token);
			curl_setopt($x, CURLOPT_POSTFIELDS, $data);
			$response = json_decode(curl_exec($x),true);
			curl_close($x);
			mysqli_query($link,"delete from queued_msgs where id='".$row['id']."'");
			if($response['code']!=''){
				$smsSid = $response['message'];
			}else{
				$isSent = 'true';
				$smsSid = $response['sid'];
			}
			$ins = "insert into conversations
						(
							customer_number,
							user_id,
							to_number,
							from_number,
							message,
							media,
							is_sent,
							sms_sid,
							direction
						)
					values
						(
							'".removeCountryCode($to)."',
							'".$userID."',
							'".removeCountryCode($to)."',
							'".removeCountryCode($from)."',
							'".DBin($message)."',
							'".$media."',
							'".$isSent."',
							'".$smsSid."',
							'out'
						)";
			mysqli_query($link,$ins);
			
		}
	}
	else{
		echo 'No pending queued message found.<br>';
	}
	
	/************************* Scheduler Handling ***************************/
	$sqlScheduler = "select * from schedulers order by id asc";
	$resScheduler = mysqli_query($link,$sqlScheduler);
	$today = strtolower(date("l"));
	$timeNow = date("H:i");
	if(mysqli_num_rows($resScheduler)){
		$isSent = 'false';
		while($rowScheduler = mysqli_fetch_assoc($resScheduler)){
			
			$startDate = $rowScheduler["start_date"];
			$endDate   = $rowScheduler['end_date'];
			$isRecurring = $rowScheduler['is_recurring'];
			$userID = $rowScheduler['user_id'];
			$workflow_id = $rowScheduler['workflow_id'];
			$message = DBout($rowScheduler['message']);
			$day = strtolower(date("l",strtotime($startDate)));
			$attendies = json_decode($rowScheduler["attendies"],true);
			
			$twilioNumbers = getRandomTwilioNumbers($userID);
			$numberkey     = array_rand($twilioNumbers,1);
			$from = removeCountryCode($twilioNumbers[$numberkey]);
			
			if($today == $day){
				$msgTime = date("H:i",strtotime($startDate));
				if($timeNow == $msgTime){ //'Sending started at => '.$msgTime.'<br>';
					for($i=0; $i < count($attendies); $i++){
						$toNumber = $attendies[$i];
						$data = array (
							'From' => $from,
							'To' => $toNumber,
							'Body' => $message
						);
						//"MediaUrl" => getServerUrl().'/uploads/'.$fileName
						$post = http_build_query($data);
						$url  = "https://api.twilio.com/2010-04-01/Accounts/".$sid."/Messages.json";
						$x = curl_init($url);
						curl_setopt($x, CURLOPT_POST, true);
						curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
						curl_setopt($x, CURLOPT_USERPWD, $sid.":".$token);
						curl_setopt($x, CURLOPT_POSTFIELDS, $data);
						$response = json_decode(curl_exec($x),true);
						curl_close($x);
						if($response['code']!=''){
							$smsSid = $response['message'];
						}else{
							$isSent = 'true';
							$smsSid = $response['sid'];
						}
						$ins = "insert into conversations
									(
										customer_number,
										user_id,
										to_number,
										from_number,
										message,
										media,
										is_sent,
										sms_sid,
										direction
									)
								values
									(
										'".removeCountryCode($toNumber)."',
										'".$userID."',
										'".removeCountryCode($toNumber)."',
										'".removeCountryCode($from)."',
										'".DBin($message)."',
										'',
										'".$isSent."',
										'".$smsSid."',
										'out'
									)";
						mysqli_query($link,$ins);
						if($workflow_id){
							runWorkFlowwithSchduler($toNumber,$workflow_id);
						}
					}
				}else{
					//echo "no time reached to send sms yet: ".$timeNow.'--- msg time is: '.$msgTime;
				}
			}else{
				//echo $today.'---'.$day.' => '.$rowScheduler["start_date"];
			}
			echo "<br>";
		}
	}
	/************************ Scheduler handling ends ****************************/
?>