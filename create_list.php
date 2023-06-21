<?php
	include_once("header.php");
?>
	<div class="py-2">
		<h3>Create New List</h3>
	</div>
	
	<?php if($_SESSION['message']!='')echo $_SESSION['message'];unset($_SESSION['message']); ?>

	<div class="card">
		<div class="card-body">
			<div class="col-lg-12 col-sm-12">
				<form action="server.php" method="post" enctype="multipart/form-data">
					<div class="mb-4">
						<label>Title</label>
						<input type="text" name="title" class="form-control" placeholder="Enter list title" required>
					</div>
					<div class="mb-4">
						<label for="formFile" class="form-label">Select CSV File</label>
						<input name="contacts_csv" class="form-control" type="file" id="formFile" required> 
					</div>
					<div class="mb-4">
						<input type="hidden" name="cmd" value="create_csv_list">
						<input type="submit" value="Create List" class="btn btn-gray-800 d-inline-flex align-items-center me-2">
					</div>
				</form>
			</div>
		</div>
	</div>
	
<?php include_once("footer.php");?>