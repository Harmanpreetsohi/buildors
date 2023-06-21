<?php 
	include_once("header.php");
	//$callSid = $_REQUEST['sid'];
	$callID  = $_REQUEST['cid'];
	$nextButton = 'none';
	if($callID!=''){
		$sqlp = "select id,call_sid,number from twillio_call_log where id='".$callID."'";
		$resp = mysqli_query($link,$sqlp);
		$rowp = mysqli_fetch_assoc($resp);
		$callSid = $rowp['call_sid'];
		$fromNumber = $rowp['number'];
		//$contactInfo = getCustomerInfoByNumber();
		
		$sql = "select * from contacts where phone='".removeCountryCode($fromNumber)."' or phone='".str_replace('+','',$fromNumber)."'";
		$res = mysqli_query($link,$sql);
		$contactInfo = mysqli_fetch_assoc($res);
		
		$info = '';
		if(trim($contactInfo['first_name'])!=''){
			$info .= $contactInfo['first_name'];
		}
		if(trim($contactInfo['last_name'])!=''){
			$info .= ' '.$contactInfo['last_name'];
		}
		if($info!='')
			$info .= ' - '.$fromNumber;
		else
			$info .= $fromNumber;
		
		// finding next call id
		$sqlc = "select id,call_sid,number from twillio_call_log where id > '".$callID."' order by id asc limit 1";
		$resc = mysqli_query($link,$sqlc);
		if(mysqli_num_rows($resc)){
			$rowc = mysqli_fetch_assoc($resc);
			$nextCallID = $rowc['id'];
			$callSid 	= $rowc['call_sid'];
			$nextButton = 'block';
		}
		// ends
		
		// finding previous call id
		$sqlc = "select id,call_sid from twillio_call_log where id < '".$callID."' order by id desc limit 1";
		$resc = mysqli_query($link,$sqlc);
		if(mysqli_num_rows($resc)){
			$rowc = mysqli_fetch_assoc($resc);
			$backCallID = $rowc['id'];
		}
		// ends
	}else{
		die("no call found.");
	}

?>
<style>
	table > thead > tr > th{
		text-align: center
	}
	table > tbody > tr > td{
		text-align: center
	}
</style>
<script>
	var customers = [];
</script>
	<div class="py-2">
		<h3>
			Call Tracking <?php echo $info?>
			<a href="track_call.php?cid=<?php echo $nextCallID?>" class="btn btn-primary" style="float: right;display: <?php echo $nextButton?>">Next</a>&nbsp;&nbsp;
			<a href="track_call.php?cid=<?php echo $backCallID?>" class="btn btn-primary" style="float: right;margin-right: 8px">Back</a>
		</h3>
	</div>
	<?php if(isset($_SESSION['message']))echo $_SESSION['message'];unset($_SESSION['message']); ?>
	<div class="card">
		<div class="table-responsive py-4">
			<div class="">
				<div class="col-md-12">
				<?php
					$sql = "select * from call_tracking where call_sid='".$callSid."' order by id asc";
					$res = mysqli_query($link,$sql);
					if(mysqli_num_rows($res)){
						while($row = mysqli_fetch_assoc($res)){
				?>
							<div class="col-md-11" style="margin: 0 auto; background: #F3F3F3; margin-bottom: 10px; border-radius: 7px; padding: 10px;text-align: center">
								<?php 
									echo $row['action'].'<br>';
									if(trim($row['recording_url'])!=''){
								?>
										<audio controls>
											<source src="<?php echo $row['recording_url']?>">
											Your browser does not support the audio element.
										</audio>
								<?php
									}
								?>
							</div>
				<?php
						}
					}else{
						die("<div class='col-md-12'>No call tracking found.</div>");
					}
				?>
				</div>
			</div>
		</div>
	</div>
<?php include_once("footer.php"); ?>