<?php include_once("header.php"); ?>
<style>
	table > thead > tr > th{
		text-align: center
	}
	table > tbody > tr > td{
		text-align: center
	}
</style>
	<div class="py-2">
		<h3>
			Staff
			<a href="add_staff.php" class="btn btn-primary" style="float: right">Add New</a>
		</h3>
	</div>

	<?php if($_SESSION['message']!='')echo $_SESSION['message'];unset($_SESSION['message']); ?>

	<div class="card">
		<div class="table-responsive py-4">
			<table class="table table-flush" id="datatable">
				<thead class="thead-light">
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Phone</th>
						<th>Designation</th>
						<th>Email</th>
						<th>Date</th>
						<th>Manage</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$sql = "select * from staff where user_id='".$_SESSION['user_id']."' order by id desc";
					$res = mysqli_query($link,$sql);
					if(mysqli_num_rows($res)){
						$index = 1;
						while($row = mysqli_fetch_assoc($res)){
				?>
							<tr>
								<td><?php echo $index++; ?></td>
								<td><?php echo $row['name']?></td>
								<td><?php echo $row["phone"];?></td>
								<td><?php echo $row["role"];?></td>
								<td><?php echo $row["email"];?></td>
								<td><?php echo $row["created_date"];?></td>
								<td style="text-align: center">
									<a href="edit_staff.php?id=<?php echo $row['id']?>"><i class="fa fa-edit" style="color: green"></i></a>&nbsp;&nbsp;
									<i class="fa fa-trash" style="cursor: pointer;color: red" onClick="confirDelete('<?php echo $row['id']?>')"></i>
								</td>
							</tr>
				<?php
						}
					}else{
				?>	
						<tr>
							<td colspan="6">No customer found.</td>
						</tr>
				<?php
					}
				?>	
				</tbody>
			</table>
		</div>
	</div>
<?php include_once("footer.php"); ?>
<script>
	function confirDelete(staffID){
		if(confirm("Are you sure you wanto to delete this staff?")){
			$.post("server.php",{"cmd":"delete_staff",staffID:staffID},function(){
				window.location = 'staff.php';
			});
		}
	}
</script>
