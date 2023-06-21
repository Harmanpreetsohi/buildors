<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

error_reporting(0);
session_start();

include_once( "database.php" );
include_once( "functions.php" );
include_once( "clsss/class-fb.php" );
$cmd = $_REQUEST[ 'cmd' ];
$sid = "ACb797881d79a639eefb0a266f275b895b";
$token = "e0419a64126bc4424d9b367d809a9aa0";
$userID = $_SESSION[ 'user_id' ];
$twilioNumbers = getRandomTwilioNumbers( $userID );
$numberkey = array_rand( $twilioNumbers, 1 );
$from = removeCountryCode( $twilioNumbers[ $numberkey ] );
date_default_timezone_set( "US/Eastern" );
$alloweMediaExtensions = array( 'png', 'jpg', 'jpeg', 'bmp', 'gif', 'wmv', 'avi', 'avchd', 'flv', 'mkv', 'webm', 'mp4', 'mpeg' );
$imageExtensions = array( 'png', 'jpg', 'jpeg', 'bmp', 'gif' );

if ( isset( $_GET[ 'chat' ] ) && $_GET[ 'chat' ] == 'calllogs' ) {
	$customerNumber = $_REQUEST[ 'customerNumber' ];
	$sql = "SELECT * FROM `twillio_call_log` WHERE number='" . $customerNumber . "'";
	$res = mysqli_query( $link, $sql );
	$totalChats = mysqli_num_rows( $res );
	$chats = '';
	$customerName = '';
	while ( $row = mysqli_fetch_assoc( $res ) ) {
		$chats .= '<div class="card p-2 mb-2 mt-2" style="background-color: #e6e5e5;border-radius: 0px;"><div class="d-flex justify-content-between align-items-center mb-2"><span class="font-small"><span class="fw-bold">Call ' . $row[ 'number' ] . '</span><span class="fw-normal text-black-300 ms-2">on ' . date( "d M, H:i a", strtotime( $row[ 'created_at' ] ) ) . '</span></span></div><p class="text-black-300 m-0">Duration: ' . date( 'i:s', number_format( ( $row[ 'time' ] / 1000 ), 0 ) ) . '</p></div>';
	}
	if ( $chats != '' ) {
		$chats = json_encode( $chats );
		echo '{"chats":' . $chats . ',"auto_load_chat":"no","customer_name":"' . $customerName . '","customer_number":"' . $customerNumber . '"}';
	} else {
		echo '{"chats":"","auto_load_chat":"no","customer_name":"' . $customerName . '","customer_number":"' . $customerNumber . '"}';
	}
	exit();
}
// switch case for FB Chat
if ( isset( $_GET[ 'chat' ] ) ) {
	switch ( $cmd ) {
		case 'get_chat':
			$msg = new FBChat( 'EABXfZCfLPDE4BAHWirU9eyNCVBJpIN7a6qIEJmImQom2PNpZCx8RrSMpWPxZBrE2oVcTwXcW5SGGCu3Vv8tH2WCtaqiLLnw958IHrrS1aXOqbQYfPCCZAg0aAdPKNmV2NGjtxTVGsZCnjTZBbxWptb2hyaaM9teDBILSxyPutJerq4xyVj13nsVc9jDEtmkfbdIr5kNk6QLAZDZD' );
			$customerNumber = $_REQUEST[ 'customerNumber' ];
			$sql = "SELECT * FROM `fb_msgs` WHERE sender='" . $customerNumber . "' OR recipient='" . $customerNumber . "'";
			$res = mysqli_query( $link, $sql );
			$totalChats = mysqli_num_rows( $res );
			$chats = '';
			$customerName = '';
			$customerInfo = getCustomerInfoByNumber( $customerNumber );
			while ( $row = mysqli_fetch_assoc( $res ) ) {
				if ( $row[ 'sender' ] == $customerNumber ) {
					$chats .= '<div class="card border-0 shadow p-4 mb-4"><div class="d-flex justify-content-between align-items-center mb-2"><span class="font-small"><a href="javascript:void(0)">
						<svg height="800px" width="800px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 309.142 309.142" xml:space="preserve" style="width: 21px;height: 19px;">
												<g>
													<path style="fill:#005CB9;" d="M154.571,0C69.662,0,0.83,68.831,0.83,153.741c0,38.283,13.997,73.295,37.147,100.207
														c-6.953,19.986-19.807,37.209-36.479,49.581c10.592,3.619,21.938,5.613,33.757,5.613c20.214,0,39.082-5.751,55.067-15.698
														c19.551,9.007,41.312,14.039,64.249,14.039c84.909,0,153.741-68.833,153.741-153.742C308.313,68.831,239.48,0,154.571,0z"></path>
													<g>
														<g>
															<path style="fill:#FFFFFF;" d="M131.495,240.044h36.112c0,0,0-49.849,0-90.282h26.804l3.29-36.115h-28.7v-14.44
																c0-7.037,4.69-8.671,7.984-8.671c3.297,0,20.258,0,20.258,0V59.593l-27.895-0.113c-30.963,0-38.002,23.075-38.002,37.849v16.318
																H113.44v36.115h18.056C131.495,190.682,131.495,240.044,131.495,240.044z"></path>
														</g>
													</g>
												</g>
												</svg>
						<span class="fw-bold">' . $row[ 'from_name' ] . '</span></a><span class="fw-normal ms-2">' . date( "M d, H:i", ( ( int )round( $row[ 'timestamp' ] / 1000 ) ) ) . '</span></span><div class="d-none d-sm-block"><svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div></div><p class="m-0">' . $row[ 'text' ] . '</p>';
					$chats .= '</div>';
					$customerName = $row[ 'from_name' ];
				} else {
					$chats .= '<div class="card text-black border-0 shadow p-4 ms-md-5 ms-lg-6 mb-4" style="background-color:#D9FDD3"><div class="d-flex justify-content-between align-items-center mb-2"><span class="font-small"><span class="fw-bold">' . $row[ 'from_name' ] . '</span><span class="fw-normal text-black-300 ms-2">' . date( "M d, H:i", ( ( int )round( $row[ 'timestamp' ] / 1000 ) ) ) . '</span></span><div class="d-none d-sm-block"><svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div></div><p class="text-black-300 m-0">' . $row[ 'text' ] . '</p>';
					$chats .= '</div>';
				}
			}
			if ( $chats != '' ) {
				$chats = json_encode( $chats );
				echo '{"chats":' . $chats . ',"auto_load_chat":"no","customer_name":"' . $customerName . '","customer_number":"' . $customerNumber . '"}';
			} else {
				echo '{"chats":"","auto_load_chat":"no","customer_name":"' . $customerName . '","customer_number":"' . $customerNumber . '"}';
			}
			break;
		case "send_chat_message":
			{
				$message = $_REQUEST[ 'message' ];
				$toNumber = $_REQUEST[ 'to_number' ];
				$msg = new FBChat( 'EABXfZCfLPDE4BAHWirU9eyNCVBJpIN7a6qIEJmImQom2PNpZCx8RrSMpWPxZBrE2oVcTwXcW5SGGCu3Vv8tH2WCtaqiLLnw958IHrrS1aXOqbQYfPCCZAg0aAdPKNmV2NGjtxTVGsZCnjTZBbxWptb2hyaaM9teDBILSxyPutJerq4xyVj13nsVc9jDEtmkfbdIr5kNk6QLAZDZD' );
				$response = $msg->send_msg( $toNumber, $message );
				$response = json_decode( $response, true );
				if ( isset( $response[ 'message_id' ] ) ) {
					$mid = $response[ 'message_id' ];
					$recipient = $toNumber;
					$sender = $msg->id;
					$text = $message;
					$msg_data = $msg->get_msg_detail( $mid );
					$to_name = $msg_data[ 'to_name' ];
					$from_name = $msg_data[ 'from_name' ];
					$sql = "INSERT INTO `fb_msgs`(`id`, `sender`, `recipient`, `timestamp`, `mid`, `text`, `from_name`, `to_name`) VALUES (NULL,'$recipient','$sender','" . strtotime( 'now' ) . "','$mid','$text','$to_name','$from_name')";
					mysqli_query( $link, $sql );
					echo '{"error":"no","message":"Send successfully."}';
				} else {
					echo '{"error":"yes","message":"Message send Failed."}';
				}
			}
			break;
	}
	exit();
}

