<?php
	include_once("header.php");
?>
	<div class="py-2">
		<h3>Create New Role
		    <a href="roles.php" class="btn btn-primary" style="float: right">Back</a>
		</h3>
	</div>
	
	

	<div class="card">
		<div class="card-body">
			<div class="col-lg-6 col-sm-12">
				<form action="server.php" method="post" enctype="multipart/form-data">
					<div class="mb-4">
						<label>Role Name</label>
						<input type="text" name="role_name" class="form-control" placeholder="Enter role name" required>
						 <?php if($_SESSION['role_exists_message']!='')echo $_SESSION['role_exists_message'];unset($_SESSION['role_exists_message']); ?>
					</div>
				
					<div class="mb-4">
						<input type="hidden" name="cmd" value="create_role">
						<input type="submit" value="Save Role" class="btn btn-gray-800 d-inline-flex align-items-center me-2">
					</div>
				</form>
			</div>
		</div>
	</div>
	
<?php include_once("footer.php");?>