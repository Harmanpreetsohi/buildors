<?php
$customers = [];
$cus = mysqli_query( $link, "SELECT * FROM `contacts`" );
while ( $row = mysqli_fetch_assoc( $cus ) ) {
	$customers[] = $row;
}
?>
<!--Table-->
<table class="table table-hover table-fixed" id="ivr_log">
	<thead>
		<tr>
			<th>#</th>
			<th>Action</th>
			<th>From</th>
			<th>Name</th>
			<th>Start Time</th>
			<th>Duration</th>
			<th>Pipe line</th>
			<th>Stage</th>
			<th>Lead value</th>
			<th>Notes</th>
		</tr>
	</thead>
	<tbody>	
<?php 
		$sql = "select * from twillio_call_log where user_id='".$_SESSION['company_id']."' order by id desc";
		$res = mysqli_query($link,$sql);
		$x=0;
		$isContact = false;
		if(mysqli_num_rows($res)){
			while($row = mysqli_fetch_assoc($res)){
				$number = str_replace('+','',$row['number']);
				$sqlu = "select business_name as user from users where id='".$row['user_id']."'";
				$resu = mysqli_query($link,$sqlu);
				$rowu = mysqli_fetch_assoc($resu);
				
				$sqlc = "select * from contacts where phone='".$number."'";
				$resc = mysqli_query($link,$sqlc);
				if(mysqli_num_rows($resc)){
					$isContact = true;
					$rowc = mysqli_fetch_assoc($resc);
				}
	?>
				<tr>
					<td scope="row">
						<?php echo ++$x ?>
					</td>
					<td>
						<i class="fa fa-comment" style="color: darkgoldenrod; cursor: pointer; margin-right: 5px;" onClick="getRecipientNumber('<?php echo $row['number']?>');"></i>
						
						<i class="icon icon-sm text-gray-900 fa fa-phone" style="margin-right: 5px;cursor: pointer; font-weight: bold" title="Call back!" onClick="callBack(this,'<?php echo $row['number']?>')"></i>
						<a href="track_call.php?cid=<?php echo $row['id']?>"><i class="fa fa-th-list" style="color: green;margin-right: 5px;"></i></a>
						
						<a data-toggle="tooltip" title="Save Contact!" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addContact" class="addContact" onclick="$('.contactInfo input[name=contact_phone]').val('<?= str_replace('+', '', $row['number']) ?>'); getContactInfo('<?= str_replace('+','',$row['number'])?>')"><i class="fa fa-users " style="color: darkorange;"></i></a>

						<?php
						if($isContact){
						?>		
							<a title="Add to Pipeline!" href="javascript:void(0)" onClick="setToNumber('<?= str_replace('+', '', $row['number']) ?>')" data-bs-toggle="modal" data-bs-target="#addToPipeline"><i class="fa fa-download" style="color: lightgreen;"></i></a>
						<?php
							}
						?>
					</td>
					<td scope="row">
						<?php echo $row['number'];?>
					</td>
					<td>
						<?php 
							if($rowc['id']!=''){
								echo $rowc['first_name'].' '.$rowc['last_name'];
							}
						?>
					</td>
					<td>
						<?php echo date('M d h:i A',strtotime($row['created_at'])) ?>
					</td>
					<td>
						<?php 
							if($row['time']!=''){
								if($row['time'] > 59){
									echo round(($row['time']/60),1).'m';
								}else{
									echo $row['time'].'s';
								}
							}else{
								echo '0s';
							}
						?>
					</td>
					<td>
						<?php
							if($rowc['id']!=''){
								if($rowc['pipeline_id']!=''){
									$pipelineInfo = getPipeline($rowc['pipeline_id']);
									echo $pipelineInfo['title'];
								}
							}
						?>
					</td>
					<td>
						<?php
							if($rowc['pipeline_id']!=''){
								echo $rowc['pipeline_stage'];
							}
						?>
					</td>
					<td>
						<?php
							if($rowc['id']!=''){
								echo $rowc['lead_value'];
							}
						?>
					</td>
					<td>
						<?php
							if($rowc['id']!=''){
								echo getLatestNote($rowc['id']);
							}
						?>
					</td>
				</tr>	
<?php	
				$rowc['id'] = '';
				$rowc['pipeline_id'] = '';
			}
		}
		else{
			echo '<tr><td colspan="7">No call log found.</td></tr>';
		}
?>
	</tbody>
	<!--Table body-->
</table>
<!--Table-->
<style type="text/css">
	.col-md-9 {
		display: none !important;
	}
	
	.col-md-3 {
		width: 100%;
	}
	
	#checkScroll> input {
		display: none;
	}
</style>
<?php
$GLOBALS[ 'script' ] = '<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
  
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
<script type="text/javascript">
var table="";
	$(document).ready( function () {
    table = $("#ivr_log").DataTable({
		"pageLength": 100,
		lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"],
        ]
	  }
  );
} );
</script>';
?>