switch($cmd){
	case "recording_callback_status":{
		logErrors(json_encode($_REQUEST));
	}
	break;
		
	case "get_contact_media":{
		$phone = $_REQUEST['phone'];
		$sql = "SELECT * FROM `conversations` WHERE media!='' and media!='no' and customer_number='".$phone."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			while($row = mysqli_fetch_assoc($res)){
				if($row['media_content_type']=='video'){
					if($row['direction']=='in'){
						echo '<p style="margin:8px 0px;"><video class="videoPlayer" width="400" controls>
							  <source src="'.$row['media'].'" type="video/mp4">
							  Your browser does not support HTML video.
							</video></p>';	
					}else{
						echo '<p style="margin:8px 0px;"><video class="videoPlayer" width="400" controls>
							  <source src="'.$row['media'].'" type="video/mp4">
							  Your browser does not support HTML video.
							</video></p>';
					}
				}else{
					if($row['direction']=='in'){
						echo '<p style="margin:8px 0px;"><img src="'.$row['media'].'" width="400" height="400"></p>';
					}else{
						echo '<p style="margin:8px 0px;"><img src="'.getServerUrl().'/uploads/'.$row['media'].'" width="400" height="400"></p>';
					}
				}
			}
		}else{
			echo '<p>No media found.</p>';
		}
	}
	break;
		
	case "remove_contact_from_pipeline":{
		$contactID = $_REQUEST['contactID'];
		$sql = "update contacts set pipeline_id='0', pipeline_stage='' where id='".$contactID."'";
		$res = mysqli_query($link,$sql);
		if($res){
			$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Booking card is removed successfullyl.</div>';
		}else{
			$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to removed booking card.</div>';
		}
	}
	break;
		
	case "get_contact_info":{
		$number = $_REQUEST['booking_id'];
		$sql = "select * from contacts where phone='".$number."' limit 1";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
			$contactID = $row['id'];
			$row['message'] = "success";
				
			$contactNotes = '';
			$sqlN = "select * from contact_notes where contact_id='".$contactID."'";
			$resN = mysqli_query($link,$sqlN);
			if(mysqli_num_rows($resN)){
				while($rowN = mysqli_fetch_assoc($resN)){
					$contactNotes .= '<p>'.date("d/m/Y h:ia",strtotime($rowN['created_date'])).' => '.$rowN['notes'].'</p>';
				}
			}
			$row['contact_notes'] = $contactNotes;

			echo json_encode($row);
		}else{
			echo '{"message":"not_found"}';
		}
	}
	break;	
		
	case "save_contact_notes":{
		$notes = $_REQUEST['notes'];
		$contactID = $_REQUEST['contactID'];
		$sql = "insert into contact_notes
					(
						contact_id,
						user_id,
						notes
					)
				values
					(
						'".$contactID."',
						'".$_SESSION['user_id']."',
						'".DBin($notes)."'
					)";
		mysqli_query($link,$sql);
	}
	break;
		
	case "save_contact_customer":{
		$firstName = $_REQUEST['first_name'];
		$lastName = $_REQUEST['last_name'];
		$phone = $_REQUEST['phone'];
		$companyName = $_REQUEST['company_name'];
		$designation = $_REQUEST['designation'];
		$type = $_REQUEST['type'];
		$email = $_REQUEST['email'];
		$streetAddress = $_REQUEST['street_address'];
		$city = $_REQUEST['city'];
		$state = $_REQUEST['state'];
		$zipcode = $_REQUEST['zipcode'];
		$kcgState = $_REQUEST['kcg_state'];
		
		$opportunityName = $_REQUEST['opportunity_name'];
		$opportunityLeadvalue = $_REQUEST['opportunity_leadvalue'];
		$opportunityPipelineID = $_REQUEST['opportunity_pipeline_id'];
		$opportunityStage = $_REQUEST['opportunity_stage'];
		$opportunitySource = $_REQUEST['opportunity_source'];
		
		$sel = "select id from contacts where phone='".$phone."' and user_id='".$_SESSION['user_id']."'";
		$exe = mysqli_query($link,$sel);
		if(mysqli_num_rows($exe)){
			$rec = mysqli_fetch_assoc($exe);
			$contactID = $rec['id'];
			$sql = "update contacts set
						first_name='".$firstName."',
						last_name='".$lastName."',
						company_name='".$companyName."',
						designation='".$designation."',
						type='".$type."',
						email='".$email."',
						street_address='".$streetAddress."',
						city='".$city."',
						state='".$state."',
						zipcode='".$zipcode."',
						kcg_state='".$kcgState."'
					where
						id='".$contactID."'";
			$res = mysqli_query($link,$sql);
			if($res){
				// Adding in opportunity table.
				$selO = "select id from booking_opportunity where contact_id='".$contactID."'";
				$exeO = mysqli_query($link,$selO);
				if(mysqli_num_rows($exeO)){
					$up = "update booking_opportunity set 
								opportunity_name='".$opportunityName."',
								opportunity_leadvalue='".$opportunityLeadvalue."',
								opportunity_pipeline='".$opportunityPipelineID."',
								opportunity_stage='".$opportunityStage."',
								opportunity_source='".$opportunitySource."'
							where
								contact_id='".$contactID."'";
					mysqli_query($link,$up);
				}
				else{
					$ins = "insert into booking_opportunity
								(
									opportunity_name,
									opportunity_leadvalue,
									opportunity_pipeline,
									opportunity_stage,
									opportunity_source,
									contact_id,
									user_id
								)
							values
								(
									'".$opportunityName."',
									'".$opportunityLeadvalue."',
									'".$opportunityPipelineID."',
									'".$opportunityStage."',
									'".$opportunitySource."',
									'".$contactID."',
									'".$_SESSION['user_id']."'
								)";
					mysqli_query($link,$ins);
				}
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Contact is updated successfullyl.</div>';
			}else{
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to update contact.</div>';
			}
		}else{
			$sql = "insert into contacts
						(
							first_name,
							last_name,
							company_name,
							designation,
							type,
							email,
							street_address,
							city,
							state,
							zipcode,
							kcg_state,
							phone,
							user_id
						)
					values
						(
							'".$firstName."',
							'".$lastName."',
							'".$companyName."',
							'".$designation."',
							'".$type."',
							'".$email."',
							'".$streetAddress."',
							'".$city."',
							'".$state."',
							'".$zipcode."',
							'".$kcgState."',
							'".$phone."',
							'".$_SESSION['user_id']."'
						)";
			$res = mysqli_query($link,$sql);
			if($res){
				$contactID = mysqli_insert_id($link);
				$ins = "insert into booking_opportunity
							(
								opportunity_name,
								opportunity_leadvalue,
								opportunity_pipeline,
								opportunity_stage,
								opportunity_source,
								contact_id,
								user_id
							)
						values
							(
								'".$opportunityName."',
								'".$opportunityLeadvalue."',
								'".$opportunityPipelineID."',
								'".$opportunityStage."',
								'".$opportunitySource."',
								'".$contactID."',
								'".$_SESSION['user_id']."'
							)";
				mysqli_query($link,$ins);
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Contact is added successfullyl.</div>';
			}else{
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to add contact.</div>';
			}
		}
	}
	break;
		
	case "get_contact":{
		$number = $_REQUEST['num'];
		$sql = "select * from contacts where phone='".$number."' and user_id='".$_SESSION['user_id']."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
			echo json_encode($row);
		}
	}
	break;
		
	case "incoming_call_status":
		{
			$CallSid = $_REQUEST[ 'CallSid' ];
			$CallDuration = $_REQUEST[ 'CallDuration' ];
			$sql = "update twillio_call_log set time='" . $CallDuration . "' where call_sid='" . $CallSid . "' limit 1";
			mysqli_query( $link, $sql );
		}
		break;
	case "assign_phone_number_to_ivr":
		{
			$phoneSid = $_REQUEST[ 'sid' ];
			$number = $_REQUEST[ 'number' ];
			$sql = "select user_id from twilio_numbers where sid='" . $phoneSid . "' limit 1";
			$res = mysqli_query( $link, $sql );
			if ( mysqli_num_rows( $res ) ) {
				$row = mysqli_fetch_assoc( $res );
				$userID = $row[ "user_id" ];
				mysqli_query( $link, "update twilio_numbers set ivr_number='0' where user_id='" . $userID . "'" );
				$data = array( "VoiceUrl" => getServerURL() . "/run_ivr.php", "SmsUrl" => getServerURL() . "/incoming_sms.php" );
				$ch = curl_init();
				curl_setopt( $ch, CURLOPT_URL, "https://api.twilio.com/2010-04-01/Accounts/" . $sid . "/IncomingPhoneNumbers/" . $phoneSid . ".json" );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt( $ch, CURLOPT_POST, 1 );
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
				curl_setopt( $ch, CURLOPT_USERPWD, $sid . ':' . $token );
				$result = json_decode( curl_exec( $ch ), true );
				if ( curl_errno( $ch ) ) {
					echo 'Error:' . curl_error( $ch );
				} else {
					mysqli_query( $link, "update twilio_numbers set ivr_number='1' where sid='" . $phoneSid . "'" );
					$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> phone number assigned successfully.</div>';
				}
				curl_close( $ch );
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> selected phone number is not exists in the current user list.</div>';
			}
		}
		break;
	case "download_contact_sample_csv_file":
		{
			downloadFile( 'sample.csv' );
		}
		break;
	case "upload_contacts":
		{
			$ext = getExtension( $_FILES[ 'contacts' ][ 'name' ] );
			if ( $ext == 'csv' ) {
				$fileName = uniqid() . '.' . $ext;
				$tmpName = $_FILES[ 'contacts' ][ 'tmp_name' ];
				$r = move_uploaded_file( $tmpName, 'uploads/' . $fileName );
				if ( $r ) {
					$index = 0;
					$handle = fopen( "uploads/$fileName", "r" );
					while ( ( $data = fgetcsv( $handle, 1000, "," ) ) !== FALSE ) {
						if ( $index > 0 ) {
							if ( $number = trim( $data[ 0 ] ) == '' ) {
								$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> csv file is empty or not well formated.</div>';
							} else {
								$firstName = trim( $data[ 0 ] );
								$lastName = trim( $data[ 1 ] );
								$companyName = trim( $data[ 2 ] );
								$phone = trim( $data[ 3 ] );
								$designation = trim( $data[ 4 ] );
								$type = strtolower( trim( $data[ 5 ] ) );
								$email = trim( $data[ 6 ] );
								$streetAddress = trim( $data[ 7 ] );
								$city = trim( $data[ 8 ] );
								$state = trim( $data[ 9 ] );
								$zipcode = trim( $data[ 10 ] );
								$kcgState = trim( $data[ 11 ] );
								$rating = trim( $data[ 12 ] );
								$illigal = array( "-", "_", " ", "(", ")", ".", "&nbsp;" );
								$phone = str_replace( $illigal, "", $phone );
								$phone = removeCountryCode( $phone );
								if ( preg_match( '/^[0-9]{10}+$/', $phone ) ) { // "Valid Phone Number";
									$sql = sprintf( "select id from contacts where phone='%s'", mysqli_real_escape_string( $link, $phone ) );
									$res = mysqli_query( $link, $sql )or die( mysqli_error( $link ) );
									if ( mysqli_num_rows( $res ) == 0 ) {
										$import = sprintf( "INSERT into contacts 
															(
																first_name,
																last_name,
																company_name,
																phone,
																designation,
																type,
																email,
																street_address,
																city,
																state,
																zipcode,
																kcg_state,
																rating,
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
																'%s',
																'%s'
															)",
											mysqli_real_escape_string( $link, DBin( $firstName ) ),
											mysqli_real_escape_string( $link, DBin( $lastName ) ),
											mysqli_real_escape_string( $link, DBin( $companyName ) ),
											mysqli_real_escape_string( $link, DBin( $phone ) ),
											mysqli_real_escape_string( $link, DBin( $designation ) ),
											mysqli_real_escape_string( $link, DBin( $type ) ),
											mysqli_real_escape_string( $link, DBin( $email ) ),
											mysqli_real_escape_string( $link, DBin( $streetAddress ) ),
											mysqli_real_escape_string( $link, DBin( $city ) ),
											mysqli_real_escape_string( $link, DBin( $state ) ),
											mysqli_real_escape_string( $link, DBin( $zipcode ) ),
											mysqli_real_escape_string( $link, DBin( $kcgState ) ),
											mysqli_real_escape_string( $link, DBin( $rating ) ),
											mysqli_real_escape_string( $link, DBin( $_SESSION[ 'user_id' ] ) )
										);
										mysqli_query( $link, $import )or die( mysqli_error( $link ) );
										$contactID = mysqli_insert_id( $link );
									}
								} else {
									//echo "Invalid Phone Number";
								}
							}
						}
						$index++;
					}
					$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Uploaded successfully.</div>';
					unlink( './uploads/' . $fileName );
				} else {
					$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to  upload csv file.</div>';
				}
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Please select a valid csv file and try again.</div>';
			}
			?>
			<script>
				window.location = "contacts.php"
			</script>
			<?php
		}
		break;
	case "load_default_chats":
		{
			$sql = "SELECT DISTINCT customer_number, MAX(created_date) 
					FROM conversations 

					where user_id='" . $_SESSION[ 'user_id' ] . "'
					GROUP BY customer_number 
					ORDER BY MAX(created_date) DESC, customer_number";
			$res = mysqli_query( $link, $sql );
			$allcount = mysqli_num_rows( $res );
			if ( $allcount ) {
				while ( $row = mysqli_fetch_assoc( $res ) ) {
					$customerInfo = getCustomerInfoByNumber( $row[ 'customer_number' ] );
					$lastMsg = getLatestMsgByNumber( $row[ 'customer_number' ] );
					$customerName = $customerInfo[ 'first_name' ] . ' ' . $customerInfo[ 'last_name' ];
					if ( trim( $customerName ) == '' ) {
						$customerName = $row[ 'customer_number' ];
					}
					?>
					<div id="<?php echo $row['customer_number']?>_container" class="d-flex align-items-center justify-content-between border-bottom py-3">
						<div style="width: 100%">
							<div class="h6 mb-0 align-items-center">
								<i class="icon icon-xs me-2 fa fa-phone" style="color: darkorange; cursor: pointer" onClick="showDialer(this,'<?php echo $row['customer_number']?>')"></i>
								<a href="javascript:void(0)" id="<?php echo $row['customer_number']?>_chatStarter" onClick="getChats(this,'<?php echo $row['customer_number']?>')" style="width: 140px;display: inline-block;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
									<?php echo $customerName?>
								</a>
								<span id="<?php echo $row['customer_number']?>_msgTime" style="font-size: 12px;float: right">
									<?php echo date("d M, H:i a",strtotime($lastMsg['created_date']));?>
								</span>
							</div>
							<div id="<?php echo $row['customer_number']?>_message" class="showMessage small card-stats">
								<?php echo $lastMsg['message'];?>
							</div>
						</div>
					</div>
					<?php
				}
				?>
				<input type="hidden" id="start" value="0">
				<input type="hidden" id="rowperpage" value="<?= $rowperpage ?>">
				<input type="hidden" id="totalrecords" value="<?= $allcount ?>">
				<?php
				} else {
					?>
				<div class="d-flex align-items-center justify-content-between border-bottom py-3">
					<div>
						<div class="h6 mb-0 d-flex align-items-center">
							No conversation found.
						</div>
					</div>
				</div>
				<?php
			}
		}
		break;
	case "search_in_sidebar_contacts":
		{
			$searchWord = $_REQUEST[ 'searchWord' ];
			$sql = "select first_name,last_name,cell from customers where user_id='" . $userID . "' and (first_name like '%" . $searchWord . "%') or (last_name like '%" . $searchWord . "%') or (cell like '%" . $searchWord . "%') limit 20";
			$res = mysqli_query( $link, $sql )or die( mysqli_error( $link ) );
			$allcount = mysqli_num_rows( $res );
			if ( $allcount ) {
				while ( $row = mysqli_fetch_assoc( $res ) ) {
					$customerInfo = getCustomerInfoByNumber( $row[ 'cell' ] );
					$lastMsg = getLatestMsgByNumber( $row[ 'cell' ] );
					$customerName = $customerInfo[ 'first_name' ] . ' ' . $customerInfo[ 'last_name' ];
					if ( trim( $customerName ) == '' ) {
						$customerName = $row[ 'cell' ];
					}
					?>
					<div id="<?php echo $row['cell']?>_container" class="d-flex align-items-center justify-content-between border-bottom py-3">
						<div style="width: 100%">
							<div class="h6 mb-0 align-items-center">
								<i class="icon icon-xs me-2 fa fa-phone" style="color: darkorange; cursor: pointer" onClick="showDialer(this,'<?php echo $row['cell']?>')"></i>
								<a href="javascript:void(0)" id="<?php echo $row['cell']?>_chatStarter" onClick="getChats(this,'<?php echo $row['cell']?>')" style="width: 140px;display: inline-block;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
									<?php echo $customerName?>
								</a>
								<span id="<?php echo $row['cell']?>_msgTime" style="font-size: 12px;float: right">
									<?php echo date("d M, H:i a",strtotime($lastMsg['created_date']));?>
								</span>
							</div>
							<div id="<?php echo $row['cell']?>_message" class="showMessage small card-stats">
								<?php echo $lastMsg['message'];?>
							</div>
						</div>
					</div>
					<?php
				}
			} else {
				?>
				<div class="d-flex align-items-center justify-content-between border-bottom py-3">
					<div>
						<div class="h6 mb-0 d-flex align-items-center">
							No conversation found.
						</div>
					</div>
				</div>
				<?php
			}
		}
		break;
	case "delete_custsomer":
		{
			$customerID = $_REQUEST[ 'customerID' ];
			$sql = "delete from customers where id='" . $customerID . "'";
			$res = mysqli_query( $link, $sql );
			if ( $res ) {
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Customer is deleted successfullyl.</div>';
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to delete customer.</div>';
			}
		}
		break;
	case "update_customer":
		{
			$firstName = $_REQUEST[ 'first_name' ];
			$lastName = $_REQUEST[ 'last_name' ];
			$phone = $_REQUEST[ 'phone' ];
			$cell = $_REQUEST[ 'cell' ];
			$address = $_REQUEST[ 'address' ];
			$city = $_REQUEST[ 'city' ];
			$state = $_REQUEST[ 'state' ];
			$zipcode = $_REQUEST[ 'zipcode' ];
			$saleManagerName = $_REQUEST[ 'sale_manager_name' ];
			$saleManagerNumber = $_REQUEST[ 'sale_manager_number' ];
			$projectManagerName = $_REQUEST[ 'project_manager_name' ];
			$projectManagerNumber = $_REQUEST[ 'project_manager_number' ];
			$managementName = $_REQUEST[ 'management_name' ];
			$managementNumber = $_REQUEST[ 'management_number' ];
			$tagworkflow = $_REQUEST[ 'tagworkflow' ];
			$sql = "update customers set
						first_name='" . $firstName . "',
						last_name='" . $lastName . "',
						phone='" . $phone . "',
						cell='" . $cell . "',
						address='" . $address . "',
						city='" . $city . "',
						state='" . $state . "',
						zipcode='" . $zipcode . "',
						sales_manager_name='" . $saleManagerName . "',
						sales_manager_number='" . $saleManagerNumber . "',
						project_manager_name='" . $_REQUEST[ 'project_manager_name' ] . "',
						project_manager_number='" . $projectManagerNumber . "',
						management_name='" . $managementName . "',
						management_number='" . $managementNumber . "',
						tag_workflow='" . $tagworkflow . "'
					where
						id='" . $_REQUEST[ 'id' ] . "'";
			$res = mysqli_query( $link, $sql );
			if ( $res ) {
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Customer is updated successfullyl.</div>';
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to update customer.</div>';
			}
			header( "location: customers.php" );
		}
		break;
	case "search_contact_for_new_chat":
		{
			$searchWord = $_REQUEST[ 'searchWord' ];
			$sql = "select first_name,last_name,cell from customers where user_id='" . $userID . "' and (first_name like '%" . $searchWord . "%') or (last_name like '%" . $searchWord . "%') or (cell like '%" . $searchWord . "%') limit 20";
			$res = mysqli_query( $link, $sql )or die( mysqli_error( $link ) );
			if ( mysqli_num_rows( $res ) ) {
				while ( $row = mysqli_fetch_assoc( $res ) ) {
					$cell = $row[ 'cell' ];
					?>
					<div id="<?php echo $cell?>_container" class="d-flex align-items-center justify-content-between border-bottom py-3">
						<div style="width: 100%">
							<div class="h6 mb-0 align-items-center">
								<i class="icon icon-xs me-2 fa fa-phone" style="color: darkorange;"></i>
								<a href="javascript:void(0)" id="<?php echo $cell?>_chatStarter" onclick="startNewChatUpdated(this,'<?php echo $cell?>','<?php echo $row['first_name'].' '.$row['last_name']?>')">
									<?php echo $row['first_name'].' '.$row['last_name']?>
								</a>
							</div>
						</div>
					</div>
					<?php
				}
			} else {
				?>
				<div class="d-flex align-items-center justify-content-between border-bottom py-3">
					<div style="width: 100%">
						<div class="h6 mb-0 align-items-center">
							No contact found according to your search.
						</div>
					</div>
				</div>
				<?php
			}
		}
		break;
	case "search_in_chat":
		{
			$customerNumber = $_REQUEST[ 'customerNumber' ];
			$searchWord = $_REQUEST[ 'searchWord' ];
			if ( trim( $searchWord ) != '' ) {
				$sql = "select direction,is_read,message,media,created_date,media_content_type,media_extension from conversations where message like '%" . $searchWord . "%' and customer_number='" . $customerNumber . "' order by id asc";
			} else {
				$sql = "select direction,is_read,message,media,created_date,media_content_type,media_extension from conversations where customer_number='" . $customerNumber . "' order by id asc";
			}
			$res = mysqli_query( $link, $sql );
			$totalChats = mysqli_num_rows( $res );
			$chats = '';
			$customerInfo = getCustomerInfoByNumber( $customerNumber );
			if ( $totalChats > $oldChats ) {
				while ( $row = mysqli_fetch_assoc( $res ) ) {
					if ( trim( $row[ 'media' ] ) != 'no' ) {
						$media = $row[ 'media' ];
					} else {
						$media = '';
					}
					if ( $row[ 'direction' ] == 'in' ) {
						$chats .= '<div class="card border-0 shadow p-4 mb-4"><div class="d-flex justify-content-between align-items-center mb-2"><span class="font-small"><a href="javascript:void(0)"><img class="avatar-sm img-fluid rounded-circle me-2" src="../assets/img/team/profile-picture-1.jpg" alt="avatar"><span class="fw-bold">' . $customerInfo[ 'first_name' ] . ' ' . $customerInfo[ 'last_name' ] . '</span></a><span class="fw-normal ms-2">' . date( "M d, H:i", strtotime( $row[ 'created_date' ] ) ) . '</span></span><div class="d-none d-sm-block"><svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div></div><p class="m-0">' . $row[ 'message' ] . '</p>';
						if ( trim( $media ) != '' ) {
							if ( $row[ 'media_content_type' ] == 'image' ) {
								$chats .= '<div class="card border-0 shadow mediaContainer" style="padding: 7px;"><div class="card-body p-0"><img src="' . $media . '" class="chatMedia card-img-top mb-2 mb-lg-3" alt="media"></div></div></div>';
							} elseif ( $row[ 'media_content_type' ] == 'video' ) {
								$chats .= '<div class="card border-0 shadow mediaContainer" style="padding: 7px;"><div class="card-body p-0"><i class="fa fa-video-camera" alt="media" style="font-size:75px;cursor:pointer" onclick="loadVideo(\'' . $media . '\')" data-bs-toggle="modal" data-bs-target="#videoPlayerbox"></i></div></div></div>';
							}
						} else {
							$chats .= '</div>';
						}
					} else {
						$chats .= '<div class="card text-black border-0 shadow p-4 ms-md-5 ms-lg-6 mb-4" style="background-color:#D9FDD3"><div class="d-flex justify-content-between align-items-center mb-2"><span class="font-small"><span class="fw-bold">' . $_SESSION[ 'first_name' ] . ' ' . $_SESSION[ 'last_name' ] . '</span><span class="fw-normal text-black-300 ms-2">' . date( "M d, H:i", strtotime( $row[ 'created_date' ] ) ) . '</span></span><div class="d-none d-sm-block"><svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div></div><p class="text-black-300 m-0">' . $row[ 'message' ] . '</p>';
						if ( trim( $media ) != '' ) {
							if ( $row[ 'media_content_type' ] == 'image' ) {
								$chats .= '<div class="card border-0 shadow mediaContainer" style="padding: 7px;"><div class="card-body p-0"><img src="uploads/' . $media . '" class="chatMedia card-img-top mb-2 mb-lg-3" alt="media"></div></div></div>';
							} elseif ( $row[ 'media_content_type' ] == 'video' ) {
								$chats .= '<div class="card border-0 shadow mediaContainer" style="padding: 7px;"><div class="card-body p-0"><i class="fa fa-video-camera" alt="media" style="font-size:75px;cursor:pointer" onclick="loadVideo(\'' . $media . '\')" data-bs-toggle="modal" data-bs-target="#videoPlayerbox"></i></div></div></div>';
							} else {
								$chats .= '</div>';
							}
						} else {
							$chats .= '</div>';
						}
					}
				}
				$chats = json_encode( $chats );
				echo '{"chats":' . $chats . ',"auto_load_chat":"no","customer_name":"' . $customerInfo[ 'first_name' ] . ' ' . $customerInfo[ 'last_name' ] . '","customer_number":"' . $customerNumber . '"}';
			} else {
				echo '{"chats":"","auto_load_chat":"no","customer_name":"' . $customerInfo[ 'first_name' ] . ' ' . $customerInfo[ 'last_name' ] . '","customer_number":"' . $customerNumber . '"}';
			}
		}
		break;
	case "download_subcon_sample_csv_file":
		{
			downloadFile( 'sample_sub_con.csv' );
		}
		break;
	case "upload_dc_subcontractor":
		{
			$ext = getExtension( $_FILES[ 'dc_subcon' ][ 'name' ] );
			if ( $ext == 'csv' ) {
				$fileName = uniqid() . '.' . $ext;
				$tmpName = $_FILES[ 'dc_subcon' ][ 'tmp_name' ];
				$r = move_uploaded_file( $tmpName, 'uploads/' . $fileName );
				if ( $r ) {
					$index = 0;
					$handle = fopen( "uploads/$fileName", "r" );
					while ( ( $data = fgetcsv( $handle, 1000, "," ) ) !== FALSE ) {
						if ( $index > 0 ) {
							if ( $number = trim( $data[ 0 ] ) == '' ) {
								$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> csv file is empty or not well formated.</div>';
							} else {
								$company = trim( $data[ 0 ] );
								$division = trim( $data[ 1 ] );
								$primary_contact = trim( $data[ 2 ] );
								$cell = trim( $data[ 3 ] );
								$phone = trim( $data[ 4 ] );
								$email = trim( $data[ 5 ] );
								$city = trim( $data[ 6 ] );
								$street_address = trim( $data[ 7 ] );
								$zipcode = trim( $data[ 8 ] );
								$kcg_state = trim( $data[ 9 ] );
								$rating = trim( $data[ 10 ] );
								$state = trim( $data[ 11 ] );
								$illigal = array( "-", "_", " ", "(", ")", ".", "&nbsp;" );
								$phone = str_replace( $illigal, "", $phone );
								$cell = str_replace( $illigal, "", $cell );
								$cell = removeCountryCode( $cell );
								if ( preg_match( '/^[0-9]{10}+$/', $cell ) ) { // "Valid Phone Number";
									$sql = sprintf( "select id from dc_subcon where cell='%s'", mysqli_real_escape_string( $link, $cell ) );
									$res = mysqli_query( $link, $sql );
									if ( mysqli_num_rows( $res ) == 0 ) {
										$import = sprintf( "INSERT into dc_subcon 
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
											mysqli_real_escape_string( $link, DBin( $company ) ),
											mysqli_real_escape_string( $link, DBin( $division ) ),
											mysqli_real_escape_string( $link, DBin( $primary_contact ) ),
											mysqli_real_escape_string( $link, DBin( $cell ) ),
											mysqli_real_escape_string( $link, DBin( $phone ) ),
											mysqli_real_escape_string( $link, DBin( $email ) ),
											mysqli_real_escape_string( $link, DBin( $city ) ),
											mysqli_real_escape_string( $link, DBin( $street_address ) ),
											mysqli_real_escape_string( $link, DBin( $zipcode ) ),
											mysqli_real_escape_string( $link, DBin( $kcg_state ) ),
											mysqli_real_escape_string( $link, DBin( $rating ) ),
											mysqli_real_escape_string( $link, DBin( $state ) ),
											mysqli_real_escape_string( $link, DBin( $userID ) )
										);
										mysqli_query( $link, $import )or die( mysqli_error( $link ) );
										$customerID = mysqli_insert_id( $link );
									}
								} else {
									//echo "Invalid Phone Number";
								}
							}
						}
						$index++;
					}
					$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Uploaded successfully.</div>';
					unlink( 'uploads/' . $fileName );
				} else {
					$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to  upload csv file.</div>';
				}
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Please select a valid csv file and try again.</div>';
			}
			?>
			<script>
				window.location = "dc_subcon.php"
			</script>
			<?php
		}
		break;
	case "start_new_chat":
		{
			$firstName = $_REQUEST[ 'first_name' ];
			$lastName = $_REQUEST[ 'last_name' ];
			$contactType = $_REQUEST[ 'contact_type' ];
			$phoneNumber = $_REQUEST[ 'phone_number' ];
			$message = $_REQUEST[ 'message' ];
			$check = "select id from customers where cell='" . $phoneNumber . "' limit 1";
			$exe = mysqli_query( $link, $check );
			if ( mysqli_num_rows( $exe ) == 0 ) {
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
								'" . $firstName . "',
								'" . $lastName . "',
								'" . $contactType . "',
								'" . $phoneNumber . "',
								'" . $userID . "'
							)";
				$res = mysqli_query( $link, $sql );
				if ( $res ) {
					$url = "https://api.twilio.com/2010-04-01/Accounts/" . $sid . "/Messages.json";
					$data = array(
						'From' => $from,
						'To' => $phoneNumber,
						'Body' => $message
					);
					$post = http_build_query( $data );
					$x = curl_init( $url );
					curl_setopt( $x, CURLOPT_POST, true );
					curl_setopt( $x, CURLOPT_RETURNTRANSFER, true );
					curl_setopt( $x, CURLOPT_SSL_VERIFYPEER, false );
					curl_setopt( $x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
					curl_setopt( $x, CURLOPT_USERPWD, $sid . ":" . $token );
					curl_setopt( $x, CURLOPT_POSTFIELDS, $data );
					$response = json_decode( curl_exec( $x ), true );
					curl_close( $x );
					$isSent = 'false';
					if ( $response[ 'sid' ] != '' ) {
						$isSent = 'true';
						$smsSid = $response[ 'sid' ];
					} else {
						$smsSid = $response[ 'message' ];
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
									'" . $phoneNumber . "',
									'" . $userID . "',
									'" . $phoneNumber . "',
									'" . $from . "',
									'" . dbIn( $message ) . "',
									'true',
									'" . $smsSid . "',
									'out'
								)";
					mysqli_query( $link, $ins );
				}
			}
		}
		break;
	case "add_customer":
		{
			$firstName = $_REQUEST[ 'first_name' ];
			$lastName = $_REQUEST[ 'last_name' ];
			$phone = $_REQUEST[ 'phone' ];
			$cell = $_REQUEST[ 'cell' ];
			$address = $_REQUEST[ 'address' ];
			$city = $_REQUEST[ 'city' ];
			$state = $_REQUEST[ 'state' ];
			$zipcode = $_REQUEST[ 'zipcode' ];
			$saleManagerName = $_REQUEST[ 'sale_manager_name' ];
			$saleManagerNumber = $_REQUEST[ 'sale_manager_number' ];
			$projectManagerName = $_REQUEST[ 'project_manager_name' ];
			$projectManagerNumber = $_REQUEST[ 'project_manager_number' ];
			$managementName = $_REQUEST[ 'management_name' ];
			$managementNumber = $_REQUEST[ 'management_number' ];
			$tagworkflow = $_REQUEST[ 'tagworkflow' ];
			$sel = "select id from customers where cell='" . $cell . "'";
			$exe = mysqli_query( $link, $sel );
			if ( mysqli_num_rows( $exe ) == 0 ) {
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
								'" . $firstName . "',
								'" . $lastName . "',
								'" . $phone . "',
								'" . $cell . "',
								'" . $address . "',
								'" . $city . "',
								'" . $state . "',
								'" . $zipcode . "',
								'" . $saleManagerName . "',
								'" . $saleManagerNumber . "',
								'" . $projectManagerName . "',
								'" . $projectManagerNumber . "',
								'" . $managementName . "',
								'" . $managementNumber . "',
								'" . $tagworkflow . "',
								'" . $_SESSION[ 'user_id' ] . "'
							)";
				$res = mysqli_query( $link, $sql );
				if ( $res ) {
					$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Customer is added successfullyl.</div>';
				} else {
					$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to add customer.</div>';
				}
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Customer with same cell number is added in the system.</div>';
			}
			header( "location: customers.php" );
		}
		break;
	case "get_user_files":
		{
			$toNumber = $_REQUEST[ 'toNumber' ];
			$sql = "select media,media_content_type,media_extension,direction from conversations where customer_number='" . $toNumber . "' order by id asc";
			$res = mysqli_query( $link, $sql );
			$html = '';
			if ( mysqli_num_rows( $res ) ) {
				while ( $row = mysqli_fetch_assoc( $res ) ) {
					if ( ( $row[ 'media' ] != '' )and( $row[ 'media' ] != 'no' ) ) {
						if ( $row[ 'direction' ] == 'in' ) {
							if ( $row[ 'media_content_type' ] == 'image' ) {
								$html .= '<div class="col-md-3"><img src="' . $row[ 'media' ] . '" class="userMedia" /></div>';
							} else {
								/*
								$html .= '<div class="col-md-3"><video width="320" height="240" controls><source src="'.$row['media'].'" type="video/'.$row['media_extension'].'">Your browser does not support the video tag.</video></div>';
								*/
								$html .= '<div class="col-md-3"><i class="fa fa-video-camera" alt="media" style="font-size:11rem"></i></div>';
							}
						} else {
							if ( $row[ 'media_content_type' ] == 'image' ) {
								$html .= '<div class="col-md-3"><img src="uploads/' . $row[ 'media' ] . '" class="userMedia" /></div>';
							} else {
								/*
								$html .= '<div class="col-md-3"><video width="320" height="240" controls><source src="uploads/'.$row['media'].'" type="video/'.$row['media_extension'].'">Your browser does not support the video tag.</video></div>';
								*/
								$html .= '<div class="col-md-3"><i class="fa fa-video-camera" alt="media" style="font-size:11rem"></i></div>';
							}
						}
					}
				}
			}
			if ( $html == '' ) {
				$html = '<div class="col-md-3">No file found.</div>';
			}
			echo $html;
		}
		break;
	case "get_notes":
		{
			$toNumber = $_REQUEST[ 'toNumber' ];
			$sql = "select notes from customers where (cell='" . $toNumber . "') or (cell='" . removeCountryCode( $toNumber ) . "')";
			$res = mysqli_query( $link, $sql );
			$row = mysqli_fetch_assoc( $res );
			echo $row[ 'notes' ];
		}
		break;
	case "save_notes":
		{
			$notes = $_REQUEST[ 'notes' ];
			$toNumber = $_REQUEST[ 'toNumber' ];
			echo $sql = "update customers set notes='" . $notes . "' where (cell='" . $toNumber . "') or (cell='" . removeCountryCode( $toNumber ) . "')";
			$res = mysqli_query( $link, $sql )or die( mysqli_error( $link ) );
		}
		break;
	case "switch_company":
		{
			$companyID = $_REQUEST[ 'companyID' ];
			$sql = "select id,first_name,last_name,business_name,type from users where id='" . $companyID . "'";
			$res = mysqli_query( $link, $sql );
			if ( mysqli_num_rows( $res ) ) {
				$row = mysqli_fetch_assoc( $res );
				$_SESSION[ 'first_name' ] = $row[ 'first_name' ];
				$_SESSION[ 'last_name' ] = $row[ 'last_name' ];
				$_SESSION[ 'user_id' ] = $row[ 'id' ];
				$_SESSION[ 'user_type' ] = $row[ 'type' ];
				$_SESSION[ 'business_name' ] = $row[ 'business_name' ];
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Failed!</strong> failed to login with the selected company.</div>';
				header( "location:index.php" );
			}
		}
		break;
	case "delete_conatact":
		{
			$contactID = $_REQUEST[ 'contactID' ];
			$listID = $_REQUEST[ 'listID' ];
			$sql = "delete from customers where id='" . $contactID . "' limit 1";
			$res = mysqli_query( $link, $sql );
			if ( $res ) {
				mysqli_query( $link, "delete from list_assignment where customer_id='" . $contactID . "' and list_id='" . $listID . "'" );
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Contact is deleted successfullyl.</div>';
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to delete contact.</div>';
			}
		}
		break;
	case "fetch_more_inbox_contacts":
		{
			//$toNumber = $_REQUEST['toNumber'];
			$start = $_REQUEST[ 'start' ];
			$rowperpage = $_REQUEST[ 'rowperpage' ];
			$sql = "select * from broadcast_history order by id desc limit " . $start . "," . $rowperpage;
			$res = mysqli_query( $link, $sql );
			if ( mysqli_num_rows( $res ) ) {
				while ( $row = mysqli_fetch_assoc( $res ) ) {
					$poneNumber = $row[ 'from_number' ];
					if ( $poneNumber == '' ) {
						$poneNumber = $row[ "to_number" ];
					}
					echo '<div class="d-flex align-items-center justify-content-between border-bottom py-3">
								<div style="width: 100%">
									<div class="h6 mb-0 align-items-center">
										<i class="icon icon-xs me-2 fa fa-phone" style="color: darkorange"></i>
										<a href="javascript:void(0)" onclick="getChats(' . $poneNumber . ')">' . $poneNumber . '</a>
										<span style="font-size: 12px;float: right">' . date( "H:i a", strtotime( $row[ 'created_date' ] ) ) . '</span>
									</div>
									<div class="showMessage small card-stats">' . $row[ 'message' ] . '</div>
								</div>
							</div>';
				}
			}
		}
		break;
	case "get_attendies":
		{
			$department = $_REQUEST[ 'department' ];
			$sql = "select * from staff where role='" . $department . "'";
			$res = mysqli_query( $link, $sql );
			$options = '';
			if ( mysqli_num_rows( $res ) ) {
				while ( $row = mysqli_fetch_assoc( $res ) ) {
					$optionsArray[ $row[ 'phone' ] ] = removeCountryCode( $row[ "name" ] );
				}
				echo json_encode( $optionsArray );
			} else {
				echo '{"Error":"Nothing found"}';
			}
		}
		break;
	case "delete_event":
		{
			$eventID = $_REQUEST[ 'eventID' ];
			$sql = "delete from schedulers where id='" . $eventID . "'";
			$res = mysqli_query( $link, $sql );
			if ( $res ) {
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Event is deleted successfully.</div>';
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to delete event.</div>';
			}
		}
		break;
	case "update_schedule_message":
		{
			$eventID = $_REQUEST[ 'eventID' ];
			$eventTitle = $_REQUEST[ 'title' ];
			$startDate = date( "Y-m-d H:i", strtotime( $_REQUEST[ 'startDate' ] ) );
			$message = $_REQUEST[ 'message' ];
			$department = $_REQUEST[ 'department' ];
			$attendies = $_REQUEST[ 'attendies' ];
			$workflow_id = $_REQUEST[ 'workflow_id' ];
			$isRecurring = $_REQUEST[ 'isRecurring' ];
			if ( $isRecurring != '1' ) {
				$isRecurring = '0';
			}
			$sql = "update schedulers set
						event_title='" . $eventTitle . "',
						start_date='" . $startDate . "',
						message='" . $message . "',
						department='" . $department . "',
						attendies='" . $attendies . "',
						workflow_id='" . $workflow_id . "',
						is_recurring='" . $isRecurring . "'
					where
						id='" . $eventID . "'";
			$res = mysqli_query( $link, $sql );
			if ( $res ) {
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Event is updated successfullyl.</div>';
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to update event.</div>';
			}
		}
		break;
	case "schedule_message":
		{
			$eventTitle = $_REQUEST[ 'title' ];
			$startDate = date( "Y-m-d H:i", strtotime( $_REQUEST[ 'startDate' ] ) );
			$message = $_REQUEST[ 'message' ];
			$department = $_REQUEST[ 'department' ];
			$attendies = $_REQUEST[ 'attendies' ];
			$workflow_id = $_REQUEST[ 'workflow_id' ];
			$isRecurring = $_REQUEST[ 'isRecurring' ];
			if ( $isRecurring != '1' ) {
				$isRecurring = '0';
			}
			$sql = "insert into schedulers
						(
							event_title,
							start_date,
							message,
							department,
							attendies,
							workflow_id,
							is_recurring,
							user_id
						)
					values
						(
							'" . $eventTitle . "',
							'" . $startDate . "',
							'" . DBin( $message ) . "',
							'" . $department . "',
							'" . $attendies . "',
							'" . $workflow_id . "',
							'" . $isRecurring . "',
							'" . $_SESSION[ 'user_id' ] . "'
						)";
			$res = mysqli_query( $link, $sql );
			if ( $res ) {
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Event is scheduled successfullyl.</div>';
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to schedule event.</div>';
			}
		}
		break;
	case "add_staff":
		{
			$phone = addCountryCode( $_REQUEST[ 'phone' ] );
			$sql = "select id from staff where phone='" . $phone . "'";
			$res = mysqli_query( $link, $sql );
			if ( mysqli_num_rows( $res ) == '0' ) {
				$ins = "insert into staff
							(
								name,
								phone,
								role,
								email
							)
						values
							(
								'" . $_REQUEST[ 'name' ] . "',
								'" . $phone . "',
								'" . $_REQUEST[ 'role' ] . "',
								'" . $_REQUEST[ 'email' ] . "'
							)";
				mysqli_query( $link, $ins );
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Staff is added successfully.</div>';
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> phone number is already added as a staff.</div>';
			}
			header( "location: staff.php" );
		}
		break;
	case "delete_staff":
		{
			$staffID = $_REQUEST[ 'staffID' ];
			$sql = "delete from contacts where id='" . $staffID . "' limit 1";
			$res = mysqli_query( $link, $sql );
			if ( $res ) {
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Contact is deleted successfullyl.</div>';
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to delete contact.</div>';
			}
			//header("location: staff.php");
		}
		break;
	case "update_staff":
		{
			$id = $_REQUEST[ 'id' ];
			$name = $_REQUEST[ 'name' ];
			$phone = $_REQUEST[ 'phone' ];
			$role = $_REQUEST[ 'role' ];
			$email = $_REQUEST[ 'email' ];
			$sql = "update staff set name='" . $name . "', phone='" . $phone . "', role='" . $role . "', email='" . $email . "' where id='" . $id . "'";
			$res = mysqli_query( $link, $sql );
			if ( $res ) {
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Staff is updated successfullyl.</div>';
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to update staff.</div>';
			}
			header( "location: staff.php" );
		}
		break;
	case "send_broadcast_to_the_customers":
		{
			$comingFrom = $_REQUEST[ 'coming_from' ];
			$recipients = json_decode( $_REQUEST[ 'recipients' ], true );
			$isSent = 'false';
			$totalRecipients = count( $recipients );
			if ( $totalRecipients > 0 ) {
				if ( $_FILES[ 'broadcast_media' ][ 'name' ] != '' ) {
					$ext = getExtension( $_FILES[ 'broadcast_media' ][ 'name' ] );
					if ( in_array( $ext, $alloweMediaExtensions ) ) {
						$fileName = uniqid() . '.' . $ext;
						$tmpName = $_FILES[ 'broadcast_media' ][ 'tmp_name' ];
						$r = move_uploaded_file( $tmpName, 'uploads/' . $fileName );
						if ( $r ) {
							$fileName = getServerUrl() . '/uploads/' . $fileName;
						}
					} else {
						$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> please select a valid media file.</div>';
						header( "location: " . $_SERVER[ 'HTTP_REFERER' ] );
						die( "Please select a valid media file." );
					}
				} else {
					$fileName = '';
				}
				for ( $i = 0; $i < $totalRecipients; $i++ ) {
					$customerInfo = getCustomerInfoByNumber( $recipients[ $i ] );
					$broadcastSms = $_REQUEST[ 'broadcast_sms' ];
					$broadcastSms = str_replace( "%name%", $customerInfo[ 'first_name' ] . ' ' . $customerInfo[ 'last_name' ], $broadcastSms );
					//$broadcastSms = str_replace("%address%",$customerInfo['address'],$broadcastSms);
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
									'" . $from . "',
									'" . $recipients[ $i ] . "',
									'" . DBin( $broadcastSms ) . "',
									'" . $fileName . "',
									'" . $comingFrom . "',
									'" . $_SESSION[ 'user_id' ] . "'
								)";
					mysqli_query( $link, $ins );
				}
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Broadcast is now sending.</div>';
				$url = getServerUrl() . '/cron.php?limit=50';
				postCurl( $url, array() );
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to queue broadcast.</div>';
			}
			/*
			if($comingFrom=='dc_subcontractors')
				header("location: dc_subcon.php");
			else if($comingFrom=='contacts')
				header("location: contacts.php");
			else
				header("location: customers.php");
			*/
			header( "location: " . $_SERVER[ "HTTP_REFERER" ] );
		}
		break;
	case "download_sample_csv":
		{
			downloadFile( 'sample.csv' );
		}
		break;
	case "send_chat_message":
		{
			$message = $_REQUEST[ 'message' ];
			$toNumber = $_REQUEST[ 'to_number' ];
			$chatNumber = getChatNumber( $toNumber, $userID );
			$from = $chatNumber;
			if ( trim( $_FILES[ 'chat_media' ][ 'name' ] ) != '' ) {
				$ext = getExtension( $_FILES[ 'chat_media' ][ 'name' ] );
				//if(in_array($ext,$alloweMediaExtensions)){
				$fileName = uniqid() . '.' . $ext;
				$tmpName = $_FILES[ 'chat_media' ][ 'tmp_name' ];
				$r = move_uploaded_file( $tmpName, 'uploads/' . $fileName );
				if ( $r ) {
					$url = "https://api.twilio.com/2010-04-01/Accounts/" . $sid . "/Messages.json";
					$data = array(
						'From' => $from,
						'To' => $toNumber,
						'Body' => $message,
						"MediaUrl" => getServerUrl() . '/uploads/' . $fileName
					);
					$post = http_build_query( $data );
					$x = curl_init( $url );
					curl_setopt( $x, CURLOPT_POST, true );
					curl_setopt( $x, CURLOPT_RETURNTRANSFER, true );
					curl_setopt( $x, CURLOPT_SSL_VERIFYPEER, false );
					curl_setopt( $x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
					curl_setopt( $x, CURLOPT_USERPWD, $sid . ":" . $token );
					curl_setopt( $x, CURLOPT_POSTFIELDS, $data );
					$response = json_decode( curl_exec( $x ), true );
					curl_close( $x );
					if ( $response[ 'sid' ] != '' ) {
						$isSent = 'true';
						$smsSid = $response[ 'sid' ];
					}
					$array = array( 'wmv', 'avi', 'avchd', 'flv', 'mkv', 'webm', 'mp4', 'mpeg' );
					if ( in_array( $ext, $array ) ) {
						$mediaContentType = 'video';
						$fileName = getServerUrl() . '/uploads/' . $fileName;
					} else {
						$mediaContentType = 'image';
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
									media,
									media_content_type,
									media_extension
								)
							values
								(
									'" . $toNumber . "',
									'" . $_SESSION[ 'user_id' ] . "',
									'" . $toNumber . "',
									'" . $from . "',
									'" . dbIn( $message ) . "',
									'true',
									'" . $smsSid . "',
									'out',
									'" . $fileName . "',
									'" . $mediaContentType . "',
									'" . $ext . "'
								)";
					mysqli_query( $link, $ins );
					echo '{"error":"no","message":"Send successfully."}';
				}
				/*	
				}else{
					echo '{"error":"yes","message":"Please select a valid image."}';
				}
				*/
			} else {
				$url = "https://api.twilio.com/2010-04-01/Accounts/" . $sid . "/Messages.json";
				$data = array(
					'From' => $from,
					'To' => $toNumber,
					'Body' => $message
				);
				$post = http_build_query( $data );
				$x = curl_init( $url );
				curl_setopt( $x, CURLOPT_POST, true );
				curl_setopt( $x, CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $x, CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
				curl_setopt( $x, CURLOPT_USERPWD, $sid . ":" . $token );
				curl_setopt( $x, CURLOPT_POSTFIELDS, $data );
				$response = json_decode( curl_exec( $x ), true );
				curl_close( $x );
				if ( $response[ 'sid' ] != '' ) {
					$isSent = 'true';
					$smsSid = $response[ 'sid' ];
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
								'" . $toNumber . "',
								'" . $_SESSION[ 'user_id' ] . "',
								'" . $toNumber . "',
								'" . $from . "',
								'" . dbIn( $message ) . "',
								'true',
								'" . $smsSid . "',
								'out'
							)";
				mysqli_query( $link, $ins );
				echo '{"error":"no","message":"Send successfully."}';
			}
		}
		break;
		
	case "get_chat":{
			$customerNumber = $_REQUEST[ 'customerNumber' ];
			$sql = "select direction,is_read,message,media,created_date,media_content_type,media_extension from conversations where customer_number='" . $customerNumber . "' order by id asc";
			$res = mysqli_query( $link, $sql );
			$totalChats = mysqli_num_rows( $res );
			$chats = '';
			$customerInfo = getCustomerInfoByNumber( $customerNumber );
			if ( $totalChats > $oldChats ) {
				while ( $row = mysqli_fetch_assoc( $res ) ) {
					if ( trim( $row[ 'media' ] ) != 'no' ) {
						$media = $row[ 'media' ];
					} else {
						$media = '';
					}
					if ( $row[ 'direction' ] == 'in' ) {
						$chats .= '<div class="card border-0 shadow p-4 mb-4"><div class="d-flex justify-content-between align-items-center mb-2"><span class="font-small"><a href="javascript:void(0)"><img class="avatar-sm img-fluid rounded-circle me-2" src="../assets/img/team/profile-picture-1.jpg" alt="avatar"><span class="fw-bold">' . $customerInfo[ 'first_name' ] . ' ' . $customerInfo[ 'last_name' ] . '</span></a><span class="fw-normal ms-2">' . date( "M d, H:i", strtotime( $row[ 'created_date' ] ) ) . '</span></span><div class="d-none d-sm-block"><svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div></div><p class="m-0">' . $row[ 'message' ] . '</p>';
						if ( trim( $media ) != '' ) {
							if ( $row[ 'media_content_type' ] == 'image' ) {
								$chats .= '<div class="card border-0 shadow mediaContainer" style="padding: 7px;"><div class="card-body p-0"><img src="' . $media . '" class="chatMedia card-img-top mb-2 mb-lg-3" alt="media"></div></div></div>';
							} elseif ( $row[ 'media_content_type' ] == 'video' ) {
								$chats .= '<div class="card border-0 shadow mediaContainer" style="padding: 7px;"><div class="card-body p-0"><i class="fa fa-video-camera" alt="media" style="font-size:75px;cursor:pointer" onclick="loadVideo(\'' . $media . '\')" data-bs-toggle="modal" data-bs-target="#videoPlayerbox"></i></div></div></div>';
							}
						} else {
							$chats .= '</div>';
						}
					} 
					else {
						$chats .= '<div class="card text-black border-0 shadow p-4 ms-md-5 ms-lg-6 mb-4" style="background-color:#D9FDD3"><div class="d-flex justify-content-between align-items-center mb-2"><span class="font-small"><span class="fw-bold">' . $_SESSION[ 'first_name' ] . ' ' . $_SESSION[ 'last_name' ] . '</span><span class="fw-normal text-black-300 ms-2">' . date( "M d, H:i", strtotime( $row[ 'created_date' ] ) ) . '</span></span><div class="d-none d-sm-block"><svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div></div><p class="text-black-300 m-0">' . $row[ 'message' ] . '</p>';
						if ( trim( $media ) != '' ) {
							if ( $row[ 'media_content_type' ] == 'image' ) {
								if ( in_array( $row[ "media_extension" ], $imageExtensions ) ) {
									$chats .= '<div class="card border-0 shadow mediaContainer" style="padding: 7px;"><div class="card-body p-0"><img src="uploads/' . $media . '" class="chatMedia card-img-top mb-2 mb-lg-3" alt="media"></div></div></div>';
								} else {
									$chats .= '<div class="card border-0 shadow mediaContainer" style="padding: 7px;"><div class="card-body p-0"><img src="' . getServerUrl() . '/assets/img/attachment.png" class="chatMedia card-img-top mb-2 mb-lg-3" alt="media"></div></div></div>';
								}
							} elseif ( $row[ 'media_content_type' ] == 'video' ) {
								$chats .= '<div class="card border-0 shadow mediaContainer" style="padding: 7px;"><div class="card-body p-0"><i class="fa fa-video-camera" alt="media" style="font-size:75px;cursor:pointer" onclick="loadVideo(\'' . $media . '\')" data-bs-toggle="modal" data-bs-target="#videoPlayerbox"></i></div></div></div>';
							} else {
								$chats .= '</div>';
							}
						} else {
							$chats .= '</div>';
						}
					}
				}
				$chats = json_encode( $chats );
				echo '{"chats":' . $chats . ',"auto_load_chat":"no","customer_name":"' . $customerInfo[ 'first_name' ] . ' ' . $customerInfo[ 'last_name' ] . '","customer_number":"'.$customerNumber.'","contact_id":"'.$customerInfo['id'].'"}';
			} else {
				echo '{"chats":"","auto_load_chat":"no","customer_name":"' . $customerInfo[ 'first_name' ] . ' ' . $customerInfo[ 'last_name' ] . '","customer_number":"' . $customerNumber . '","contact_id":"'.$customerInfo['id'].'"}';
			}
		}
		break;
		
	case "send_broadcast":{
		$listID = $_REQUEST['list_id'];
		$sql = "select * from contacts c, list_assignment li where li.customer_id=c.id and li.list_id='" . $listID . "'";
		$res = mysqli_query( $link, $sql )or die( mysqli_error( $link ) );
		if ( mysqli_num_rows( $res ) ) {
			$twilioNumbers = getRandomTwilioNumbers( $_SESSION[ 'user_id' ] );
			if ( count( $twilioNumbers ) < 1 ) {
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> you have no twilio number to send message.</div>';
				header( "location: " . $_SERVER[ "HTTP_REFERER" ] );
				die();
			}
			if ( $_FILES[ 'broadcast_media' ][ 'name' ] != '' ) {
				$ext = getExtension( $_FILES[ 'broadcast_media' ][ 'name' ] );
				if ( in_array( $ext, $alloweMediaExtensions ) ) {
					$fileName = uniqid() . '.' . $ext;
					$tmpName = $_FILES[ 'broadcast_media' ][ 'tmp_name' ];
					$r = move_uploaded_file( $tmpName, 'uploads/' . $fileName );
					if ( $r ) {
						$fileName = getServerUrl() . '/uploads/' . $fileName;
					}
				} else {
					$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> please select a valid image.</div>';
					header( "location: " . $_SERVER[ 'HTTP_REFERER' ] );
					die( "Please select a valid image file." );
				}
			} else {
				$fileName = '';
			}
			while ( $row = mysqli_fetch_assoc( $res ) ) {
				$to = $row[ 'phone' ];
				$bulkSMS = $_REQUEST[ 'bulk_sms' ];
				$address = '';
				if(trim($row['street_address'])!='')
					$address .= $row['street_address'];
				
				if(trim($row['city'])!='')
					$address .= ', '.$row['city'];
				
				if(trim($row['state'])!='')
					$address .= ', '.$row['state'];
				
				if(trim($row['zipcode'])!='')
					$address .= ', '.$row['zipcode'];
					
				if(trim($address)==''){
					$address = 'your home';
				}
				
				$bulkSMS = str_replace( "%name%", $row[ 'first_name' ], $bulkSMS );
				$bulkSMS = str_replace( "%address%", $address, $bulkSMS );
				//$bulkSMS = str_replace( "%project_manager%", $row[ 'project_manager' ], $bulkSMS );
				$bulkSMS = str_replace( "%email%", $row[ 'email' ], $bulkSMS );
				$key = array_rand( $twilioNumbers, 1 );
				$from = removeCountryCode( $twilioNumbers[ $key ] );
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
								'" . $from . "',
								'" . $to . "',
								'" . DBin( $bulkSMS ) . "',
								'" . $fileName . "',
								'" . $_SESSION[ 'user_id' ] . "'
							)";
				$r = mysqli_query( $link, $ins );
				if ( $r ) {
					$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Broadcast is now sending.</div>';
				} else {
					$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to queue broadcast.</div>';
				}
			}
			$url = getServerUrl() . '/cron.php?limit=50';
			postCurl( $url, array() );
		} else {
			$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> No customer found in this list.</div>';
		}
		header( "location: bulksms.php" );
	}
	break;
	case "delete_list":
		{
			$listID = $_REQUEST[ 'listID' ];
			$sql = "delete from lists where id='" . $listID . "'";
			$res = mysqli_query( $link, $sql );
			if ( $res ) {
				$del = "delete from list_assignment where list_id='" . $listID . "'";
				mysqli_query( $link, $del );
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> list is deleted successfully.</div>';
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to  delete list.</div>';
			}
		}
		break;
		
	case "create_csv_list":{
		$title = $_REQUEST['title'];
		$ext = getExtension($_FILES['contacts_csv']['name']);
		if ( $ext == 'csv' ) {
			$fileName = uniqid() . '.' . $ext;
			$tmpName = $_FILES[ 'contacts_csv' ][ 'tmp_name' ];
			$r = move_uploaded_file( $tmpName, 'uploads/' . $fileName );
			if ( $r ) {
				$sel = "select id from lists where title='" . $title . "' and user_id='" . $_SESSION[ 'user_id' ] . "'";
				$rec = mysqli_query( $link, $sel );
				if ( mysqli_num_rows( $rec ) ) {
					$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> A list with same title is already exists.</div>';
				} else {
					$sql = "insert into lists (title, user_id) values ('" . $title . "','" . $_SESSION[ 'user_id' ] . "')";
					$res = mysqli_query( $link, $sql );
					if ( $res ) {
						$listID = mysqli_insert_id( $link );
						importSubscribers( $fileName, $listID, $_SESSION[ 'user_id' ] );
						$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> List is created successfully.</div>';
					} else {
						$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to  create new list.</div>';
					}
				}
				unlink( 'uploads/' . $fileName );
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to  upload csv file.</div>';
			}
		} else {
			$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Please select a valid csv file and try again.</div>';
		}
		?>
		<script>
			window.location = "bulksms.php"
		</script>
		<?php
	}
	break;
		
	case "oauth_redirect_url":
		{
			logErrors( json_encode( $_REQUEST ) );
			mail( "mirzaahsan42@gmail.com", "oauth redirect post", print_r( $_REQUEST, true ) );
		}
		break;
		
	case "login":
		{
			$userName = DBin( $_REQUEST[ 'username' ] );
			$password = encodePassword( $_REQUEST[ 'password' ] );
			$sql = sprintf( "SELECT * FROM users WHERE email='%s' AND password='%s'",
				mysqli_real_escape_string( $link, $userName ),
				mysqli_real_escape_string( $link, $password ) );
			$res = mysqli_query( $link, $sql );
			if ( mysqli_num_rows( $res ) == 0 ) {
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Incorrect login information, please try again.</div>';
				header( "location: " . $_SERVER[ 'HTTP_REFERER' ] );
				die();
			} else {
				$row = mysqli_fetch_assoc( $res );
				if ( $row[ 'status' ] == '1' ) {
					$_SESSION[ 'first_name' ] = $row[ 'first_name' ];
					$_SESSION[ 'last_name' ] = $row[ 'last_name' ];
					$_SESSION[ 'user_id' ] = $row[ 'id' ];
					$_SESSION[ 'user_type' ] = $row[ 'type' ];
					$_SESSION[ 'business_name' ] = $row[ 'business_name' ];
					header( "Location: dashboard.php" );
				} else if ( $row[ 'status' ] == '2' ) {
					$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> your account is blocked.</div>';
					header( "Location: " . $_SERVER[ 'HTTP_REFERER' ] );
				} else if ( $row[ 'status' ] == '3' ) {
					$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> your account is deleted.</div>';
					header( "Location: " . $_SERVER[ 'HTTP_REFERER' ] );
				}
			}
		}
		break;
	case "logout":
		{
			unset( $_SESSION[ 'first_name' ] );
			unset( $_SESSION[ 'last_name' ] );
			unset( $_SESSION[ 'user_id' ] );
			unset( $_SESSION[ 'user_type' ] );
			unset( $_SESSION[ 'business_name' ] );
			unset( $_SESSION[ 'company' ] );
			$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Logged out successfully.</div>';
			header( "location:index.php" );
		}
	case 'all_bookings':
		{
			$bookings = [];
			$res = mysqli_query( $link, "SELECT * FROM `bookings` WHERE user_id=" . $userID );
			if ( mysqli_num_rows( $res ) ) {
				while ( $row = mysqli_fetch_assoc( $res ) ) {
					$bookings[] = $row;
				}
			}
			$bookings = json_encode( $bookings );
			echo '{"error":"no","message":"successfull.","data":' . $bookings . '}';
			break;
		}
		
	case 'update_booking_status':{
		//if(isset(explode('_', $_POST['id'])[1])){
		//$id = explode('_', $_POST['id'])[1];
		$id = $_REQUEST['id'];
		$status = $_POST[ 'status' ];
		//mysqli_query( $link, "UPDATE `bookings` SET `status`='$status' WHERE id=" . $id );
		$sql = "UPDATE `contacts` SET `pipeline_stage`='$status' WHERE id=" . $id;
		mysqli_query( $link, $sql);
		//}		
	}
	break;
	case 'update_pipeline_order':
		{
			$res = mysqli_query( $link, "SELECT * FROM `pipelines`" );
			$rows = mysqli_num_rows( $res );
			foreach ( $_POST[ 'data' ] as $key => $value ) {
				$card = $key;
				foreach ( $value as $a => $b ) {
					$rows++;
					$child = $b[ 'id' ];
					mysqli_query( $link, "REPLACE INTO `pipelines` (`id`, `order`, `card`,`child`) VALUES (" . $rows . ", '$a', '$card','$child')" );
				}
			}
			break;
		}
	case 'all_pipeline_booking':{
		$bookings = [];
		$ress = mysqli_query($link, "SELECT * FROM `pipeline_list` WHERE id=".$_REQUEST['pipeline_id']);
		if(mysqli_num_rows($ress)){
			$rows 	 = mysqli_fetch_assoc($ress);
			$stagess = str_replace('[','(',$rows['stages']);
			$stagess = str_replace(']',')',$stagess);
			
			//echo $sql = "select * from contacts where WHERE pipeline_id='".$rows['id']."' AND pipeline_stage in ".$stagess."";
			//$res = mysqli_query($link,$sql);
			$res = mysqli_query($link,"SELECT * FROM `contacts` WHERE pipeline_id=".$rows['id']." AND pipeline_stage in ".$stagess);
			if(mysqli_num_rows($res)){
				while($row = mysqli_fetch_assoc($res)){
					$bookings[] = $row;
				}
			}
		}
		
		$bookings = json_encode($bookings);
		echo '{"error":"no","message":"successfull.","data":'.$bookings.'}';			
	}
	break;
		
	case 'new_outgoing_call':
		{
			extract( $_POST );
			mysqli_query( $link, "INSERT INTO `twillio_call_log`(`id`, `number`, `mode`, `time`) VALUES (NULL,'$number','out','0')" );
			break;
		}
	case 'call_time':
		{
			extract( $_POST );
			$res = mysqli_query( $link, "SELECT id FROM twillio_call_log ORDER BY id DESC LIMIT 1" );
			$row = mysqli_fetch_assoc( $res );
			$id = $row[ 'id' ];
			mysqli_query( $link, "UPDATE `twillio_call_log` SET `time`='" . $time . "' WHERE `number`=" . $number . " AND id=" . $id );
			break;
		}
		break;
		// Case switches for IVR responses
	case 'new_ivr_response':
		{
			extract( $_POST );
			$res = mysqli_query( $link, "INSERT INTO `ivr_rsponses` (`id`, `name`, `treeData`, `actionss`, `assigned_number`) VALUES (NULL,'$name','$treeData','$actionss','$assigned_number')" );
			$flowID = mysqli_insert_id( $link );
			$number = $assigned_number;
			$sql = "select * from twilio_numbers where phone_number='" . $number . "' limit 1";
			$query_res = mysqli_query( $link, $sql );
			if ( mysqli_num_rows( $query_res ) ) {
				$row = mysqli_fetch_assoc( $query_res );
				$phoneSid = $row[ 'sid' ];
				$data = array( "VoiceUrl" => getServerURL() . "/api/ivr_call.php", "SmsUrl" => getServerURL() . "/incoming_sms.php" );
				// $ch = curl_init();
				// curl_setopt( $ch, CURLOPT_URL, "https://api.twilio.com/2010-04-01/Accounts/" . $sid . "/IncomingPhoneNumbers/" . $phoneSid . ".json" );
				// curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
				// curl_setopt( $ch, CURLOPT_POST, 1 );
				// curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
				// curl_setopt( $ch, CURLOPT_USERPWD, $sid . ':' . $token );
				// $result = json_decode( curl_exec( $ch ), true );
				// curl_close( $ch );
			}
			if ( $res ) {
				$pageUrl = "ivr-edit.php?flowid=" . $flowID;
				$message = 'Success! IVR Response is Created successfully.';
				echo json_encode( [ 'status' => 1, 'message' => $message, 'pageUrl' => $pageUrl ] );
			} else {
				$message = 'Failed! something went wrong, please try again later.';
				echo json_encode( [ 'status' => 2, 'message' => $message ] );
			}
			break;
		}
	case 'save_ivr_response':
		{
			extract( $_POST );
			$res = mysqli_query( $link, "UPDATE `ivr_rsponses` SET `name`='$name', `treeData`='$treeData', `actionss`='$actionss',
			`assigned_number`='$assigned_number' WHERE `id`='" . $workflow_id . "'" );
			$flowID = mysqli_insert_id( $link );
			$number = $assigned_number;
			$sql = "select * from twilio_numbers where phone_number='" . $number . "' limit 1";
			$query_res = mysqli_query( $link, $sql );
			if ( mysqli_num_rows( $query_res ) ) {
				$row = mysqli_fetch_assoc( $query_res );
				$phoneSid = $row[ 'sid' ];
				$data = array( "VoiceUrl" => getServerURL() . "/api/ivr_call.php", "SmsUrl" => getServerURL() . "/incoming_sms.php" );
				// $ch = curl_init();
				// curl_setopt( $ch, CURLOPT_URL, "https://api.twilio.com/2010-04-01/Accounts/" . $sid . "/IncomingPhoneNumbers/" . $phoneSid . ".json" );
				// curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
				// curl_setopt( $ch, CURLOPT_POST, 1 );
				// curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
				// curl_setopt( $ch, CURLOPT_USERPWD, $sid . ':' . $token );
				// $result = json_decode( curl_exec( $ch ), true );
				// curl_close( $ch );
			}
			if ( $res ) {
				$message = 'Success! IVR Response is Updated successfully.';
				echo json_encode( [ 'status' => 1, 'message' => $message ] );
			} else {
				$message = 'Failed! something went wrong, please try again later.';
				echo json_encode( [ 'status' => 2, 'message' => $message ] );
			}
			break;
		}
	case 'delete_ivr_response':
		{
			extract( $_REQUEST );
			if ( $id ) {
				mysqli_query( $link, "DELETE FROM `ivr_rsponses` WHERE `id`='" . $id . "'" );
			}
			header( "Location: ivr.php" );
			break;
		}
	case "assign_workflow_to_booked":
		{
			$workflow = $_REQUEST[ 'workflow' ];
			$sql = "update bookings set
						workflow_id='" . $workflow . "'
					where
						status='" . $_REQUEST[ 'booking_status' ] . "'";
			$res = mysqli_query( $link, $sql );
			if ( $res ) {
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Workflow assigned to Selected Booking successfully.</div>';
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to assigned.</div>';
			}
			header( "location: pipelines.php" );
		}
		break;
	
	case "add_opportunity":{
		$firstName = $_REQUEST['firstName'];
		$lastName  = $_REQUEST['lastName'];
		$email	   = $_REQUEST['email'];
		$phone	   = $_REQUEST['phone'];
		$companyName = $_REQUEST['companyName'];
		$OpportunityName = $_REQUEST['OpportunityName'];
		$pipeLineID = $_REQUEST['pipeLineID'];
		$pipeLineStage = $_REQUEST['pipeLineStage'];
		$leadValue = $_REQUEST['leadValue'];
		$opportunitySource = $_REQUEST['opportunitySource'];
		$contactID = trim($_REQUEST['contactID']);
		$notes = trim($_REQUEST['notes']);
		if($contactID==''){
			$sql = "insert into contacts
						(
							first_name,
							last_name,
							email,
							company_name,
							opportunity_name,
							pipeline_id,
							pipeline_stage,
							lead_value,
							opportunity_source,
							phone,
							user_id
						)
					values
						(
							'".trim($firstName)."',
							'".trim($lastName)."',
							'".$email."',
							'".$companyName."',
							'".$OpportunityName."',
							'".$pipeLineID."',
							'".$pipeLineStage."',
							'".$leadValue."',
							'".$opportunitySource."',
							'".$phone."',
							'".$_SESSION['user_id']."'
						)";
			$res = mysqli_query($link,$sql);
			if($res){
				$contactID = mysqli_insert_id($link);
				if(trim($notes)!=''){
					$ins = "insert into contact_notes
								(
									contact_id,
									user_id,
									notes
								)
							values
								(
									'".$contactID."',
									'".$_SESSION['user_id']."',
									'".DBin($notes)."'
								)";
					mysqli_query($link,$ins);
				}
				$_SESSION['message'] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Contact is saved successfully.</div>';
			}else{
				$_SESSION['message'] = '<div class="alert alert-success" role="alert"><strong>Failed!</strong> Something went wrong, please try again later.</div>';
			}
		}
		else{
			$sql = "update contacts set
						first_name='".trim($firstName)."',
						last_name='".trim($lastName)."',
						email='".$email."',
						company_name='".$companyName."',
						opportunity_name='".$OpportunityName."',
						pipeline_id='".$pipeLineID."',
						pipeline_stage='".$pipeLineStage."',
						lead_value='".$leadValue."',
						opportunity_source='".$opportunitySource."'
					where
						id='".$contactID."'";
			$res = mysqli_query($link,$sql);
			if($res){
				if(trim($notes)!=''){
					$ins = "insert into contact_notes
								(
									contact_id,
									user_id,
									notes
								)
							values
								(
									'".$contactID."',
									'".$_SESSION['user_id']."',
									'".DBin($notes)."'
								)";
					mysqli_query($link,$ins);
				}
				$_SESSION['message'] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Contact is saved successfully.</div>';
			}else{
				$_SESSION['message'] = '<div class="alert alert-success" role="alert"><strong>Failed!</strong> Something went wrong, please try again later.</div>';
			}
		}	
	}
	break;	
		
	case "update_opportunity":{
		$firstName = $_REQUEST['firstName'];
		$lastName  = $_REQUEST['lastName'];
		$email	   = $_REQUEST['email'];
		$phone	   = $_REQUEST['phone'];
		$companyName = $_REQUEST['companyName'];
		$OpportunityName = $_REQUEST['OpportunityName'];
		$pipeLineID = $_REQUEST['pipeLineID'];
		$pipeLineStage = $_REQUEST['pipeLineStage'];
		$leadValue = $_REQUEST['leadValue'];
		$opportunitySource = $_REQUEST['opportunitySource'];
		$contactID = $_REQUEST['contactID'];
		$notes = $_REQUEST['notes'];
		
		if(trim($notes)!=''){
			$ins = "insert into contact_notes
						(
							contact_id,
							user_id,
							notes
						)
					values
						(
							'".$contactID."',
							'".$_SESSION['user_id']."',
							'".DBin($notes)."'
						)";
			mysqli_query($link,$ins);
		}
		
		$sql = "update contacts set
					first_name='".trim($firstName)."',
					last_name='".trim($lastName)."',
					email='".$email."',
					company_name='".$companyName."',
					opportunity_name='".$OpportunityName."',
					pipeline_id='".$pipeLineID."',
					pipeline_stage='".$pipeLineStage."',
					lead_value='".$leadValue."',
					opportunity_source='".$opportunitySource."'
				where
					id='".$contactID."'";
		$res = mysqli_query($link,$sql);
		if($res){
			$_SESSION['message'] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Opportunity box is update successfully.</div>';
		}else{
			$_SESSION['message'] = '<div class="alert alert-success" role="alert"><strong>Failed!</strong> Something went wrong, please try again later.</div>';
		}
	}
	break;
		
	case "get_bookings_oppert_details":{
		$contactID = $_REQUEST['booking_id'];
		
		$sql = "select * from contacts where id='".$contactID."' limit 1";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
			$row['message'] = "success";
			
			
			$contactNotes = '';
			$sqlN = "select * from contact_notes where contact_id='".$contactID."'";
			$resN = mysqli_query($link,$sqlN);
			if(mysqli_num_rows($resN)){
				while($rowN = mysqli_fetch_assoc($resN)){
					$contactNotes .= '<p>'.date("d/m/Y h:ia",strtotime($rowN['created_date'])).' => '.$rowN['notes'].'</p>';
				}
			}
			$row['contact_notes'] = $contactNotes;
			
			echo json_encode($row);
		}else{
			echo '{"message":"error"}';
		}
		
		
		
		
		//$bookingID = explode('_',$booking_id);
		/*
		$sql2 = "select * from bookings where id=".$bookingID[1];
		$res2 = mysqli_query($link, $sql2);
		if(mysqli_num_rows($res2)){
			$row2 = mysqli_fetch_assoc($res2);
			
			$contactNotes = '';
			$sqlN = "select * from contact_notes where contact_id='".$row2['contact_id']."'";
			$resN = mysqli_query($link,$sqlN);
			if(mysqli_num_rows($resN)){
				while($rowN = mysqli_fetch_assoc($resN)){
					$contactNotes .= '<p>'.date("d/m/Y h:ia",strtotime($rowN['created_date'])).' => '.$rowN['notes'].'</p>';
				}
			}
			
			$contactInfo = getContactInfo($row2['contact_id']);
			$contactInfo["contact_notes"] = $contactNotes;
			
			$sql = "select *, id as bok_oppid from booking_opportunity where booking_id='" . $booking_id . "'";
			$res = mysqli_query($link, $sql);
			if(mysqli_num_rows($res)){
				$rowwww = mysqli_fetch_assoc($res);	
				$row3 = array_merge($contactInfo,$rowwww);
				echo json_encode($row3);
			}else{
				$contactInfo["opportunity_pipeline"] = $row2['pipeline_id'];
				$contactInfo["opportunity_stage"] = strtolower($row2['status']);
				$contactInfo["phone"] = $row2['phone'];
				$contactInfo["first_name"] = $row2['firstName'];
				$contactInfo["email"] = $row2['email'];
				echo json_encode($contactInfo);
			}
		}
		else{
			echo '{"message":"no booking found"}';
		}
		*/
	}
	break;
		
	case "get_pipeline_stages":
		{
			$pipeline_id = $_REQUEST[ 'pipeline_id' ];
			$sql = "select stages from pipeline_list where id=" . $pipeline_id;
			$res = mysqli_query( $link, $sql );
			$row = mysqli_fetch_assoc( $res );
			$stagess = json_decode( $row[ 'stages' ] );
			$stags = '';
			foreach ( $stagess as $stage ) {
				$stags .= '<option value="' . strtolower( $stage ) . '">' . $stage . '</option>';
			}
			echo json_encode( $stags );
		}
		break;
	case "create_pipeline":
		{
			$title = $_REQUEST[ 'title' ];
			$stages = isset( $_REQUEST[ 'stages' ] ) ? json_encode( $_REQUEST[ 'stages' ] ) : json_encode( '' );
			$userID = $_SESSION[ 'user_id' ];
			$sql = "insert into pipeline_list
						(
							title,
							stages,
							user_id
						)
					values
						(
							'" . $title . "',
							'" . $stages . "',
							'" . $userID . "'
						)";
			$res = mysqli_query( $link, $sql );
			if ( $res ) {
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success! Pipeline is Saved successfully.</div>';
				header( "location: pipelineslist.php" );
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Failed! something went wrong, please try again later.</div>';
				header( "location: pipelineslist.php" );
			}
		}
		break;
	case "update_pipeline":
		{
			$pipeline_id = $_REQUEST[ 'id' ];
			$title = $_REQUEST[ 'title' ];
			$stages = isset( $_REQUEST[ 'stages' ] ) ? json_encode( $_REQUEST[ 'stages' ] ) : json_encode( '' );
			$sql = "update pipeline_list set
						title='" . $title . "',
						stages='" . $stages . "'
					where
						id='" . $pipeline_id . "'";
			$res = mysqli_query( $link, $sql );
			if ( $res ) {
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success! Pipeline is Update successfully.</div>';
				header( "location: pipelineslist.php" );
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Failed! something went wrong, please try again later.</div>';
				header( "location: pipelineslist.php" );
			}
		}
		break;
	case "delete_pipeline":
		{
			$pipeline_id = $_REQUEST[ 'id' ];
			$sql = "delete from pipeline_list where id='" . $pipeline_id . "'";
			$res = mysqli_query( $link, $sql );
			if ( $res ) {
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success! Pipeline is Deleted successfully.</div>';
				//header( "location: pipelineslist.php" );
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Failed! something went wrong, please try again later.</div>';
				//header( "location: pipelineslist.php" );
			}
		}
		break;
	case "add_contact":
		{
			$contact_fname = $_REQUEST[ 'contact_fname' ];
			$contact_lname = $_REQUEST[ 'contact_lname' ];
			$contact_email = $_REQUEST[ 'contact_email' ];
			$contact_company = $_REQUEST[ 'contact_company' ];
			$contact_phonenumber = $_REQUEST[ 'contact_phonenumber' ];
			$contact_designation = $_REQUEST[ 'contact_designation' ];
			$contact_type = $_REQUEST[ 'contact_type' ];
			$contact_address = $_REQUEST[ 'contact_address' ];
			$contact_city = $_REQUEST[ 'contact_city' ];
			$contact_state = $_REQUEST[ 'contact_state' ];
			$contact_zipcode = $_REQUEST[ 'contact_zipcode' ];
			$userID = $_SESSION[ 'user_id' ];
			$sql = "insert into contacts
						(
							first_name,
							last_name,
							email,
							company_name,
							phone,
							designation,
							type,
							street_address,
							city,
							state,
							zipcode,
							user_id
						)
					values
						(
							'" . $contact_fname . "',
							'" . $contact_lname . "',
							'" . $contact_email . "',
							'" . $contact_company . "',
							'" . $contact_phonenumber . "',
							'" . $contact_designation . "',
							'" . $contact_type . "',
							'" . $contact_address . "',
							'" . $contact_city . "',
							'" . $contact_state . "',
							'" . $contact_zipcode . "',
							'" . $userID . "'
						)";
			$res = mysqli_query( $link, $sql );
			if ( $res ) {
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success! Contact is Saved successfully.</div>';
				// header("location: contacts.php");
				header( "location: " . $_SERVER[ 'HTTP_REFERER' ] );
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Failed! something went wrong, please try again later.</div>';
				header( "location: " . $_SERVER[ 'HTTP_REFERER' ] );
			}
		}
		break;
		
	case 'add_to_pipeline': {
			$number = $_REQUEST['num'];
			$pipelineID = $_REQUEST['pipeline_id'];
			$stageID = $_REQUEST['stage_id'];
			$sql = "update `contacts` set pipeline_id='".$pipelineID."', pipeline_stage='".strtolower($stageID)."' WHERE phone='".$number."'";
			mysqli_query($link, $sql);
		}
	break;
		
	case "create_role":{
		$role_name = $_REQUEST['role_name'];
	
		$sql = "insert into role_master
					(
						role
					)
				values
					(
						'".$role_name."'
					)";
		$add_role = mysqli_query($link,$sql);
		
		if($add_role){
		    $_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Role is added successfully.</div>';
		    header('location:roles.php');
		}
		else{
		    $_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to add role.</div>';
		}
	}
	break;
	
	case "update_role":
		{
			$role_id = $_REQUEST['role_id'];
			$role_name = $_REQUEST['role_name'];
		
			$sql = "update role_master set
						role='" . $role_name . "'
					where
						role_id='" . $role_id . "'";
			$res = mysqli_query( $link, $sql );
			if ( $res ) {
				$_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> Role is updated successfullyl.</div>';
			} else {
				$_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Unable to update Role.</div>';
			}
			header( "location: roles.php" );
		}
	break;
	
	case "add_inner_user":
		{
		    ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
		    $role = $_REQUEST[ 'role' ];
    		$fn = $_REQUEST[ 'first_name' ];
    		$ln = $_REQUEST[ 'last_name' ];
    		$email = $_REQUEST[ 'email' ];
    		$phone = $_REQUEST[ 'phone' ];
    		$password = md5($_REQUEST[ 'password' ]);
    		$newpass = encodePassword($_REQUEST[ 'password' ]);
    		$cpassword = $_REQUEST[ 'cpassword' ];
    		$company_id = $_REQUEST['company'];
            $module_name = $_REQUEST[ 'module_name' ];
            
            if($_REQUEST[ 'password' ]!=$_REQUEST[ 'cpassword' ]){
                $_SESSION[ 'message' ] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Password and Confirm Password does not matched.</div>';
		        header('location:add_inner_user.php');
            }else{
                
    //             $sql = "insert into inner_users
				// 		(
				// 			role_id,
				// 			first_name,
				// 			last_name,
				// 			email,
				// 			phone_number,
				// 			password,
				// 			view_password
				// 		)
				// 	values
				// 		(
				// 		    '" . $role . "',
				// 			'" . $fn . "',
				// 			'" . $ln . "',
				// 			'" . $email . "',
				// 			'" . $phone . "',
				// 			'" . $password . "',
				// 			'" . $cpassword . "'
				// 		)";
			 //   $res = mysqli_query( $link, $sql );
			    
			    $sql1 = "insert into users
						(
							role_id,
							company_id,
							first_name,
							last_name,
							email,
							phone_number,
							password
						)
					values
						(
						    '" . $role . "',
						    '" . $company_id . "',
							'" . $fn . "',
							'" . $ln . "',
							'" . $email . "',
							'" . $phone . "',
							'" . $newpass . "'
						)";
			    $res1 = mysqli_query( $link, $sql1 );
			    $lastInsertID = mysqli_insert_id($link);
			    
			    foreach($module_name as $module){
			        $view=0;
                    $ins=0;
                    $upd=0;
                    $del=0;
                    if(!empty($_REQUEST[$module])){
                        $new_key =$_REQUEST[$module];
                        if(!empty($new_key)){
                            foreach($new_key as $key1 => $id1){
                                if($id1=='view'){
                                    $view=1;
                                }
                                if($id1=='ins'){
                                    $ins=1;
                                }
                                if($id1=='upd'){
                                    $upd=1;
                                }
                                if($id1=='del'){
                                    $del=1;
                                }
                            }
                        }
			        }
                        $permisions = "insert into permission
    						(
    							inner_user_id,
    							company_id,
    							module_name,
    							insert_permission,
    							update_permission,
    							delete_permission,
    							view_permission
    						)
    					values
    						(
    						    '" . $lastInsertID . "',
    						    '" . $company_id . "',
    							'" . $module . "',
    							'" . $ins . "',
    							'" . $upd . "',
    							'" . $del . "',
    							'" . $view . "'
    						)";
    					$res1 = mysqli_query( $link, $permisions );
			    }
			    
			    
                
                //Load Composer's autoloader
                // require 'vendor/autoload.php';
                require 'PHPMailer/src/Exception.php';
                require 'PHPMailer/src/PHPMailer.php';
                require 'PHPMailer/src/SMTP.php';
                //Create an instance; passing `true` enables exceptions
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                   
                    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host       = 'buildors.com';                     //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username   = 'smtp@buildors.com';                     //SMTP username
                    $mail->Password   = 'cATN;f{c[yOC';                               //SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
              
                    //Recipients
                    $mail->setFrom('smtp@buildors.com', 'buildors');
                    $mail->addAddress($email, $fn.$ln);     //Add a recipient
                    $mail->addAddress($email);               //Name is optional
                    // $mail->addReplyTo('info@example.com', 'Information');
                    // $mail->addCC('cc@example.com');
                    // $mail->addBCC('bcc@example.com');
                
                    //Attachments
                    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
                
                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'Buildors login details';
                    // $mail->Body    = '<div>Dear <b>'.$fn.'</b><br><div style="text-align:-webkit-center;"><p style="text-align:center;padding: 10px;background-color: lightskyblue;width: 30%;border-radius: 25px;">Your buildors <b>EMAIL</b> and <b>PASSWORD</b></p></div>';
                    // $mail->Body .='<div style="display: flex;justify-content: center;"><h4>Email - </h4><p style="font-size: 1rem;">'.   $email.'</p></div><div style="display: flex;justify-content: center;"><h4>Password - </h4><p style="font-size: 1rem;">'.  $cpassword.'</p></div></div>';
                    $mail->Body = 'This is the body in plain text for non-HTML mail clients.';
                  
                    $mail->send();
                    echo 'Message has been sent';
                } catch (Exception $e) {
                     echo "asgh";die;
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
                $_SESSION[ 'message' ] = '<div class="alert alert-success" role="alert"><strong>Success!</strong> User is added successfully.</div>';
		        header('location:add_inner_user.php');  
            }
		}
		break;
}
?>