<?php
	error_reporting(0);
	session_start();
	include_once("database.php");
	include_once("functions.php");
	$cmd = $_REQUEST['cmd'];
	$sid = "ACb797881d79a639eefb0a266f275b895b";
	$token = "e0419a64126bc4424d9b367d809a9aa0";
	$userID = $_SESSION['user_id'];
	$twilioNumbers = getRandomTwilioNumbers($userID);
	$numberkey     = array_rand($twilioNumbers,1);
	$from = removeCountryCode($twilioNumbers[$numberkey]);
	date_default_timezone_set("US/Eastern");
	$alloweMediaExtensions = array('png','jpg','jpeg','bmp','gif','wmv','avi','avchd','flv','mkv','webm','mp4','mpeg');

	switch($cmd){
		case "download_subcon_sample_csv_file":{
			downloadFile('sample_sub_con.csv');
		}
		break;
			
		case "upload_dc_subcontractor":{
			$ext = getExtension($_FILES['dc_subcon']['name']);
			if($ext=='csv'){
				$fileName = uniqid().'.'.$ext;
				$tmpName  = $_FILES['dc_subcon']['tmp_name'];
				$r = move_uploaded_file($tmpName,'uploads/'.$fileName);
				if($r){
					$index = 0;
					$handle = fopen("uploads/$fileName", "r");
					while(($data=fgetcsv($handle,1000,",")) !== FALSE){
						if($index>0){
							if($number = trim($data[0])==''){
								$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> csv file is empty or not well formated.</div>';
							}else{
								$company    = trim($data[0]);
								$division = trim($data[1]);
								$primary_contact  = trim($data[2]);
								$cell  = trim($data[3]);
								$phone  = trim($data[4]);
								$email  = trim($data[5]);
								$city  = trim($data[6]);
								$street_address  = trim($data[7]);
								$zipcode  = trim($data[8]);
								$kcg_state  = trim($data[9]);
								$rating  = trim($data[10]);
								$state  = trim($data[11]);
								$illigal = array("-","_"," ","(",")",".","&nbsp;");
								$phone = str_replace($illigal,"",$phone);
								$cell  = str_replace($illigal,"",$cell);
								$cell  = removeCountryCode($cell);
								if(preg_match('/^[0-9]{10}+$/', $cell)){ // "Valid Phone Number";
									$sql = sprintf("select id from dc_subcon where cell='%s'",mysqli_real_escape_string($link,$cell));
									$res = mysqli_query($link,$sql);
									if(mysqli_num_rows($res)==0){
										$import = sprintf("INSERT into dc_subcon 
															(
																company,
																division,
																primary_contact,
																cell,
																phone,
																email,
																city,
																street_address,
																zipcode,
																kcg_state,
																rating,
																state,
																user_id
															)
														values
															(
																'%s',
																'%s',
																'%s',
																'%s',
																'%s',
																'%s',
																'%s',
																'%s',
																'%s',
																'%s',
																'%s',
																'%s',
																'%s'
															)",
													mysqli_real_escape_string($link,DBin($company)),
													mysqli_real_escape_string($link,DBin($division)),
													mysqli_real_escape_string($link,DBin($primary_contact)),
													mysqli_real_escape_string($link,DBin($cell)),
													mysqli_real_escape_string($link,DBin($phone)),
													mysqli_real_escape_string($link,DBin($email)),
													mysqli_real_escape_string($link,DBin($city)),
													mysqli_real_escape_string($link,DBin($street_address)),
													mysqli_real_escape_string($link,DBin($zipcode)),
													mysqli_real_escape_string($link,DBin($kcg_state)),
													mysqli_real_escape_string($link,DBin($rating)),
													mysqli_real_escape_string($link,DBin($state)),
													mysqli_real_escape_string($link,DBin($userID))
											);
										mysqli_query($link,$import) or die(mysqli_error($link));
										$customerID = mysqli_insert_id($link);
									}
								}
								else{
									//echo "Invalid Phone Number";
								}
							}
						}
						$index++;
					}
					$_SESSION['message'] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Uploaded successfully.</div>';
					unlink('uploads/'.$fileName);
				}else{
					$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to  upload csv file.</div>';
				}
			}else{
				$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Please select a valid csv file and try again.</div>';
			} 
			?><script>window.location="dc_subcon.php"</script><?php
		}
		break;
			
		case "start_new_chat":{
			$firstName   = $_REQUEST['first_name'];
			$lastName    = $_REQUEST['last_name'];
			$contactType = $_REQUEST['contact_type'];
			$phoneNumber = $_REQUEST['phone_number'];
			$message	 = $_REQUEST['message'];
			$check = "select id from customers where cell='".$phoneNumber."' limit 1";
			$exe   = mysqli_query($link,$check);
			if(mysqli_num_rows($exe)==0){
				$sql = "insert into customers
							(
								first_name,
								last_name,
								contact_type,
								cell,
								user_id
							)
						values
							(
								'".$firstName."',
								'".$lastName."',
								'".$contactType."',
								'".$phoneNumber."',
								'".$userID."'
							)";
				$res = mysqli_query($link,$sql);
				if($res){
					$url = "https://api.twilio.com/2010-04-01/Accounts/".$sid."/Messages.json";
					$data = array (
						'From' => $from,
						'To' => $phoneNumber,
						'Body' => $message
					);
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
					$isSent = 'false';
					if($response['sid']!=''){
						$isSent = 'true';
						$smsSid = $response['sid'];
					}else{
						$smsSid = $response['message'];
					}
					$ins = "insert into conversations
								(
									customer_number,
									user_id,
									to_number,
									from_number,
									message,
									is_sent,
									sms_sid,
									direction
								)
							values
								(
									'".$phoneNumber."',
									'".$userID."',
									'".$phoneNumber."',
									'".$from."',
									'".dbIn($message)."',
									'true',
									'".$smsSid."',
									'out'
								)";
					mysqli_query($link,$ins);
				}
			}
		}
		break;	
			
		case "add_customer":{
			$firstName = $_REQUEST['first_name'];
			$lastName  = $_REQUEST['last_name'];
			$phone     = $_REQUEST['phone'];
			$cell	   = $_REQUEST['cell'];
			$address   = $_REQUEST['address'];
			$city	   = $_REQUEST['city'];
			$state 	   = $_REQUEST['state'];
			$zipcode   = $_REQUEST['zipcode'];
			$saleManagerName = $_REQUEST['sale_manager_name'];
			$saleManagerNumber = $_REQUEST['sale_manager_number'];
			$projectManagerName = $_REQUEST['project_manager_name'];
			$projectManagerNumber = $_REQUEST['project_manager_number'];
			$managementName  = $_REQUEST['management_name'];
			$managementNumber = $_REQUEST['management_number'];
			$tagworkflow = $_REQUEST['tagworkflow'];
			
			$sel = "select id from customers where cell='".$cell."'";
			$exe = mysqli_query($link,$sel);
			if(mysqli_num_rows($exe)==0){
				$sql = "insert into customers
							(
								first_name,
								last_name,
								phone,
								cell,
								address,
								city,
								state,
								zipcode,
								sales_manager_name,
								sales_manager_number,
								project_manager_name,
								project_manager_number,
								management_name,
								management_number,
								tag_workflow,
								user_id
							)
						values
							(
								'".$firstName."',
								'".$lastName."',
								'".$phone."',
								'".$cell."',
								'".$address."',
								'".$city."',
								'".$state."',
								'".$zipcode."',
								'".$saleManagerName."',
								'".$saleManagerNumber."',
								'".$projectManagerName."',
								'".$projectManagerNumber."',
								'".$managementName."',
								'".$managementNumber."',
								'".$tagworkflow."',
								'".$_SESSION['user_id']."'
							)";
				$res = mysqli_query($link,$sql);
				if($res){
					$_SESSION['message'] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Customer is added successfullyl.</div>';	
				}else{
					$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to add customer.</div>';
				}
			}else{
				$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Customer with same cell number is added in the system.</div>';
			}
			header("location: customers.php");
		}
		break;
			
		case "get_user_files":{
			$toNumber = $_REQUEST['toNumber'];
			$sql = "select media,media_content_type,media_extension,direction from conversations where customer_number='".$toNumber."' order by id asc";
			$res = mysqli_query($link,$sql);
			$html = '';
			if(mysqli_num_rows($res)){
				while($row = mysqli_fetch_assoc($res)){
					if(($row['media']!='') and ($row['media']!='no')){
						if($row['direction']=='in'){
							if($row['media_content_type']=='image'){
								$html .= '<div class="col-md-3"><img src="'.$row['media'].'" class="userMedia" /></div>';
							}else{
								$html .= '<div class="col-md-3"><video width="320" height="240" controls><source src="'.$row['media'].'" type="video/'.$row['media_extension'].'">Your browser does not support the video tag.</video></div>';
							}
						}
						else{
							if($row['media_content_type']=='image'){
								$html .= '<div class="col-md-3"><img src="uploads/'.$row['media'].'" class="userMedia" /></div>';
							}else{
								$html .= '<div class="col-md-3"><video width="320" height="240" controls><source src="uploads/'.$row['media'].'" type="video/'.$row['media_extension'].'">Your browser does not support the video tag.</video></div>';
							}
						}
					}
				}
			}
			if($html == ''){
				$html = '<div class="col-md-3">No file found.</div>';
			}
			echo $html;
		}
		break;
			
		case "get_notes":{
			$toNumber = $_REQUEST['toNumber'];
			$sql = "select notes from customers where (cell='".$toNumber."') or (cell='".removeCountryCode($toNumber)."')";
			$res = mysqli_query($link,$sql);
			$row = mysqli_fetch_assoc($res);
			echo $row['notes'];
		}
		break;
			
		case "save_notes":{
			$notes = $_REQUEST['notes'];
			$toNumber = $_REQUEST['toNumber'];
			echo $sql = "update customers set notes='".$notes."' where (cell='".$toNumber."') or (cell='".removeCountryCode($toNumber)."')";
			$res = mysqli_query($link,$sql) or die(mysqli_error($link));
		}
		break;
			
		case "switch_company":{
			$companyID = $_REQUEST['companyID'];
			$sql = "select id,first_name,last_name,business_name,type from users where id='".$companyID."'";
			$res = mysqli_query($link,$sql);
			if(mysqli_num_rows($res)){
				$row = mysqli_fetch_assoc($res);
				$_SESSION['first_name'] = $row['first_name'];
				$_SESSION['last_name']  = $row['last_name'];
				$_SESSION['user_id']    = $row['id'];
				$_SESSION['user_type']  = $row['type'];
				$_SESSION['business_name']  = $row['business_name'];
			}else{
				$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Failed!</strong> failed to login with the selected company.</div>';
				header("location:index.php");
			}
		}
		break;
			
		case "delete_conatact":{
			$contactID = $_REQUEST['contactID'];
			$listID = $_REQUEST['listID'];
			$sql = "delete from customers where id='".$contactID."' limit 1";
			$res = mysqli_query($link,$sql);
			if($res){
				mysqli_query($link,"delete from list_assignment where customer_id='".$contactID."' and list_id='".$listID."'");
				$_SESSION['message'] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Contact is deleted successfullyl.</div>';	
			}else{
				$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to delete contact.</div>';
			}
		}
		break;
			
		case "fetch_more_inbox_contacts":{
			//$toNumber = $_REQUEST['toNumber'];
			$start = $_REQUEST['start'];
			$rowperpage = $_REQUEST['rowperpage'];
			$sql = "select * from broadcast_history order by id desc limit ".$start.",".$rowperpage;
			$res = mysqli_query($link,$sql);
			if(mysqli_num_rows($res)){
				while($row = mysqli_fetch_assoc($res)){
					$poneNumber = $row['from_number'];
					if($poneNumber==''){
						$poneNumber = $row["to_number"];
					}	
					echo '<div class="d-flex align-items-center justify-content-between border-bottom py-3">
								<div style="width: 100%">
									<div class="h6 mb-0 align-items-center">
										<i class="icon icon-xs me-2 fa fa-phone" style="color: darkorange"></i>
										<a href="javascript:void(0)" onclick="getChats('.$poneNumber.')">'.$poneNumber.'</a>
										<span style="font-size: 12px;float: right">'.date("H:i a",strtotime($row['created_date'])).'</span>
									</div>
									<div class="showMessage small card-stats">'.$row['message'].'</div>
								</div>
							</div>';
				}
			}
		}
		break;
			
		case "get_attendies":{
			$department = $_REQUEST['department'];
			$sql = "select * from staff where role='".$department."'";
			$res = mysqli_query($link,$sql);
			$options = '';
			if(mysqli_num_rows($res)){
				while($row = mysqli_fetch_assoc($res)){
					$optionsArray[$row['phone']] = removeCountryCode($row["name"]);
				}
				echo json_encode($optionsArray);
			}else{
				echo '{"Error":"Nothing found"}';
			}
		}
		break;
			
		case "delete_event":{
			$eventID = $_REQUEST['eventID'];
			$sql = "delete from schedulers where id='".$eventID."'";
			$res = mysqli_query($link,$sql);
			if($res){
				$_SESSION['message'] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Event is deleted successfully.</div>';	
			}else{
				$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to delete event.</div>';
			}
		}
		break;
			
		case "update_schedule_message":{
			$eventID 	 = $_REQUEST['eventID'];
			$eventTitle  = $_REQUEST['title'];
			$startDate   = date("Y-m-d H:i",strtotime($_REQUEST['startDate']));
			$message     = $_REQUEST['message'];
			$department  = $_REQUEST['department'];
			$attendies   = $_REQUEST['attendies'];
			$isRecurring = $_REQUEST['isRecurring'];
			if($isRecurring!='1'){
				$isRecurring = '0';
			}
			$sql = "update schedulers set
						event_title='".$eventTitle."',
						start_date='".$startDate."',
						message='".$message."',
						department='".$department."',
						attendies='".$attendies."',
						is_recurring='".$isRecurring."'
					where
						id='".$eventID."'";
			$res = mysqli_query($link,$sql);
			if($res){
				$_SESSION['message'] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Event is updated successfullyl.</div>';	
			}else{
				$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to update event.</div>';
			}
		}
		break;
			
		case "schedule_message":{
			$eventTitle  = $_REQUEST['title'];
			$startDate   = date("Y-m-d H:i",strtotime($_REQUEST['startDate']));
			$message     = $_REQUEST['message'];
			$department  = $_REQUEST['department'];
			$attendies   = $_REQUEST['attendies'];
			$isRecurring = $_REQUEST['isRecurring'];
			if($isRecurring!='1'){
				$isRecurring = '0';
			}
			$sql = "insert into schedulers
						(
							event_title,
							start_date,
							message,
							department,
							attendies,
							is_recurring,
							user_id
						)
					values
						(
							'".$eventTitle."',
							'".$startDate."',
							'".DBin($message)."',
							'".$department."',
							'".$attendies."',
							'".$isRecurring."',
							'".$_SESSION['user_id']."'
						)";
			$res = mysqli_query($link,$sql);
			if($res){
				$_SESSION['message'] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Event is scheduled successfullyl.</div>';	
			}else{
				$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to schedule event.</div>';
			}
		}
		break;
			
		case "add_staff":{
			$phone = addCountryCode($_REQUEST['phone']);
			$sql = "select id from staff where phone='".$phone."'";
			$res = mysqli_query($link,$sql);
			if(mysqli_num_rows($res)=='0'){
				$ins = "insert into staff
							(
								name,
								phone,
								role,
								email
							)
						values
							(
								'".$_REQUEST['name']."',
								'".$phone."',
								'".$_REQUEST['role']."',
								'".$_REQUEST['email']."'
							)";
				mysqli_query($link,$ins);
				$_SESSION['message'] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Staff is added successfully.</div>';
			}else{
				$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> phone number is already added as a staff.</div>';
			}
			header("location: staff.php");
		}
		break;
			
		case "delete_staff":{
			$staffID = $_REQUEST['staffID'];
			$sql = "delete from staff where id='".$staffID."' limit 1";
			$res = mysqli_query($link,$sql);
			if($res){
				$_SESSION['message'] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Staff is deleted successfullyl.</div>';	
			}else{
				$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to delete staff.</div>';
			}
			header("location: staff.php");
		}
		break;
			
		case "update_staff":{
			$id = $_REQUEST['id'];
			$name = $_REQUEST['name'];
			$phone = $_REQUEST['phone'];
			$role = $_REQUEST['role'];
			$email = $_REQUEST['email'];
			$sql = "update staff set name='".$name."', phone='".$phone."', role='".$role."', email='".$email."' where id='".$id."'";
			$res = mysqli_query($link,$sql);
			if($res){
				$_SESSION['message'] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Staff is updated successfullyl.</div>';	
			}else{
				$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to update staff.</div>';
			}
			header("location: staff.php");
		}
		break;
			
		case "send_broadcast_to_the_customers":{
			$comingFrom = $_REQUEST['coming_from'];
			$recipients = json_decode($_REQUEST['recipients'],true);
			$isSent = 'false';
			$totalRecipients = count($recipients);
			if($totalRecipients > 0){
				if($_FILES['broadcast_media']['name']!=''){
					$ext = getExtension($_FILES['broadcast_media']['name']);
					if(in_array($ext,$alloweMediaExtensions)){
						$fileName = uniqid().'.'.$ext;
						$tmpName  = $_FILES['broadcast_media']['tmp_name'];
						$r = move_uploaded_file($tmpName,'uploads/'.$fileName);
						if($r){
							$fileName = getServerUrl().'/uploads/'.$fileName;
						}
					}else{
						$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> please select a valid media file.</div>';
						header("location: ".$_SERVER['HTTP_REFERER']);
						die("Please select a valid media file.");
					}
				}else{
					$fileName = '';
				}
				
				for($i=0; $i<$totalRecipients; $i++){
					$customerInfo = getCustomerInfoByNumber($recipients[$i]);
					$broadcastSms = $_REQUEST['broadcast_sms'];
					$broadcastSms = str_replace("%name%",$customerInfo['name'],$broadcastSms);
					$broadcastSms = str_replace("%address%",$customerInfo['address'],$broadcastSms);
					//$broadcastSms = str_replace("%project_manager%",$row['project_manager'],$broadcastSms);
					//$broadcastSms = str_replace("%email%",$row['email'],$broadcastSms);
					$ins = "insert into queued_msgs
								(
									from_number,
									to_number,
									message,
									media,
									contact_type,
									user_id
								)
							values
								(
									'".$from."',
									'".$recipients[$i]."',
									'".DBin($broadcastSms)."',
									'".$fileName."',
									'".$comingFrom."',
									'".$_SESSION['user_id']."'
								)";
					mysqli_query($link,$ins);
				}
				$_SESSION['message'] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Broadcast is now sending.</div>';
				$url = getServerUrl().'/cron.php?limit=50';
				postCurl($url,array());
			}else{
				$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to queue broadcast.</div>';
			}
			if($comingFrom=='dc_subcontractors')
				header("location: dc_subcon.php");
			else
				header("location: customers.php");
		}
		break;
			
		case "download_sample_csv":{
        	downloadFile('sample.csv');
    	}
        break;
			
		case "send_chat_message":{
			$message = $_REQUEST['message'];
			$toNumber = $_REQUEST['to_number'];
			$chatNumber = getChatNumber($toNumber,$userID);
			$from = $chatNumber;
			
			if(trim($_FILES['chat_media']['name'])!=''){
				$ext = getExtension($_FILES['chat_media']['name']);
				if(in_array($ext,$alloweMediaExtensions)){
					$fileName = uniqid().'.'.$ext;
					$tmpName  = $_FILES['chat_media']['tmp_name'];
					$r = move_uploaded_file($tmpName,'uploads/'.$fileName);
					if($r){
						$url  = "https://api.twilio.com/2010-04-01/Accounts/".$sid."/Messages.json";
						$data = array (
							'From' => $from,
							'To' => $toNumber,
							'Body' => $message,
							"MediaUrl" => getServerUrl().'/uploads/'.$fileName
						);
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
						if($response['sid']!=''){
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
										is_sent,
										sms_sid,
										direction,
										media
									)
								values
									(
										'".$toNumber."',
										'".$_SESSION['user_id']."',
										'".$toNumber."',
										'".$from."',
										'".dbIn($message)."',
										'true',
										'".$smsSid."',
										'out',
										'".$fileName."'
									)";
						mysqli_query($link,$ins);
						echo '{"error":"no","message":"Send successfully."}';
					}
				}else{
					echo '{"error":"yes","message":"Please select a valid image."}';
				}
			}
			else{
				$url  = "https://api.twilio.com/2010-04-01/Accounts/".$sid."/Messages.json";
				$data = array (
					'From' => $from,
					'To' => $toNumber,
					'Body' => $message
				);
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
				if($response['sid']!=''){
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
								is_sent,
								sms_sid,
								direction
							)
						values
							(
								'".$toNumber."',
								'".$_SESSION['user_id']."',
								'".$toNumber."',
								'".$from."',
								'".dbIn($message)."',
								'true',
								'".$smsSid."',
								'out'
							)";
				mysqli_query($link,$ins);
				echo '{"error":"no","message":"Send successfully."}';
			}
		}
		break;	
			
		case "get_chat":{
			$customerNumber = $_REQUEST['customerNumber'];
			$sql = "select direction,is_read,message,media,created_date,media_content_type,media_extension from conversations where customer_number='".$customerNumber."' order by id asc";
			$res = mysqli_query($link,$sql);
			$totalChats = mysqli_num_rows($res);
			$chats = '';
			if($totalChats > $oldChats){
				while($row = mysqli_fetch_assoc($res)){
					$customerInfo = getCustomerInfoByNumber($customerNumber);
					if(trim($row['media'])!='no'){
						$media = $row['media'];
					}else{
						$media = '';
					}
					if($row['direction']=='in'){
						$chats .= '<div class="card border-0 shadow p-4 mb-4"><div class="d-flex justify-content-between align-items-center mb-2"><span class="font-small"><a href="javascript:void(0)"><img class="avatar-sm img-fluid rounded-circle me-2" src="../assets/img/team/profile-picture-1.jpg" alt="avatar"><span class="fw-bold">'.$customerInfo['first_name'].' '.$customerInfo['last_name'].'</span></a><span class="fw-normal ms-2">'.date("M d, H:i",strtotime($row['created_date'])).'</span></span><div class="d-none d-sm-block"><svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div></div><p class="m-0">'.$row['message'].'</p>';
						if(trim($media)!=''){
							if($row['media_content_type']=='image'){
								$chats .= '<div class="card border-0 shadow mediaContainer" style="padding: 7px;"><div class="card-body p-0"><img src="'.$media.'" class="chatMedia card-img-top mb-2 mb-lg-3" alt="media"></div></div></div>';
							}else{
								echo '<i class="fa fa-video-camera" class="chatMedia card-img-top mb-2 mb-lg-3" alt="media"></i>';
							}
						}else{
							$chats .= '</div>';
						}
					}
					else{
						$chats .= '<div class="card text-black border-0 shadow p-4 ms-md-5 ms-lg-6 mb-4" style="background-color:#D9FDD3"><div class="d-flex justify-content-between align-items-center mb-2"><span class="font-small"><span class="fw-bold">'.$_SESSION['first_name'].' '.$_SESSION['last_name'].'</span><span class="fw-normal text-black-300 ms-2">'.date("M d, H:i",strtotime($row['created_date'])).'</span></span><div class="d-none d-sm-block"><svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div></div><p class="text-black-300 m-0">'.$row['message'].'</p>';
						if(trim($media)!=''){
							if($row['media_content_type']=='image'){
								$chats .= '<div class="card border-0 shadow mediaContainer" style="padding: 7px;"><div class="card-body p-0"><img src="uploads/'.$media.'" class="chatMedia card-img-top mb-2 mb-lg-3" alt="media"></div></div></div>';
							}else{
								echo '<i class="fa fa-video-camera" class="chatMedia card-img-top mb-2 mb-lg-3" alt="media"></i>';
							}
						}else{
							$chats .= '</div>';
						}
					}
				}
				$chats = json_encode($chats);
				echo '{"chats":'.$chats.',"auto_load_chat":"no"}';
			}else{
				echo '{"chats":""}';
			}
		}
		break;
			
		case "send_broadcast":{
			$listID  = $_REQUEST['list_id'];
			$sql = "select c.cell, c.first_name, c.address from customers c, list_assignment li where li.customer_id=c.id and li.list_id='".$listID."' and c.status='1'";
			$res = mysqli_query($link,$sql) or die(mysqli_error($link));
			if(mysqli_num_rows($res)){
				$twilioNumbers = getRandomTwilioNumbers($_SESSION['user_id']);
				if(count($twilioNumbers) < 1){
					$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> you have no twilio number to send message.</div>';
					header("location: ".$_SERVER["HTTP_REFERER"]);
					die();
				}
				if($_FILES['broadcast_media']['name']!=''){
					$ext = getExtension($_FILES['broadcast_media']['name']);
					if(in_array($ext,$alloweMediaExtensions)){
						$fileName = uniqid().'.'.$ext;
						$tmpName  = $_FILES['broadcast_media']['tmp_name'];
						$r = move_uploaded_file($tmpName,'uploads/'.$fileName);
						if($r){
							$fileName = getServerUrl().'/uploads/'.$fileName;
						}
					}else{
						$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> please select a valid image.</div>';
						header("location: ".$_SERVER['HTTP_REFERER']);
						die("Please select a valid image file.");
					}
				}
				else{
					$fileName = '';
				}
				while($row = mysqli_fetch_assoc($res)){
					$to = $row['cell'];
					$bulkSMS = $_REQUEST['bulk_sms'];
					$bulkSMS = str_replace("%name%",$row['first_name'],$bulkSMS);
					$bulkSMS = str_replace("%address%",$row['address'],$bulkSMS);
					$bulkSMS = str_replace("%project_manager%",$row['project_manager'],$bulkSMS);
					$bulkSMS = str_replace("%email%",$row['email'],$bulkSMS);
					
					$key = array_rand($twilioNumbers,1);
					$from = removeCountryCode($twilioNumbers[$key]);
					
					$ins = "insert into queued_msgs
								(
									from_number,
									to_number,
									message,
									media,
									user_id
								)
							values
								(
									'".$from."',
									'".$to."',
									'".DBin($bulkSMS)."',
									'".$fileName."',
									'".$_SESSION['user_id']."'
								)";
					$r = mysqli_query($link,$ins);
					if($r){
						$_SESSION['message'] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Broadcast is now sending.</div>';
					}else{
						$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to queue broadcast.</div>';
					}
				}
				$url = getServerUrl().'/cron.php?limit=50';
        		postCurl($url,array());
			}else{
				$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> No customer found in this list.</div>';
			}
			header("location: bulksms.php");
		}
		break;
			
		case "delete_list":{
			$listID = $_REQUEST['listID'];
			$sql = "delete from lists where id='".$listID."'";
			$res = mysqli_query($link,$sql);
			if($res){
				$del = "delete from list_assignment where list_id='".$listID."'";
				mysqli_query($link,$del);
				$_SESSION['message'] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> list is deleted successfully.</div>';
			}else{
				$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to  delete list.</div>';
			}
		}
		break;
			
		case "create_csv_list":{
			$title = $_REQUEST['title'];
			$ext = getExtension($_FILES['contacts_csv']['name']);
			if($ext=='csv'){
				$fileName = uniqid().'.'.$ext;
				$tmpName  = $_FILES['contacts_csv']['tmp_name'];
				$r = move_uploaded_file($tmpName,'uploads/'.$fileName);
				if($r){
					$sel = "select id from lists where title='".$title."' and user_id='".$_SESSION['user_id']."'";
					$rec = mysqli_query($link,$sel);
					if(mysqli_num_rows($rec)){
						$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> A list with same title is already exists.</div>';
					}else{
						$sql = "insert into lists (title, user_id) values ('".$title."','".$_SESSION['user_id']."')";
						$res = mysqli_query($link,$sql);
						if($res){
							$listID = mysqli_insert_id($link);
							importSubscribers($fileName, $listID, $_SESSION['user_id']);
							$_SESSION['message'] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> List is created successfully.</div>';
						}else{
							$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to  create new list.</div>';
						}
					}
					unlink('uploads/'.$fileName);
				}else{
					$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to  upload csv file.</div>';
				}
			}else{
				$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Please select a valid csv file and try again.</div>';
			} 
			?><script>window.location="bulksms.php"</script><?php
		}
		break;
			
		case "oauth_redirect_url":{
			logErrors(json_encode($_REQUEST));
			mail("mirzaahsan42@gmail.com","oauth redirect post",print_r($_REQUEST,true));
		}
		break;
			
		case "login":{
			$userName = DBin($_REQUEST['username']);
			$password = encodePassword($_REQUEST['password']);
			$sql = sprintf("SELECT * FROM users WHERE email='%s' AND password='%s'",
				mysqli_real_escape_string($link,$userName),
				mysqli_real_escape_string($link,$password));
			$res = mysqli_query($link,$sql);
			if(mysqli_num_rows($res)==0){
				$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Incorrect login information, please try again.</div>';
				header("location: ".$_SERVER['HTTP_REFERER']);
				die();
			}else{
				$row = mysqli_fetch_assoc($res);
				if($row['status']=='1'){
					$_SESSION['first_name'] = $row['first_name'];
					$_SESSION['last_name']  = $row['last_name'];
					$_SESSION['user_id']    = $row['id'];
					$_SESSION['user_type']  = $row['type'];
					$_SESSION['business_name']  = $row['business_name'];
					header("Location: dashboard.php");
				}else if($row['status']=='2'){
					$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> your account is blocked.</div>';
					header("Location: ".$_SERVER['HTTP_REFERER']);
				}else if($row['status']=='3'){
					$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> your account is deleted.</div>';
					header("Location: ".$_SERVER['HTTP_REFERER']);
				}
			}
		}
		break;

		case "logout":{
			unset($_SESSION['first_name']);
			unset($_SESSION['last_name']);
			unset($_SESSION['user_id']);
			unset($_SESSION['user_type']);
			unset($_SESSION['business_name']);
			unset($_SESSION['company']);
			$_SESSION['message'] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Logged out successfully.</div>';
			header("location:index.php");
		}
		break;
	}
?>