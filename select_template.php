<?php
	include_once("header.php");
	
	$user_id = $_SESSION['user_id'];
	//$role_id = $_SESSION['role_id'];
	$user_type = $_SESSION['user_type'];
	
	
	$user_qry = mysqli_query($link,"select role_id from users where id='$user_id'");
	$user_data = mysqli_fetch_assoc($user_qry);
	$role_id = $user_data['role_id'];
	
	
	$role_qry = mysqli_query($link,"select * from role_master where role_id='$role_id'");
	$role_data = mysqli_fetch_assoc($role_qry);
	
	$permission_qry = mysqli_query($link,"select * from permission where inner_user_id='$user_id' AND module_name='workflows' AND insert_permission='1'");
	
	if(mysqli_num_rows($permission_qry)==1 || $role_data['role']=="Full Admin" || $user_type==1){
?>
<style>
	.workflowTypes{
		padding: 15px;
		border: 1px solid #eee;
		border-radius: 10px;
		margin-bottom: 15px
	}
	.workflowTypes input[type="radio"]{
		opacity: 0;
	}
</style>

	<div class="py-2">
		<h3>Select a template
			<a href="create-workflow.php" class="btn btn-primary" onClick="goToWorkflow()" style="float: right">Create new workflow</a>
		</h3>
	</div>

	<div class="card" style="padding: 15px">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<select name="filter" class="form-control">
						<option value="all">All</option>
					</select>
				</div>
			</div>

			<div class="col-md-9">
				<div class="workflowTypes">
					<input type="radio" name="flow_type" value="import_from_campaign">
					<p>
						<h6>Import from a campaign</h6>
						Import all the steps from an already existing campaign.<br>
						Pick a campaign to import from<br>
					</p>
					<div class="form-group">
						<select class="form-control">
							<option value="">Pick a campaign</option>
						</select>
					</div>
				</div>
				<div class="workflowTypes">
					<input type="radio" name="flow_type" value="start_from_scratch">
					<p>
						<h6>Start from scratch</h6>
						Start from scratch with a fresh, clean slate for your automation and add your own triggers and actions.<br>
					</p>
				</div>
				<div class="workflowTypes">
					<input type="radio" name="flow_type" value="appointment_booking">
					<p>
						<h6>Recipe - Appointment Booking</h6>
						Detect intent on customer reply to send them booking link or create a manual SMS to help them make a decision
					</p>
				</div>
			</div>
		</div>	
	</div>
<?php
	}else{
	    
	    echo "<h3 style='height: 250px;' class='mt-4'>Access Denied</h3>";
	}
?>

<?php include_once("footer.php");?>
<script>
	function goToWorkflow(){
		var flowType = $('input[name=flow_type]:checked').val();
		window.location = 'create_flow.php?flowType='+flowType;
	}
	$(document).ready(function(){
		$(".workflowTypes").on("click",function(){
			$(".workflowTypes").css("background","");
			$("input[type='radio']").prop("checked",false);
			
			$(this).css("background","#f2f4f6");
			$(this).find("input[type='radio']").prop("checked",true);
		});
	});
</script>