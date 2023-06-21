<?php
	include_once("header.php");
	$flowType = $_REQUEST['flowType'];
?>
<style>
	.center-block {
		display: block;
		margin-right: auto;
		margin-left: auto;
	}
	.flowBtn{
		margin-bottom: 0px !important
	}
	.showTriggerStyle{
		border-radius: 10px;
		background: #F5DEB3;
		color: black;
		padding: 10px 0px;
		margin: 10px 0px;
	}
</style>
	<div class="py-2">
		<h3>Create Workflow</h3>
	</div>

	<div class="card" style="padding: 15px;">
		<div class="row">
			<div class="col-md-4"></div>
			<div class="col-md-4 text-center showTriggers">
				<button class="btn btn-pill btn-outline-primary btn-lg" data-bs-toggle="modal" data-bs-target="#triggers" type="button" data-backdrop="static" data-keyboard="false">Add new workflow trigger</button>
				
				<div class="row">
					<div class="col-xs-12 text-center">
						<p class="btn flowBtn"><span class="fa fa-arrow-down"></span></p>
					</div>
				</div>
				
				<button class="btn btn-pill btn-outline-primary" data-bs-toggle="modal" data-bs-target="#actions" type="button" data-backdrop="static" data-keyboard="false">Add your first action</button>
			</div>
			<div class="col-md-4"></div>
		</div>
		<!--
		<div class="row">
			<div class="col-xs-12 text-center">
				<p class="center-block">
					<button type="button" class="btn btn-danger btn-lg">Demo</button>
				</p>
				<p class="btn"><span class="fa fa-arrow-down"></span></p>
				<p class="lead text-center bg-info btn text-info center-block" data-toggle="modal" data-target="#myModal">Add your first action</p>
				<div class="row">
					<div class="col-xs-6 text-center">
						<p class="btn"><span class="fa fa-arrow-down"></span></p>
					</div>
					<div class="col-xs-6 text-center">
						<p class="btn"><span class="fa fa-arrow-down"></span></p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6">
						<p class="center-block">
							<button type="button" class="btn btn-success btn-lg" >Demo</button>
						</p>
						<p class="bg-success text-success btn text-wrap">hhjkjuhjhkjuhjk</p>
					</div>
					<div class="col-xs-6 text-center">
						<p class="center-block">
							<button type="button" class="btn btn-danger btn-lg">Demo</button>
						</p>
						<p class="btn bg-danger text-danger text-wrap">hjklhkljhjlkjkjklj</p>
					</div>
				</div>
			</div>
		</div>
		-->
	</div>

	
	<div class="modal fade" id="triggers" tabindex="-1" role="dialog" aria-labelledby="triggers" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
			<div class="modal-content">
				<form action="server.php" method="post" enctype="multipart/form-data">
					<div class="modal-header">
						<h2 class="h4 modal-title">
							Workflow Trigger
							<p>Adds a workflow trigger, and on execution, the contact gets added to the workflow.</p>
						</h2>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body triggerContainer">
						<ul class="list-group">
							<h6>Appointments</h6>
							<div class="list-group-item">
								<span class="d-inline-flex align-items-center justify-content-center text-white rounded-circle m-1 me-2" style="background-color: #0082ca; width: 40px; height: 40px;">
								<i class="fa fa-calendar"></i>
								</span>
								<a href="javascript:void(0)" onClick="addTrigger(this,'appointment_status')">Appointment Status</a>
							</div>
							<div class="list-group-item">
								<div class="d-inline-flex align-items-center justify-content-center text-white rounded-circle m-1 me-2" style="background-color: #00b386; width: 40px; height: 40px;">
								<i class="fa fa-calendar-check-o"></i>
								</div>
								Customer Booked Appointment
							</div>
							
							<h6>Contacts</h6>
							<div class="list-group-item">
								<div class="d-inline-flex align-items-center justify-content-center text-white rounded-circle m-1 me-2" style="background-color: #0082ca; width: 40px; height: 40px;">
								<i class="fa fa-calendar-o"></i>
								</div>
								Birthday Reminder
							</div>
							<div class="list-group-item">
								<div class="d-inline-flex align-items-center justify-content-center text-white rounded-circle m-1 me-2" style="background-color: #ff4500; width: 40px; height: 40px;">
								<i class="fa fa-user"></i>
								</div>
								Contact Changed
							</div>
							<div class="list-group-item">
								<div class="d-inline-flex align-items-center justify-content-center text-white rounded-circle m-1 me-2" style="background-color: #00b386; width: 40px; height: 40px;">
								<i class="fa fa-user-plus"></i>
								</div>
								Contact Created
							</div>
							<div class="list-group-item">
								<div class="d-inline-flex align-items-center justify-content-center text-white rounded-circle m-1 me-2" style="background-color: #0082ca; width: 40px; height: 40px;">
								<i class="fa fa-times-circle"></i>
								</div>
								Contact DND
							</div>
							<div class="list-group-item">
								<div class="d-inline-flex align-items-center justify-content-center text-white rounded-circle m-1 me-2" style="background-color: #ff4500; width: 40px; height: 40px;">
								<i class="fa fa-tag"></i>
								</div>
								Contact Tag
							</div>
							<div class="list-group-item">
								<div class="d-inline-flex align-items-center justify-content-center text-white rounded-circle m-1 me-2" style="background-color: #00b386; width: 40px; height: 40px;">
								<i class="fa fa-calendar-o"></i>
								</div>
								Custom Date Reminder
							</div>
							<div class="list-group-item">
								<div class="d-inline-flex align-items-center justify-content-center text-white rounded-circle m-1 me-2" style="background-color: #0082ca; width: 40px; height: 40px;">
								<i class="fa fa-calendar-o"></i>
								</div>
								Note Added
							</div>
						</ul>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" onClick="showTriggers()">Save Trigger</button>
						<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Cancel</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="actions" tabindex="-1" role="dialog" aria-labelledby="actions" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
			<div class="modal-content">
				<form action="server.php" method="post" enctype="multipart/form-data">
					<div class="modal-header">
						<h2 class="h4 modal-title">
							Actions
							<p>Pick an action for this step.</p>
						</h2>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<ul class="list-group">
							<h6>External Communications</h6>
							<div class="list-group-item">
								<span class="d-inline-flex align-items-center justify-content-center text-white rounded-circle m-1 me-2" style="background-color: #0082ca; width: 40px; height: 40px;">
									<i class="fa fa-envelope"></i>
								</span>
								<a href="javascript:void(0)" onClick="addAction(this,'send_email')">Send Email</a>
							</div>
							<div class="list-group-item">
								<span class="d-inline-flex align-items-center justify-content-center text-white rounded-circle m-1 me-2" style="background-color: #ff4500; width: 40px; height: 40px;">
									<i class="fa fa-comment-o"></i>
								</span>
								<a href="javascript:void(0)" onClick="addAction(this,'send_sms')">Send SMS</a>
							</div>
							<div class="list-group-item">
								<span class="d-inline-flex align-items-center justify-content-center text-white rounded-circle m-1 me-2" style="background-color: #00b386; width: 40px; height: 40px;">
									<i class="fa fa-phone"></i>
								</span>
								<a href="javascript:void(0)" onClick="addAction(this,'make_call')">Call</a>
							</div>
						</ul>
					</div>
					<!--
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" onClick="alert('under development')">Save Trigger</button>
						<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Cancel</button>
					</div>
					-->
				</form>
			</div>
		</div>
	</div>
	<input type="hidden" id="hidden_trigger" value="">
