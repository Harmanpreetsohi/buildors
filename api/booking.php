<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

	include_once("../database.php");
	include_once("../functions.php");

	$number = $_POST['num'];
	$email  = $_POST['email'];
	$firstName = $_POST['firstName'];
	$addressLine = $_POST['addressLine'];
	$addressLine2 = $_POST['addressLine2'];
	$city = $_POST['city'];
	$phone = $_POST['phone'];
	$service = $_POST['service'];
	$time = $_POST['time'];
	$user_id = isset($_GET['user']) ? $_GET['user'] : 1;

	
	$sql = "select id from contacts where phone='".$phone."' and user_id='".$user_id."'";
	$res = mysqli_query($link,$sql);
	if(mysqli_num_rows($res)){  // contact exists
		$row = mysqli_fetch_assoc($res);
		$contactID = $row["id"];
		
		$up = "update contacts set 
					first_name='".$firstName."',
					email='".$email."',
					street_address='".$addressLine.' '.$addressLine2."',
					city='".$city."',
					service='".$service."',
					booking_time='".$time."',
					number='".$number."',
					user_id='".$user_id."'
				where
					id='".$contactID."'";
		mysqli_query($link,$up);
	}
	else{ // new contact
		$sql = "insert into contacts
					(
						first_name,
						email,
						street_address,
						city,
						service,
						booking_time,
						number,
						user_id,
						phone
					)
				values
					(
						'".$firstName."',
						'".$email."',
						'".$addressLine.' '.$addressLine2."',
						'".$city."',
						'".$service."',
						'".$time."',
						'".$number."',
						'".$user_id."',
						'".$phone."'
					)";
		mysqli_query($link,$sql);
		$contactID = mysqli_insert_id($link);
	}

	$pipeline_list = mysqli_query($link,"select * from pipeline_list where user_id=".$user_id." order by id asc");
	$pipeline_id = 0;
	$status = 'booked';
	if(mysqli_num_rows($pipeline_list)>0){
		$row = mysqli_fetch_assoc($pipeline_list);
		$pipeline_id = $row['id'];
		$stagess = json_decode($row['stages']);
		if(isset($stagess[0]) && $stagess[0] != ''){
			$status = $stagess[0];
			
		}else{
			$sql = "update pipeline_list set
						stages='" . json_encode(["booked"]) . "'
					where
						id='" . $pipeline_id . "'";

			$res = mysqli_query($link, $sql);
		}
	}
	else{
		$sql = "insert into pipeline_list
					(
						title,
						stages,
						user_id
					)
				values
					(
						'default',
						'".json_encode(["booked"])."',
						'".$user_id."'
					)";
		$res = mysqli_query($link,$sql);
		$pipeline_id = mysqli_insert_id($link);
	}

	$up = "update contacts set pipeline_id='".$pipeline_id."', pipeline_stage='".strtolower($status)."' where id='".$contactID."' limit 1";
	mysqli_query($link,$up);
	//logErrors($up.' '.mysqli_error($link));
	/*
	die();
	$res = mysqli_query($link,"SELECT * FROM `bookings` WHERE `number`='".$number."' AND user_id=".$user_id);

	if($number != '' && mysqli_num_rows($res) == 0){
		
		
		
		$pipeline_list = mysqli_query($link,"select * from pipeline_list where user_id=".$user_id." order by id asc");
		$pipeline_id = 0;
		$status = 'booked';
		if(mysqli_num_rows($pipeline_list)>0){
			$row = mysqli_fetch_assoc($pipeline_list);
			$pipeline_id = $row['id'];
			$stagess = json_decode($row[ 'stages' ]);
			if(isset($stagess[0]) && $stagess[0] != ''){
				$status = $stagess[0];
			}else{
				$sql = "update pipeline_list set
							stages='" . json_encode(["booked"]) . "'
						where
							id='" . $pipeline_id . "'";

				$res = mysqli_query($link, $sql);
			}
		}
		else{
			$sql = "insert into pipeline_list
						(
							title,
							stages,
							user_id
						)
					values
						(
							'default',
							'" . json_encode(["booked"]) . "',
							'" . $user_id . "'
						)";
			$res = mysqli_query( $link, $sql );
			$pipeline_id = mysqli_insert_id($link);
		}
		
		
		
		$sql = "INSERT INTO `bookings`(`number`, `email`, `firstName`, `addressLine`, `addressLine2`, `city`, `phone`, `service`, `time`, `user_id`, `pipeline_id`, `status`) VALUES ('$number','$email','$firstName','$addressLine','$addressLine2','$city','$phone','$service','$time', $user_id, $pipeline_id, '$status')";

		mysqli_query($link,$sql);
		$bookingID = mysqli_insert_id($link);
		runWorkFlow($bookingID);
	}
	*/
	print_r($_POST);