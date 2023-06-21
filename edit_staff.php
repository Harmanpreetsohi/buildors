<?php
	include_once("header.php");
	$id = $_REQUEST['id'];
	$sql = "select * from staff where id='".$id."'";
	$res = mysqli_query($link,$sql);
	if(mysqli_num_rows($res)){
		$row = mysqli_fetch_assoc($res);
	}else{
		$_SESSION['message'] = '<div class="aler alert-danger">Staff already deleted.</div>';
		header("location: ".$_SERVER["HTTP_REFERER"]);
		die("Staff already deleted.");
	}
?>
	<div class="py-2">
		<h3>Edit Staff
			<a href="staff.php" class="btn btn-primary" style="float: right">Back</a>
		</h3>
	</div>
	
	<?php if($_SESSION['message']!='')echo $_SESSION['message'];unset($_SESSION['message']); ?>

	<div class="card">
		<div class="card-body">
			<div class="col-lg-12 col-sm-12">
				<form action="server.php" method="post" enctype="multipart/form-data">
					<div class="mb-4">
						<label>Name</label>
						<input type="text" name="name" class="form-control" placeholder="Enter list name" required value="<?php echo $row['name']?>">
					</div>
					<div class="mb-4">
						<label>Phone</label>
						<input type="text" name="phone" class="form-control" placeholder="Enter phone" required value="<?php echo $row['phone']?>">
					</div>
					<div class="mb-4">
						<label>Role</label>
						<select name="role" class="form-control">
						<?php
							$roles = staffRoles();
							foreach($roles as $role){
								if($role == $row['role'])
									$sel = 'selected="selected"';
								else
									$sel = '';
								echo '<option '.$sel.' value="'.$role.'">'.$role.'</option>';
							}	
						?>	
						</select>
					</div>
					<div class="mb-4">
						<label>Email</label>
						<input type="email" name="email" class="form-control" placeholder="Enter email" required value="<?php echo $row['email']?>">
					</div>
					<div class="mb-4">
						<input type="hidden" name="id" value="<?php echo $id?>">
						<input type="hidden" name="cmd" value="update_staff">
						<input type="submit" value="Update Staff" class="btn btn-gray-800 d-inline-flex align-items-center me-2">
					</div>
				</form>
			</div>
		</div>
	</div>
	
<?php include_once("footer.php");?>