<div class="modal fade" id="sendSMSToContact" tabindex="-1" aria-labelledby="callBack_modal_label" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="">Send SMS to <span class="showToNumber"></span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form method="post" action="server.php?cmd=send_sms_to_call_log" enctype="multipart/form-data">
					<div class="form-group mt-2">
						<label>Recipient</label>
						<input type="text" name="recipient_number" id="recipient_number" class="form-control" readonly>
					</div>
					<div class="form-group mt-2">
						<label>Write message</label>
						<textarea type="text" name="recipient_message" class="form-control" required></textarea>
					</div>
					<div class="form-group mt-2">
						<label>Attachment <span style="font-size: 10px">(png, jpg, jpeg, bmp, gif)</sapn></label>
						<input type="file" name="recipient_media" class="form-control">
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-secondary">Send now</button>
						<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="callBack_modal" tabindex="-1" aria-labelledby="callBack_modal_label" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="">Callback</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label>Number to call</label>
					<input type="text" name="callback_number" id="callback_number" class="form-control" readonly>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" onClick="alert('under dev')">Call now</button>
					<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
				</div>
				<p>Under development</p>
			</div>
		</div>
	</div>
</div>
<style type="text/css">
	input[type="search"] {
		display: block !important;
	}
</style>
<script type="text/javascript">
	function getRecipientNumber(recipientNumber){
		$("#recipient_number").val(recipientNumber);
		$(".showToNumber").text(recipientNumber);
		$("#sendSMSToContact").modal("show");
	}
	function setToNumber(number){
		$("#to_number").val(number);
	}
	function getContactInfo(number){
		$('input[name=booking_id]').val(number);
		//$('#loading_data').removeClass('d-none');
		//$('#showforms').addClass('d-none');
		//$(".notesContainer").html('');
		jQuery.ajax({
			type: "post",
			dataType: "json",
			url: "server.php",
			data:{
				cmd: "get_contact_info",
				booking_id: number
			},
			success: function(response){
				//$('#loading_data').addClass('d-none');
				//$('#showforms').removeClass('d-none');
				//console.log(response);
				var contactInfo = response;
				if(contactInfo.message == 'success'){
					$('input[name=contact_name]').val(contactInfo.first_name+' '+contactInfo.last_name);
					$("#last_name").val(contactInfo.last_name);
					$('input[name=contact_email]').val(contactInfo.email);
					//$('input[name=contact_phone]').val(contactInfo.phone);
					$('input[name=contact_companyname]').val(contactInfo.company_name);
					$('input[name=opportunity_name]').val(contactInfo.opportunity_name);
					$('select[name=opportunity_pipeline]').val(contactInfo.pipeline_id);
					$('select[name=opportunity_stage]').val(contactInfo.pipeline_stage);
					$('input[name=opportunity_leadvalue]').val(contactInfo.lead_value);
					$('input[name=opportunity_source]').val(contactInfo.opportunity_source);
					$('#contact_id').val(contactInfo.id);
					$("#redirect_to").val("calllogs");
					$(".notesContainer").html(contactInfo.contact_notes);
					getStagess(contactInfo.pipeline_id, contactInfo.pipeline_stage);
				}else{
					$('input[name=contact_name]').val('');
					$("#last_name").val('');
					$('input[name=contact_email]').val('');
					//$('input[name=contact_phone]').val('');
					$('input[name=contact_companyname]').val('');
					$('input[name=opportunity_name]').val('');
					$('select[name=opportunity_pipeline]').val('');
					$('select[name=opportunity_stage]').val('');
					$('input[name=opportunity_leadvalue]').val('');
					$('input[name=opportunity_source]').val('');
					$('#contact_id').val('');
					$("#redirect_to").val('');
					$(".notesContainer").html('');
				}
			}
		})
	}
	function getStagess(pipelinss_id=0, status=''){
		// console.log(pipelinss_id);
		jQuery.ajax({
			type: "post",
			dataType: "json",
			url: "server.php",
			data: {
				cmd: "get_pipeline_stages",
				pipeline_id: pipelinss_id
			},
			success: function ( response ) {
				//console.log( response );
				if ( response ) {
					//console.log( status );
					$( '#opportunity_stage' ).html( response );
					$( 'select[name=opportunity_stage]' ).val( status );
				}
			}
		} )
	}
	function callBack( obj, customerNumber = '' ) {
		$( "#callback_number" ).val( '' );
		$( "#callBack_modal" ).modal( 'show' );
		if ( $.trim( customerNumber ) != '' ) {
			$( "#callback_number" ).val( customerNumber );
		}
	}
	function update_call_log_name_table( num, name ) {
		table.destroy();
		$( "#ivr_log tr" ).each( function ( a, b, c ) {
			if ( $( b ).find( 'th' ).eq( 1 ).text().includes( num ) ) {
				$( b ).find( 'th' ).eq( 2 ).text( name )
			}
		} )
		table = $( "#ivr_log" ).DataTable();
	}
</script>