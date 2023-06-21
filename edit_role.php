<?php
	include_once("header.php");
	
if(!isset($_GET['role_id'])){
    die('404');
}

$role_data = mysqli_query($link,"select * from role_master where role_id='".$_GET['role_id']."'");
if(mysqli_num_rows($role_data)>0){
    $roledata = mysqli_fetch_assoc($role_data);
}else{
    die('404');
}
?>
	<div class="py-2">
		<h3>Update Role
		    <a href="roles.php" class="btn btn-primary" style="float: right">Back</a>
		</h3>
	</div>
	


	<div class="card">
		<div class="card-body">
			<div class="col-lg-12 col-sm-12">
				<form action="server.php" method="post" enctype="multipart/form-data">
					<div class="mb-4">
						<label>Role Name</label>
						<input type="text" name="role_name" value="<?php if(!empty($roledata) && !empty($roledata['role'])){ echo $roledata['role']; }else{ echo ""; } ?>" class="form-control" placeholder="Enter role name" required>
						<?php if($_SESSION['role_exists_message']!='')echo $_SESSION['role_exists_message'];unset($_SESSION['role_exists_message']); ?>
					</div>
				
					<div class="mb-4">
						<input type="hidden" name="cmd" value="update_role">
						<input type="hidden" name="role_id" value="<?= $roledata['role_id'] ?>">
						<input type="submit" value="Update Role" class="btn btn-gray-800 d-inline-flex align-items-center me-2">
					</div>
				</form>
			</div>
		</div>
	</div>
	
<?php include_once("footer.php");?>