<?php include_once("footer.php");?>
<script>
	function showTriggers(){
		var triggerName = $("#hidden_trigger").val();
		var html = '<div class="showTriggerStyle">'+triggerName+'</div>';
		$("#triggers").modal("hide");
		$(".showTriggers").prepend(html);
	}
	function addAction(obj,optionType){
		alert("will add "+optionType+" option in the flow.");
		if(optionType == 'appointment_status'){
			//var aptStatus = getAppointmentStatus();
		}
		//$(".triggerContainer").html(aptStatus);
	}
	function addTrigger(obj,triggerType){
		if(triggerType == 'appointment_status'){
			var aptStatus = getAppointmentStatus();
		}
		$(".triggerContainer").html(aptStatus);
	}
	
	function getAppointmentStatus(){
		var html = `<div class="form-group">
						<label>Choose a workflow trigger</label>
						<select name="workflow_trigger" class="form-control" onchange="addWorkflowTriggerOptions(this)">
							<optgroup label="Appointments">
								<option value="apt_status">Appointments Status</option>	
								<option value="customer_booked_apt">Custom booked appointment</option>
							</optgroup>
							<optgroup label="Contacts">
								<option value="bd_reminder">Birthday Reminder</option>	
								<option value="contact_changed">Contact Changed</option>
								<option value="contact_created">Contact Created</option>
							</optgroup>
						</select>
					</div>
					<div class="triggerOptionsContainer"></div>`;
		return html;
	}
	function addWorkflowTriggerOptions(obj){
		var triggerOption = $(obj).val();
		if(triggerOption == 'apt_status'){
			var html = `<div class="form-group"><label>Workflow trigger name</label><input type="text" name="workflow_trigger_name" id="workflow_trigger_name" class="form-control" value="Appointment Status"></div>`;
			$("#hidden_trigger").val('Appointment Status');
		}
		else if(triggerOption == 'customer_booked_apt'){
			var html = `<div class="form-group"><label>Workflow trigger name</label><input type="text" name="workflow_trigger_name" id="workflow_trigger_name" class="form-control" value="Customer Booked Appointment"></div>`;
			$("#hidden_trigger").val('Customer booked appointment');
		}
		else if(triggerOption == 'bd_reminder'){
			var html = `<div class="form-group"><label>Workflow trigger name</label><input type="text" name="workflow_trigger_name" id="workflow_trigger_name" class="form-control" value="Birthday Reminder"></div>`;
			$("#hidden_trigger").val('Birthday Reminder');
		}
		else if(triggerOption == 'contact_changed'){
			var html = `<div class="form-group"><label>Workflow trigger name</label><input type="text" name="workflow_trigger_name" id="workflow_trigger_name" class="form-control" value="Contact Changed"></div>`;
			$("#hidden_trigger").val('Contact Changed');
		}
		else if(triggerOption == 'contact_created'){
			var html = `<div class="form-group"><label>Workflow trigger name</label><input type="text" name="workflow_trigger_name" id="workflow_trigger_name" class="form-control" value="Contact Created"></div>`;
			$("#hidden_trigger").val('Contact Created');
		}
		$(".triggerOptionsContainer").html(html);
	}
	$(document).ready(function(){
		$("#triggers").on("hidden.bs.modal", function () {
			var flowTriggers = `<ul class="list-group">
							<h6>Appointments</h6>
							<div class="list-group-item">
								<span class="d-inline-flex align-items-center justify-content-center text-white rounded-circle m-1 me-2" style="background-color: #0082ca; width: 40px; height: 40px;">
								<i class="fa fa-calendar"></i>
								</span>
								<a href="javascript:void(0)" onClick="addTrigger(this,'appointment_status')">Appointment Status</a>
							</div>
							<div class="list-group-item">
								<div class="d-inline-flex align-items-center justify-content-center text-white rounded-circle m-1 me-2" style="background-color: #00b386; width: 40px; height: 40px;">
								<i class="fa fa-calendar-check-o"></i>
								</div>
								Customer Booked Appointment
							</div>
							
							<h6>Contacts</h6>
							<div class="list-group-item">
								<div class="d-inline-flex align-items-center justify-content-center text-white rounded-circle m-1 me-2" style="background-color: #0082ca; width: 40px; height: 40px;">
								<i class="fa fa-calendar-o"></i>
								</div>
								Birthday Reminder
							</div>
							<div class="list-group-item">
								<div class="d-inline-flex align-items-center justify-content-center text-white rounded-circle m-1 me-2" style="background-color: #ff4500; width: 40px; height: 40px;">
								<i class="fa fa-user"></i>
								</div>
								Contact Changed
							</div>
							<div class="list-group-item">
								<div class="d-inline-flex align-items-center justify-content-center text-white rounded-circle m-1 me-2" style="background-color: #00b386; width: 40px; height: 40px;">
								<i class="fa fa-user-plus"></i>
								</div>
								Contact Created
							</div>
							<div class="list-group-item">
								<div class="d-inline-flex align-items-center justify-content-center text-white rounded-circle m-1 me-2" style="background-color: #0082ca; width: 40px; height: 40px;">
								<i class="fa fa-times-circle"></i>
								</div>
								Contact DND
							</div>
							<div class="list-group-item">
								<div class="d-inline-flex align-items-center justify-content-center text-white rounded-circle m-1 me-2" style="background-color: #ff4500; width: 40px; height: 40px;">
								<i class="fa fa-tag"></i>
								</div>
								Contact Tag
							</div>
							<div class="list-group-item">
								<div class="d-inline-flex align-items-center justify-content-center text-white rounded-circle m-1 me-2" style="background-color: #00b386; width: 40px; height: 40px;">
								<i class="fa fa-calendar-o"></i>
								</div>
								Custom Date Reminder
							</div>
							<div class="list-group-item">
								<div class="d-inline-flex align-items-center justify-content-center text-white rounded-circle m-1 me-2" style="background-color: #0082ca; width: 40px; height: 40px;">
								<i class="fa fa-calendar-o"></i>
								</div>
								Note Added
							</div>
						</ul>`;
			$(".triggerContainer").html(flowTriggers);
		});
	});
</script>