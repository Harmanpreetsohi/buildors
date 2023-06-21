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
	
	$permission_qry = mysqli_query($link,"select * from permission where inner_user_id='$user_id' AND module_name='workflows' AND view_permission='1'");
	
	if(mysqli_num_rows($permission_qry)==1 || $role_data['role']=="Full Admin" || $user_type==1){
?>
	<div class="py-2">
		<h3>
			Workflows
			<a href="select_template.php" class="btn btn-primary" style="float: right">Create workflow</a>
			<input type="button" value="Create folder" class="btn btn-primary" style="float: right;margin-right: 5px;" onClick="alert('under development');">
		</h3>
	</div>

	<div class="card">
		<div class="table-responsive py-4">
			<table class="table table-flush" id="datatable">
				<thead class="thead-light">
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Total Enrolled</th>
						<th>Active Enrolled</th>
						<th>Last Updated</th>
						<th>Created</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
				<?php
                    $workflow_data = mysqli_query($link,"select * from workflow where user_id='".$_SESSION['company_id']."'");

                    if(mysqli_num_rows($workflow_data)>0){
                        $i = 1;
                        while($row = mysqli_fetch_assoc($workflow_data) ){
                ?>
                <tr>
                <th><?= $i++ ?></th>
                <td><?= $row['name'] ?></td>
                <td></td>
                <td></td>
                <td><?= $row['updated_at'] ?></td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <a href="edit-workflow.php?flowid=<?= base64_encode($row['id']) ?>" class="btn btn-primary">Edit</a>
                    <a href="backend.php?cmd=delete_workflow&flowid=<?= base64_encode($row['id']) ?>" class="btn btn-danger">Delete</a>
                </td>
                </tr>
                <?php }
                    }else{ ?>
					<tr>
						<td colspan="6">No flow is created yet.</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>

<?php
	}else{
	    
	    echo "<h3 style='height: 250px;' class='mt-4'>Access Denied</h3>";
	}
?>	
<?php include_once("footer.php");?>