<?php
	include_once("header.php");
	
	$user_id = $_SESSION['user_id'];
// 	$role_id = $_SESSION['role_id'];
	$user_type = $_SESSION['user_type'];
	
	$user_qry = mysqli_query($link,"select role_id from users where id='$user_id'");
	$user_data = mysqli_fetch_assoc($user_qry);
	$role_id = $user_data['role_id'];
	
	$role_qry = mysqli_query($link,"select * from role_master where role_id='$role_id'");
	$role_data = mysqli_fetch_assoc($role_qry);
	
	$permission_qry = mysqli_query($link,"select * from permission where inner_user_id='$user_id' AND module_name='bulksms' AND view_permission='1'");
	
	if(mysqli_num_rows($permission_qry)==1 || $role_data['role']=="Full Admin" || $user_type==1){
?>
<style>
	table > thead > tr > th{
		text-align: center
	}
	table > tbody > tr > td{
		text-align: center
	}
	#listTitle a:hover{
		text-decoration: underline;
	}
</style>
	<div class="py-2">
		<h3>
			SMS Broadcast
			<a href="create_list.php" class="btn btn-gray-800 d-inline-flex align-items-center me-2" style="float: right">Create New</a>
			<a href="server.php?cmd=download_sample_csv" class="btn btn-primary move-right" style="float: right;margin-right: 5px;">Sample CSV</a>
		</h3>
	</div>

	<?php 
		$sendable = true;
		
		if($_SESSION['message']!='')echo $_SESSION['message'];unset($_SESSION['message']); 
		if(count($twilioNumbers) < 1){
			echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> You have no twilio number to send message.</div>';
			$sendable = false;
		}
	?>

	<div class="card">
		<div class="table-responsive py-4">
			<table class="table table-flush" id="datatable">
				<thead class="thead-light">
					<tr>
						<th>Sr#</th>
						<th>Title</th>
						<th>Recipients</th>
						<th>Send</th>
						<th>Manage</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$sql = "select * from lists where user_id='".$_SESSION['company_id']."' order by id desc";
					$res = mysqli_query($link,$sql);
					if(mysqli_num_rows($res)){
						$index = 1;
						while($row = mysqli_fetch_assoc($res)){
				?>
							<tr>
								<td><?php echo $index++;?></td>
								<td id="listTitle"><a href="view_list.php?lid=<?php echo $row['id']?>" title="View details"><?php echo $row['title']?></a></td>
								<td>
									<?php 
										getTotalListCustomers($row['id']);
									?>
								</td>
								<td>
									<?php if($sendable){?>
									<button type="button" class="btn btn-block btn-primary mb-3 btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default" onClick="getListInfo('<?php echo $row['id']?>','<?php echo $row['title']?>')">Send</button>
									<?php } ?>
								</td>
								
								<td>
								   <?php
	
                                    	$permission_qry = mysqli_query($link,"select * from permission where inner_user_id='$user_id' AND module_name='bulksms' AND delete_permission='1'");
                                    	
                                    	if(mysqli_num_rows($permission_qry)==1 || $role_data['role']=="Full Admin" || $user_type==1){
                                    ?> 
								    <i class="fa fa-trash" style="color: red; cursor: pointer" onClick="deleteList('<?php echo $row['id']?>')"></i>
								    <?php } ?>
								</td>
							</tr>
				<?php
						}
					}else{
				?>	
							<tr>
								<td colspan="4">No broadcast sent yet.</td>
							</tr>
				<?php
					}
				?>	
				</tbody>
			</table>
		</div>		
	</div>
	
	<div class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<form action="server.php" method="post" enctype="multipart/form-data">
					<div class="modal-header">
						<h2 class="h6 modal-title" id="showListtitle"></h2>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<label>Message </label>
						<label>
							<span style="margin-left: 10px;font-size: 12px;">%name% = Name</span>
							<span style="margin-left: 10px;font-size: 12px;">%address% = Address</span>
							<!--<span style="margin-left: 10px;font-size: 12px;">%project_manager% = Project manager</span>-->
							<span style="margin-left: 10px;font-size: 12px;">%email% = Email</span>
						</label>
						<textarea name="bulk_sms" class="form-control" style="height: 150px;" placeholder="Write your message here"></textarea><br>
						<label>Media</label>
						<input type="file" name="broadcast_media">
					</div>
					<div class="modal-footer">
						<input type="hidden" name="cmd" value="send_broadcast">
						<input type="hidden" name="list_id" id="list_id" value="">
						<button type="submit" class="btn btn-secondary">Send now</button>
						<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
					</div>
				</form>
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
	function getListInfo(listID,title){
		$("#list_id").val(listID);
		$("#showListtitle").html(title);
	}
	function deleteList(listID){
		if(confirm("Are you sure you want to delete this list?")){
			$(".overlay").show();
			$.post("server.php",{"cmd":"delete_list",listID:listID},function(){
				window.location = 'bulksms.php';
			});
		}
	}
</script>