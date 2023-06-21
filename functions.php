<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
	function getLatestNote($contactID){
		global $link;
		$sql = "select notes from contact_notes where contact_id='".$contactID."' order by id desc limit 1";
		$res = mysqli_query($link,$sql);
		$row = mysqli_fetch_assoc($res);
		return $row['notes'];
	}
	function getFinalCallStatus(){
		return array("busy","no-answer","canceled","failed");
	}
	function getContactInfo($contactID){
		global $link;
		$sql = "select *, id as contactID from contacts where id='".$contactID."'";
		$res = mysqli_query($link,$sql);
		return mysqli_fetch_assoc($res);
	}
	function getPipeline($pipeLineID){
		global $link;
		$pipelineress = mysqli_query($link,"SELECT * FROM `pipeline_list` WHERE id=".$pipeLineID);
		$rowdattt = mysqli_fetch_assoc($pipelineress);
		return $rowdattt;
	}
	function getPipelineOptions($userID){
		global $link;
		$pipelineress = mysqli_query($link,"SELECT * FROM `pipeline_list` WHERE user_id=".$userID." ORDER BY id ASC");
		while($rowdattt = mysqli_fetch_assoc($pipelineress)){
			echo '<option value="'.$rowdattt['id'].'">'.$rowdattt['title'].'</option>';
		} 
	}
	function getNumberOwner($number){
		global $link;
		$sql = "select user_id from twilio_numbers where phone_number='".$number."' limit 1";
		$res = mysqli_query($link,$sql);
		$row = mysqli_fetch_assoc($res);
		return $row['user_id'];
	}
	function getChatNumber($toNumber,$userID){
		global $link;
		$sql = "select from_number from conversations where customer_number='".$toNumber."' and direction='out'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
			return $row['from_number'];
		}else{
			$twilioNumbers = getRandomTwilioNumbers($userID);
			$key = array_rand($twilioNumbers,1);
			return removeCountryCode($twilioNumbers[$key]);
		}
	}
	function exportSubscribers($userID, $listID=""){
		global $link;
		$filename = 'replies.csv';
		$fp = fopen($filename, "w");
		$line = "";
		$comma = "";
		$line .= $comma . 'From number, Message';
		$comma = ",";
		$line .= "\n";
		fputs($fp, $line);
		$line = "";
		$comma = "";
		
		$sql = sprintf("select to_number, from_number, message from broadcast_history where direction='in' order by id asc");
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$index = 1;
			$count = 0;
			while($row=mysqli_fetch_assoc($res)){
				$line = "";
				$comma = "";
				$count++;
				$line .= $comma . '"'.$row['from_number'].'","'.DBout($row['message']).'"';
				$comma = ",";
				$line .= "\n";
				fputs($fp, $line);
			}
		}
	}
	function getListInfo($listID){
		global $link;
		$sql = "select * from lists where id='".$listID."'";
		$res = mysqli_query($link,$sql);
		return mysqli_fetch_assoc($res);
	}
	function getRandomTwilioNumbers($userID){
		global $link;
		$sql = "select phone_number from twilio_numbers where user_id='".$userID."'";
		$res = mysqli_query($link,$sql)or die(mysqli_error($link));
		$numbers = [];
		while($row = mysqli_fetch_assoc($res)){
			$numbers[] = $row["phone_number"];
		}
		return $numbers;
	}
	function staffRoles(){
		$roles = array(
			"Project Manager",
			"Client Support 1",
			"Management 2",
			"NC Management",
			"Management 99",
			"Solar Management",
			"Office Support",
			"Materials Manager",
			"Billing 2",
			"Management 1",
			"Subcontractor Support",
			"Senior Project Manager",
			"PM and Sales",
			"Tech Support"
		);
		return $roles;
	}
	function getLatestMsgByNumber($customerNumber){
		global $link;
		$sql = "select id,message,created_date from conversations where customer_number='".$customerNumber."' order by id desc limit 1";
		$res = mysqli_query($link,$sql);
		return mysqli_fetch_assoc($res);
	}
	function checkExistence($number){
		global $link;
		$sql = "select id from customers where (cell='".$number."') or (cell=".removeCountryCode($number).")";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res))
			return false;
		else
			return true;
	}
	function getCustomerInfoByNumber($customerNumber){
		global $link;
		$sql = "select * from contacts where phone='".$customerNumber."'";
		$res = mysqli_query($link,$sql);
		return mysqli_fetch_assoc($res);
	}
	function removeCountryCode($phone){
		$pos = strpos($phone,'+');
		if($pos === false){
			if(strlen($phone)=='11'){
				$phone = substr($phone, 1);
			}
		}else{
			if(strlen($phone)=='12'){
				$phone = substr($phone, 2);
			}elseif(strlen($phone)=='11'){
				$phone = substr($phone, 1);
			}
		}
		return $phone;
	}
	function addCountryCode($phone){
		$pos = strpos($phone,'+');
		if($pos === false){
			if(strlen($phone)=='11'){
				$phone = '+'.trim($phone);
				return $phone;
			}else if(strlen($phone)=='10'){
				$phone = '+1'.trim($phone);
				return $phone;
			}else{
				return 'Invalid_recipient_number';
			}
		}else{
			if(strlen($phone)=='12'){
				return $phone;
			}else{
				return 'Invalid_recipient_number';
			}
		}
	}
	function getCurl($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HTTPGET);
		curl_setopt($ch, CURLOPT_USERPWD, 'ACb797881d79a639eefb0a266f275b895b'.':'.'e0419a64126bc4424d9b367d809a9aa0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20110814 Firefox/6.0');
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	function postCurl($url,$data){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20110814 Firefox/6.0');
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	function importSubscribers($filename,$listID,$userID){
		global $link;
		$index = 0;
		$handle = fopen("uploads/$filename", "r");
		while(($data=fgetcsv($handle,1000,",")) !== FALSE){
			if($index>0){
				if($number = trim($data[0])==''){
					$_SESSION['message'] = '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> csv file is empty or not well formated.</div>';
				}else{
					$firstName    = trim($data[0]);
					$lastName = trim($data[1]);
					$companyName  = trim($data[2]);
                    $phone  = trim($data[3]);
					$designation  = trim($data[4]);
					$type  = trim($data[5]);
					$email  = trim($data[6]);
					$street  = trim($data[7]);
					$city  = trim($data[8]);
					$state  = trim($data[9]);
					$zipCode  = trim($data[10]);
					$kcgState  = trim($data[11]);
					$rating  = trim($data[12]);
					
					$illigal = array("-","_"," ","(",")",".","&nbsp;");
					$phone = str_replace($illigal,"",$phone);
					$phone = removeCountryCode($phone);
					if(preg_match('/^[0-9]{10}+$/', $phone)){ // "Valid Phone Number";						
						$sql = sprintf("select id from contacts where phone='%s'",mysqli_real_escape_string($link,$phone));
						$res = mysqli_query($link,$sql);
						if(mysqli_num_rows($res)==0){
							$import = "insert into contacts
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
												'".DBin($firstName)."',
												'".DBin($lastName)."',
												'".DBin($companyName)."',
												'".DBin($phone)."',
												'".DBin($designation)."',
												'".DBin($type)."',
												'".DBin($email)."',
												'".DBin($street)."',
												'".DBin($city)."',
												'".DBin($state)."',
												'".DBin($zipCode)."',
												'".DBin($kcgState)."',
												'".DBin($rating)."',
												'".$_SESSION['user_id']."'
											)";
							/*
							$import = sprintf("INSERT into customers 
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
													'%s',
													'%s',
													'%s'
												)",
										mysqli_real_escape_string($link,DBin($firstName)),
										mysqli_real_escape_string($link,DBin($lastName)),
										mysqli_real_escape_string($link,DBin($phone)),
										mysqli_real_escape_string($link,DBin($cell)),
										mysqli_real_escape_string($link,DBin($address)),
										mysqli_real_escape_string($link,DBin($city)),
										mysqli_real_escape_string($link,DBin($state)),
										mysqli_real_escape_string($link,DBin($zipCode)),
										mysqli_real_escape_string($link,DBin($salesManagerName)),
										mysqli_real_escape_string($link,DBin($saleManageNumber)),
										mysqli_real_escape_string($link,DBin($projectManagerName)),
										mysqli_real_escape_string($link,DBin($projectManagerNumber)),
										mysqli_real_escape_string($link,DBin($mangementName)),
										mysqli_real_escape_string($link,DBin($managementNumber)),
										mysqli_real_escape_string($link,DBin($tagWorkflow)),
										mysqli_real_escape_string($link,DBin($_SESSION['user_id']))
								);
							*/
							mysqli_query($link,$import) or die(mysqli_error($link));
							$customerID = mysqli_insert_id($link);
							/*
							$sel = sprintf("select id from list_assignment where customer_id='%s' and list_id='%s'",
											mysqli_real_escape_string($link,DBin($customerID)),
											mysqli_real_escape_string($link,DBin($listID)));
							$exe = mysqli_query($link,$sel) or die(mysqli_error($link));
							if(mysqli_num_rows($exe)=='0'){
							*/	
								$sql1 = sprintf("insert into list_assignment (customer_id,list_id,user_id) values('%s','%s','%s')",
											mysqli_real_escape_string($link,DBin($customerID)),
											mysqli_real_escape_string($link,DBin($listID)),
											mysqli_real_escape_string($link,DBin($_SESSION['user_id']))
									);
								mysqli_query($link,$sql1) or die(mysqli_error($link));
							//}
						}
						else{
							$row = mysqli_fetch_assoc($res);
							$customerID = $row['id'];
							/*
							$sel = sprintf("select id from list_assignment where customer_id='%s' and list_id='%s'",
											mysqli_real_escape_string($link,DBin($customerID)),
											mysqli_real_escape_string($link,DBin($listID))
								);
							$exe = mysqli_query($link,$sel);
							if(mysqli_num_rows($exe)=='0'){
							*/	
								$sql2 = sprintf("insert into list_assignment (customer_id,list_id,user_id) values('%s','%s','%s')",
												mysqli_real_escape_string($link,DBin($customerID)),
												mysqli_real_escape_string($link,DBin($listID)),
												mysqli_real_escape_string($link,DBin($_SESSION['user_id']))
									);
								mysqli_query($link,$sql2);
							//}
						}
					}
					else{
						//echo "Invalid Phone Number";
					}
				}
			}
			$index++;
		}
	}
	function getTotalListCustomers($listID){
		global $link;
		$sql = "select id from list_assignment where list_id='".$listID."'";
		$res = mysqli_query($link,$sql);
		echo mysqli_num_rows($res);
	}
	function downloadFile($file){
		$mime = 'application/force-download';
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private',false);
		header('Content-Type: '.$mime);
		header('Content-Disposition: attachment; filename="'.basename($file).'"');
		header('Content-Transfer-Encoding: binary');
		header('Connection: close');
		readfile($file);
		exit();
	}
	function sendMessage($from, $to, $body, $media="", $customerInfo=array()){
		global $link;
		$sid   = 'ACb797881d79a639eefb0a266f275b895b';
		$token = 'e0419a64126bc4424d9b367d809a9aa0';
		
		$url  = "https://api.twilio.com/2010-04-01/Accounts/".$sid."/Messages.json";
		$data = array (
			'From' => $from,
			'To' => $to,
			'Body' => $body
		);
		if(trim($media)!=''){
			$data["MediaUrl"] = $media;
		}
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
		return $response;
	}
	
	function logErrors($data){
		$myFile = "logs.txt";
		$fh = fopen($myFile, 'a');
		fwrite($fh, $data);
		fclose($fh);
	}
	function getCurrentPageName(){
		$currentFile = $_SERVER["PHP_SELF"];
		$parts = explode('/', $currentFile);
		$Name = $parts[count($parts) - 1];
		return $Name;
	}
	function getAppSettings($userID,$isAdmin=false){
		global $link;
		if($isAdmin)
			$sql = sprintf("select * from application_settings where user_type='1'");
		else
			$sql = sprintf("select * from application_settings where user_id=%s",
                        mysqli_real_escape_string($link,DBin($userID))
                );
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			return mysqli_fetch_assoc($res);
		}else
			return false;
	}
	function encodePassword($str){
	    $str = DBin($str);
		for($i=0; $i<2; $i++){
			$str=strrev(base64_encode($str));
		}
		return $str;
	}
	function decodePassword($str){
		for($i=0; $i<2; $i++){
			$str=base64_decode(strrev($str));
		}
		return $str;
	}
	function DBin($string){
        return  filter_var($string,FILTER_SANITIZE_STRING);
    }
	function DBout($string,$flag=ENT_NOQUOTES){
		//$a = htmlspecialchars($string,$flag,'UTF-8');
		$a = htmlspecialchars_decode($string);
		$a = str_replace("&#39;","'",$a);
		return $a;
	}
	function getExtension($str){
	    $str = DBin($str);
		$i = strrpos($str,".");
		if (!$i) { return ""; }
		$l = strlen($str) - $i;
		$ext = substr($str,$i+1,$l);
		return $ext;
	}
	function getServerURL(){
		$protocol = ( ((!empty($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] !== 'off')) || ($_SERVER['SERVER_PORT'] == 443) ) ? "https://" : "http://";
		$domainName = $_SERVER['HTTP_HOST'];
		$filePath   = $_SERVER['REQUEST_URI'];
		$fullUrl = $protocol.$domainName.$filePath;
		$installURL = substr($fullUrl,0,strrpos($fullUrl,'/'));
		return $installURL;
	}
	function emojis(){
		$emojis = array(
			"smiley_faces" => array(
				"&#128512;",
				"&#128513;",
				"&#128514;",
				"&#128515;",
				"&#128516;",
				"&#128517;",
				"&#128518;",
				"&#128519;",
				"&#128520;",
				"&#128521;",
				"&#128522;",
				"&#128523;",
				"&#128524;",
				"&#128525;",
				"&#128526;",
				"&#128527;",
				"&#128528;",
				"&#128529;",
				"&#128530;",
				"&#128531;",
				"&#128532;",
				"&#128533;",
				"&#128534;",
				"&#128535;",
				"&#128536;",
				"&#128537;",
				"&#128538;",
				"&#128539;",
				"&#128540;",
				"&#128541;",
				"&#128542;",
				"&#128543;",
				"&#128544;",
				"&#128545;",
				"&#128546;",
				"&#128547;",
				"&#128548;",
				"&#128549;",
				"&#128550;",
				"&#128551;",
				"&#128552;",
				"&#128553;",
				"&#128554;",
				"&#128555;",
				"&#128556;",
				"&#128557;",
				"&#128558;",
				"&#128559;",
				"&#128560;",
				"&#128561;",
				"&#128562;",
				"&#128563;",
				"&#128564;",
				"&#128565;",
				"&#128566;",
				"&#128567;",
				"&#128568;",
				"&#128569;",
				"&#128570;",
				"&#128571;",
				"&#128572;",
				"&#128573;",
				"&#128574;",
				"&#128575;",
				"&#128576;",
				"&#128577;",
				"&#128578;",
				"&#128579;",
				"&#128580;",
				"&#129296;",
				"&#129297;",
				"&#129298;",
				"&#129299;",
				"&#129300;",
				"&#129301;",
				"&#129312;",
				"&#129313;",
				"&#129314;",
				"&#129315;",
				"&#129316;",
				"&#129317;",
				"&#129320;",
				"&#129321;",
				"&#129322;",
				"&#129323;",
				"&#129324;",
				"&#129325;",
				"&#129326;",
				"&#129327;",
				"&#129488;"
			),
			"hands_indication" => array(
				"&#9757;",
				"&#9994;",
				"&#9995;",
				"&#9996;",
				"&#9997;",
				"&#128070;",
				"&#128071;",
				"&#128072;",
				"&#128073;",
				"&#128074;",
				"&#128075;",
				"&#128076;",
				"&#128077;",
				"&#128078;",
				"&#128079;",
				"&#128080;",
				"&#128400;",
				"&#128405;",
				"&#128406;",
				"&#128591;",
				"&#129304;",
				"&#129305;",
				"&#129304;",
				"&#129306;",
				"&#129307;",
				"&#129308;",
				"&#129309;",
				"&#129310;",
				"&#129311;"
			),
			"animal" => array(
				"&#9924;",
				"&#128000;",
				"&#128001;",
				"&#128002;",
				"&#128003;",
				"&#128004;",
				"&#128005;",
				"&#128006;",
				"&#128007;",
				"&#128008;",
				"&#128009;",
				"&#128010;",
				"&#128011;",
				"&#128012;",
				"&#128013;",
				"&#128014;",
				"&#128015;",
				"&#128016;",
				"&#128017;",
				"&#128018;",
				"&#128019;",
				"&#128020;",
				"&#128021;",
				"&#128022;",
				"&#128023;",
				"&#128024;",
				"&#128025;",
				"&#128026;",
				"&#128027;",
				"&#128028;",
				"&#128029;",
				"&#128030;",
				"&#128031;",
				"&#128032;",
				"&#128033;",
				"&#128034;",
				"&#128035;",
				"&#128036;",
				"&#128037;",
				"&#128038;",
				"&#128039;",
				"&#128040;",
				"&#128041;",
				"&#128042;",
				"&#128043;",
				"&#128044;",
				"&#128045;",
				"&#128046;",
				"&#128047;",
				"&#128048;",
				"&#128049;",
				"&#128050;",
				"&#128051;",
				"&#128052;",
				"&#128053;",
				"&#128054;",
				"&#128055;",
				"&#128056;",
				"&#128057;",
				"&#128058;",
				"&#128059;",
				"&#128060;",
				"&#128375;"
			),
			"sports" => array(
				"&#9823;",
				"&#127922;",
				"&#127936;",
				"&#127941;",
				"&#127942;",
				"&#127943;",
				"&#127944;",
				"&#127945;",
				"&#127947;",
				"&#127949;",
				"&#127951;",
				"&#127952;",
				"&#127953;",
				"&#127954;",
				"&#127955;"
			),
			"fruits" => array (
				"&#127815;",
				"&#127816;",
				"&#127817;",
				"&#127818;",
				"&#127819;",
				"&#127820;",
				"&#127821;",
				"&#127822;",
				"&#127823;",
				"&#127824;",
				"&#127825;",
				"&#127826;",
				"&#127827;",
				"&#129373;"
			),
			"symbol" => array (
				"&#8987;",
				"&#9193;",
				"&#9194;",
				"&#9195;",
				"&#9196;",
				"&#9197;",
				"&#9198;",
				"&#9199;",
				"&#9203;",
				"&#9208;",
				"&#9209;",
				"&#9210;",
				"&#9410;",
				"&#9800;",
				"&#9801;",
				"&#9802;",
				"&#9803;",
				"&#9804;",
				"&#9805;",
				"&#9806;",
				"&#9807;",
				"&#9808;",
				"&#9809;",
				"&#9810;",
				"&#9811;",
				"&#9855;",
				"&#9875;",
				"&#9889;",
				"&#9898;",
				"&#9899;",
				"&#9934;",
				"&#9934;",
				"&#9935;",
				"&#9937;",
				"&#9939;",
				"&#9940;",
				"&#9961;",
				"&#9986;",
				"&#9989;",
				"&#9992;",
				"&#9993;",
				"&#10060;",
				"&#10062;",
				"&#10067;",
				"&#10068;",
				"&#10069;",
				"&#10071;",
				"&#10175;",
				"&#10548;",
				"&#10549;",
				"&#11013;",
				"&#11014;",
				"&#10548;",
				"&#10549;",
				"&#11013;",
				"&#11014;",
				"&#11015;",
				"&#11088;",
				"&#11093;",
				"&#12336;",
				"&#12349;",
				"&#12951;",
				"&#12953;",
				"&#126980;",
				"&#127183;",
				"&#127344;",
				"&#127345;",
				"&#127358;",
				"&#127359;",
				"&#127374;",
				"&#127377;",
				"&#127378;",
				"&#127379;",
				"&#127380;",
				"&#127381;",
				"&#127382;",
				"&#127383;",
				"&#127384;",
				"&#127385;",
				"&#127386;",
				"&#127489;",
				"&#127490;",
				"&#127514;",
				"&#127535;",
				"&#127538;",
				"&#127539;",
				"&#127540;",
				"&#127541;",
				"&#127542;",
				"&#127543;",
				"&#127544;",
				"&#127545;",
				"&#127546;",
				"&#127568;",
				"&#127569;",
				"&#127744;",
				"&#127745;",
				"&#127757;",
				"&#127758;",
				"&#127759;",
				"&#127787;",
				"&#127784;",
				"&#127895;",
				"&#127894;",
				"&#127897;",
				"&#127910;",
				"&#127911;",
				"&#127912;",
				"&#127925;",
				"&#127926;",
				"&#128172;",
				"&#128173;",
				"&#128175;",
				"&#128176;",
				"&#128177;",
				"&#128177;",
				"&#128191;",
				"&#128204;",
				"&#128256;",
				"&#128257;",
				"&#128258;",
				"&#128259;",
				"&#128260;",
				"&#128263;",
				"&#128266;",
				"&#128269;",
				"&#128270;",
				"&#128277;",
				"&#128281;",
				"&#128282;",
				"&#128283;",
				"&#128284;",
				"&#128285;",
				"&#128286;",
				"&#128287;",
				"&#128288;",
				"&#128289;",
				"&#128290;",
				"&#128291;",
				"&#128292;",
				"&#128293;",
				"&#128316;",
				"&#128317;",
				"&#128685;",
				"&#128683;",
				"&#128681;",
				"&#128687;",
				"&#128686;",
				"&#128687;",
				"&#128688;",
				"&#128689;",
				"&#128697;",
				"&#128698;",
				"&#128699;"
				),
			"general_emoji" => array (
				"&#8986;",
				"&#9200;",
				"&#9201;",
				"&#9202;",
				"&#9748;",
				"&#10024;",
				"&#10084;",
				"&#10083;",
				"&#127789;",
				"&#127790;",
				"&#127792;",
				"&#127793;",
				"&#127794;",
				"&#127795;",
				"&#127796;",
				"&#127797;",
				"&#127798;",
				"&#127799;",
				"&#127800;",
				"&#127801;",
				"&#127802;",
				"&#127803;",
				"&#127804;",
				"&#127805;",
				"&#127806;",
				"&#127807;",
				"&#127808;",
				"&#127809;",
				"&#127810;",
				"&#127828;",
				"&#127829;",
				"&#127830;",
				"&#127831;",
				"&#127832;",
				"&#127833;",
				"&#127834;",
				"&#127835;",
				"&#127836;",
				"&#127838;",
				"&#127839;",
				"&#127840;",
				"&#127841;",
				"&#127842;",
				"&#127843;",
				"&#127846;",
				"&#127848;",
				"&#127849;",
				"&#127850;",
				"&#127851;",
				"&#127853;",
				"&#127854;",
				"&#127855;",
				"&#127856;",
				"&#127857;",
				"&#127858;",
				"&#127859;",
				"&#127860;",
				"&#127861;",
				"&#127862;",
				"&#127863;",
				"&#127864;",
				"&#127865;",
				"&#127866;",
				"&#127867;",
				"&#127868;",
				"&#127869;",
				"&#127871;",
				"&#127872;",
				"&#127873;",
				"&#127874;",
				"&#127875;",
				"&#127876;",
				"&#127877;",
				"&#127878;",
				"&#127879;",
				"&#127880;",
				"&#128087;",
				"&#128088;",
				"&#128089;",
				"&#128090;",
				"&#128091;",
				"&#128092;",
				"&#128093;",
				"&#128094;",
				"&#128095;",
				"&#128096;",
				"&#128097;",
				"&#128098;",
				"&#128099;",
				"&#128128;",
				"&#128139;",
				"&#128140;",
				"&#128147;",
				"&#128148;",
				"&#128149;",
				"&#128150;",
				"&#128151;",
				"&#128152;",
				"&#128153;",
				"&#128154;",
				"&#128155;",
				"&#128156;",
				"&#128157;",
				"&#128158;",
				"&#128159;",
				"&#128336;",
				"&#128337;",
				"&#128338;",
				"&#128339;",
				"&#128340;",
				"&#128341;",
				"&#128342;",
				"&#128343;",
				"&#128344;",
				"&#128345;",
				"&#128346;",
				"&#128347;",
				"&#128348;",
				"&#128349;",
				"&#128350;",
				"&#128351;",
				"&#128352;",
				"&#128353;",
				"&#128354;",
				"&#128355;",
				"&#128356;",
				"&#128357;",
				"&#128358;",
				"&#128359;",
				"&#128643;",
				"&#128644;",
				"&#128645;",
				"&#128646;",
				"&#128647;",
				"&#128648;",
				"&#128649;",
				"&#128650;",
				"&#128651;",
				"&#128652;",
				"&#128653;",
				"&#128654;",
				"&#128655;",
				"&#128656;",
				"&#128657;",
				"&#128658;",
				"&#128659;",
				"&#128660;",
				"&#128661;",
				"&#128662;",
				"&#128663;",
				"&#128664;",
				"&#128665;",
				"&#128666;",
				"&#128667;",
				"&#128668;",
				"&#128669;",
				"&#128670;",
				"&#128671;",
				"&#128672;",
				"&#128673;",
				"&#128674;",
				"&#128675;",
				"&#128676;",
				"&#128677;",
				"&#128678;",
				"&#128679;",
				"&#128680;"
			),
		);
		return $emojis;
	}
	function getTimeArray(){
		$timeArray = array('00:00'=>'12:00 AM','00:15'=>'12:15 AM','00:30'=>'12:30 AM','00:45'=>'12:45 AM','01:00'=>'01:00 AM','01:15'=>'01:15 AM','01:30'=>'01:30 AM','01:45'=>'01:45 AM','02:00'=>'02:00 AM','02:15'=>'02:15 AM','02:30'=>'02:30 AM','02:45'=>'02:45 AM','03:00'=>'03:00 AM','03:15'=>'03:15 AM','03:30'=>'03:30 AM','03:45'=>'03:45 AM','04:00'=>'04:00 AM','04:15'=>'04:15 AM','04:30'=>'04:30 AM','04:45'=>'04:45 AM','05:00'=>'05:00 AM','05:15'=>'05:15 AM','05:30'=>'05:30 AM','05:45'=>'05:45 AM','06:00'=>'06:00 AM','06:15'=>'06:15 AM','06:30'=>'06:30 AM','06:45'=>'06:45 AM','07:00'=>'07:00 AM','07:15'=>'07:15 AM','07:30'=>'07:30 AM','07:45'=>'07:45 AM','08:00'=>'08:00 AM','08:15'=>'08:15 AM','08:30'=>'08:30 AM','08:45'=>'08:45 AM','09:00'=>'09:00 AM','09:15'=>'09:15 AM','09:30'=>'09:30 AM','09:45'=>'09:45 AM','10:00'=>'10:00 AM','10:15'=>'10:15 AM','10:30'=>'10:30 AM','10:45'=>'10:45 AM','11:00'=>'11:00 AM','11:15'=>'11:15 AM','11:30'=>'11:30 AM','11:45'=>'11:45 AM','12:00'=>'12:00 PM','12:15'=>'12:15 PM','12:30'=>'12:30 PM','12:45'=>'12:45 PM','13:00'=>'01:00 PM','13:15'=>'01:15 PM','13:30'=>'01:30 PM','13:45'=>'01:45 PM','14:00'=>'02:00 PM','14:15'=>'02:15 PM','14:30'=>'02:30 PM','14:45'=>'02:45 PM','15:00'=>'03:00 PM','15:15'=>'03:15 PM','15:30'=>'03:30 PM','15:45'=>'03:45 PM','16:00'=>'04:00 PM','16:15'=>'04:15 PM','16:30'=>'04:30 PM','16:45'=>'04:45 PM','17:00'=>'05:00 PM','17:15'=>'05:15 PM','17:30'=>'05:30 PM','17:45'=>'05:45 PM','18:00'=>'06:00 PM','18:15'=>'06:15 PM','18:30'=>'06:30 PM','18:45'=>'06:45 PM','19:00'=>'07:00 PM','19:15'=>'07:15 PM','19:30'=>'07:30 PM','19:45'=>'07:45 PM','20:00'=>'08:00 PM','20:15'=>'08:15 PM','20:30'=>'08:30 PM','20:45'=>'08:45 PM','21:00'=>'09:00 PM','21:15'=>'09:15 PM','21:30'=>'09:30 PM','21:45'=>'09:45 PM','22:00'=>'10:00 PM','22:15'=>'10:15 PM','22:30'=>'10:30 PM','22:45'=>'10:45 PM','23:00'=>'11:00 PM','23:15'=>'11:15 PM','23:30'=>'11:30 PM','23:45'=>'11:45 PM');
		return $timeArray;
	}
	$timezones = array(
		'Pacific/Midway'       => "(GMT-11:00) Midway Island",
		'US/Samoa'             => "(GMT-11:00) Samoa",
		'US/Hawaii'            => "(GMT-10:00) Hawaii",
		'US/Alaska'            => "(GMT-09:00) Alaska",
		'US/Pacific'           => "(GMT-08:00) Pacific Time (US &amp; Canada)",
		'America/Tijuana'      => "(GMT-08:00) Tijuana",
		'US/Arizona'           => "(GMT-07:00) Arizona",
		'US/Mountain'          => "(GMT-07:00) Mountain Time (US &amp; Canada)",
		'America/Chihuahua'    => "(GMT-07:00) Chihuahua",
		'America/Mazatlan'     => "(GMT-07:00) Mazatlan",
		'America/Mexico_City'  => "(GMT-06:00) Mexico City",
		'America/Monterrey'    => "(GMT-06:00) Monterrey",
		'Canada/Saskatchewan'  => "(GMT-06:00) Saskatchewan",
		'US/Central'           => "(GMT-06:00) Central Time (US &amp; Canada)",
		'US/Eastern'           => "(GMT-05:00) Eastern Time (US &amp; Canada)",
		'US/East-Indiana'      => "(GMT-05:00) Indiana (East)",
		'America/Bogota'       => "(GMT-05:00) Bogota",
		'America/Lima'         => "(GMT-05:00) Lima",
		'America/Caracas'      => "(GMT-04:30) Caracas",
		'Canada/Atlantic'      => "(GMT-04:00) Atlantic Time (Canada)",
		'America/La_Paz'       => "(GMT-04:00) La Paz",
		'America/Santiago'     => "(GMT-04:00) Santiago",
		'Canada/Newfoundland'  => "(GMT-03:30) Newfoundland",
		'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
		'Greenland'            => "(GMT-03:00) Greenland",
		'Atlantic/Stanley'     => "(GMT-02:00) Stanley",
		'Atlantic/Azores'      => "(GMT-01:00) Azores",
		'Atlantic/Cape_Verde'  => "(GMT-01:00) Cape Verde Is.",
		'Africa/Casablanca'    => "(GMT) Casablanca",
		'Europe/Dublin'        => "(GMT) Dublin",
		'Europe/Lisbon'        => "(GMT) Lisbon",
		'Europe/London'        => "(GMT) London",
		'Africa/Monrovia'      => "(GMT) Monrovia",
		'Europe/Amsterdam'     => "(GMT+01:00) Amsterdam",
		'Europe/Belgrade'      => "(GMT+01:00) Belgrade",
		'Europe/Berlin'        => "(GMT+01:00) Berlin",
		'Europe/Bratislava'    => "(GMT+01:00) Bratislava",
		'Europe/Brussels'      => "(GMT+01:00) Brussels",
		'Europe/Budapest'      => "(GMT+01:00) Budapest",
		'Europe/Copenhagen'    => "(GMT+01:00) Copenhagen",
		'Europe/Ljubljana'     => "(GMT+01:00) Ljubljana",
		'Europe/Madrid'        => "(GMT+01:00) Madrid",
		'Europe/Paris'         => "(GMT+01:00) Paris",
		'Europe/Prague'        => "(GMT+01:00) Prague",
		'Europe/Rome'          => "(GMT+01:00) Rome",
		'Europe/Sarajevo'      => "(GMT+01:00) Sarajevo",
		'Europe/Skopje'        => "(GMT+01:00) Skopje",
		'Europe/Stockholm'     => "(GMT+01:00) Stockholm",
		'Europe/Vienna'        => "(GMT+01:00) Vienna",
		'Europe/Warsaw'        => "(GMT+01:00) Warsaw",
		'Europe/Zagreb'        => "(GMT+01:00) Zagreb",
		'Europe/Athens'        => "(GMT+02:00) Athens",
		'Europe/Bucharest'     => "(GMT+02:00) Bucharest",
		'Africa/Cairo'         => "(GMT+02:00) Cairo",
		'Africa/Harare'        => "(GMT+02:00) Harare",
		'Europe/Helsinki'      => "(GMT+02:00) Helsinki",
		'Europe/Istanbul'      => "(GMT+02:00) Istanbul",
		'Asia/Jerusalem'       => "(GMT+02:00) Jerusalem",
		'Europe/Kiev'          => "(GMT+02:00) Kyiv",
		'Europe/Minsk'         => "(GMT+02:00) Minsk",
		'Europe/Riga'          => "(GMT+02:00) Riga",
		'Europe/Sofia'         => "(GMT+02:00) Sofia",
		'Europe/Tallinn'       => "(GMT+02:00) Tallinn",
		'Europe/Vilnius'       => "(GMT+02:00) Vilnius",
		'Asia/Baghdad'         => "(GMT+03:00) Baghdad",
		'Asia/Kuwait'          => "(GMT+03:00) Kuwait",
		'Africa/Nairobi'       => "(GMT+03:00) Nairobi",
		'Asia/Riyadh'          => "(GMT+03:00) Riyadh",
		'Europe/Moscow'        => "(GMT+03:00) Moscow",
		'Asia/Tehran'          => "(GMT+03:30) Tehran",
		'Asia/Baku'            => "(GMT+04:00) Baku",
		'Europe/Volgograd'     => "(GMT+04:00) Volgograd",
		'Asia/Muscat'          => "(GMT+04:00) Muscat",
		'Asia/Tbilisi'         => "(GMT+04:00) Tbilisi",
		'Asia/Yerevan'         => "(GMT+04:00) Yerevan",
		'Asia/Kabul'           => "(GMT+04:30) Kabul",
		'Asia/Karachi'         => "(GMT+05:00) Karachi",
		'Asia/Tashkent'        => "(GMT+05:00) Tashkent",
		'Asia/Kolkata'         => "(GMT+05:30) Kolkata",
		'Asia/Kathmandu'       => "(GMT+05:45) Kathmandu",
		'Asia/Yekaterinburg'   => "(GMT+06:00) Ekaterinburg",
		'Asia/Almaty'          => "(GMT+06:00) Almaty",
		'Asia/Dhaka'           => "(GMT+06:00) Dhaka",
		'Asia/Novosibirsk'     => "(GMT+07:00) Novosibirsk",
		'Asia/Bangkok'         => "(GMT+07:00) Bangkok",
		'Asia/Jakarta'         => "(GMT+07:00) Jakarta",
		'Asia/Krasnoyarsk'     => "(GMT+08:00) Krasnoyarsk",
		'Asia/Chongqing'       => "(GMT+08:00) Chongqing",
		'Asia/Hong_Kong'       => "(GMT+08:00) Hong Kong",
		'Asia/Kuala_Lumpur'    => "(GMT+08:00) Kuala Lumpur",
		'Australia/Perth'      => "(GMT+08:00) Perth",
		'Asia/Singapore'       => "(GMT+08:00) Singapore",
		'Asia/Taipei'          => "(GMT+08:00) Taipei",
		'Asia/Ulaanbaatar'     => "(GMT+08:00) Ulaan Bataar",
		'Asia/Urumqi'          => "(GMT+08:00) Urumqi",
		'Asia/Irkutsk'         => "(GMT+09:00) Irkutsk",
		'Asia/Seoul'           => "(GMT+09:00) Seoul",
		'Asia/Tokyo'           => "(GMT+09:00) Tokyo",
		'Australia/Adelaide'   => "(GMT+09:30) Adelaide",
		'Australia/Darwin'     => "(GMT+09:30) Darwin",
		'Asia/Yakutsk'         => "(GMT+10:00) Yakutsk",
		'Australia/Brisbane'   => "(GMT+10:00) Brisbane",
		'Australia/Canberra'   => "(GMT+10:00) Canberra",
		'Pacific/Guam'         => "(GMT+10:00) Guam",
		'Australia/Hobart'     => "(GMT+10:00) Hobart",
		'Australia/Melbourne'  => "(GMT+10:00) Melbourne",
		'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
		'Australia/Sydney'     => "(GMT+10:00) Sydney",
		'Asia/Vladivostok'     => "(GMT+11:00) Vladivostok",
		'Asia/Magadan'         => "(GMT+12:00) Magadan",
		'Pacific/Auckland'     => "(GMT+12:00) Auckland",
		'Pacific/Fiji'         => "(GMT+12:00) Fiji"
	);
	function generatePaging($sql,$pageLink,$pageNum,$max_records_per_page){ // Modified
		echo '<style> .btn-grey{
			-webkit-border-radius: 5;
			-moz-border-radius: 5;
			-o-border-radius: 5;
			border-radius: 5px;
			color: #999999;
			font-size: 16px;
			background: #fff;
			padding: 5px 10px 5px 10px;
			border: solid #eeeeee 1px;
			text-decoration: none;
		}
		.btn-grey:hover{
			background: #7E57C2;
			text-decoration: none;
			color:#fff;
		}
		.btn-pages-active{
			font-family: Arial;
			color: #ffffff;
			font-size: 16px;
			background: #7E57C2 !important;
			padding: 8px 10px 8px 10px;
			text-decoration: none;
			border: solid #7E57C2 1px;
		}
		.btn-pages-active:hover{
			background: #1B53B7;
			text-decoration: none;
			border: solid #7E57C2 1px;
		}
		.btn-pages-inactive{
			font-family: Arial;
			color: #999999;
			font-size: 16px;
			background: #FFFFFF;
			padding: 8px 10px 8px 10px;
			text-decoration: none;
			border: solid #eeeeee 1px;
		}
		.btn-pages-inactive:hover{
			background: #EEEEEE;
			text-decoration: none;
			color:#2A6496;
		} </style>';
		global $link;
		if($pageNum==1){
			$tmpRes = mysqli_query($link,$sql);
			$totalRecs = mysqli_num_rows($tmpRes);
			$_SESSION['TOTAL_RECORDS'] = $totalRecs;
		}
		$recStart = ((int)($pageNum-1))*((int)$max_records_per_page);
		$totalRecs = $_SESSION['TOTAL_RECORDS'];
		$pagingString = '<table border="0" cellspacing="0" cellpadding="0" ><tr><td align="right" valign="middle" style="padding:5px">';
		$totalPages = ceil(((int) $totalRecs)/((int)$max_records_per_page));
		$pagingStartPage = 1;
		$pagingEndPage = $totalPages ;
		if($pageNum>6)
			$pagingStartPage = $pageNum - 5;
		if($pageNum<($totalPages-5))
			$pagingEndPage = $pageNum + 5;
		if($pageNum>1){
			$prPage = $pageNum -1;
			$pagingString .= '<a href="'.$pageLink.'page=1"><span class="btn-grey">First</span></a>';
			$pagingString .= ' <a href="'.$pageLink.'page='. $prPage .'"><span class="btn-grey">Previous</span></a> ';
		}
		for($i=$pagingStartPage;$i<=$pagingEndPage;$i++){
			if($pageNum == $i){
				$pagingString .= '<span class="btn-pages-active">' . $i . '</span>';
			}else{
				$pagingString .= '<a href="'.$pageLink.'page='.$i.'" class="btn-pages-inactive">'.$i.'</a>';
			}
			if($i != $pagingEndPage)
				$pagingString .= ' ';
		}
		if($pageNum < $totalPages){
			$nePage = $pageNum + 1;
			$pagingString .= ' <a href="'.$pageLink.'page='. $nePage .'" ><span class="btn-grey">Next</span></a> ';
			$pagingString .= '<a href="'.$pageLink.'page='. $totalPages .'" ><span class="btn-grey">Last</span></a> ';
		}
		$pagingString .= '</td></tr></table>';	
		$sqlLIMIT = " LIMIT ". $recStart . " , " . $max_records_per_page;
		if($totalPages == 1){
			$a['pagingString'] = '';
			$a['limit'] = '';
		}else{
			$a['pagingString'] = $pagingString;
			$a['limit'] =  $sqlLIMIT;
		}
		return $a;
	} 
	function sendEmail($subject,$to,$from,$msg,$FullName){
		$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'To: <'.$to.'>'. "\r\n";
		$headers .= 'From: '.$FullName.' <'.$from.'>' . "\r\n";
		mail($to, $subject, $msg, $headers);
	}
	function sendPHPMailerEmail($subject,$to,$from,$msg,$FullName){
		
		require_once 'PHPMailer/src/Exception.php';
		require_once 'PHPMailer/src/PHPMailer.php';
		require_once 'PHPMailer/src/SMTP.php';
	
		//Create an instance; passing `true` enables exceptions
		$mail = new PHPMailer(true);
		
		try {
			//Server settings
			$mail->SMTPDebug  = 0;                      	//Enable verbose debug output
			$mail->isSMTP();                                //Send using SMTP
			$mail->Host       = 'buildors.com';     	//Set the SMTP server to send through
			$mail->SMTPAuth   = true;                       //Enable SMTP authentication
			$mail->Username   = 'info@buildors.com';   	//SMTP username
			$mail->Password   = 'Temp@12345';             //SMTP password
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  //Enable implicit TLS encryption
			$mail->Port       = 465;                        //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
		
			//Recipients
			$mail->setFrom('info@buildors.com', 'Admin');
			$mail->addAddress($to, $FullName);     //Add a recipient
		
			//Content
			$mail->isHTML(true);                  //Set email format to HTML
			$mail->Subject = $subject;
			$mail->Body    = $msg;
		
			$mail->send();
			// return 'Message has been sent';
		} catch (Exception $e) {
			// return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	}
	function checkCondition($condion){
		$conditons = [
			"Is"=>"==",
			"Is not"=>"!=",
			"Contains"=>"==",
			"Does not contain"=>"==",
			"Is not empty"=>"!=",
			"Is empty"=>"==",
		];
		return $conditons[$condion];
	}
	function getCoulmValue($booking_data_row,$condion){
		$conditons = [
			"Email"=>"email",
			"First Name"=>"firstName",
			"Last Name"=>"lastName",
			"Full Name"=>"firstName",
			"Company Name"=>"companyname",
			"Phone"=>"phone",
			"Address"=>"addressLine",
			"City"=>"city",
			"Postal Code"=>"postalcode",
			"State"=>"state",
			"Country"=>"country",
			"Website"=>"website",
			"Date of Birth"=>"dob"
		];
		if(isset($booking_data_row[$conditons[$condion]])){
			return $booking_data_row[$conditons[$condion]];
		}
		return false;
	}
	function runWorkFlowold($booking_id){
		global $link;
		// $booking_data = mysqli_query($link,"select * from bookings where id=".($booking_id));
		// if(mysqli_num_rows($booking_data)>0){
		// 	$booking_data_row = mysqli_fetch_assoc($booking_data);
		// 	if($booking_data_row['status'] == "booked"){
		// 		$workflow_data = mysqli_query($link,"select * from workflow");
		// 		if(mysqli_num_rows($workflow_data)>0){
		// 			while($row = mysqli_fetch_assoc($workflow_data)){
		// 				// print_r(($row['triggers']));
		// 				if($row['triggers']){
		// 					$userID = $row['user_id'];
		// 					$triggers = json_decode($row['triggers']);
		// 					foreach($triggers as $trigger){
		// 						if($trigger->id == 1 && $trigger->filters_value == "booked"){
		// 							if($row['actions']){
		// 								$actions = json_decode($row['actions']);
		// 								echo('<pre>');
		// 								print_r($actions);
		// 								die;
		// 								foreach($actions as $acion){
		// 									if($acion){
		// 										switch($acion->modal_id){
		// 											case 'emailactionModal':{
		// 												// echo('<pre>');
		// 												// print_r($acion->formdt);
		// 												// die;
		// 												$fromname = $acion->formdt->fromname;
		// 												$fromemail = $acion->formdt->fromemail;
		// 												$email_subject = $acion->formdt->email_subject;
		// 												$email_msg = $acion->formdt->email_msg;
		// 												$toemail = $booking_data_row['email'];
		// 												sendPHPMailerEmail($email_subject,$toemail,$fromemail,$email_msg,$fromname);
		// 											}break;
		// 											case 'smsactionModal':{
		// 												$sms_msg = $acion->formdt->sms_msg;
		// 												$to_number = $booking_data_row['phone'];
		// 												$twilioNumbers = getRandomTwilioNumbers($userID);
		// 												if($twilioNumbers){
		// 													$numberkey     = array_rand($twilioNumbers,1);
		// 													$from_number = removeCountryCode($twilioNumbers[$numberkey]);
		// 													sendMessage($from_number, $to_number, $sms_msg);
		// 												}
		// 											}break;
		// 										}
		// 									}
		// 								}
		// 							}
		// 						}
		// 					}
		// 				}
		// 			}
		// 		}
		// 	}
		// }
		die('404');
	}
	function checkBookedStatus($given_status){
		$statusss = [
			"booked",
			"new",
			"cancelled",
			"invalid"
		];
		return in_array($given_status,$statusss);
	}
	function runWorkFlow($booking_id){
		global $link;
		$booking_data = mysqli_query($link,"select * from bookings where id=".($booking_id));
		if(mysqli_num_rows($booking_data)>0){
			$booking_data_row = mysqli_fetch_assoc($booking_data);
			// echo('<pre>');
			// print_r(checkBookedStatus($booking_data_row['status']));
			// die;
			if(checkBookedStatus($booking_data_row['status'])){
				$workflow_data = mysqli_query($link,"select * from workflow");
				if(mysqli_num_rows($workflow_data)>0){
					while($row = mysqli_fetch_assoc($workflow_data)){
						// print_r(($row['triggers']));
						if($row['triggers']){
							$userID = $row['user_id'];
							$triggers = json_decode($row['triggers']);
							foreach($triggers as $trigger){
								// echo('<pre>');
								// print_r($trigger);
								// die;
								if($trigger->id == 1 && $trigger->filters_value == $booking_data_row['status']){
									if($row['actions']){
										$actions = json_decode($row['actions']);
										// echo('<pre>');
										// print_r($actions);
										// die;
										foreach($actions as $acion){
											if($acion){
												if($acion->modal_id == 'conditionsactionModal'){
													$is_have_branch = false;
													foreach($acion->formdt->branches as $key=>$branch) {
														// echo('<pre>');
														// print_r($branch);
														// die;
														$condition_segment = $branch[0][1];
														$branch_segment_res = true;
														foreach($branch[1] as $segments) {
															// echo('<pre>');
															// print_r($segments);
															// die;
															$is_first_segment = 1;
															$branch_segments_res = true;
															foreach($segments as $conditions) {
																// echo('<pre>');
																// print_r($conditions);
																// die;
																$is_first_condition = 1;
																$segment_condion_res = true;
																foreach($conditions as $condion) {
																	// echo('<pre>');
																	// print_r($condion[0]);
																	// die;
																	$segment_condionres = true;
																	if($is_first_condition == 1){
																		$condition_condion = $condion[3];
																	}
																	if($condion[1] == "Is"){
																		if(getCoulmValue($booking_data_row,$condion[0]) == $condion[2]){
																			$segment_condionres = true;
																		}else{
																			$segment_condionres = false;
																			if($is_first_condition == 1){
																				$segment_condion_res = false;
																			}
																		}
																	}elseif($condion[1] == "Is not"){
																		// echo('<pre>');
																		// print_r(getCoulmValue($booking_data_row,$condion[0]) != $condion[2]);
																		// die;
																		if(getCoulmValue($booking_data_row,$condion[0]) != $condion[2]){
																			$segment_condionres = true;
																		}else{
																			$segment_condionres = false;
																			if($is_first_condition == 1){
																				$segment_condion_res = false;
																			}
																		}
																	}elseif($condion[1] == "Contains"){
																		if(stristr(getCoulmValue($booking_data_row,$condion[0]), $condion[2])){
																			$segment_condionres = true;
																		}else{
																			$segment_condionres = false;
																			if($is_first_condition == 1){
																				$segment_condion_res = false;
																			}
																		}
																	}elseif($condion[1] == "Does not contain"){
																		if(stristr(getCoulmValue($booking_data_row,$condion[0]), $condion[2])){
																			$segment_condionres = false;
																		}else{
																			$segment_condionres = true;
																			if($is_first_condition == 1){
																				$segment_condion_res = true;
																			}
																		}
																	}elseif($condion[1] == "Is empty"){
																		if(getCoulmValue($booking_data_row,$condion[0])){
																			$segment_condionres = false;
																		}else{
																			$segment_condionres = true;
																			if($is_first_condition == 1){
																				$segment_condion_res = true;
																			}
																		}
																	}elseif($condion[1] == "Is not empty"){
																		if(getCoulmValue($booking_data_row,$condion[0])){
																			$segment_condionres = true;
																		}else{
																			$segment_condionres = false;
																			if($is_first_condition == 1){
																				$segment_condion_res = false;
																			}
																		}
																	}
																	if($is_first_condition == 1){
																		$is_first_condition = 0;
																	}
																	if($condition_condion == "OR"){
																		if($segment_condionres || $segment_condion_res){
																			$segment_condion_res = true;
																		}else{
																			$segment_condion_res = false;
																		}
																	}elseif($condition_condion == "AND"){
																		if($segment_condionres && $segment_condion_res){
																			$segment_condion_res = true;
																		}else{
																			$segment_condion_res = false;
																		}
																	}
																}
																// echo('<pre>');
																// print_r($conditions);
																// die;
																if($is_first_segment == 1){
																	$branch_segment_res = $segment_condion_res;
																	$is_first_segment = 0;
																}
																// else{
																// 	echo('<pre>');
																// 	print_r($conditions);
																// 	die;
																// }
																if($condition_segment == "OR"){
																	if($branch_segment_res || $segment_condion_res){
																		$branch_segment_res = true;
																	}else{
																		$branch_segment_res = false;
																	}
																}elseif($condition_segment == "AND"){
																	if($branch_segment_res && $segment_condion_res){
																		$branch_segment_res = true;
																	}else{
																		$branch_segment_res = false;
																	}
																}
															}
														}
														// echo('<pre>');
														// print_r($branch_segment_res);
														// die;
														if($branch_segment_res){
															$is_have_branch = true;
															// $child_node_actions = ishave_parent_acction($acion->formdt->parent_id,'branch_'.$key);
															foreach ($actions as $x) {
																if($x){
																	// echo('<pre>');
																	// print_r($x);
																	// die;
																	if('branch_'.$key == $x->branch_id){
																		switch($x->modal_id){
																			case 'emailactionModal':{
																				// echo('<pre>');
																				// print_r($x->formdt);
																				// die;
																				$fromname = $x->formdt->fromname;
																				$fromemail = $x->formdt->fromemail;
																				$email_subject = $x->formdt->email_subject;
																				$email_msg = $x->formdt->email_msg;
																				$toemail = $booking_data_row['email'];
																				sendPHPMailerEmail($email_subject,$toemail,$fromemail,$email_msg,$fromname);
																			}break;
																			case 'smsactionModal':{
																				$sms_msg = $x->formdt->sms_msg;
																				$to_number = $booking_data_row['phone'];
																				$twilioNumbers = getRandomTwilioNumbers($userID);
																				if($twilioNumbers){
																					$numberkey     = array_rand($twilioNumbers,1);
																					$from_number = removeCountryCode($twilioNumbers[$numberkey]);
																					sendMessage($from_number, $to_number, $sms_msg);
																				}
																			}break;
																			case 'callactionModal':{
																				$call_msg = $x->formdt->call_msg;
	
																				$to_number = $booking_data_row['phone'];
	
																				$twilioNumbers = getRandomTwilioNumbers($userID);
	
																				if($twilioNumbers){
	
																					$numberkey     = array_rand($twilioNumbers,1);
	
																					$from_number = removeCountryCode($twilioNumbers[$numberkey]);
	
																					Makecall($to_number, $from_number, $call_msg);
	
																				}
	
																			}break;
																		}
																	}
																}
															}
														}
													}
													if(!$is_have_branch){
														foreach ($actions as $x) {
															if($x){
																if('elsebranch_'.$key == $x->branch_id){
																	switch($x->modal_id){
																		case 'emailactionModal':{
																			// echo('<pre>');
																			// print_r($x->formdt);
																			// die;
																			$fromname = $x->formdt->fromname;
																			$fromemail = $x->formdt->fromemail;
																			$email_subject = $x->formdt->email_subject;
																			$email_msg = $x->formdt->email_msg;
																			$toemail = $booking_data_row['email'];
																			sendPHPMailerEmail($email_subject,$toemail,$fromemail,$email_msg,$fromname);
																		}break;
																		case 'smsactionModal':{
																			$sms_msg = $x->formdt->sms_msg;
																			$to_number = $booking_data_row['phone'];
																			$twilioNumbers = getRandomTwilioNumbers($userID);
																			if($twilioNumbers){
																				$numberkey     = array_rand($twilioNumbers,1);
																				$from_number = removeCountryCode($twilioNumbers[$numberkey]);
																				sendMessage($from_number, $to_number, $sms_msg);
																			}
																		}break;
																		case 'callactionModal':{
																			$call_msg = $x->formdt->call_msg;
																			$to_number = $booking_data_row['phone'];
																			$twilioNumbers = getRandomTwilioNumbers($userID);
																			if($twilioNumbers){
																				$numberkey     = array_rand($twilioNumbers,1);
																				$from_number = removeCountryCode($twilioNumbers[$numberkey]);
																				Makecall($to_number, $from_number, $call_msg);
																			}
																		}break;
																	}
																}
															}
														}
													}
													break;
												}else{
													if(-1 == $acion->branch_id){
														switch($acion->modal_id){
															case 'emailactionModal':{
																// echo('<pre>');
																// print_r($acion->formdt);
																// die;
																$fromname = $acion->formdt->fromname;
																$fromemail = $acion->formdt->fromemail;
																$email_subject = $acion->formdt->email_subject;
																$email_msg = $acion->formdt->email_msg;
																$toemail = $booking_data_row['email'];
																sendPHPMailerEmail($email_subject,$toemail,$fromemail,$email_msg,$fromname);
															}break;
															case 'smsactionModal':{
																$sms_msg = $acion->formdt->sms_msg;
																$to_number = $booking_data_row['phone'];
																$twilioNumbers = getRandomTwilioNumbers($userID);
																if($twilioNumbers){
																	$numberkey     = array_rand($twilioNumbers,1);
																	$from_number = removeCountryCode($twilioNumbers[$numberkey]);
																	sendMessage($from_number, $to_number, $sms_msg);
																}
															}break;
															case 'callactionModal':{
																$call_msg = $acion->formdt->call_msg;
																$to_number = $booking_data_row['phone'];
																$twilioNumbers = getRandomTwilioNumbers($userID);
																if($twilioNumbers){
																	$numberkey     = array_rand($twilioNumbers,1);
																	$from_number = removeCountryCode($twilioNumbers[$numberkey]);
																	Makecall($to_number, $from_number, $call_msg);
																}
															}break;
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		die('404');
	}
	function Makecall($to_number, $from_number, $call_msg)
	{
		$TwilioSid   = 'ACb797881d79a639eefb0a266f275b895b';
		$TwilioToken = 'e0419a64126bc4424d9b367d809a9aa0';
		$url = "https://buildors.com/workflow_call_handle.php?call_msg=".urlencode($call_msg);
		$url1 = "https://$TwilioSid:$TwilioToken@api.twilio.com/2010-04-01/Accounts/$TwilioSid/Calls.json";
		$val = array(
			"To" => $to_number,
			"From" => $from_number,
			"Url" => $url,
		);
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_URL, $url1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $val);
		$response = curl_exec($ch);
		curl_close($ch);
		$check = (array) json_decode($response);
		//print_r($check);
		if (!isset($check['RestException'])) {
			return true;
		} else {
			return $check['RestException']->Message;
			// return 'false';
		}
	}
	function ishave_parent_acction($parent_index,$branddid){
		$have_parent_acction = []; 
		foreach ($actionss as $x) {
			if($actionss[$x]){
				if($branddid == $actionss[x]['branch_id']){
					$have_parent_acction.push($actionss[$x]);
				}
			}
		}
	
		return $have_parent_acction;
	}
	function getDayIndex($day){
		$days = [
			'monday'=>1,
			'tuesday'=>2,
			'wednesday'=>3,
			'thursday'=>4,
			'friday'=>5,
			'saturday'=>6,
			'sunday'=>7
		];
		return $days[$day];
	}
	function runWorkFlowwithoutTrigger($booking_id,$workflow_id){
		global $link;
		$booking_data = mysqli_query($link,"select * from bookings where id=".($booking_id));
		if(mysqli_num_rows($booking_data)>0){
			$booking_data_row = mysqli_fetch_assoc($booking_data);
			// echo('<pre>');
			// print_r(checkBookedStatus($booking_data_row['status']));
			// die;
			if(checkBookedStatus($booking_data_row['status'])){
				$workflow_data = mysqli_query($link,"select * from workflow where id=".($workflow_id));
				if(mysqli_num_rows($workflow_data)>0){
					$row = mysqli_fetch_assoc($workflow_data);
					if($row['actions']){
						$actions = json_decode($row['actions']);
						// echo('<pre>');
						// print_r($actions);
						// die;
						foreach($actions as $acion){
							if($acion){
								if($acion->modal_id == 'conditionsactionModal'){
									$is_have_branch = false;
									foreach($acion->formdt->branches as $key=>$branch) {
										// echo('<pre>');
										// print_r($branch);
										// die;
										$condition_segment = $branch[0][1];
										$branch_segment_res = true;
										foreach($branch[1] as $segments) {
											// echo('<pre>');
											// print_r($segments);
											// die;
											$is_first_segment = 1;
											$branch_segments_res = true;
											foreach($segments as $conditions) {
												// echo('<pre>');
												// print_r($conditions);
												// die;
												$is_first_condition = 1;
												$segment_condion_res = true;
												foreach($conditions as $condion) {
													// echo('<pre>');
													// print_r($condion[0]);
													// die;
													$segment_condionres = true;
													if($is_first_condition == 1){
														$condition_condion = $condion[3];
													}
													if($condion[1] == "Is"){
														if(getCoulmValue($booking_data_row,$condion[0]) == $condion[2]){
															$segment_condionres = true;
														}else{
															$segment_condionres = false;
															if($is_first_condition == 1){
																$segment_condion_res = false;
															}
														}
													}elseif($condion[1] == "Is not"){
														// echo('<pre>');
														// print_r(getCoulmValue($booking_data_row,$condion[0]) != $condion[2]);
														// die;
														if(getCoulmValue($booking_data_row,$condion[0]) != $condion[2]){
															$segment_condionres = true;
														}else{
															$segment_condionres = false;
															if($is_first_condition == 1){
																$segment_condion_res = false;
															}
														}
													}elseif($condion[1] == "Contains"){
														if(stristr(getCoulmValue($booking_data_row,$condion[0]), $condion[2])){
															$segment_condionres = true;
														}else{
															$segment_condionres = false;
															if($is_first_condition == 1){
																$segment_condion_res = false;
															}
														}
													}elseif($condion[1] == "Does not contain"){
														if(stristr(getCoulmValue($booking_data_row,$condion[0]), $condion[2])){
															$segment_condionres = false;
														}else{
															$segment_condionres = true;
															if($is_first_condition == 1){
																$segment_condion_res = true;
															}
														}
													}elseif($condion[1] == "Is empty"){
														if(getCoulmValue($booking_data_row,$condion[0])){
															$segment_condionres = false;
														}else{
															$segment_condionres = true;
															if($is_first_condition == 1){
																$segment_condion_res = true;
															}
														}
													}elseif($condion[1] == "Is not empty"){
														if(getCoulmValue($booking_data_row,$condion[0])){
															$segment_condionres = true;
														}else{
															$segment_condionres = false;
															if($is_first_condition == 1){
																$segment_condion_res = false;
															}
														}
													}
													if($is_first_condition == 1){
														$is_first_condition = 0;
													}
													if($condition_condion == "OR"){
														if($segment_condionres || $segment_condion_res){
															$segment_condion_res = true;
														}else{
															$segment_condion_res = false;
														}
													}elseif($condition_condion == "AND"){
														if($segment_condionres && $segment_condion_res){
															$segment_condion_res = true;
														}else{
															$segment_condion_res = false;
														}
													}
												}
												// echo('<pre>');
												// print_r($conditions);
												// die;
												if($is_first_segment == 1){
													$branch_segment_res = $segment_condion_res;
													$is_first_segment = 0;
												}
												// else{
												// 	echo('<pre>');
												// 	print_r($conditions);
												// 	die;
												// }
												if($condition_segment == "OR"){
													if($branch_segment_res || $segment_condion_res){
														$branch_segment_res = true;
													}else{
														$branch_segment_res = false;
													}
												}elseif($condition_segment == "AND"){
													if($branch_segment_res && $segment_condion_res){
														$branch_segment_res = true;
													}else{
														$branch_segment_res = false;
													}
												}
											}
										}
										// echo('<pre>');
										// print_r($branch_segment_res);
										// die;
										if($branch_segment_res){
											$is_have_branch = true;
											// $child_node_actions = ishave_parent_acction($acion->formdt->parent_id,'branch_'.$key);
											foreach ($actions as $x) {
												if($x){
													// echo('<pre>');
													// print_r($x);
													// die;
													if('branch_'.$key == $x->branch_id){
														switch($x->modal_id){
															case 'emailactionModal':{
																// echo('<pre>');
																// print_r($x->formdt);
																// die;
																$fromname = $x->formdt->fromname;
																$fromemail = $x->formdt->fromemail;
																$email_subject = $x->formdt->email_subject;
																$email_msg = $x->formdt->email_msg;
																$toemail = $booking_data_row['email'];
																sendPHPMailerEmail($email_subject,$toemail,$fromemail,$email_msg,$fromname);
															}break;
															case 'smsactionModal':{
																$sms_msg = $x->formdt->sms_msg;
																$to_number = $booking_data_row['phone'];
																$twilioNumbers = getRandomTwilioNumbers($userID);
																if($twilioNumbers){
																	$numberkey     = array_rand($twilioNumbers,1);
																	$from_number = removeCountryCode($twilioNumbers[$numberkey]);
																	sendMessage($from_number, $to_number, $sms_msg);
																}
															}break;
															case 'callactionModal':{
																$call_msg = $x->formdt->call_msg;
																$to_number = $booking_data_row['phone'];
																$twilioNumbers = getRandomTwilioNumbers($userID);
																if($twilioNumbers){
																	$numberkey     = array_rand($twilioNumbers,1);
																	$from_number = removeCountryCode($twilioNumbers[$numberkey]);
																	Makecall($to_number, $from_number, $call_msg);
																}
															}break;
														}
													}
												}
											}
										}
									}
									if(!$is_have_branch){
										foreach ($actions as $x) {
											if($x){
												if('elsebranch_'.$key == $x->branch_id){
													switch($x->modal_id){
														case 'emailactionModal':{
															// echo('<pre>');
															// print_r($x->formdt);
															// die;
															$fromname = $x->formdt->fromname;
															$fromemail = $x->formdt->fromemail;
															$email_subject = $x->formdt->email_subject;
															$email_msg = $x->formdt->email_msg;
															$toemail = $booking_data_row['email'];
															sendPHPMailerEmail($email_subject,$toemail,$fromemail,$email_msg,$fromname);
														}break;
														case 'smsactionModal':{
															$sms_msg = $x->formdt->sms_msg;
															$to_number = $booking_data_row['phone'];
															$twilioNumbers = getRandomTwilioNumbers($userID);
															if($twilioNumbers){
																$numberkey     = array_rand($twilioNumbers,1);
																$from_number = removeCountryCode($twilioNumbers[$numberkey]);
																sendMessage($from_number, $to_number, $sms_msg);
															}
														}break;
														case 'callactionModal':{
															$call_msg = $x->formdt->call_msg;
															$to_number = $booking_data_row['phone'];
															$twilioNumbers = getRandomTwilioNumbers($userID);
															if($twilioNumbers){
																$numberkey     = array_rand($twilioNumbers,1);
																$from_number = removeCountryCode($twilioNumbers[$numberkey]);
																Makecall($to_number, $from_number, $call_msg);
															}
														}break;
													}
												}
											}
										}
									}
									break;
								}else{
									if(-1 == $acion->branch_id){
										switch($acion->modal_id){
											case 'emailactionModal':{
												// echo('<pre>');
												// print_r($acion->formdt);
												// die;
												$fromname = $acion->formdt->fromname;
												$fromemail = $acion->formdt->fromemail;
												$email_subject = $acion->formdt->email_subject;
												$email_msg = $acion->formdt->email_msg;
												$toemail = $booking_data_row['email'];
												sendPHPMailerEmail($email_subject,$toemail,$fromemail,$email_msg,$fromname);
											}break;
											case 'smsactionModal':{
												$sms_msg = $acion->formdt->sms_msg;
												$to_number = $booking_data_row['phone'];
												$twilioNumbers = getRandomTwilioNumbers($userID);
												if($twilioNumbers){
													$numberkey     = array_rand($twilioNumbers,1);
													$from_number = removeCountryCode($twilioNumbers[$numberkey]);
													sendMessage($from_number, $to_number, $sms_msg);
												}
											}break;
											case 'callactionModal':{
												$call_msg = $acion->formdt->call_msg;
												$to_number = $booking_data_row['phone'];
												$twilioNumbers = getRandomTwilioNumbers($userID);
												if($twilioNumbers){
													$numberkey     = array_rand($twilioNumbers,1);
													$from_number = removeCountryCode($twilioNumbers[$numberkey]);
													Makecall($to_number, $from_number, $call_msg);
												}
											}break;
										}
									}
								}
							}
						}
					}
				}
			}
		}
		die('404');
	}
	function runWorkFlowwithSchduler($contact_number,$workflow_id){
		global $link;
		// $booking_data = mysqli_query($link,"select * from bookings where id=".($booking_id));
		// if(mysqli_num_rows($booking_data)>0){
		// 	$booking_data_row = mysqli_fetch_assoc($booking_data);
			// echo('<pre>');
			// print_r(checkBookedStatus($booking_data_row['status']));
			// die;
			// if(checkBookedStatus($booking_data_row['status'])){
				$workflow_data = mysqli_query($link,"select * from workflow where id=".($workflow_id));
				if(mysqli_num_rows($workflow_data)>0){
					$row = mysqli_fetch_assoc($workflow_data);
					if($row['actions']){
						$actions = json_decode($row['actions']);
						// echo('<pre>');
						// print_r($actions);
						// die;
						foreach($actions as $acion){
							if($acion){
								// if($acion->modal_id == 'conditionsactionModal'){
								// 	$is_have_branch = false;
								// 	foreach($acion->formdt->branches as $key=>$branch) {
								// 		// echo('<pre>');
								// 		// print_r($branch);
								// 		// die;
								// 		$condition_segment = $branch[0][1];
								// 		$branch_segment_res = true;
								// 		foreach($branch[1] as $segments) {
								// 			// echo('<pre>');
								// 			// print_r($segments);
								// 			// die;
								// 			$is_first_segment = 1;
								// 			$branch_segments_res = true;
								// 			foreach($segments as $conditions) {
								// 				// echo('<pre>');
								// 				// print_r($conditions);
								// 				// die;
								// 				$is_first_condition = 1;
								// 				$segment_condion_res = true;
								// 				foreach($conditions as $condion) {
								// 					// echo('<pre>');
								// 					// print_r($condion[0]);
								// 					// die;
								// 					$segment_condionres = true;
								// 					if($is_first_condition == 1){
								// 						$condition_condion = $condion[3];
								// 					}
								// 					if($condion[1] == "Is"){
								// 						if(getCoulmValue($booking_data_row,$condion[0]) == $condion[2]){
								// 							$segment_condionres = true;
								// 						}else{
								// 							$segment_condionres = false;
								// 							if($is_first_condition == 1){
								// 								$segment_condion_res = false;
								// 							}
								// 						}
								// 					}elseif($condion[1] == "Is not"){
								// 						// echo('<pre>');
								// 						// print_r(getCoulmValue($booking_data_row,$condion[0]) != $condion[2]);
								// 						// die;
								// 						if(getCoulmValue($booking_data_row,$condion[0]) != $condion[2]){
								// 							$segment_condionres = true;
								// 						}else{
								// 							$segment_condionres = false;
								// 							if($is_first_condition == 1){
								// 								$segment_condion_res = false;
								// 							}
								// 						}
								// 					}elseif($condion[1] == "Contains"){
								// 						if(stristr(getCoulmValue($booking_data_row,$condion[0]), $condion[2])){
								// 							$segment_condionres = true;
								// 						}else{
								// 							$segment_condionres = false;
								// 							if($is_first_condition == 1){
								// 								$segment_condion_res = false;
								// 							}
								// 						}
								// 					}elseif($condion[1] == "Does not contain"){
								// 						if(stristr(getCoulmValue($booking_data_row,$condion[0]), $condion[2])){
								// 							$segment_condionres = false;
								// 						}else{
								// 							$segment_condionres = true;
								// 							if($is_first_condition == 1){
								// 								$segment_condion_res = true;
								// 							}
								// 						}
								// 					}elseif($condion[1] == "Is empty"){
								// 						if(getCoulmValue($booking_data_row,$condion[0])){
								// 							$segment_condionres = false;
								// 						}else{
								// 							$segment_condionres = true;
								// 							if($is_first_condition == 1){
								// 								$segment_condion_res = true;
								// 							}
								// 						}
								// 					}elseif($condion[1] == "Is not empty"){
								// 						if(getCoulmValue($booking_data_row,$condion[0])){
								// 							$segment_condionres = true;
								// 						}else{
								// 							$segment_condionres = false;
								// 							if($is_first_condition == 1){
								// 								$segment_condion_res = false;
								// 							}
								// 						}
								// 					}
								// 					if($is_first_condition == 1){
								// 						$is_first_condition = 0;
								// 					}
								// 					if($condition_condion == "OR"){
								// 						if($segment_condionres || $segment_condion_res){
								// 							$segment_condion_res = true;
								// 						}else{
								// 							$segment_condion_res = false;
								// 						}
								// 					}elseif($condition_condion == "AND"){
								// 						if($segment_condionres && $segment_condion_res){
								// 							$segment_condion_res = true;
								// 						}else{
								// 							$segment_condion_res = false;
								// 						}
								// 					}
								// 				}
								// 				// echo('<pre>');
								// 				// print_r($conditions);
								// 				// die;
								// 				if($is_first_segment == 1){
								// 					$branch_segment_res = $segment_condion_res;
								// 					$is_first_segment = 0;
								// 				}
								// 				// else{
								// 				// 	echo('<pre>');
								// 				// 	print_r($conditions);
								// 				// 	die;
								// 				// }
								// 				if($condition_segment == "OR"){
								// 					if($branch_segment_res || $segment_condion_res){
								// 						$branch_segment_res = true;
								// 					}else{
								// 						$branch_segment_res = false;
								// 					}
								// 				}elseif($condition_segment == "AND"){
								// 					if($branch_segment_res && $segment_condion_res){
								// 						$branch_segment_res = true;
								// 					}else{
								// 						$branch_segment_res = false;
								// 					}
								// 				}
								// 			}
								// 		}
								// 		// echo('<pre>');
								// 		// print_r($branch_segment_res);
								// 		// die;
								// 		if($branch_segment_res){
								// 			$is_have_branch = true;
								// 			// $child_node_actions = ishave_parent_acction($acion->formdt->parent_id,'branch_'.$key);
								// 			foreach ($actions as $x) {
								// 				if($x){
								// 					// echo('<pre>');
								// 					// print_r($x);
								// 					// die;
								// 					if('branch_'.$key == $x->branch_id){
								// 						switch($x->modal_id){
								// 							// case 'emailactionModal':{
								// 							// 	// echo('<pre>');
								// 							// 	// print_r($x->formdt);
								// 							// 	// die;
								// 							// 	$fromname = $x->formdt->fromname;
								// 							// 	$fromemail = $x->formdt->fromemail;
								// 							// 	$email_subject = $x->formdt->email_subject;
								// 							// 	$email_msg = $x->formdt->email_msg;
								// 							// 	$toemail = $booking_data_row['email'];
								// 							// 	sendPHPMailerEmail($email_subject,$toemail,$fromemail,$email_msg,$fromname);
								// 							// }break;
								// 							case 'smsactionModal':{
								// 								$sms_msg = $x->formdt->sms_msg;
								// 								$to_number = $contact_number;
								// 								$twilioNumbers = getRandomTwilioNumbers($userID);
								// 								if($twilioNumbers){
								// 									$numberkey     = array_rand($twilioNumbers,1);
								// 									$from_number = removeCountryCode($twilioNumbers[$numberkey]);
								// 									sendMessage($from_number, $to_number, $sms_msg);
								// 								}
								// 							}break;
								// 							case 'callactionModal':{
								// 								$call_msg = $x->formdt->call_msg;
								// 								$to_number = $contact_number;
								// 								$twilioNumbers = getRandomTwilioNumbers($userID);
								// 								if($twilioNumbers){
								// 									$numberkey     = array_rand($twilioNumbers,1);
								// 									$from_number = removeCountryCode($twilioNumbers[$numberkey]);
								// 									Makecall($to_number, $from_number, $call_msg);
								// 								}
								// 							}break;
								// 						}
								// 					}
								// 				}
								// 			}
								// 		}
								// 	}
								// 	if(!$is_have_branch){
								// 		foreach ($actions as $x) {
								// 			if($x){
								// 				if('elsebranch_'.$key == $x->branch_id){
								// 					switch($x->modal_id){
								// 						// case 'emailactionModal':{
								// 						// 	// echo('<pre>');
								// 						// 	// print_r($x->formdt);
								// 						// 	// die;
								// 						// 	$fromname = $x->formdt->fromname;
								// 						// 	$fromemail = $x->formdt->fromemail;
								// 						// 	$email_subject = $x->formdt->email_subject;
								// 						// 	$email_msg = $x->formdt->email_msg;
								// 						// 	$toemail = $booking_data_row['email'];
								// 						// 	sendPHPMailerEmail($email_subject,$toemail,$fromemail,$email_msg,$fromname);
								// 						// }break;
								// 						case 'smsactionModal':{
								// 							$sms_msg = $x->formdt->sms_msg;
								// 							$to_number = $contact_number;
								// 							$twilioNumbers = getRandomTwilioNumbers($userID);
								// 							if($twilioNumbers){
								// 								$numberkey     = array_rand($twilioNumbers,1);
								// 								$from_number = removeCountryCode($twilioNumbers[$numberkey]);
								// 								sendMessage($from_number, $to_number, $sms_msg);
								// 							}
								// 						}break;
								// 						case 'callactionModal':{
								// 							$call_msg = $x->formdt->call_msg;
								// 							$to_number = $contact_number;
								// 							$twilioNumbers = getRandomTwilioNumbers($userID);
								// 							if($twilioNumbers){
								// 								$numberkey     = array_rand($twilioNumbers,1);
								// 								$from_number = removeCountryCode($twilioNumbers[$numberkey]);
								// 								Makecall($to_number, $from_number, $call_msg);
								// 							}
								// 						}break;
								// 					}
								// 				}
								// 			}
								// 		}
								// 	}
								// 	break;
								// }else{
									if(-1 == $acion->branch_id){
										switch($acion->modal_id){
											// case 'emailactionModal':{
											// 	// echo('<pre>');
											// 	// print_r($acion->formdt);
											// 	// die;
											// 	$fromname = $acion->formdt->fromname;
											// 	$fromemail = $acion->formdt->fromemail;
											// 	$email_subject = $acion->formdt->email_subject;
											// 	$email_msg = $acion->formdt->email_msg;
											// 	$toemail = $booking_data_row['email'];
											// 	sendPHPMailerEmail($email_subject,$toemail,$fromemail,$email_msg,$fromname);
											// }break;
											case 'smsactionModal':{
												$sms_msg = $acion->formdt->sms_msg;
												$to_number = $contact_number;
												$twilioNumbers = getRandomTwilioNumbers($userID);
												if($twilioNumbers){
													$numberkey     = array_rand($twilioNumbers,1);
													$from_number = removeCountryCode($twilioNumbers[$numberkey]);
													sendMessage($from_number, $to_number, $sms_msg);
												}
											}break;
											case 'callactionModal':{
												$call_msg = $acion->formdt->call_msg;
												$to_number = $contact_number;
												$twilioNumbers = getRandomTwilioNumbers($userID);
												if($twilioNumbers){
													$numberkey     = array_rand($twilioNumbers,1);
													$from_number = removeCountryCode($twilioNumbers[$numberkey]);
													Makecall($to_number, $from_number, $call_msg);
												}
											}break;
										}
									}
								// }
							}
						}
					}
				}
			// }
		// }
		die('404');
	}
?>