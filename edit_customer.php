<?php
	include_once("header.php");
	$id = $_REQUEST['id'];
	$sql = "select * from customers where id='".$id."'";
	$res = mysqli_query($link,$sql);
	if(mysqli_num_rows($res)){
		$row = mysqli_fetch_assoc($res);
	}else{
		$row = array();
	}
?>
	<div class="py-2">
		<h3>Update Customer
			<a href="customers.php" class="btn btn-primary" style="float: right">Back</a>
		</h3>
	</div>
	
	<?php if($_SESSION['message']!='')echo $_SESSION['message'];unset($_SESSION['message']); ?>

	<div class="card">
		<div class="card-body">
			<div class="col-lg-12 col-sm-12">
				<form action="server.php" method="post" enctype="multipart/form-data">
					<div class="mb-4">
						<label>First name</label>
						<input type="text" name="first_name" class="form-control" placeholder="Enter first name" required value="<?php echo $row['first_name']?>">
					</div>
					<div class="mb-4">
						<label>Last name</label>
						<input type="text" name="last_name" class="form-control" placeholder="Enter last name" required value="<?php echo $row['last_name']?>">
					</div>
					<div class="mb-4">
						<label>Phone</label>
						<input type="text" name="phone" class="form-control" placeholder="Enter phone" required value="<?php echo $row['phone']?>">
					</div>
					<div class="mb-4">
						<label>Cell</label>
						<input type="text" name="cell" class="form-control" placeholder="Enter cell" required value="<?php echo $row['cell']?>">
					</div>
					<div class="mb-4">
						<label>Address</label>
						<input type="text" name="address" class="form-control" placeholder="Enter address" required value="<?php echo $row['address']?>">
					</div>
					<div class="mb-4">
						<label>City</label>
						<input type="text" name="city" class="form-control" placeholder="Enter city" required value="<?php echo $row['city']?>">
					</div>
					<div class="mb-4">
						<label>State</label>
						<input type="text" name="state" class="form-control" placeholder="Enter state" required value="<?php echo $row['state']?>">
					</div>
					<div class="mb-4">
						<label>Zip</label>
						<input type="text" name="zipcode" class="form-control" placeholder="Enter zip" required value="<?php echo $row['zipcode']?>">
					</div>
					<div class="mb-4">
						<label>Sales Manager Name</label>
						<input type="text" name="sale_manager_name" class="form-control" value="<?php echo $row['sales_manager_name']?>">
					</div>
					<div class="mb-4">
						<label>Sales Manager Number</label>
						<input type="text" name="sale_manager_number" class="form-control" value="<?php echo $row['sales_manager_number']?>">
					</div>
					<div class="mb-4">
						<label>Project manager name</label>
						<input type="text" name="project_manager_name" class="form-control" value="<?php echo $row['project_manager_name']?>">
					</div>
					<div class="mb-4">
						<label>Project manager number</label>
						<input type="text" name="project_manager_number" class="form-control" value="<?php echo $row['project_manager_number']?>">
					</div>
					<div class="mb-4">
						<label>Mannagement name</label>
						<input type="text" name="management_name" class="form-control" value="<?php echo $row['management_name']?>">
					</div>
					<div class="mb-4">
						<label>Mannagement number</label>
						<input type="text" name="management_number" class="form-control" value="<?php echo $row['management_number']?>">
					</div>
					<div class="mb-4">
						<label>Tag workflow</label>
						<input type="text" name="tagworkflow" class="form-control" value="<?php echo $row['tag_workflow']?>">
					</div>
					<div class="mb-4">
						<input type="hidden" name="id" value="<?php echo $id?>">
						<input type="hidden" name="cmd" value="update_customer">
						<input type="submit" value="Update Customer" class="btn btn-gray-800 d-inline-flex align-items-center me-2">
					</div>
				</form>
			</div>
		</div>
	</div>
	
<?php include_once("footer.php");?>