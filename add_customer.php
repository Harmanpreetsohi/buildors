<?php
	include_once("header.php");
?>
	<div class="py-2">
		<h3>Add Customer
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
						<input type="text" name="first_name" class="form-control" placeholder="Enter first name" required>
					</div>
					<div class="mb-4">
						<label>Last name</label>
						<input type="text" name="last_name" class="form-control" placeholder="Enter last name" required>
					</div>
					<div class="mb-4">
						<label>Phone</label>
						<input type="text" name="phone" class="form-control" placeholder="Enter phone" required>
					</div>
					<div class="mb-4">
						<label>Cell</label>
						<input type="text" name="cell" class="form-control" placeholder="Enter cell" required>
					</div>
					<div class="mb-4">
						<label>Address</label>
						<input type="text" name="address" class="form-control" placeholder="Enter address" required>
					</div>
					<div class="mb-4">
						<label>City</label>
						<input type="text" name="city" class="form-control" placeholder="Enter city" required>
					</div>
					<div class="mb-4">
						<label>State</label>
						<input type="text" name="state" class="form-control" placeholder="Enter state" required>
					</div>
					<div class="mb-4">
						<label>Zip</label>
						<input type="text" name="zipcode" class="form-control" placeholder="Enter zip" required>
					</div>
					<div class="mb-4">
						<label>Sales Manager Name</label>
						<input type="text" name="sale_manager_name" class="form-control" >
					</div>
					<div class="mb-4">
						<label>Sales Manager Number</label>
						<input type="text" name="sale_manager_number" class="form-control" >
					</div>
					<div class="mb-4">
						<label>Project manager name</label>
						<input type="text" name="project_manager_name" class="form-control" >
					</div>
					<div class="mb-4">
						<label>Project manager number</label>
						<input type="text" name="project_manager_number" class="form-control" >
					</div>
					<div class="mb-4">
						<label>Mannagement name</label>
						<input type="text" name="management_name" class="form-control" >
					</div>
					<div class="mb-4">
						<label>Mannagement number</label>
						<input type="text" name="management_number" class="form-control" >
					</div>
					<div class="mb-4">
						<label>Tag workflow</label>
						<input type="text" name="tagworkflow" class="form-control" >
					</div>
					<div class="mb-4">
						<input type="hidden" name="cmd" value="add_customer">
						<input type="submit" value="Add Customer" class="btn btn-gray-800 d-inline-flex align-items-center me-2">
					</div>
				</form>
			</div>
		</div>
	</div>
	
<?php include_once("footer.php");?>