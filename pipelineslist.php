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
	
	$permission_qry = mysqli_query($link,"select * from permission where inner_user_id='$user_id' AND module_name='pipelines' AND view_permission='1'");
	
	if(mysqli_num_rows($permission_qry)==1 || $role_data['role']=="Full Admin" || $user_type==1){
?>
	<div class="py-2">
		<h3>
			Pipelines
			<a href="create-pipeline.php" class="btn btn-primary" style="float: right">Create New Pipeline</a>
		</h3>
		<?php if(isset($_SESSION['message']) && $_SESSION['message']!='')echo $_SESSION['message'];unset($_SESSION['message']); ?>
	</div>

	<div class="card">
		<div class="table-responsive py-4">
			<table class="table table-flush" id="datatable">
				<thead class="thead-light">
					<tr>
						<th>#</th>
						<th>Title</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
				<?php
                    $pipeline_list = mysqli_query($link,"select * from pipeline_list where user_id=".$_SESSION['company_id']);

                    if(mysqli_num_rows($pipeline_list)>0){
                        $i = 1;
                        while($row = mysqli_fetch_assoc($pipeline_list) ){
                ?>
                <tr>
                <th><?= $i++ ?></th>
                <td><?= $row['title'] ?></td>
                <td>
					<a href="pipelines.php?pipeline_id=<?= $row['id']?>" class="btn btn-success">View</a>
                    <a href="pipeline-edit.php?pipelineid=<?= $row['id'] ?>" class="btn btn-primary">Edit</a>
                    <!--<a href="server.php?cmd=delete_pipeline&id=<?= $row['id']?>" class="btn btn-danger">Delete</a>-->
                    <?php
                    	$permission_qry2 = mysqli_query($link,"select * from permission where inner_user_id='$user_id' AND module_name='pipelines' AND delete_permission='1'");
                        if(mysqli_num_rows($permission_qry2)==1 || $role_data['role']=="Full Admin" || $user_type==1){
                    ?>
					<button class="btn btn-danger" onClick="deletePipilien('<?php echo $row['id']?>')">Delete</button>
					<?php } ?>
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
<script>
	function deletePipilien(pipilineID){
		if(confirm("Are you sure you want to delete this pipeline?")){
			$.post("server.php",{"cmd":"delete_pipeline",id:pipilineID},function(){
				window.location = 'pipelineslist.php';
			});
		}
	}
</script>